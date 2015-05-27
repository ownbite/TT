<section class="news-section">
    <ul class="news-list-large row">
        <?php
            foreach ($items as $num => $item) :
                $item_id = $item_ids[$num];
                $page = get_page($item_id, OBJECT, 'display');
                if ($page->post_status !== 'publish') continue;

                // Get the content, see if <!--more--> is inserted
                $the_content = get_extended(strip_shortcodes($page->post_content));
                $main = $the_content['main'];
                $content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main

                $link = get_permalink($page->ID);
        ?>
        <li class="news-item large-12 columns">
            <div class="row">
                <?php if (has_post_thumbnail( $page->ID ) ) : ?>
                    <div class="large-5 medium-4 small-12 columns news-image">
                        <?php
                            $image_id = get_post_thumbnail_id( $page->ID );
                            $image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' );
                            $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                        ?>
                        <a href="<?php echo $link; ?>"><img src="<?php echo $image[0]; ?>" alt="<?php echo $alt_text; ?>"></a>
                    </div>
                <?php endif; ?>

                <div class="large-7 medium-8 small-12 columns news-content">
                    <a href="<?php echo $link; ?>">
                        <h2 class="news-title"><?php echo $page->post_title ?></h2>
                        <span class="news-date>"></span>
                        <?php echo wpautop($main, true); ?>
                        <span class="read-more">Läs mer</span>
                    </a>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</section>