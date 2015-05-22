<div class="article-page-layout row">
	<!-- main-page-layout -->
	<div class="main-area large-9 columns">

		<div class="main-content row">
			<?php get_template_part('templates/partials/sidebar-left'); ?>

			<div class="large-8 medium-8 columns article-column">
				<div class="alert row"></div>

				<?php get_template_part('templates/partials/header','image'); ?>

				<!-- Slider -->
				<?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
				<div class="row <?php echo $hasSlides; ?>">
					<?php dynamic_sidebar("slider-area"); ?>
				</div><!-- /.row -->


				<?php // the_breadcrumb(); ?>
