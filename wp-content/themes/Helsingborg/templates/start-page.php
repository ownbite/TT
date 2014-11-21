<?php
/*
Template Name: Startsida
*/
get_header(); ?>

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
          <input type="text" placeholder="Vad letar du efter?" name="search"/>
          <input type="submit" value="Sök">

      <a href="#" class="archive-search-link">S&ouml;k i arkivet</a>
      </div><!-- /.search-inputs -->

              <?php dynamic_sidebar("left-sidebar"); ?>
              <?php Helsingborg_sidebar_menu(); ?>

      </div><!-- /.row -->
    </div><!-- /.sidebar-left -->

            <section class="news-section large-8 medium-8 columns">
                    <div class="listen-to">
                        <a href="#" class="icon"><span>Lyssna på innehållet</span></a>
                    </div>

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
