<?php
/*
Template Name: Evenemang
*/
get_header();

$events      = HelsingborgEventModel::load_events();
$event_types = HelsingborgEventModel::load_event_types();

$json_items = json_encode($events);
$json_event_types = json_encode($event_types);

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main

?>

<div class="article-page-layout row">
    <!-- main-page-layout -->
        <div class="main-area large-9 columns">

        <div class="main-content row">
            <!-- SIDEBAR LEFT -->
            <div class="sidebar sidebar-left large-4 medium-4 columns">
                <div class="search-container row">
                    <div class="search-inputs large-12 columns">
                        <input type="text" placeholder="Vad letar du efter?" name="search" class="input-field"/>
                        <input type="submit" value="Sök" class="button search">
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

                <?php the_breadcrumb(); ?>

                <?php // Present the list of new events bounded to the user
                if (is_user_logged_in() && !empty($unpublished_events)) {
                  echo "<ul>";
                  foreach($unpublished_events as $new_event) {
                    echo "<a href='http://localhost/TT/wp-admin/admin.php?page=gf_entries&view=entry&id=3&lid=2&filter=&paged=1&pos=0&field_id=&operator='>";
                    echo "<li>" . $new_event->Name . " - " . $new_event->EventID . " - " . $new_event->Date . "</li></a>";
                  }
                  echo "</ul>";
                } ?>

                <?php /* Start loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>
                      <article class="article">
                        <header>
                          <?php get_template_part('templates/partials/accessability','menu'); ?>

                          <h1 class="article-title"><?php the_title(); ?></h1>
                        </header>
                        <?php if (!empty($content)) : ?>
                          <div class="ingress">
                            <?php apply_filters('the_content', $main); ?>
                          </div><!-- /.ingress -->
                        <?php endif; ?>
                        <div class="article-body">
                          <?php if(!empty($content)){
                              echo apply_filters('the_content', $content);
                            } else {
                              echo apply_filters('the_content', $main);
                            } ?>
                        </div>
                        <footer>
                          <ul class="socialmedia-list">
                              <li class="fbook"><a href="#">Facebook</a></li>
                              <li class="twitter"><a href="#">Twitter</a></li>
                          </ul>
                        </footer>
                      </article>
                    <?php endwhile; // End the loop ?>


                    <form class="list-form">
                          <!-- ko foreach: filter.filters -->
                          <!--<label data-bind="text: Name">:</label>-->
                          <!-- ko if: (Type == 'select') -->
                                <div class="input-column">
                                  <div>
                                    <label for="municipality_multiselect">Selectbox rubrik</label>
                                    <select id="municipality_multiselect">
                                      <option value="Bjuv">Bjuv</option>
                                      <option value="Helsingborg" data-selected>Helsingborg</option>
                                      <option value="Höganäs">Höganäs</option>
                                      <option value="Klippan">Klippan</option>
                                      <option value="Landskrona">Landskrona</option>
                                      <option value="Åstorp">Åstorp</option>
                                      <option value="Ängelholm">Ängelholm</option>
                                      <option value="Örkelljunga">Örkelljunga</option>
                                    </select>
                                  </div>

                                </div>
                          <!-- /ko -->
                          <!-- ko if: (Type == 'text') -->
                                <div class="input-column">
                                  <label for="ID på det specifka fältet">Input rubrik</label>
                                  <input type="text" class="input-text" data-bind="value: Value, valueUpdate: 'afterkeydown'" />
                                </div>
                          <!-- /ko -->
                          <!-- ko if: (Type == 'calendar') -->
                                <div class="input-column-container">
                                  <div class="input-column input-column-half">
                                      <label for="ID på det specifka fältet">Input rubrik</label>
                                      <input type="text" class="input-calendar" data-bind="value: Value, valueUpdate: 'afterkeydown', attr: {id: CalendarID}" />
                                  </div>
                                </div>
                          <!-- /ko -->
                        <!-- /ko -->
                        <input type="text" id="selectedTypes" style="display: none;" data-bind="textInput: selectedEventTypes"/>
                  </form><!-- /.event-list-form -->

                  <div class="list-container">

                      <h2 class="section-title">Din sökning</h2>
                      <div class="divider fade">
                        <div class="upper-divider"></div>
                        <div class="lower-divider"></div>
                      </div>

                    <div class="Pager"></div>
                    <div class="NoRecords"></div>
                        <ul data-bind="template: {name:'eventTemplate',foreach: pager.currentPageRecords}" class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>
                    <div class="Pager"></div>

                    <!-- MODAL TEMPLATE -->
                    <div id="eventModal" class="reveal-modal modal" data-reveal>
                      <img class="modal-image"/>

                      <div class="row">
                        <div class="modal-event-info large-12 columns">
                            <h2 class="modal-title"></h2>
                            <p class="modal-description"></p>
                            <!--<p class="modal-date"></p>-->
                        </div>
                      </div>
                      <!-- IF arrangör exist -->
                      <div class="row">
                      <div class="large-6 columns">
                          <h2 class="section-title">Datum, tid och plats</h2>
                          <div class="divider fade">
                            <div class="upper-divider"></div>
                            <div class="lower-divider"></div>
                          </div>

                          <ul class="modal-list">
                            <li><span>2014-12-07</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>
                            <li><span>2014-12-10</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>
                            <li><span>2014-12-11</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>
                            <li><span>2014-12-15</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>

                          </ul>
                        </div><!-- /.modal-column -->
                        <div class="large-6 columns">
                          <h2 class="section-title">Arrangör</h2>
                          <div class="divider fade">
                            <div class="upper-divider"></div>
                            <div class="lower-divider"></div>
                          </div>

                          <ul class="modal-list">
                            <li><a href="#">Tic-Net</a></li>

                          </ul>
                        </div><!-- /.modal-column -->

                        <!-- ELSE --><!--
                        <div class="large-12 columns">
                          <h2 class="section-title">Datum, tid och plats</h2>
                          <div class="divider fade">
                            <div class="upper-divider"></div>
                            <div class="lower-divider"></div>
                          </div>

                          <ul class="modal-list">
                            <li><span>2014-12-07</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>
                            <li><span>2014-12-10</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>
                            <li><span>2014-12-11</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>
                            <li><span>2014-12-15</span><span>kl 20.00</span><span>Dunkers kulturhus</span></li>

                          </ul>
                        </div>
                        --><!-- /.large-12 -->
                        <!-- END ELSE -->

                      </div><!-- /.row -->
                      <a class="close-reveal-modal">&#215;</a>
                    </div>
                    <!-- END MODAL -->

                    <script type="text/html" id="eventTemplate">
                      <li>
                        <a class="modal-link" href="#" data-bind="attr: {id: EventID}" data-reveal-id="eventModal" desc="link-desc">
                          <img data-bind="attr: {src: ImagePath}" alt="alt-text"/>
                          <p data-bind="text: Location" style="display: none;"></p>
                          <p data-bind="text: EventTypesName" style="display: none;"></p>
                          <h2 data-bind="text: Name" class="list-title"></h2>
                          <span data-bind="text: Date" class="list-date"></span>
                          <div data-bind="trimText: Description" class="list-content"></div>
                        </a>
                      </li>
                    </script>

                    <script>
                      jQuery(document).ready(function() {
                        jQuery(document).on('click', '.modal-link', function(event){
                            event.preventDefault();
                            var image = $('.modal-image');
                            var title = $('.modal-title');
                            var date = $('.modal-date');
                            var description = $('.modal-description');

                            var events = _eventPageModel.events;
                            var result;

                            for (var i = 0; i < events.length; i++) {
                              if (events[i].EventID === this.id) {
                                result = events[i];
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

                    jQuery("select#municipality_multiselect").zmultiselect({
                      live: "#selectedTypes",
                      filter: true,
                      filterPlaceholder: 'Filtrera...',
                      filterResult: true,
                      filterResultText: "Visar",
                      selectedText: ['Valt','av'],
                      selectAll: true,
                      selectAllText: ['Markera alla','Avmarkera alla']
                    });
                    // jQuery("#events_multi").zmultiselect('checkall');
                    </script>

            <?php if ( (is_active_sidebar('content-area') == TRUE) ) : ?>
              <?php dynamic_sidebar("content-area"); ?>
            <?php endif; ?>

          </div><!-- .event-list-container -->
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

<script>
jQuery(function() {
  var currentDate = new Date();
  currentDate.setDate(currentDate.getDate());

  jQuery('#datetimepickerstart').datetimepicker({
    minDate: currentDate,
    weeks: true,
    lang:'se',
    timepicker:false,
    format:'Y-m-d',
    formatDate:'Y-m-d',
    onShow:function( ct ){
     this.setOptions({
      maxDate:jQuery('#datetimepickerend').val()?jQuery('#datetimepickerend').val():false
     })
    }
  });

  jQuery('#datetimepickerend').datetimepicker({
    weeks: true,
    lang:'se',
    timepicker:false,
    format:'Y-m-d',
    formatDate:'Y-m-d',
    onShow:function( ct ){
     this.setOptions({
      minDate:jQuery('#datetimepickerstart').val()?jQuery('#datetimepickerstart').val():false
     })
    }
  });
});
</script>

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/hbg.dev.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/plugins/jquery.tablesorter.min.js"></script>

<?php get_footer(); ?>
