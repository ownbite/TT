<?php get_header(); ?>
<?php get_template_part('templates/partials/beforeblogloop','section'); ?>
<div class="row">
	<div class="small-12 medium-12 large-12 columns" role="main">
	<?php
		if (have_posts()) {
			while (have_posts()) {
				the_post();
				get_template_part('content', get_post_format());
			}
		} else {
			get_template_part('content', 'none');
		}

		if (function_exists('Helsingborg_pagination')) :
			Helsingborg_pagination();
		elseif (is_paged()) :
	?>
		<nav id="post-nav">
			<div class="post-previous"><?php next_posts_link(__('&larr; Older posts','helsingborg')); ?></div>
			<div class="post-next"><?php previous_posts_link(__('Newer posts &rarr;','helsingborg')); ?></div>
		</nav>
	<?php endif; ?>
	</div>
</div>
<?php get_template_part('templates/partials/afterblogloop','section'); ?>
<?php get_footer(); ?>