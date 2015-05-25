				<?php if ( (is_active_sidebar('content-area') == TRUE) ) : ?>
					<?php dynamic_sidebar("content-area"); ?>
				<?php endif; ?>
			<footer>
				<?php get_template_part('templates/partials/share'); ?>
			</footer>

			<div class="timestamp">
				<?php if (get_the_modified_time() != get_the_time()) : ?>
	 				<p class= "timestamp">Publicerad: <?php the_time('j F, Y'); ?> kl <?php the_time('H:i'); ?> </BR>   Senast ändrad: <?php the_modified_time('j F, Y'); ?> kl <?php the_modified_time('H:i'); ?></p>
	 			<?php else: ?>
	 				<p class= "timestamp">Publicerad: <?php the_time('j F, Y'); ?> kl <?php the_time('H:i'); ?></p>
	 			<?php endif; ?>
 			</div><!-- /.timestamp -->

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

	<?php get_template_part('templates/partials/sidebar-right'); ?>
</div>