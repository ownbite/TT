<?php
/*
Template Name: Artikelsida
*/
get_header();

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main
?>

<div class="article-page-layout row">
    <!-- main-page-layout -->
        <div class="main-area large-9 columns">

        <div class="main-content row">
            <!-- SIDEBAR LEFT -->
            <div class="sidebar sidebar-left large-4 medium-4 columns">
                <div class="search-container row">
                    <div class="search-inputs large-12 columns">
                        <input type="text" placeholder="Vad letar du efter?" name="search" class="input-field"/>
                        <input type="submit" value="Sök" class="button search">
                    </div>
                </div><!-- /.search-container -->

                <div class="row">

                    <!-- large-up menu-->
                  <?php dynamic_sidebar("left-sidebar"); ?>
                  <?php Helsingborg_sidebar_menu(); ?>
                    <!-- END large up menu-->

                </div><!-- /.row -->
            </div><!-- /.sidebar-left -->

            <div class="large-8 medium-8 columns article-column">

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                    <?php dynamic_sidebar("slider-area"); ?>
                </div><!-- /.row -->


								<?php the_breadcrumb(); ?>

                <?php /* Start loop */ ?>
                    <?php while (have_posts()) : the_post(); ?>
                      <article class="article">
                        <header>
                          <?php get_template_part('templates/partials/accessability','menu'); ?>
                          
                          <h1 class="article-title"><?php the_title(); ?></h1>
                        </header>
                        <?php if (!empty($content)) : ?>
                          <div class="ingress">
                            <?php echo apply_filters('the_content', $main); ?>
                          </div><!-- /.ingress -->
                        <?php endif; ?>
                        <div class="article-body">
                          <?php if(!empty($content)){
															echo apply_filters('the_content', $content);
                            } else {
															echo apply_filters('the_content', $main);
														} ?>
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

    <div class="sidebar sidebar-right large-3 columns">
        <div class="row">

          <?php /* Add the page's widgets */ ?>
          <?php if ( (is_active_sidebar('right-sidebar') == TRUE) ) : ?>
            <?php dynamic_sidebar("right-sidebar"); ?>
          <?php endif; ?>

    </div><!-- /.rows -->
</div><!-- /.sidebar -->
</div><!-- /.article-page-layout -->
</div><!-- /.main-site-container -->

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/hbg.dev.js"></script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/plugins/jquery.tablesorter.min.js"></script>

<?php get_footer(); ?>
