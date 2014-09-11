<!doctype html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- START -->
		<title>Helsingborg stad</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css/app.css">
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css/normalize.css">

		<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/modernizr/modernizr.min.js"></script>
		<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/jquery/dist/jquery.min.js"></script>

		<!-- STOP -->

		<link rel="icon" href="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/icons/favicon.ico" type="image/x-icon">
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/icons/apple-touch-icon-144x144-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/icons/apple-touch-icon-114x114-precomposed.png">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/icons/apple-touch-icon-72x72-precomposed.png">
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/icons/apple-touch-icon-precomposed.png">

		<?php if (!empty(get_field('color_code'))) :
			list($r,$g,$b) = array_map('hexdec',str_split(ltrim(get_field('color_code'), '#'),2)); ?>
			<style>
				.divider.fade .upper-divider,
				.divider.fade .lower-divider {
							background: rgb(<?php echo "$r,$g,$b" ?>); /* Old browsers */
							/* IE9 SVG, needs conditional override of 'filter' to 'none' */
							background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMCUiPgogICAgPHN0b3Agb2Zmc2V0PSI2MCUiIHN0b3AtY29sb3I9IiNlZDhiMDAiIHN0b3Atb3BhY2l0eT0iMSIvPgogICAgPHN0b3Agb2Zmc2V0PSIxMDAlIiBzdG9wLWNvbG9yPSIjZmZmZmZmIiBzdG9wLW9wYWNpdHk9IjEiLz4KICA8L2xpbmVhckdyYWRpZW50PgogIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZ3JhZC11Y2dnLWdlbmVyYXRlZCkiIC8+Cjwvc3ZnPg==);
							background: -moz-linear-gradient(left,  rgba(<?php echo "$r,$g,$b" ?>,1) 60%, rgba(255,255,255,1) 100%); /* FF3.6+ */
							background: -webkit-gradient(linear, left top, right top, color-stop(60%,rgba(<?php echo "$r,$g,$b" ?>,1)), color-stop(100%,rgba(255,255,255,1))); /* Chrome,Safari4+ */
							background: -webkit-linear-gradient(left,  rgba(<?php echo "$r,$g,$b" ?>,1) 60%,rgba(255,255,255,1) 100%); /* Chrome10+,Safari5.1+ */
							background: -o-linear-gradient(left,  rgba(<?php echo "$r,$g,$b" ?>,1) 60%,rgba(255,255,255,1) 100%); /* Opera 11.10+ */
							background: -ms-linear-gradient(left,  rgba(<?php echo "$r,$g,$b" ?>,1) 60%,rgba(255,255,255,1) 100%); /* IE10+ */
							background: linear-gradient(to right,  rgba(<?php echo "$r,$g,$b" ?>,1) 60%,rgba(255,255,255,1) 100%); /* W3C */
							filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo get_field("color_code") ?>', endColorstr='#ffffff',GradientType=1 ); /* IE6-8 */
							}
			</style>
	<?php endif; ?>

		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>

	<!-- HEADER START -->
	<div class="main-site-container">
			<div class="site-header row">
					<div class="site-logo large-4 medium-4 columns">
							<a href="#" class="logo-link">
									<img src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/images/hbg-logo.svg" alt="helsingborg stad" class="logo" />
							</a>
					</div><!-- /.site-logo -->
					<div class="support-nav large-8 medium-8 columns">
							<ul class="support-nav-list inline-list">
									<li><a href="#">RSS</a></li>
									<li><a href="#">Press</a></li>
									<li><a href="#">Larm</a></li>
									<li><a href="#">Teckenspråk</a></li>
									<li><a href="#">Lättläst</a></li>
									<li><a href="#">RSS</a></li>
									<li><a href="#">English</a></li>
									<li><a href="#">Translate</a></li>

							</ul>
					</div><!-- /.support-nav -->
			</div><!-- /.site-header -->
