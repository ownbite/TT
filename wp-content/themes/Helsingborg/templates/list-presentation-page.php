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
$content = [];

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
    $content[$i] = $pages[$i]->post_content;
  }
}

?>

<script>

    jQuery(document).ready(function(){
        jQuery("#table tr:odd").addClass("odd");
        jQuery("#table tr:not(.odd)").hide();
        jQuery("#table tr:first-child").show();

        jQuery("#table tr.odd").click(function(){
            jQuery(this).next("tr").toggle();
            jQuery(this).find(".arrow").toggleClass("up");
        });
        //$("#report").jExpand();
    });

</script>

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

        <table id="table">
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
            for ($row = 0; $row < count($list); $row++) {
              echo '<tr>';
              for($column = 0; $column < count($headers);$column++) {
                if (!empty($list[$row][$column])) {
                  echo '<td>' . $list[$row][$column] . '</td>';
                } else {
                  echo '<td>-</td>';
                }
              }
              echo '</tr>';
              echo '<tr>';
                echo '<td colspan="4">' . $content[$row] . '</td>';
              echo '</tr>';
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
