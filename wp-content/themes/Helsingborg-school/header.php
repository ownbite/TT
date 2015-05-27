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
            <?php get_template_part('templates/partials/mobile','menu'); ?>

            <a class="exit-off-canvas"></a>
            <nav class="mobile-nav" role="navigation">
                <div class="mobile-navigation clearfix" role="navigation">
                        <a href="#" class="show-mobile-nav left-off-canvas-toggle">Meny</a>
                        <a href="#" class="show-mobile-search">Sök</a>
                </div>
                <div class="mobile-search">
                    <div class="mobile-search-input-container">
                        <form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
                            <input type="text" class="mobile-search-input" name="s" placeholder="Din S&ouml;kning"/>
                            <input type="submit" class="mobile-search-btn" value="s&ouml;k" />
                        </form>
                    </div>
                </div>
            </nav>

            <div class="main-site-container">
                <header class="header-main">
                    <div class="color-band color-band-red"></div>

                    <div class="nav-bar">
                        <div class="row">
                            <div class="large-3 medium-4 small-8 columns">
                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/olympiaskolan.svg">
                            </div>

                            <nav class="nav-mainmenu large-9 medium-8 small-4 columns clearfix">
                                <ul>
                                    <li><a href="#">Hem</a></li>
                                    <li><a href="#">Ansök</a></li>
                                    <li><a href="#">Elev</a></li>
                                    <li><a href="#">Framtid</a></li>
                                    <li><a href="#">Program</a></li>
                                    <li><a href="#">Skolan</a></li>
                                    <li><a href="#">Kontakt</a></li>
                                    <li><a href="#"><i class="fa fa-search"></i></a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <?php get_template_part('templates/partials/header', 'orbit'); ?>

                    <?php get_template_part('templates/partials/header', 'welcome'); ?>
                </header>