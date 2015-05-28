<div class="collection collection-test-colors">
    <div class="row">
        <?php
            foreach ($items as $num => $item) :
                $item_id = $item_ids[$num];
                $page = get_page($item_id, OBJECT, 'display');
                if ($page->post_status !== 'publish') continue;

                $link = get_permalink($page->ID);

                $the_content = get_extended($page->post_content);
                $main = $the_content['main'];
                $content = $the_content['extended'];

                $image = false;
                if (has_post_thumbnail($page->ID)) {
                    $image_id = get_post_thumbnail_id( $page->ID );
                    $image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' );
                    $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                }

                $title = $page->post_title;
                if (isset($instance['headline' . ($num+1)]) && strlen($instance['headline' . ($num+1)]) > 0) {
                    $title = $instance['headline' . ($num+1)];
                }
        ?>
        <a href="<?php echo $link ?>" class="collection-item columns large-6 medium-6 small-12 left">
            <div class="collection-item-content">
                <div class="collection-item-image" style="background-image:url('<?php echo $image[0]; ?>');"></div>
                <div class="collection-item-headline">
                    <?php echo $title; ?>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>