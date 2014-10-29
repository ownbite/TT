function CustomerModel(data)
{
	if (!data)
	{
		data = {};
	}

	var self = this;
	self.Date = data.Date;
	self.Name = data.Name;
	self.Description = data.Description;
}

function CustomerPageModel(data)
{
	if (!data)
	{
		data = {};
	}

	var self = this;
	self.customers = ExtractModels(self, data.customers, CustomerModel);

	var filters = [
		{
			Type: "text",
			Name: "Name",
			Value: ko.observable(""),
			RecordValue: function(record) { return record.Name; }
		},
		{
			Type: "select",
			Name: "Status",
			Options: [
				GetOption("All", "All", null),
				GetOption("None", "None", "None"),
				GetOption("New", "New", "New"),
				GetOption("Recently Modified", "Recently Modified", "Recently Modified")
			],
			CurrentOption: ko.observable(),
			RecordValue: function(record) { return record.status; }
		}
	];
	var sortOptions = [
		{
			Name: "Date",
			Value: "Date",
			Sort: function(left, right) { return CompareCaseInsensitive(left.Date, right.Date) }
		},
        {
			Name: "Name",
			Value: "Name",
			Sort: function(left, right) { return CompareCaseInsensitive(left.Name, right.Name); }
		},
		{
			Name: "Description",
			Value: "Description",
			Sort: function(left, right) { return CompareCaseInsensitive(left.Description, right.Description); }
		}
	];
	self.filter = new FilterModel(filters, self.customers);
	self.sorter = new SorterModel(sortOptions, self.filter.filteredRecords);
	self.pager = new PagerModel(self.sorter.orderedRecords);
}

function PagerModel(records)
{
	var self = this;
	self.pageSizeOptions = ko.observableArray([1,3, 5, 25, 50]);

	self.records = GetObservableArray(records);
	self.currentPageIndex = ko.observable(self.records().length > 0 ? 0 : -1);
	self.currentPageSize = ko.observable(25);
	self.recordCount = ko.computed(function() {
		return self.records().length;
	});
	self.maxPageIndex = ko.computed(function() {
		return Math.ceil(self.records().length / self.currentPageSize()) - 1;
	});
	self.currentPageRecords = ko.computed(function() {
		var newPageIndex = -1;
		var pageIndex = self.currentPageIndex();
		var maxPageIndex = self.maxPageIndex();
		if (pageIndex > maxPageIndex)
		{
			newPageIndex = maxPageIndex;
		}
		else if (pageIndex == -1)
		{
			if (maxPageIndex > -1)
			{
				newPageIndex = 0;
			}
			else
			{
				newPageIndex = -2;
			}
		}
		else
		{
			newPageIndex = pageIndex;
		}

		if (newPageIndex != pageIndex)
		{
			if (newPageIndex >= -1)
			{
				self.currentPageIndex(newPageIndex);
			}

			return [];
		}

		var pageSize = self.currentPageSize();
		var startIndex = pageIndex * pageSize;
		var endIndex = startIndex + pageSize;
		return self.records().slice(startIndex, endIndex);
	}).extend({ throttle: 5 });
	self.moveFirst = function() {
		self.changePageIndex(0);
	};
	self.movePrevious = function() {
		self.changePageIndex(self.currentPageIndex() - 1);
	};
	self.moveNext = function() {
		self.changePageIndex(self.currentPageIndex() + 1);
	};
	self.moveLast = function() {
		self.changePageIndex(self.maxPageIndex());
	};
	self.changePageIndex = function(newIndex) {
		if (newIndex < 0
			|| newIndex == self.currentPageIndex()
			|| newIndex > self.maxPageIndex())
		{
			return;
		}

		self.currentPageIndex(newIndex);
	};
	self.onPageSizeChange = function() {
		self.currentPageIndex(0);
	};
	self.renderPagers = function() {
		var pager = "<div><a href=\"#\" data-bind=\"click: pager.moveFirst, enable: pager.currentPageIndex() > 0\">&lt;&lt;</a><a href=\"#\" data-bind=\"click: pager.movePrevious, enable: pager.currentPageIndex() > 0\">&lt;</a>Page <span data-bind=\"text: pager.currentPageIndex() + 1\"></span> of <span data-bind=\"text: pager.maxPageIndex() + 1\"></span> [<span data-bind=\"text: pager.recordCount\"></span> Record(s)]<select data-bind=\"options: pager.pageSizeOptions, value: pager.currentPageSize, event: { change: pager.onPageSizeChange }\"></select><a href=\"#\" data-bind=\"click: pager.moveNext, enable: pager.currentPageIndex() < pager.maxPageIndex()\">&gt;</a><a href=\"#\" data-bind=\"click: pager.moveLast, enable: pager.currentPageIndex() < pager.maxPageIndex()\">&gt;&gt;</a></div>";
		$("div.Pager").html(pager);
	};
	self.renderNoRecords = function() {
		var message = "<span data-bind=\"visible: pager.recordCount() == 0\">No records found.</span>";
		$("div.NoRecords").html(message);
	};

	self.renderPagers();
	self.renderNoRecords();
}

