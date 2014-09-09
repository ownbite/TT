<?php
/*
Template Name: Artikelsida
*/
get_header(); ?>
<div class="row">
  <div class="small-8 large-8 columns" role="main">

  <?php /* Start loop */ ?>
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
      <header>
        <h1 class="entry-title"><?php the_title(); ?></h1>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
      </footer>
      <?php comments_template(); ?>
    </article>
  <?php endwhile; // End the loop ?>

  </div>
  <div class="small-4 large-4 columns" role="main">
    <?php dynamic_sidebar("sidebar-widgets"); ?>
  </div>
</div>

<?php get_footer(); ?>
