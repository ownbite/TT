<?php
/*
Template Name: Alarm-sök
*/
get_header();

// Get all AdministrationUnitID -> so we can
$administration_unit_ids = $_GET['q'];
if(!$administration_unit_ids) { $administration_unit_ids = 0; }

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

                <?php get_search_form(); ?>

                <div class="row">
                  <?php dynamic_sidebar("left-sidebar"); ?>
                  <?php get_template_part('templates/partials/sidebar','menu'); ?>
                </div><!-- /.row -->
            </div><!-- /.sidebar-left -->

            <div class="large-8 medium-8 columns">

                <div class="alert row"></div>
                <?php get_template_part('templates/partials/header','image'); ?>

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                    <?php dynamic_sidebar("slider-area"); ?>
                </div><!-- /.row -->

                <?php the_breadcrumb(); ?>

                <?php /* Start loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>
                      <article class="article" id="article">
                        <header>
                          <?php get_template_part('templates/partials/accessability','menu'); ?>

                          <h1 class="article-title"><?php the_title(); ?></h1>
                        </header>
                        <?php if (!empty($content)) : ?>
                          <div class="ingress">
                            <?php echo apply_filters('the_content', $main); ?>
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
                              <li class="fbook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>">Facebook</a></li>
                              <li class="twitter"><a href="http://twitter.com/share?text=<?php echo strip_tags(get_the_excerpt()); ?>&amp;url=<?php echo urlencode(wp_get_shortlink()); ?>">Twitter</a></li>
                          </ul>
                        </footer>
                      </article>
                    <?php endwhile; // End the loop ?>

                    <div class="form-container">
                    <form class="list-form">
                          <!-- ko foreach: filter.filters -->
                          <!-- ko if: (Type == 'select') -->
                                <div class="input-column">
                                  <div>
                                    <span data-bind="text: Name"></span>:
                                    <select id="municipality_multiselect" data-bind="options: Options, optionsText: 'Name', optionsValue: 'ID', value='CurrentOption'"></select>
                                  </div>
                                </div>
                          <!-- /ko -->
                          <!-- ko if: (Type == 'text') -->
                                <div class="input-column">
                                  <span data-bind="text: Name"></span>:
                                  <input type="text" class="input-text" data-bind="value: Value, valueUpdate: 'afterkeydown'" />
                                </div>
                          <!-- /ko -->
                          <!-- ko if: (Type == 'calendar') -->
                                <div class="input-column-container">
                                  <div class="input-column input-column-half">
                                      <span data-bind="text: Name"></span>
                                      <input type="text" class="input-calendar" data-bind="value: Value, valueUpdate: 'afterkeydown', attr: {id: CalendarID}" />
                                  </div>
                                </div>
                          <!-- /ko -->
                        <!-- /ko -->
                  </form><!-- /.event-list-form -->
                  </div><!-- /.form-container -->

                  <div class="list-container">

                      <h2 class="section-title">Din sökning</h2>
                      <div class="divider fade">
                        <div class="upper-divider"></div>
                        <div class="lower-divider"></div>
                      </div>

                    <div class="Pager" id="event-pager-top"></div>
                    <div class="event-list-loader" id="loading-event" style="margin-top:10px;position:relative;"></div>
                    <div class="NoEvents" id="no-event"></div>
                    <ul data-bind="template: {name:'eventTemplate',foreach: pager.currentPageEvents}" class="alarm-list block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>
                    <div class="Pager" id="event-pager-bottom"></div>

                    <!-- MODAL TEMPLATE -->
                    <div id="alarmModal" class="reveal-modal modal modal-alarm" data-reveal>
                      <div class="row">
                        <div class="modal-event-info large-12 columns">
                            <h2 class="modal-title"></h2>
                            <p class="modal-date"></p>
                            <div class="modal-description"></div>
                        </div>
                      </div>

                      <a class="close-reveal-modal">&#215;</a>
                    </div>
                    <!-- END MODAL -->

                    <script type="text/javascript">
                      var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                    </script>

                    <script type="text/html" id="eventTemplate">
                      <li class="alarm radius">
                        <a class="modal-link" href="#" data-bind="attr: {id: IDnr}" data-reveal-id="alarmModal" desc="link-desc">
                          <h2 data-bind="text: HtText" class="list-title"></h2>
                          <span data-bind="text: SentTime + ' - ' + Place" class="list-date"></span>
                          <!-- ko if: Comment.length > 0 -->
                          <div data-bind="visible: Comment.length > 5, text: Comment" class="list-content">Inget meddelande</div>
                          <!-- /ko -->
                        </a>
                      </li>
                    </script>

                    <script>
                      var _alarmPageModel = null;

                      jQuery(document).ready(function() {
                        var events = {};
                        var eventTypes = {};

                        document.getElementById('loading-event').style.display = "block";
                        document.getElementById('event-pager-top').style.display = "none";
                        document.getElementById('event-pager-bottom').style.display = "none";

                        document.getElementById('no-event').style.display = "none";

                        ko.bindingHandlers.trimText = {
                          init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
                            var trimmedText = ko.computed(function () {
                              var untrimmedText = ko.utils.unwrapObservable(valueAccessor());
                              var minLength = 5;
                              var maxLength = 250;
                              var text = untrimmedText.length > maxLength ? untrimmedText.substring(0, maxLength - 1) + '...' : untrimmedText;
                              var text = text.replace(/&nbsp;/gi, ' ');
                              var text = text.trim();
                              return text;
                            });
                            ko.applyBindingsToNode(element, {
                              text: trimmedText
                            }, viewModel);
                            return {
                              controlsDescendantBindings: true
                            }
                          }
                        };

                        _alarmPageModel = new AlarmPageModel(events);
                        ko.applyBindings(_alarmPageModel);

                        $(document).on('click', '.modal-link', function(event){
                            event.preventDefault();

                            var title = $('.modal-title');
                            var date = $('.modal-date');
                            var description = $('.modal-description');

                            description.empty();

                            var alarms = _alarmPageModel.alarms();
                            var result;

                            for (var i = 0; i < alarms.length; i++) {
                              if (alarms[i].IDnr === this.id) {
                                result = alarms[i];
                              }
                            }

                            title.html(result.HtText);
                            date.html(result.SentTime + ' - ' + result.Place);

                            if (result.Comment.length > 5) {
                              description.append('<p><strong>Kommentar</strong><br>' + result.Comment + '</p>');
                            }

                            if (result.MoreInfo.length > 0) {
                              description.append('<p><strong>Mer information</strong><br>' + result.MoreInfo + '</p>');
                            }

                            description.append('<p><strong>Address</strong><br>' + result.Address + '</p>')
                        });

                        var data = { action: 'load_alarms' };
                        jQuery.post(ajaxurl, data, function(response) {
                          _alarmPageModel.alarms(ExtractModels(_alarmPageModel, JSON.parse(response), AlarmModel));
                          document.getElementById('loading-event').style.display = "none";
                          document.getElementById('event-pager-top').style.display = "block";
                          document.getElementById('event-pager-bottom').style.display = "block";
                          document.getElementById('no-event').style.display = "block";
                        });

                        jQuery(function() {
                          var currentDate = new Date();
                          currentDate.setDate(currentDate.getDate());

                          jQuery('#datetimepickerstart').datetimepicker({
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
                      });

                      function updateEvents(checkbox) {
                        if (checkbox.checked) {
                          var data = { action: 'load_events', ids: '0' };
                          jQuery.post(ajaxurl, data, function(response) {
                            _alarmPageModel.alarms(ExtractModels(_alarmPageModel, JSON.parse(response), EventModel));
                          });
                        } else {
                          var data = { action: 'load_events', ids: '<?php echo $administration_unit_ids; ?>' };
                          jQuery.post(ajaxurl, data, function(response) {
                            _alarmPageModel.alarms(ExtractModels(_alarmPageModel, JSON.parse(response), EventModel));
                          });
                        }
                      }
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

<?php get_footer(); ?>
