<?php
/*
Template Name: Fullbredd
*/
get_header();
?>

<div class="full-width-page-layout row">
    <!-- main-page-layout -->
        <div class="main-area large-12 columns">

        <div class="main-content row">

            <div class="large-12 medium-12 columns">

              <div class="alert row"></div>
              <?php get_template_part('templates/partials/header','image'); ?>

                <div class="row no-image">
                </div><!-- /.row -->

                <?php the_breadcrumb(); ?>

                <?php /* Start loop */ ?>
                <?php while (have_posts()) : the_post(); ?>
                  <article class="article">
                    <header>
                      <?php get_template_part('templates/partials/accessability','menu'); ?>

                      <h1 class="article-title"><?php the_title(); ?></h1>
                    </header>
                    <div class="article-body">
                      <?php the_content(); ?>
                    </div>
                    <footer>
                      <ul class="socialmedia-list">
                          <li class="fbook"><a href="#">Facebook</a></li>
                          <li class="twitter"><a href="#">Twitter</a></li>
                      </ul>
                    </footer>
                  </article>
                <?php endwhile; // End the loop ?>

                <?php if ( (is_active_sidebar('content-area') == TRUE) ) : ?>
                  <?php dynamic_sidebar("content-area"); ?>
                <?php endif; ?>

            <!-- END LIST + BLOCK puffs :-) -->
        </div><!-- /.columns -->
    </div><!-- /.main-content -->
    </div>  <!-- /.main-area -->
</div><!-- /.article-page-layout -->
</div><!-- /.main-site-container -->


<?php get_footer(); ?>
