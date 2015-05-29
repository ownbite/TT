<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?> >
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Helsingborg stad</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="pubdate" content="<?php echo the_time('d M Y'); ?>">
    <meta name="moddate" content="<?php echo the_modified_time('d M Y'); ?>">
    <meta name="google-translate-customization" content="10edc883cb199c91-cbfc59690263b16d-gf15574b8983c6459-12">

    <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/icons/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/assets/img/icons/apple-touch-icon-144x144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_template_directory_uri(); ?>/assets/img/icons/apple-touch-icon-114x114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_template_directory_uri(); ?>/assets/img/icons/apple-touch-icon-72x72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?php echo get_template_directory_uri(); ?>/assets/img/icons/apple-touch-icon-precomposed.png">

    <?php wp_head(); ?>
</head>
<body>
    <div class="off-canvas-wrap" data-offcanvas>
        <div class="inner-wrap">
            <?php get_template_part('templates/partials/navigation','off-canvas'); ?>

            <div class="main-site-container">
                <header class="header-main">
                    <div class="color-band color-band-red"></div>

                    <div class="nav-bar">
                        <div class="row">
                            <div class="large-3 medium-4 small-12 columns logotype">
                                <img class="logotype" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/olympiaskolan.svg">
                            </div>

                            <nav class="nav-mainmenu large-9 medium-8 small-4 columns clearfix">
                                <?php get_template_part('templates/partials/navigation', 'main'); ?>
                            </nav>

                            <nav class="mobile-nav" role="navigation">
                                <?php get_template_part('templates/partials/navigation', 'mobile'); ?>
                            </nav>
                        </div>
                    </div>

                    <?php
                        get_template_part('templates/partials/header', 'orbit');

                        /**
                         * Display Welcome section if this is the font page
                         */
                        if (is_front_page()) {
                            get_template_part('templates/partials/header', 'welcome');
                        }
                    ?>
                </header>