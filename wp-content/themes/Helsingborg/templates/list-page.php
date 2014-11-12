<?php
/*
Template Name: Listsida
*/
get_header();

// Load the list used by List pages and childs
define('LIST_ARRAY', get_template_directory() . '/meta_boxes/UI/list-array.php');
include_once(LIST_ARRAY);

// Lets see which headers the user wants to use
$header_keys = [];
$fields = get_fields($post->ID);

// Get the list options for this page and create array with the values which we can use later on
$meta = get_post_meta($post->ID,'_helsingborg_meta',TRUE);
$selected_list_options_meta = $meta['list_options'];
$selected_list_options = explode(",",$selected_list_options_meta);
$headers = [];

// Compare the selection with the actual list to save key and value
foreach($list as $key => $value) {
  if (in_array(($key), $selected_list_options)) {
    array_push($header_keys, $key);
    array_push($headers, $value);
  }
}

// Get the child pages
$pages = get_pages(array(
  'sort_order' => 'DESC',
  'sort_column' => 'post_modified',
  'child_of' => $post->ID,
  'post_type' => 'page',
  'post_status' => 'publish')
);

// Create empty array that will hold our items
$list_items = array();

// Go through all childs and compare with selected keys from page
for ($i = 0; $i < count($pages); $i++) {

  // Create new empty object
  $item = array();

  // Go through all header_keys so we kan try to pick up all saved meta data
  for ($j = 0; $j < count($header_keys); $j++) {

    // Get the meta data from child
    $child_meta = get_post_meta($pages[$i]->ID,'_helsingborg_meta',TRUE);
    $data = $child_meta['article_options_'.$header_keys[$j]];

    // We dont want empty data, show "-" instead !
    if (empty($data)) $data = " - ";

    // Save this data as keyX->value
    $arr = array(
      strval('item'.$j) => $data
    );

    // Add the data to our item
    $item = array_merge($item, $arr);
  }

  // Build the content and add as array item
  $content = '<h2>' . esc_attr($pages[$i]->post_title) . '</h2>
  <div class="td-content">
    <p>' . esc_attr($pages[$i]->post_content) . '</p>
    <a href="' . esc_attr(get_permalink($pages[$i]->ID)) . '" desc="link-desc" class="read-more">Läs mer</a>
  </div>
  <span class="icon"></span>';
  $item_content = array('content' => $content);

  // Add the content to the current item
  $item = array_merge($item, $item_content);

  // Add the item to the list
  array_push($list_items, $item);
}

// JSON encode the current data for usage with knockout!
$json_items = json_encode($list_items);

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main
?>

<script src="http://knockoutjs.com/downloads/knockout-3.0.0.debug.js" type="text/javascript"></script>

<div class="list-page-layout no-page-image row">
    <!-- main-page-layout -->
        <div class="main-area large-12 columns">


        <div class="main-content row">
            <!-- SIDEBAR LEFT -->
            <div class="sidebar sidebar-left large-3 medium-4 columns">
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

            <div class="large-9 medium-8 columns article-column">

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                    <?php dynamic_sidebar("slider-area"); ?>
                </div><!-- /.row -->

                  <?php /* Start loop */ ?>
                  <?php while (have_posts()) : the_post(); ?>
                    <article class="article">
                      <header>
                        <div class="listen-to">
                            <a href="#" class="icon"><span>Lyssna på innehållet</span></a>
                        </div>
                        <?php the_breadcrumb(); ?>
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

                        <div class="filter-search">
                            <input type="text" placeholder="Sök i listan..." data-bind="value: query, valueUpdate: 'keyup'" autocomplete="off"/>
                            <input type="submit" value="sök">
                        </div>

                        <table class="table-list">
                          <thead>
                            <tr>
                              <th></th>
                              <?php foreach ($headers as $header) :
                                echo('<th>' . $header . '</th>');
                              endforeach; ?>
                            </tr>
                          </thead>
                            <!-- Todo: Generate table body -->
                          <tbody data-bind="foreach: { data: itemstoshow, afterRender: afterRender }">
                              <tr class="table-item">
                                <?php foreach ($header_keys as $key => $value) :
                                  echo('<td data-bind="text: item' . strval($key) . '"></td>');
                                endforeach; ?>
                              </tr>
                              <tr class="table-content">
                                  <td colspan="<?php echo count($headers); ?>" data-bind="html: content"></td>
                              </tr>
                          </tbody>
                        </table>

                      </div>
                      <footer>
                        <ul class="socialmedia-list">
                            <li class="fbook"><a href="#">Facebook</a></li>
                            <li class="twitter"><a href="#">Twitter</a></li>
                        </ul>

                        <script type="text/javascript" language="javascript">
                          function ListViewModel() {
                              var self = this;
                              var itemjson = <?php echo $json_items; ?>;

                              self.query = ko.observable('');

                              self.itemstoshow = ko.dependentObservable(function() {
                                  var search = this.query().toLowerCase();
                                  return ko.utils.arrayFilter(itemjson, function(item) {
                                    return ((item.content.toLowerCase().indexOf(search) >= 0) ||
                                    <?php foreach ($header_keys as $number => $key) :

                                      echo '(item.item' . strval($number) . '.toLowerCase().indexOf(search) >= 0)';
                                      if ($number != (count($header_keys) - 1)) { echo ' || '; }
                                    endforeach; ?>
                                    );

                                  });
                              }, self);

                          }
                          ko.applyBindings(new ListViewModel());

                          function afterRender() {
                            $('.show-support-nav').bind('click', function(){
                                 $('.support-nav-list').toggle();
                                 $(this).toggleClass('active');
                             });

                             $('.show-mobile-nav').bind('click', function(){
                                $(this).toggleClass('active');
                             });
                             $('.exit-off-canvas').bind('click', function(){
                                 if($('.show-mobile-nav').hasClass('active')) {
                                   $('.show-mobile-nav').removeClass('active');
                                 }
                             });

                             $('.show-mobile-search').bind('click', function(e){
                                 $('.mobile-search').toggle();
                                 e.preventDefault();
                                 $(this).toggleClass('active');
                             });


                              if($('.table-list').length > 0) {

                                 $('.table-item').bind('click', function(){
                                     if($(this).not('active')) {
                                         $('.table-item').removeClass('active');
                                         $('.table-content').removeClass('open');
                                         $(this).addClass('active');
                                         $(this).next('.table-content').addClass('open');
                                     } else if($(this).hasClass('active')){
                                         $('.table-item').removeClass('active');
                                         $('.table-content').removeClass('open');
                                     }
                                 });

                                 $('.table-list tr td:last-child').append('<span class="icon"></span>');
                                 $('.table-list .table-item:odd').addClass('odd');
                                 //$('.table-list').tablesorter();
                               }
                          }
                        </script>

                      </footer>
                    </article>
                  <?php endwhile; // End the loop ?>
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


</div><!-- /.article-page-layout -->
</div><!-- /.main-site-container -->
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/hbg.dev.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/plugins/jquery.tablesorter.min.js"></script>
<!-- END -->

<?php get_footer(); ?>
