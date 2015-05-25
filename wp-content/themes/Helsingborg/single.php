<?php get_header(); ?>
<?php get_template_part('templates/partials/beforeblogloop','section'); ?>
<div class="row">
	<div class="small-12 medium-12 large-12 columns" role="main">
	<?php
		while (have_posts()) {
			the_post();
			get_template_part('content', get_post_type());
			comments_template();
		}
	?>
	</div>
</div>
<?php get_template_part('templates/partials/afterblogloop','section'); ?>
<?php get_footer(); ?>