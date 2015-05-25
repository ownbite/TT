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
        <div class="alert row"></div>
        <?php get_template_part('templates/partials/header','image'); ?>

        <div class="row">
            <?php dynamic_sidebar("slider-area"); ?>
        </div>

        <div class="main-content row">
            <?php get_template_part('templates/partials/sidebar-left'); ?>

            <?php if (!empty($content)) : ?>
                <div class="start-content large-8 medium-8 columns">
                    <?php echo apply_filters('the_content', $content); ?>
                </div>
            <?php endif; ?>

            <section class="news-section large-8 medium-8 columns" id="article">
                <?php get_template_part('templates/partials/accessability','menu'); ?>

                <?php $title = get_field('content_title'); ?>
                <h1 class="section-title"><?php echo $title; ?></h1>
                <div class="divider fade">
                    <div class="upper-divider"></div>
                    <div class="lower-divider"></div>
                </div>

                <?php dynamic_sidebar("content-area"); ?>

            </section>
        </div>

        <div class="lower-content row">
            <div class="sidebar large-4 columns">
            <div class="row">

            </div><!-- /.row -->
            </div><!-- /.sidebar -->

            <?php
                if ((is_active_sidebar('content-area-bottom') == TRUE)) {
                    dynamic_sidebar("content-area-bottom");
                }
            ?>
        </div>
    </div>

    <div class="sidebar large-3 columns">
        <div class="row">
            <?php dynamic_sidebar("right-sidebar"); ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