function SorterModel(sortOptions, records)
{
	var self = this;
	self.records = GetObservableArray(records);
	self.sortOptions = ko.observableArray(sortOptions);
	self.sortDirections = ko.observableArray([
		{
			Name: "Asc",
			Value: "Asc",
			Sort: false
		},
		{
			Name: "Desc",
			Value: "Desc",
			Sort: true
		}]);
	self.currentSortOption = ko.observable(self.sortOptions()[0]);
	self.currentSortDirection = ko.observable(self.sortDirections()[0]);
	self.orderedRecords = ko.computed(function()
	{
		var records = self.records();
		var sortOption = self.currentSortOption();
		var sortDirection = self.currentSortDirection();
		if (sortOption == null || sortDirection == null)
		{
			return records;
		}

		var sortedRecords = records.slice(0, records.length);
		SortArray(sortedRecords, sortDirection.Sort, sortOption.Sort);
		return sortedRecords;
	}).extend({ throttle: 5 });
}

function FilterModel(filters, records)
{
	var self = this;
	self.records = GetObservableArray(records);
	self.filters = ko.observableArray(filters);
	self.activeFilters = ko.computed(function() {
		var filters = self.filters();
		var activeFilters = [];
		for (var index = 0; index < filters.length; index++)
		{
			var filter = filters[index];
			if (filter.CurrentOption)
			{
				var filterOption = filter.CurrentOption();
				if (filterOption && filterOption.FilterValue != null)
				{
					var activeFilter = {
						Filter: filter,
						IsFiltered: function(filter, record)
						{
							var filterOption = filter.CurrentOption();
							if (!filterOption)
							{
								return;
							}

							var recordValue = filter.RecordValue(record);
							return recordValue != filterOption.FilterValue;NoMat
						}
					};
					activeFilters.push(activeFilter);
				}
			}
			else if (filter.Value)
			{
				var filterValue = filter.Value();
				if (filterValue && filterValue != "")
				{
					var activeFilter = {
						Filter: filter,
						IsFiltered: function(filter, record)
						{
							var filterValue = filter.Value();
							filterValue = filterValue.toUpperCase();

							var recordValue = filter.RecordValue(record);
							recordValue = recordValue.toUpperCase();
							return recordValue.indexOf(filterValue) == -1;
						}
					};
					activeFilters.push(activeFilter);
				}
			}
		}

		return activeFilters;
	});
	self.filteredRecords = ko.computed(function() {
		var records = self.records();
		var filters = self.activeFilters();
		if (filters.length == 0)
		{
			return records;
		}

		var filteredRecords = [];
		for (var rIndex = 0; rIndex < records.length; rIndex++)
		{
			var isIncluded = true;
			var record = records[rIndex];
			for (var fIndex = 0; fIndex < filters.length; fIndex++)
			{
				var filter = filters[fIndex];
				var isFiltered = filter.IsFiltered(filter.Filter, record);
				if (isFiltered)
				{
					isIncluded = false;
					break;
				}
			}

			if (isIncluded)
			{
				filteredRecords.push(record);
			}
		}

		return filteredRecords;
	}).extend({ throttle: 200 });
}

function ExtractModels(parent, data, constructor)
{
	var models = [];
	if (data == null)
	{
		return models;
	}

	for (var index = 0; index < data.length; index++)
	{
		var row = data[index];
		var model = new constructor(row, parent);
		models.push(model);
	}

	return models;
}

