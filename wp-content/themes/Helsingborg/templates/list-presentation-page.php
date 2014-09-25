<?php
/*
Template Name: Listpresentation
*/
get_header();

$meta = get_post_meta($post->ID);

$header_keys = [];
$fields = get_fields($post->ID);
foreach ($fields as $field_key => $field_value) {
  if ($field_value == '0') continue;
  array_push($header_keys, str_replace('get_', '', $field_key));
}

// Get the selected lists children
$selected_node = $meta['list_select'];
$args = array(
  'sort_order' => 'DESC',
  'sort_column' => 'post_modified',
  'child_of' => $selected_node,
  'post_type' => 'page',
  'post_status' => 'publish'
);
$pages = get_pages($args);

// Create empty lists
$list[][] = null;
$headers = [];
$content = [];
$titles = [];

for ($i = 0; $i < count($pages); $i++) {
  for ($j = 0; $j < count($header_keys); $j++) {
    $data = get_field_object($header_keys[$j], $pages[$i]->ID);
    if (empty($data['value'])) continue;
    if(!in_array($data['label'], $headers, true)){
      array_push($headers, $data['label']);
    }
    $list[$i][$j] = $data['value'];
    $content[$i] = $pages[$i]->post_content;
    $titles[$i] = $pages[$i]->post_title;
  }
}

// Clean up empty fields if any!
$list = array_map('array_filter',($list));
$list = array_filter($list);
?>

<!-- START -->

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

                <!-- TODO: Slider OR empty -->
                <div class="row no-image"><!-- OBS! addera no-image om denna container INTE innehåller en orbit-slider -->
                <!-- If NO ORBIT  -->

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
                              <input type="text" placeholder="Sök i listan..."/>
                              <input type="submit" value="sök">
                          </div>

                          <table class="table-list">
                                    <thead>
                                      <tr>
                                        <th>Rubrik</th>
                                        <?php
                                        foreach ($headers as $header) {
                                          echo '<th>' . $header . '</th>';
                                        }
                                        ?>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php

                                      foreach ($list as $row => $value) {
                                        echo '<tr class="table-item">';
                                        echo('<th rowspan="2" scope="2">' . $titles[$row] . "</th>");
                                        foreach ($headers as $column => $data) {
                                          echo '<td>' . $list[$row][$column] . '</td>';
                                        }
                                        echo '</tr>';
                                        echo '<tr class="table-content">';
                                        echo '<td colspan="' . count($headers) . '">';
                                        echo '<h2>' . $titles[$row] . '</h2>';
                                        echo '<div class="td-content"><p>' . $content[$row] . '</p><div>';
                                        echo '</td>';
                                        echo '</tr>';
                                      }

                                      ?>
                                    </tbody>
                                  </table>

                      </div>
                      <footer>
                        <ul class="socialmedia-list">
                            <li class="fbook"><a href="#">Facebook</a></li>
                            <li class="twitter"><a href="#">Twitter</a></li>
                        </ul>
                      </footer>
                    </article>
                  <?php endwhile; // End the loop ?>

        </div><!-- /.columns -->
    </div><!-- /.main-content -->

        <div class="lower-content row">
            <div class="sidebar large-3 medium-4 columns">
                <div class="row">
                    <!-- PUSH LINKS -->
                    <div class="push-links-widget widget large-12 columns">
                        <ul class="push-links-list">
                            <li class="item-1"><a href="#">Självservice</a></li>
                            <li class="item-2"><a href="#">Felanmälan</a></li>
                            <li class="item-3"><a href="#">Tyck till</a></li>
                            <li class="item-4"><a href="#">Turism</a></li>
                            <li class="item-5"><a href="#">Företag</a></li>
                            <li class="item-6"><a href="#">Inspiration</a></li>

                        </ul>

                    </div><!-- /.widget -->
                </div><!-- /.row -->
            </div><!-- /.sidebar -->

            <section class="large-9 medium-8 columns">
                <ul class="block-list news-block-list large-block-grid-3 medium-block-grid-3 small-block-grid-2">
                        <li>
                            <img src="http://www.placehold.it/330x170" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x370" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                        <li>
                            <img src="http://www.placehold.it/330x270" alt="alt-text"/>
                        </li>
                    </ul>

            </section>

        </div><!-- /.lower-content -->
    </div>  <!-- /.main-area -->


</div><!-- /.article-page-layout -->
</div><!-- /.main-site-container -->
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/hbg.dev.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/knockout/dist/knockout.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/plugins/jquery.tablesorter.min.js"></script>
<!-- END -->

<?php get_footer(); ?>
