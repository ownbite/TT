<?php
/*
Template Name: Start
*/
get_header();
$content = $post->post_content;
?>

<div class="main-page-layout row">
  <!-- main-page-layout -->
  <div class="main-area large-9 columns">

    <div class="row">
      <?php dynamic_sidebar("slider-area"); ?>
    </div><!-- /.row -->

<div class="main-content row">
    <!-- SIDEBAR LEFT -->
    <div class="sidebar large-4 medium-4 columns">
      <div class="row">

      <div class="search-inputs large-12 columns">
          <input type="text" placeholder="Vad letar du efter?" name="search" class="input-field"/>
          <input type="submit" value="Sök" class="button search">

      <a href="#" class="archive-search-link">S&ouml;k i arkivet</a>
      </div><!-- /.search-inputs -->

              <?php dynamic_sidebar("left-sidebar"); ?>
              <?php Helsingborg_sidebar_menu(); ?>

      </div><!-- /.row -->
    </div><!-- /.sidebar-left -->

    <?php /* Show content if available */ ?>
    <?php if(!empty($content)) : ?>
      <div class="large-8 medium-8 columns">
        <?php echo apply_filters('the_content', $content); ?>
      </div>
    <?php endif; ?>

    <section class="news-section large-8 medium-8 columns">

      <?php get_template_part('templates/partials/accessability','menu'); ?>

      <?php $title = get_field('content_title'); ?>
      <h1 class="section-title"><?php echo $title; ?></h1>

      <div class="divider fade">
          <div class="upper-divider"></div>
          <div class="lower-divider"></div>
      </div>

      <?php /* Start listing the news */ ?>
      <?php dynamic_sidebar("content-area"); ?>

    </section>
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

    <div class="sidebar large-3 columns">
        <div class="row">

          <?php /* Add the page's widgets */ ?>
          <?php dynamic_sidebar("right-sidebar"); ?>

      </div><!-- /.rows -->
    </div><!-- /.sidebar -->

  </div>
</div><!-- /.main-site-container -->

<?php get_footer(); ?>
