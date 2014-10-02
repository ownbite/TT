<?php
/*
 
*/
get_header(); ?>
<div class="row">
	<div class="small-12 large-12 columns" role="main">

	<?php /* Start loop */ ?>
	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
			<footer>

				<!-- Do stuff -->

				<?php

					$args = array(
						'post_parent' => $post->ID,
						'sort_order' => 'DESC',
						'sort_column' => 'post_title',
						'post_type' => 'page',
						'post_status' => 'publish'
					);

					$pages = get_children($args);

					foreach ($pages as $page) {

					if (has_post_thumbnail( $page->ID ) ):
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'single-post-thumbnail' );
						?>
						<div id="custom-bg" style="background-image: url('')">
						</div>
						<img src="<?php echo $image[0]; ?>'>
					  <?php endif;

						echo " : ";
					}

				/* $args = array(
					'sort_order' => 'ASC',
					'sort_column' => 'post_title',
					'post_type' => 'page',
					'post_status' => 'publish'
				);
				$pages = get_pages($args);

				foreach ($pages as $page) {
					echo $page->post_title;
				}

				*/
				?>

			</footer>
			<?php //comments_template(); ?>
		</article>
	<?php endwhile; // End the loop ?>

	</div>
</div>

<?php get_footer(); ?>
