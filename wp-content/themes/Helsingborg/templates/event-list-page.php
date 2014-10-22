<?php
/*
Template Name: Evenemangslistning
*/
get_header();

if( is_user_logged_in() && has_shortcode( $post->post_content, 'gravityform' ) ) {

  $user_meta = get_user_meta(get_current_user_id(), 'happy_user_id', TRUE );
  if (!empty($user_meta)) {
    //$events = HelsingborgEventModel::load_unpublished_events($user_meta);
    // $events = HelsingborgEventModel::load_events();
    //var_dump($events);
  }
}

$events = HelsingborgEventModel::load_events();
$event_types = HelsingborgEventModel::load_event_types();

$json_items = json_encode($events);
// var_dump($json_items);

// Get the content, see if <!--more--> is inserted
$the_content = get_extended(strip_shortcodes($post->post_content));

$pattern = get_shortcode_regex();
preg_match('/'.$pattern.'/s', $post->post_content, $matches);
if (is_array($matches) && $matches[2] == 'gravityform') {
   $shortcode = $matches[0];
}

$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main
?>

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/bower_components/foundation-multiselect/zmultiselect/zurb5-multiselect.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/bower_components/foundation-multiselect/zmultiselect/zurb5-multiselect.css">
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/jquery.datetimepicker.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/js/jquery.datetimepicker.css">
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/bower_components/knockout/dist/knockout.debug.js"></script>

