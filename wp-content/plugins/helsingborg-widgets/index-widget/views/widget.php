 <ul class="block-list page-block-list<?php echo $list_class; ?>large-block-grid-3 medium-block-grid-3 small-block-grid-2">
<?php // Go through all list items and present as a list
foreach ($items as $num => $item) :
$item_id = $item_ids[$num];
$page = get_page($item_id, OBJECT, 'display');
if ($page->post_status !== 'publish') continue;

$link = get_permalink($page->ID);

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($page->post_content);
$main = $the_content['main'];
$content = $the_content['extended'];

$image = false;
if (has_post_thumbnail( $page->ID ) ) :
$image_id = get_post_thumbnail_id( $page->ID );
$image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' );
$alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
endif;

$title = $page->post_title;
if (isset($instance['headline' . ($num+1)]) && strlen($instance['headline' . ($num+1)]) > 0) {
$title = $instance['headline' . ($num+1)];
}
?>
<li>
<a href="<?php echo $link ?>" desc="link-desc">
<?php if($image) : ?>
<img src="<?php echo $image[0]; ?>" alt="<?php echo $alt_text; ?>">
<?php endif; ?>
<div class="list-content-container">
<h2 class="list-title"><?php echo $title ?></h2>
<div class="list-content">
<?php echo wpautop($main, true); ?>
</div>
</div>
</a>
</li>
<?php endforeach; ?>
</ul>