function GetObservableArray(array)
{
	if (typeof(array) == 'function')
	{
		return array;
	}

	return ko.observableArray(array);
}

function CompareCaseInsensitive(left, right)
{
	if (left == null)
	{
		return right == null;
	}
	else if (right == null)
	{
		return false;
	}

	return left.toUpperCase() <= right.toUpperCase();
}

function GetOption(name, value, filterValue)
{
	var option = {
		Name: name,
		Value: value,
		FilterValue: filterValue
	};
	return option;
}

function SortArray(array, direction, comparison)
{
	if (array == null)
	{
		return [];
	}

	for (var oIndex = 0; oIndex < array.length; oIndex++)
	{
		var oItem = array[oIndex];
		for (var iIndex = oIndex + 1; iIndex < array.length; iIndex++)
		{
			var iItem = array[iIndex];
			var isOrdered = comparison(oItem, iItem);
			if (isOrdered == direction)
			{
				array[iIndex] = oItem;
				array[oIndex] = iItem;
				oItem = iItem;
			}
		}
	}

	return array;
}

var testCustomers = [{"EventID":"68250","Name":"Sjung med ditt barn","Description":"Stadsbiblioteket, Kulan\n S\u00c3\u00a5nger, rim och ramsor med Berit &amp;","Date":"2014-10-17","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/32\/72\/38\/57\/4689\/33e2a2b6ca71eca5\/thumbs\/allmnKK_691_389_DEFAULT_scale.jpg"},{"EventID":"68251","Name":"Buschwick - drop in","Description":"Vi bekantar oss med de sk\u00c3\u00b6nlitter\u00c3\u00a4ra verk som bildar underlag f\u00c3\u00b6r h\u00c3\u00b6stens Bushwick Book Club p\u00c3\u00a5 Dunkers, d\u00c3\u00a4r ett antal musiker och f\u00c3\u00b6rfattare kommer att tolka de sk\u00c3\u00b6nlitter\u00c3\u00a4ra verken genom sitt eget konstn\u00c3\u00a4rskap.\n Verk 1. Frankenstein av Mary Shelley \n Fri entr\u00c3\u00a9&nbsp;","Date":"2014-10-17","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/32\/72\/38\/57\/4690\/ca31ac68a48ebeb6\/thumbs\/Bushwick691x389_691_389_DEFAULT_scale.jpg"},{"EventID":"68252","Name":"Fredagskul","Description":"Stadsbiblioteket, Kulan\n P\u00c3\u00a5 fredagar \u00c3\u00a4r alla mellan 0-15 \u00c3\u00a5r v\u00c3\u00a4lkomna! Vi spelar spel, m\u00c3\u00a5lar, leker, pysslar och har UNO-turnering\n Fri entr\u00c3\u00a9\n\nSamarrangemang med ABFKulturskolan bes\u00c3\u00b6ker Stadsbiblioteket\n","Date":"2014-10-17","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/32\/72\/38\/57\/4691\/5b8392be98c98972\/thumbs\/691x389Pyssel_691_389_DEFAULT_scale.jpg"},{"EventID":"68253","Name":"Tjejgrejer med Josefin Johansson","Description":"Tjejgrejer \u00c3\u00a4r Josefin Johanssons f\u00c3\u00b6rsta soloshow. Det \u00c3\u00a4r en f\u00c3\u00b6rest\u00c3\u00a4llning om grejer tjejer gillar, allts\u00c3\u00a5 killar och smink. Eller?\n\nBarndomsminnen fr\u00c3\u00a5n Stubbabo varvas med s\u00c3\u00a5nger fr\u00c3\u00a5n hj\u00c3\u00a4rnan. Vi f\u00c3\u00a5r f\u00c3\u00b6lja med i resonemang kring utseende, beteende och vikten av k\u00c3\u00b6n. Bokstavligen. Kv\u00c3\u00a4llen blir som henne sj\u00c3\u00a4lv - rolig, peppig och lite ekivok.\n\nJosefin Johansson \u00c3\u00a4r komiker och artist och har arbetat p\u00c3\u00a5 radio, tv och scen sedan 2006. Hon har medverkat i produktioner som: Partaj, Torsk p\u00c3\u00a5 tuben, Robins, Gabba Gabba, Dobidoo, Extra Extra, P2 Live, Elektroniskt i P2, Pang Prego, podcasten Crazy Town, Musikguiden i P3 och Morgonpasset i P3. \u00c3\u201er just nu aktuell i Helt Sant i P4 -ett humorprogram om vetenskap f\u00c3\u00b6r barn, Humorkollo och Det Stora Matslaget i SVT.\n\nSamarrangemang med The Tivoli.\n\nSpeltid 90 minuter plus paus.\n\nPlats: Teatersalen\n\nEntr\u00c3\u00a9: 180 kronor, studerande och kulturkortsinnehavare 150 kronor\n","Date":"2014-10-17","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/32\/72\/38\/57\/4552\/4252cf18b3a4ce65\/thumbs\/Tjejegrejer691x389_691_389_DEFAULT_scale.jpg"},{"EventID":"68287","Name":"Sundsp\u00c3\u00a4rlan Live - Drifters","Description":"i kv\u00c3\u00a4ll dans till DriftersDans 21.30-01.30. Bistro fr\u00c3\u00a5n kl 21.00 som serverar ett urval av varma och kalla enklare r\u00c3\u00a4tter. I loungen spelar Martin Nilsson.","Date":"2014-10-17","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2947848"},{"EventID":"68219","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-17","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68198","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-17","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"68254","Name":"Ly\u00c3\u00b6starini","Description":"I Ly\u00c3\u00b6straini tar de avstamp i Japans klassiska musik, l\u00c3\u00a4gger till Lenas texter p\u00c3\u00a5 \u00c3\u00a4lvdalsm\u00c3\u00a5l och toppar med Anders \u00c3\u00b6ppna landskap av improvisation.\n\nBara i musikens vidunderliga v\u00c3\u00a4rld kan en konstellation som Ly\u00c3\u00b6strini bli verklighet: Karin Nakagawa \u00c3\u00a4r en av Japans fr\u00c3\u00a4msta virtuoser p\u00c3\u00a5 25-str\u00c3\u00a4ngad koto -ett l\u00c3\u00a5ngt, ur\u00c3\u00a5ldrig instrument som s\u00c3\u00a4llan spelas utanf\u00c3\u00b6r Japan-. Lena Willemark, med r\u00c3\u00b6tterna i \u00c3\u201elvdalen, har spridit svensk folkmusik i flera decennier. Anders Jormin \u00c3\u00a4r en av v\u00c3\u00a5ra mest eftertraktade tonkonstn\u00c3\u00a4rer.\n\nI Ly\u00c3\u00b6straini -tr\u00c3\u00a4d av ljus p\u00c3\u00a5 \u00c3\u00a4lvdalsm\u00c3\u00a5l- tar de avstamp i Japans klassiska musik, l\u00c3\u00a4gger till Lenas texter p\u00c3\u00a5 \u00c3\u00a4lvdalsm\u00c3\u00a5l och toppar med Anders \u00c3\u00b6ppna landskap av improvisation. Resultatet blir m\u00c3\u00a4ktig musik med en s\u00c3\u00a4llsam f\u00c3\u00b6rnimmelse av historia, samtid och morgondag \u00e2\u20ac\u201c i samma klingande andetag. Allts\u00c3\u00a5 s\u00c3\u00a5dant som bara kan h\u00c3\u00a4nda i musikens v\u00c3\u00a4rld.\n\nLena Willemark \u00e2\u20ac\u201c fiol, s\u00c3\u00a5ng Karin Nakagawa \u00e2\u20ac\u201c koto Anders Jormin \u00e2\u20ac\u201c bas\n\nPaus: 20 minuter\n\nPlats: Konserthuset\n\nEntr\u00c3\u00a9: 275 kronor, 235 kronor Kulturkortet. K\u00c3\u00b6p biljett direkt via Ticnet eller i Dunkers biljettcentral\n","Date":"2014-10-17","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/62\/91\/40\/6\/4408\/36c26b7d5a45acd7\/thumbs\/lyostarinikk_691_389_DEFAULT_scale.jpg"},{"EventID":"68220","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-18","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68199","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-18","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"68296","Name":"M\u00c3\u00a5rten G\u00c3\u00a5s","Description":"En svensk h\u00c3\u00b6gtid som numera \u00c3\u00a4r mer sk\u00c3\u00a5nsk \u00c3\u00a4n svensk.M\u00c3\u00a5rten G\u00c3\u00a5s eller M\u00c3\u00a5rtensafton firas den 10 november men m\u00c3\u00a5nga restauranger och g\u00c3\u00a4stgiverier serverar g\u00c3\u00a5s och firar denna h\u00c3\u00b6gtid under en l\u00c3\u00a4ngre period. En h\u00c3\u00b6gtid d\u00c3\u00a5 man traditionsenligt h\u00c3\u00a4r i Sk\u00c3\u00a5ne oftast serverar svartsoppa, g\u00c3\u00a5smiddag och klassisk sk\u00c3\u00a5nsk \u00c3\u00a4ppelkaka.\n\nNi hittar en lista \u00c3\u00b6ver restauranger och g\u00c3\u00a4stgiverier i nordv\u00c3\u00a4stsk\u00c3\u00a5ne p\u00c3\u00a5 f\u00c3\u00b6ljande l\u00c3\u00a4nk:\nhttp:\/\/www.helsingborg.se\/Besokare\/bo\/paket\/marten-gas\/\n","Date":"2014-10-20","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2957707"},{"EventID":"68200","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-21","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"68201","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-22","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"68221","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-22","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68255","Name":"Maat- en gudinna utan tempel","Description":"Jonas Otterb\u00c3\u00a4ck, professor och islamolog fr\u00c3\u00a5n Lund&nbsp;","Date":"2014-10-22","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/01\/42\/36\/562\/4569\/48145fd8e9956dd4\/thumbs\/logga3_691_389_DEFAULT_scale.png"},{"EventID":"68202","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-23","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"66400","Name":"1","Description":"NULL","Date":"2014-10-23","ImagePath":"\\192.168.50.30Content\beta.helsingborg.seHappyPictures;Foto: Annika von Hauswolff"},{"EventID":"68256","Name":"Laurin\/ Bergcrantz Project featuring Victor Lewis","Description":"2013 uts\u00c3\u00a5gs Anna-Lena Laurin av Sveriges Radio till \u00c3\u2026rets komposit\u00c3\u00b6r - Jazzkatten. Med anledning av denna utm\u00c3\u00a4rkelse best\u00c3\u00a4llde SR ny musik av Laurin och hon fick fria h\u00c3\u00a4nder att s\u00c3\u00a4tta ihop sitt band - ett band som ocks\u00c3\u00a5 ska uruppf\u00c3\u00b6ra best\u00c3\u00a4llningen.\n\nBandet best\u00c3\u00a5r av hennes make och trumpetaren Anders Bergcrantz, d\u00c3\u00b6ttrarna och s\u00c3\u00a5ngerskorna Rebecca och Iris, basisten Stefan Belln\u00c3\u00a4s samt v\u00c3\u00a4rldstrummisen Victor Lewis.&nbsp;","Date":"2014-10-23","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/57\/56\/57\/442\/4573\/4c4dd1e1f4610747\/thumbs\/691x389_Anna-Lena_Laurin._Fotograf_Goran_James_Djordjevic_IMG9085_691_389_DEFAULT_scale.jpg"},{"EventID":"68222","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-23","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68223","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-24","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68257","Name":"Queenie \u00e2\u20ac\u201c Remember Wembley","Description":"Queen \u00e2\u20ac\u201c f\u00c3\u00b6rmodligen den mest framg\u00c3\u00a5ngsrika rockbandet p\u00c3\u00a5 70- och 80-talet \u00e2\u20ac\u201c har sedan l\u00c3\u00a4nge blivit en legend med m\u00c3\u00a5nga l\u00c3\u00a5tar som blivit verkliga klassiker! Nu kan du h\u00c3\u00b6ra Queens musik igen n\u00c3\u00a4r tributbandet Queenie \u00c3\u00a4ntligen g\u00c3\u00a4star Sverge.\n\nMed hundratals konserter i hela Europa och delar av Asien sedan 2006 har Queenie f\u00c3\u00a5tt entusiastiska fans \u00c3\u00b6ver hela v\u00c3\u00a4rlden. \u00c3\u2026r 2008 spelade Queenie som huvudband p\u00c3\u00a5 v\u00c3\u00a4rldens st\u00c3\u00b6rsta Queen-festival i Montreux, inbjuden av Freddie Mercurys  personlig assistent   Peter Freestone sj\u00c3\u00a4lv.\n\nF\u00c3\u00b6rsta stegen i karri\u00c3\u00a4ren f\u00c3\u00b6r detta band var deltagandet i upplagan av TV-serien   I Got Talent   och inf\u00c3\u00b6r miljontals passionerade TV-tittare fick Queenie sitt genombrott.\n\nQueenie framf\u00c3\u00b6r musiken p\u00c3\u00a5 exakt samma s\u00c3\u00a4tt som Queen gjorde. Kombinationen av originalinstrumentering, Michael Kluch fantastiska r\u00c3\u00b6st, autentiska kostymer och en massiv ljud\/ljusproduktion g\u00c3\u00b6r varje show till en unik upplevelse. En kv\u00c3\u00a4ll fylld av megahits som Radio Gaga, I Want It All , Bohemian Rhapsody, We Will Rock You , We Are The Champions och m\u00c3\u00a5nga, m\u00c3\u00a5nga fler.\n\nEntr\u00c3\u00a9: 395 kronor, med HD-pass 295 kronor. K\u00c3\u00b6p biljett!\n","Date":"2014-10-24","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/62\/91\/40\/6\/4376\/e14e9c27807774da\/thumbs\/queeniekk_691_389_DEFAULT_scale.jpg"},{"EventID":"68203","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-24","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"68286","Name":"Europa Cup Swedish Judo Open","Description":"Europeiska Judof\u00c3\u00b6rbundet rankar Swedish Judo Open som en av de fem b\u00c3\u00a4sta Europa Cup-t\u00c3\u00a4vlingarna. Cupen drar cirka 250 deltagare i \u00c3\u00a5ldrarna 18 \u00c3\u00a5r och upp\u00c3\u00a5t fr\u00c3\u00a5n m\u00c3\u00a5nga olika l\u00c3\u00a4nder. T\u00c3\u00a4vlingarna \u00c3\u00a4ger rum p\u00c3\u00a5 Helsingborg Arena 24-26 oktober 2014 och \u00c3\u00a4r \u00c3\u00b6ppna f\u00c3\u00b6r alla som vill komma och titta p\u00c3\u00a5 judo av h\u00c3\u00b6g klass.","Date":"2014-10-24","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2877812"},{"EventID":"68224","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-25","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68204","Name":"Pelle Er\u00c3\u00b6vraren","Description":"Musikalen Pelle Er\u00c3\u00b6vraren \u00c3\u00a4r en gripande historia om unge Pelle och hans far, \u00c3\u00a4nkemannen och analfabeten Lasse fr\u00c3\u00a5n sk\u00c3\u00a5nska Tomelilla och deras kamp f\u00c3\u00b6r \u00c3\u00b6verlevnad i slutet av 1870-talet.Sk\u00c3\u00a5ne \u00c3\u00a4r fattigt och n\u00c3\u00b6dst\u00c3\u00a4llt. M\u00c3\u00a4nniskor tvingas bort fr\u00c3\u00a5n sina hem. F\u00c3\u00b6r de som inte har r\u00c3\u00a5d att ta sig till Amerika blir den danska \u00c3\u00b6n Bornholm en h\u00c3\u00a4grande r\u00c3\u00a4ddning. Men f\u00c3\u00b6rhoppningarna om en ljus framtid krossas i m\u00c3\u00b6tet med den brutala verkligheten p\u00c3\u00a5 g\u00c3\u00a5rden d\u00c3\u00a4r de f\u00c3\u00a5tt arbete. Pelle m\u00c3\u00b6ter den utom\u00c3\u00a4ktenskapliga, utst\u00c3\u00b6tta Rud och den upproriske dr\u00c3\u00a4ngen Erik. De delar hans dr\u00c3\u00b6m om frihet. Dr\u00c3\u00b6mmen om ytterligare en flykt. Pelle b\u00c3\u00b6rjar er\u00c3\u00b6vra sitt eget liv.","Date":"2014-10-25","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2993452"},{"EventID":"68284","Name":"Helsingborgs IF - IFK G\u00c3\u00b6teborg","Description":"Allsvensk hemmamatch p\u00c3\u00a5 Olympia.Ta plats p\u00c3\u00a5 Olympias l\u00c3\u00a4ktare och k\u00c3\u00a4nn st\u00c3\u00a4mningen. Se HIF i kampen om po\u00c3\u00a4ngen.\nSe www.hif.se f\u00c3\u00b6r tid\n","Date":"2014-10-26","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2835662"},{"EventID":"68258","Name":"V\u00c3\u00a4senvandring","Description":"Ett sp\u00c3\u00a4nnande \u00c3\u00a4ventyr n\u00c3\u00a4r m\u00c3\u00b6rkret lagt sig och v\u00c3\u00a4sen ur den svenska folktron smyger fram. Bara f\u00c3\u00b6r de modiga!\n\nBiljetter f\u00c3\u00b6rk\u00c3\u00b6p 135 kr vuxen och 40 kr barn och ungdom upp till och med 18 \u00c3\u00a5r p\u00c3\u00a5 Ticnet. Mer information om biljettsl\u00c3\u00a4pp och exakta tider kommer inom kort.\n\nFr\u00c3\u00a5n 6 \u00c3\u00a5r. Vandringen tar ca en timme.\n","Date":"2014-10-28","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/57\/56\/57\/442\/4412\/9ef452208899e6a4\/thumbs\/691x389_Baeckahaesten3_691_389_DEFAULT_scale.jpg"},{"EventID":"68225","Name":"Frankenstein","Description":"En sp\u00c3\u00a4nnande och vacker rysare om monstret inom m\u00c3\u00a4nniskan. Sveriges fr\u00c3\u00a4msta skr\u00c3\u00a4ckregiss\u00c3\u00b6r Rikard Lekander dramatiserar och iscens\u00c3\u00a4tter Mary Shelleys klassiska ber\u00c3\u00a4ttelse om Frankenstein- en av skr\u00c3\u00a4ck- och sciencefictionlitteraturens mest legendariska och stilbildande romaner. \nDen ber\u00c3\u00b6mda ber\u00c3\u00a4ttelsen handlar om studenten Frankenstein som lyckas ge liv \u00c3\u00a5t en samling hopsydda likdelar, insamlade i b\u00c3\u00a5rhus och p\u00c3\u00a5 avr\u00c3\u00a4ttningsplatser. Men g\u00c3\u00a4rningen visar sig f\u00c3\u00a5 minst sagt mardr\u00c3\u00b6mslika konsekvenser. Det som b\u00c3\u00b6rjar som en jakt efter evigt liv, blir i st\u00c3\u00a4llet en blodig kamp mellan Frankenstein och monstret.\n","Date":"2014-10-29","ImagePath":"http:\/\/images.citybreak.com\/image.aspx?ImageId=2995294"},{"EventID":"68259","Name":"H\u00c3\u00b6stlovskul","Description":"H\u00c3\u00b6stlovskul\nMer information kommer p\u00c3\u00a5 R\u00c3\u00a5\u00c3\u00a5 museums hemsida.\n","Date":"2014-10-29","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/57\/56\/57\/442\/4443\/be5c4bb0c00fcc4b\/thumbs\/691x389_P5300025_691_389_DEFAULT_scale.jpg"},{"EventID":"68260","Name":"V\u00c3\u00a4senvandring","Description":"Ett sp\u00c3\u00a4nnande \u00c3\u00a4ventyr n\u00c3\u00a4r m\u00c3\u00b6rkret lagt sig och v\u00c3\u00a4sen ur den svenska folktron smyger fram. Bara f\u00c3\u00b6r de modiga!\n\nBiljetter f\u00c3\u00b6rk\u00c3\u00b6p 135 kr vuxen och 40 kr barn och ungdom upp till och med 18 \u00c3\u00a5r p\u00c3\u00a5 Ticnet. Mer information om biljettsl\u00c3\u00a4pp och exakta tider kommer inom kort.\n\nFr\u00c3\u00a5n 6 \u00c3\u00a5r. Vandringen tar ca en timme.\n","Date":"2014-10-29","ImagePath":"http:\/\/mittkulturkort.se\/data\/kulturkortet\/media\/calendar\/image\/57\/56\/57\/442\/4413\/015da473e68f1226\/thumbs\/691x389_Baeckahaesten3_691_389_DEFAULT_scale.jpg"}];
var testData = {
    customers: testCustomers
};
ko.applyBindings(new CustomerPageModel(testData));
