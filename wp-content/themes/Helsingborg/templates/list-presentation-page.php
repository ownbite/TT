<?php
/*
Template Name: Listpresentation
*/
get_header();

// Lets see which headers the user wants to use
$header_keys = [];
$fields = get_fields($post->ID);
foreach ($fields as $field_key => $field_value) {
  if ($field_value == '0') continue;
  array_push($header_keys, str_replace('get_', '', $field_key));
}

// Get the selected lists children
$meta = get_post_meta($post->ID);
$selected_node = $meta['list_select'];
$args = array(
  'sort_order' => 'DESC',
  'sort_column' => 'post_modified',
  'child_of' => $selected_node,
  'post_type' => 'page',
  'post_status' => 'publish'
);

// Get the pages
$pages = get_pages($args);
$headers = [];
$list = array();

// Go through all childs and compare with selected keys from page
for ($i = 0; $i < count($pages); $i++) {
  $item = array();
  for ($j = 0; $j < count($header_keys); $j++) {

    // Get the meta data from child
    $data = get_field_object($header_keys[$j], $pages[$i]->ID);

    // We dont want empty data !
    if (empty($data['value'])) continue;
    if(!in_array($data['label'], $headers, true)){
      array_push($headers, $data['label']);
    }

    // Save this data as key->value
    $arr = array(
      $header_keys[$j] => $data['value']
    );

    // No need to add if empty
    if (empty($arr)) continue;
    $item = array_merge($item, $arr);
  }

  // No need to add if empty
  if (empty($item)) continue;

  // These fields are always added.
  $item_content = array(
    'content' => esc_attr($pages[$i]->post_content),
    'title' => esc_attr($pages[$i]->post_title)
  );

  // Add the content to the current item
  $item = array_merge($item, $item_content);

  // Add the item to the list
  array_push($list, $item);
}

// JSON encode the current data for usage with knockout!
$json_items = json_encode($list);

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
                    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
                      <header>
                        <div class="listen-to">
                            <a href="#" class="icon"><span>Lyssna på innehållet</span></a>
                        </div>
                        <h1 class="article-title"><?php the_title(); ?></h1>
                      </header>
                      <div class="article-body">

                        <p>
                        <?php the_content(); ?>
                        </p>

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
                                <?php foreach ($header_keys as $key) :
                                  echo('<td data-bind="text: ' . $key . '"></td>');
                                endforeach; ?>
                              </tr>
                              <tr class="table-content">
                                  <td colspan="<?php echo count($headers); ?>" data-bind="text: content"></td>
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
                              var itemjson = <?php echo $json_items; ?>

                              self.query = ko.observable('');

                              self.itemstoshow = ko.dependentObservable(function() {
                                  var search = this.query().toLowerCase();
                                  return ko.utils.arrayFilter(itemjson, function(item) {
                                    return ((item.content.toLowerCase().indexOf(search) >= 0) ||
                                    (item.title.toLowerCase().indexOf(search) >= 0) ||
                                    <?php foreach ($header_keys as $number => $key) :
                                      echo '(item.' . $key . '.toLowerCase().indexOf(search) >= 0)';
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