<div class="article-page-layout row">
    <!-- main-page-layout -->
        <div class="main-area large-9 columns">

        <div class="main-content row">
            <!-- SIDEBAR LEFT -->
            <div class="sidebar sidebar-left large-4 medium-4 columns">
                <div class="search-container row">
                    <div class="search-inputs large-12 columns">
                        <input type="text" placeholder="Vad letar du efter?" name="search"/>
                        <input type="submit" value="Sök">
                    </div>
                </div><!-- /.search-container -->

                <div class="row">

                    <!-- large-up menu-->
                  <?php dynamic_sidebar("left-sidebar"); ?>
                  <?php Helsingborg_sidebar_menu(); ?>
                    <!-- END large up menu-->

                </div><!-- /.row -->
            </div><!-- /.sidebar-left -->

            <div class="large-8 medium-8 columns">

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                    <?php dynamic_sidebar("slider-area"); ?>
                </div><!-- /.row -->

                <div class="listen-to">
                    <a href="#" class="icon"><span>Lyssna på innehållet</span></a>
                </div>

                <?php /* Start loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>
                      <article class="article">
                        <header>
                          <h1 class="article-title"><?php the_title(); ?></h1>
                        </header>
                        <?php if (!empty($content)) : ?>
                          <div class="ingress">
                            <?php echo wpautop($main, true); ?>
                          </div><!-- /.ingress -->
                        <?php endif; ?>
                        <div class="article-body">
                          <?php if(!empty($content)){
                            echo wpautop($content, true);
                            } else {
                              echo wpautop($main, true);
                            }
                            if ($shortcode) {
                              echo do_shortcode($shortcode);
                            }
                            ?>
                        </div>
                        <footer>
                          <ul class="socialmedia-list">
                              <li class="fbook"><a href="#">Facebook</a></li>
                              <li class="twitter"><a href="#">Twitter</a></li>
                          </ul>
                        </footer>
                      </article>
                    <?php endwhile; // End the loop ?>

                     <table class="table">
                      <thead>
                        <tr>
                         <th>Evenemangstyp:
                           <select id="events_multi">
                             <?php
                             // List has been loaded in meta-functions.php
                             $i=0;
                             foreach ($event_types as $item) {
                               echo('<option value="' . $i++ . '">' . $item->Name . '</option>');
                             } ?>
                           </select>
                         <th>Plats:
                           <input type="text" id="event_location"/>
                         </th>
                         </th>
                        </tr>
                      </thead>
                      <thead>
                        <tr>
                          <th>Startdatum:
                            <input type="text" id="datetimepicker2"/>
                          </th>
                          <th>Slutdatum:
                            <input type="text" id="datetimepicker21"/>
                          </th>
                        </tr>
                      </thead>
                      <thead>
                        <tr>
                          <th>Fritext:
                            <input type="text" id="event_text"/>
                          </th>
                          <th>Alla Helsingborgs evenemang:<br>
                            <input type="checkbox" id="event_all"/>
                          </th>
                        </tr>
                      </thead>
                    </table>
                    <button type="button" class="button expand">Sök</button>

                    <b>Filters:</b><br />
                    <div data-bind="foreach: filter.filters">
                        <div>
                            <span data-bind="text: Name"></span>:<br />
                        </div>
                        <div data-bind="if: Type == 'select'">
                            <select data-bind="options: Options, optionsText: 'Name', value: CurrentOption"></select>
                        </div>
                        <div data-bind="if: Type == 'text'">
                            <input type="text" data-bind="value: Value, valueUpdate: 'afterkeydown'" />
                        </div>
                    </div>
                    <br />
                    <b>Sorts:</b>
                    Field:<br />
                    <select data-bind="options: sorter.sortOptions, optionsText: 'Name', value: sorter.currentSortOption"></select>
                    Direction:
                    <select data-bind="options: sorter.sortDirections, optionsText: 'Name', value: sorter.currentSortDirection"></select>
                    <br />
                    <br />

                    <div class="Pager"></div>
                    <div class="NoRecords"></div>

                    <ul data-bind="template: {name:'eventTemplate',foreach: pager.currentPageRecords}" class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>

                    <script type="text/html" id="eventTemplate">
                      <li>
                        <a href="#" desc="link-desc">
                          <img src="http://www.placehold.it/300x200" alt="alt-text"/>
                          <h2 data-bind="text: Name" class="list-title"></h2>
                          <span data-bind="text: Date" class="news-date"></span>
                          <div data-bind="text: Description" class="list-content"></div>
                        </a>
                      </li>
                    </script>

                    <script>
                    function EventModel(data)
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

                    function EventPageModel(data)
                    {
                      if (!data)
                      {
                        data = {};
                      }

                      var self = this;
                      self.customers = ExtractModels(self, data.customers, EventModel);

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

                    var testCustomers = <?php echo $json_items; ?>;
                    var testData = {
                        customers: testCustomers
                    };
                    ko.applyBindings(new EventPageModel(testData));

                    </script>

                    <!-- <ul class="pagination" role="menubar" aria-label="Pagination">
                      <li class="arrow unavailable" aria-disabled="true"><a href="">&laquo; Föregående</a></li>
                      <li class="current"><a href="">1</a></li>
                      <li><a href="">2</a></li>
                      <li><a href="">3</a></li>
                      <li><a href="">4</a></li>
                      <li><a href="">5</a></li>
                      <li><a href="">6</a></li>
                      <li class="unavailable" aria-disabled="true"><a href="">&hellip;</a></li>
                      <li><a href="">10</a></li>
                      <li><a href="">11</a></li>
                      <li><a href="">12</a></li>
                      <li><a href="">13</a></li>
                      <li class="arrow"><a href="">Nästa &raquo;</a></li>
                    </ul> -->

                    <script>
                    jQuery("select#events_multi").zmultiselect({
                      filter: true,
                      filterPlaceholder: 'Filter...',
                      filterResult: true,
                      filterResultText: "Showed",
                      selectedText: ['Valt','av'],
                      selectAll: true,
                      selectAllText: ['Markera alla','Avmarkera alla']
                    });
                    </script>

            <?php if ( (is_active_sidebar('content-area') == TRUE) ) : ?>
              <?php dynamic_sidebar("content-area"); ?>
            <?php endif; ?>

            <!-- END LIST + BLOCK puffs :-) -->
        </div><!-- /.columns -->
    </div><!-- /.main-content -->

        <div class="lower-content row">
            <div class="sidebar large-4 columns">
                <div class="row">
                  <?php if ( (is_active_sidebar('left-sidebar-bottom') == TRUE) ) : ?>
                    <?php dynamic_sidebar("left-sidebar-bottom"); ?>
                  <?php endif; ?>
                </div><!-- /.row -->
            </div><!-- /.sidebar -->

            <?php if ( (is_active_sidebar('content-area-bottom') == TRUE) ) : ?>
              <?php dynamic_sidebar("content-area-bottom"); ?>
            <?php endif; ?>

        </div><!-- /.lower-content -->
    </div>  <!-- /.main-area -->

    <div class="sidebar sidebar-right large-3 columns">
        <div class="row">

          <?php /* Add the page's widgets */ ?>
          <?php if ( (is_active_sidebar('right-sidebar') == TRUE) ) : ?>
            <?php dynamic_sidebar("right-sidebar"); ?>
          <?php endif; ?>

    </div><!-- /.rows -->
</div><!-- /.sidebar -->

</div><!-- /.article-page-layout -->
</div>
</div><!-- /.main-site-container -->

<script>
var dateToDisable1 = new Date();
dateToDisable1.setDate(dateToDisable1.getDate());

jQuery('#datetimepicker2').datetimepicker({
  beforeShowDay: function(date) {
    if (date.getMonth() == dateToDisable1.getMonth() && date.getDate() == dateToDisable1.getDate()) {
      return [false, ""]
    }
    return [true, ""];
  },
  lang:'se',
  timepicker:false,
  format:'d/m/Y',
  formatDate:'Y/m/d'
});

jQuery('#datetimepicker21').datetimepicker({
  lang:'se',
  timepicker:false,
  format:'d/m/Y',
  formatDate:'Y/m/d',

});
</script>

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/hbg.dev.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/plugins/jquery.tablesorter.min.js"></script>

<?php get_footer(); ?>
