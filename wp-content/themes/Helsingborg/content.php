<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @subpackage Helsingborg
 * @since Helsingborg 1.0
 */

$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended'];
?>

<article class="article post" id="post-<?php the_ID(); ?>">
	<header>
		<h1 class="article-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
		<div class="post-date"><span class="dashicons dashicons-clock"></span> <?php the_time('Y-m-d \k\l\. H:i'); ?></div>
	</header>

	<?php the_post_thumbnail(); ?>

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

	<footer>
		<ul>
			<li>Skrevs av: <?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?></li>
			<?php $tags = get_the_tags(); if ($tags) : ?>
			<li><p><?php the_tags(_('Etiketter') . ': '); ?></p></li>
			<?php endif; ?>
		</ul>
	</footer>
</article>

<div class="divider fade">
	<div class="upper-divider"></div>
	<div class="lower-divider"></div>
</div>