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

                    <ul data-bind="template: { name: 'event-template', foreach: event }" class="block-list page-block-list page-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>
                    <script type="text/html" id="event-template">
                        <li>
                          <a href="#" desc="link-desc">
                            <img data-bind="attr: { src: ImagePath }" />
                            <h2 class="list-title" data-bind="text: Name"></h2>
                            <span data-bind="text: Date" class="news-date"></span>
                            <div data-bind="trimText: Description" class="list-content"></div>
                          </a>
                        </li>
                    </script>

                    <script>
                     function EventViewModel() {
                       this.event = <?php echo $json_items; ?>                     }

                       ko.bindingHandlers.trimLengthText = {};
                       ko.bindingHandlers.trimText = {
                         init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
                           var trimmedText = ko.computed(function () {
                             var untrimmedText = ko.utils.unwrapObservable(valueAccessor());
                             var defaultMaxLength = 250;
                             var minLength = 5;
                             var maxLength = ko.utils.unwrapObservable(allBindingsAccessor().trimTextLength) || defaultMaxLength;
                             if (maxLength < minLength)
                               maxLength = minLength;
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

                       ko.applyBindings(new EventViewModel());
                    </script>

                    <ul class="pagination" role="menubar" aria-label="Pagination">
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
                    </ul>

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
