<?php
/*
Template Name: Artikelsida
*/
get_header();
?>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css/custom.php">

<div class="row">

  <div class="small-3 large-3 columns" role="main">
    <?php dynamic_sidebar("left-sidebar"); ?>
  </div>

  <div class="small-6 large-6 columns" role="main">

  <?php /* Start loop */ ?>
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
      <header>

        <?php // Bild 1 ?>

        <?php // Bild 2 ?>

        <h1 class="entry-title"><?php the_title(); ?></h1>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
      </footer>
    </article>
  <?php endwhile; // End the loop ?>

  </div>
  <div class="small-3 large-3 columns" role="main">
    <?php dynamic_sidebar("sidebar-widgets"); ?>
  </div>
</div>

<?php get_footer(); ?>
