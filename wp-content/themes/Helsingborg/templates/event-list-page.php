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
$json_event_types = json_encode($event_types);
// var_dump($json_event_types);

// Get the content, see if <!--more--> is inserted
$the_content = get_extended(strip_shortcodes($post->post_content));

$pattern = get_shortcode_regex();
preg_match('/'.$pattern.'/s', $post->post_content, $matches);
if (is_array($matches) && $matches[2] == 'gravityform') {
   $shortcode = $matches[0];
}

$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main


// TODO: Change these to proper CSS !!! ?>
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



                    <table>
                      <thead>
                        <!-- ko foreach: filter.filters -->
                          <tr>
                            <td><span data-bind="text: Name"></span>:<br /></td>
                          </tr>
                          <!-- ko if: (Type == 'select') -->
                            <tr>
                              <td>
                                <select id="events_multi" data-bind="options: Options, optionsText: 'Name', optionsValue: 'Name', value: Options, click: $root.change" ></select>
                              </td>
                            </tr>
                          <!-- /ko -->
                          <!-- ko if: (Type == 'text') -->
                            <tr>
                              <td><input type="text" data-bind="value: Value, valueUpdate: 'afterkeydown'" /></td>
                            </tr>
                          <!-- /ko -->
                          <!-- ko if: (Type == 'calendar') -->
                            <tr>
                              <td><input type="text" data-bind="value: Value, valueUpdate: 'afterkeydown', attr: {id: CalendarID}" /></td>
                            </tr>
                          <!-- /ko -->
                        <!-- /ko -->
                      </thead>
                    </table>

                    <input type="text" id="selectedTypes" style="display: none;" data-bind="textInput: selectedEventTypes"/>

                    <div class="Pager"></div>
                    <div class="NoRecords"></div>
                    <ul data-bind="template: {name:'eventTemplate',foreach: pager.currentPageRecords}" class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>
                    <div class="Pager"></div>

                    <div id="eventModal" class="reveal-modal" data-reveal>
                      <img class="modalImage"/>
                      <h2 class="modalTitle"></h2>
                      <p class="modalDate"></p>
                      <p class="modalDescription"></p>
                      <a class="close-reveal-modal">&#215;</a>
                    </div>

                    <script type="text/html" id="eventTemplate">
                      <li>
                        <a class="modalLink" href="#" data-bind="attr: {id: EventID}" data-reveal-id="eventModal" desc="link-desc">
                          <img data-bind="attr: {src: ImagePath}" alt="alt-text"/>
                          <p data-bind="text: Location" style="display: none;"></p>
                          <p data-bind="text: EventTypesName" style="display: none;"></p>
                          <h2 data-bind="text: Name" class="list-title"></h2>
                          <span data-bind="text: Date" class="news-date"></span>
                          <div data-bind="trimText: Description" class="list-content"></div>
                        </a>
                      </li>
                    </script>

                    <script>
                      jQuery(document).ready(function() {
                        jQuery(document).on('click', '.modalLink', function(event){
                            event.preventDefault();
                            var image = $('.modalImage');
                            var title = $('.modalTitle');
                            var date = $('.modalDate');
                            var description = $('.modalDescription');

                            var customer = _eventPageModel.events;
                            var result;

                            for (var i = 0; i < customer.length; i++) {
                              if (customer[i].EventID === this.id) {
                                result = customer[i];
                              }
                            }

                            jQuery(image).attr("src", result.ImagePath);
                            jQuery(title).html(result.Name);
                            jQuery(date).html(result.Date);
                            jQuery(description).html(result.Description);
                        });
                      });

                    </script>

                    <script>
                    function EventModel(data)
                    {
                      if (!data)
                      {
                        data = {};
                      }

                      var self = this;
                      self.EventID = data.EventID;
                      self.Date = data.Date;
                      self.Name = data.Name;
                      self.Description = data.Description;
                      self.ImagePath = data.ImagePath;
                      self.Location = data.Location;
                      self.EventTypesName = data.EventTypesName;
                    }

                    function TypeModel(data) {
                      if (!data)
                      {
                        data = {};
                      }

                      var self = this;
                      self.ID = data.ID;
                      self.Name = data.Name;
                    }

                    function EventPageModel(data)
                    {
                      if (!data)
                      {
                        data = {};
                      }

                      var self = this;
                      self.events = ExtractModels(self, data.events, EventModel);
                      self.eventTypes = ExtractModels(self, data.eventTypes, TypeModel);
                      self.selectedEventTypes = ko.observable();

                      self.change = function() {
                        jQuery('#selectedTypes').trigger('change');
                      }

                      var filters = [
                        {
                          Type: "text",
                          Name: "Namn",
                          Value: ko.observable(""),
                          RecordValue: function(record) { return record.Name; }
                        },
                        {
                          Type: "text",
                          Name: "Plats",
                          Value: ko.observable(""),
                          RecordValue: function(record) { return (record.Location != null) ? record.Location : ""; }
                        },
                        {
                          Type: "calendar",
                          Name: "Startdatum",
                          CalendarID: "datetimepickerstart",
                          Value: ko.observable(""),
                          RecordValue: function(record) { return (record.Date != null) ? record.Date : ""; }
                        },
                        {
                          Type: "calendar",
                          Name: "Slutdatum",
                          CalendarID: "datetimepickerend",
                          Value: ko.observable(""),
                          RecordValue: function(record) { return (record.Date != null) ? record.Date : ""; }
                        },
                        {
                          Type: "select",
                          Name: "Evenemangstyp",
                          Options: self.eventTypes,
                          CurrentOption: self.selectedEventTypes,
                          RecordValue: function(record) { return (record.EventTypesName != null ) ? record.EventTypesName : ""; }
                        }
                      ];

                      self.filter = new FilterModel(filters, self.events);
                      self.pager = new PagerModel(self.filter.filteredRecords);
                    }

                    function PagerModel(records)
                    {
                      var self = this;

                      self.records = GetObservableArray(records);
                      self.currentPageIndex = ko.observable(self.records().length > 0 ? 0 : -1);
                      self.currentPageSize = 7;
                      self.recordCount = ko.computed(function() {
                        return self.records().length;
                      });
                      self.maxPageIndex = ko.computed(function() {
                        return Math.ceil(self.records().length / self.currentPageSize) - 1;
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

                        var pageSize = self.currentPageSize;
                        var startIndex = pageIndex * pageSize;
                        var endIndex = startIndex + pageSize;
                        return self.records().slice(startIndex, endIndex);
                      }).extend({ throttle: 5 });
                      self.currentStatus = function(index) {
                        return (self.currentPageIndex() == index) ? 'current' : '';
                      };
                      self.isHidden = function(index) {
                        return (self.maxPageIndex() >= index) ? true : false;
                      }
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
                        var pager = '<ul class="pagination" role="menubar" aria-label="Pagination">';
                        pager += '<li class="arrow"><a href="#" data-bind="click: pager.movePrevious, enable: pager.currentPageIndex() > 0">&laquo; Föregående</a></li>';
                        var max = self.maxPageIndex();
                        for (i = 0; i <= max; i++) {
                          pager += '<li data-bind="css: pager.currentStatus('+i+'), visible: pager.isHidden('+i+')"><a href="#" data-bind="click: function(data, event) { pager.currentPageIndex('+i+') }">'+(i+1)+'</a></li>';
                        }
                        pager += '<li class="arrow"><a href="#" data-bind="click: pager.moveNext, enable: pager.currentPageIndex() < pager.maxPageIndex()">Nästa &raquo;</a></li>';
                        pager += '</ul>';
                        $("div.Pager").html(pager);
                      };
                      self.renderNoRecords = function() {
                        var message = "<span data-bind=\"visible: pager.recordCount() == 0\">Hittade inga event.</span>";
                        $("div.NoRecords").html(message);
                      };
                      self.renderPagers();
                      self.renderNoRecords();
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
                            if (filterOption != null)
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
                                  return filterOption.indexOf(recordValue) == -1;
                                }
                              };
                              activeFilters.push(activeFilter);
                            }
                          }
                          else if (filter.Value)
                          {
                            var filterValue = filter.Value();
                            if (filterValue && filterValue != "" && filterValue != null)
                            {
                              var activeFilter = {
                                Filter: filter,
                                IsFiltered: function(filter, record)
                                {
                                  var filterValue = filter.Value();
                                  filterValue = filterValue.toUpperCase();

                                  var recordValue = filter.RecordValue(record);
                                  recordValue = recordValue.toUpperCase();

                                  if (filter.Type == "calendar") {
                                    var recordDate   = new Date(filterValue);
                                    var selectedDate = new Date(recordValue);

                                    if (filter.Name.indexOf("Start") > -1 ){
                                      return recordDate > selectedDate;
                                    }else{
                                      return recordDate < selectedDate;
                                    }
                                  } else {
                                    return recordValue.indexOf(filterValue) == -1;
                                  }
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

                    var eventsData = {
                        events: <?php echo $json_items; ?>,
                        eventTypes: <?php echo $json_event_types; ?>
                    };

                    ko.bindingHandlers.trimText = {
                       init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
                         var trimmedText = ko.computed(function () {
                           var untrimmedText = ko.utils.unwrapObservable(valueAccessor());
                           var minLength = 5;
                           var maxLength = 250;
                           var text = untrimmedText.length > maxLength ? untrimmedText.substring(0, maxLength - 1) + '...' : untrimmedText;
                           return text;
                         });
                         ko.applyBindingsToNode(element, {
                           text: trimmedText
                         }, viewModel);
                           return {
                           controlsDescendantBindings: true
                         };
                       }
                     };
                     _eventPageModel = new EventPageModel(eventsData);
                     ko.applyBindings(_eventPageModel);

                    </script>

                    <script>
                    jQuery("select#events_multi").zmultiselect({
                      live: "#selectedTypes",
                      filter: true,
                      filterPlaceholder: 'Filtrera...',
                      filterResult: true,
                      filterResultText: "Visar",
                      selectedText: ['Valt','av'],
                      selectAll: true,
                      selectAllText: ['Markera alla','Avmarkera alla']
                    });
                    jQuery("#events_multi").zmultiselect('checkall');
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

jQuery('#datetimepickerstart').datetimepicker({
  beforeShowDay: function(date) {
    if (date.getMonth() == dateToDisable1.getMonth() && date.getDate() == dateToDisable1.getDate()) {
      return [false, ""]
    }
    return [true, ""];
  },
  lang:'se',
  timepicker:false,
  format:'Y-m-d',
  formatDate:'Y-m-d'
});

jQuery('#datetimepickerend').datetimepicker({
  lang:'se',
  timepicker:false,
  format:'Y-m-d',
  formatDate:'Y-m-d',

});
</script>

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/hbg.dev.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/plugins/jquery.tablesorter.min.js"></script>

<?php get_footer(); ?>
