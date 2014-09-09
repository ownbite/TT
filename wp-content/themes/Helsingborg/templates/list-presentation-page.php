<?php
/*
Template Name: Listpresentation
*/
get_header();

$meta = get_post_meta($post->ID,'_helsingborg_meta',TRUE);
$selected_node = $meta['list_select'];

$args = array(
  'sort_order' => 'DESC',
  'sort_column' => 'post_modified',
  'child_of' => $selected_node,
  'post_type' => 'page',
  'post_status' => 'publish'
);

$pages = get_pages($args);

$list[][] = null;
$headers = [];

// RETRIEVE DATA FROM ALL
for ($i = 0; $i < count($pages); $i++) {
  $post_meta = get_post_custom($pages[$i]->ID);

  // GET HEADERS
  foreach ($post_meta as $field_key => $field_values ) {
    if (strpos($field_key, "_", 0) === 0) continue;
    if(!in_array($field_key, $headers, true)){
      array_push($headers, $field_key);
    }
  }

  // GET ROWS
  foreach ($post_meta as $field_key => $field_values ) {
    if (strpos($field_key, "_", 0) === 0) continue;
    $list[$i][array_search($field_key, $headers)] = $field_values[0];
  }
}

?>

<div class="row">
  <div class="small-12 large-12 columns" role="main">

    <?php /* Start loop */ ?>
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <header>
          <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>
        <div class="entry-content">
          <?php the_content(); ?>
        </div>

        <table>
          <thead>
            <tr>
              <?php
              // PRINT THE HEADERS
              foreach ($headers as $header) {
                echo '<th>' . $header . '</th>';
              }
              ?>
            </tr>
          </thead>
          <tbody>
            <?php

            // PRINT ALL ROWS AND COLUMNS
            for ($j = 0; $j < count($list); $j++) {
              echo '<tr>';
              for($c = 0; $c < count($headers);$c++) {
                if (!empty($list[$j][$c])) {
                  echo '<td>' . $list[$j][$c] . '</td>';
                } else {
                  echo '<td>-</td>';
                }
              }
              echo '<tr>';
            }

            ?>
          </tbody>
        </table>

        <footer>
        </footer>
      </article>
    <?php endwhile; // End the loop ?>

  </div>
</div>

<?php get_footer(); ?>
