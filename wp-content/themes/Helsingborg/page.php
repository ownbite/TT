<?php
get_header();

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main
?>

<div class="article-page-layout row">
	<div class="main-area large-9 columns">
		<div class="main-content row">

			<!-- SIDEBAR LEFT -->
			<div class="sidebar sidebar-left large-4 medium-4 columns">
				<?php get_search_form(); ?>

				<div class="row">
				<?php dynamic_sidebar("left-sidebar"); ?>
				<?php get_template_part('templates/partials/sidebar','menu'); ?>
				<?php
					if ( (is_active_sidebar('left-sidebar-bottom') == TRUE) ) {
						dynamic_sidebar("left-sidebar-bottom");
					}
				?>
				</div>
			</div>

			<div class="large-8 medium-8 columns article-column">
				<div class="alert row"></div>
				<?php get_template_part('templates/partials/header','image'); ?>

				<!-- Slider -->
				<?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
				<div class="row <?php echo $hasSlides; ?>">
					<?php dynamic_sidebar("slider-area"); ?>
				</div>


				<?php the_breadcrumb(); ?>

				<?php /* Start loop */ ?>
				<?php while (have_posts()) : the_post(); ?>
					<article class="article" id="article">
						<header>
							<h1 class="article-title"><?php the_title(); ?></h1>
							<?php get_template_part('templates/partials/accessability','menu'); ?>
						</header>

						<?php if (!empty($content)) : ?>
						<div class="ingress">
							<?php echo apply_filters('the_content', $main); ?>
						</div>
						<?php endif; ?>

						<div class="article-body">
						<?php
							if (!empty($content)) {
								echo apply_filters('the_content', $content);
							} else {
								echo apply_filters('the_content', $main);
							}
						?>
						</div>
					</article>
				<?php endwhile; // End the loop ?>

				<?php if ( (is_active_sidebar('content-area') == TRUE) ) : ?>
					<?php dynamic_sidebar("content-area"); ?>
					<div class="clearfix"></div>
				<?php endif; ?>

				<footer>
					<ul class="socialmedia-list">
						<li class="fbook"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_the_permalink()); ?>">Facebook</a></li>
						<li class="twitter"><a href="http://twitter.com/share?url=<?php echo urlencode(wp_get_shortlink()); ?>">Twitter</a></li>
					</ul>
				</footer>

				<div class="timestamp">
					<?php if (get_the_modified_time() != get_the_time()) : ?>
						<p class= "timestamp">Publicerad: <?php the_time('j F, Y'); ?> kl <?php the_time('H:i'); ?> </BR>   Senast ändrad: <?php the_modified_time('j F, Y'); ?> kl <?php the_modified_time('H:i'); ?></p>
					<?php else: ?>
						<p class= "timestamp">Publicerad: <?php the_time('j F, Y'); ?> kl <?php the_time('H:i'); ?></p>
					<?php endif; ?>
				</div>

			</div><!-- /.columns -->
		</div><!-- /.main-content -->

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

	<div class="sidebar sidebar-right large-3 columns">
		<div class="row">
		<?php
			if ( (is_active_sidebar('right-sidebar') == TRUE) ) {
				dynamic_sidebar("right-sidebar");
			}
		?>
		</div>
	</div>
</div>
<?php get_footer(); ?>
