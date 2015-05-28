<?php
    $columns = 6;
    if (isset($instance['col_count']) && $instance['col_count'] > 0) {
        $columns = ceil(12/$instance['col_count']);
    }
?>

<!-- Pinterest feed -->
<div class="widget-content-holder">
    <h2><i class="fa fa-pinterest"></i> Pinterest</h2>
    <div class="divider">
        <div class="upper-divider"></div>
        <div class="lower-divider"></div>
    </div>
    <div class="textwidget hbg-social-feed hbg-social-feed-pinterest">
        <?php if ($feed && count($feed) > 0) : ?>
        <ul>
            <?php $int = 0; foreach ($feed as $post) : ?>
            <li class="large-<?php echo $columns; ?> medium-<?php echo $columns; ?> small-6 columns left <?php echo $columns; ?>">
                <a href="http://pinterest.com<?php echo $post->board->url; ?>" target="_blank" style="background-image:url('<?php echo $post->images->{'237x'}->url; ?>');">
                    <span class="zoom-icon dashicons dashicons-visibility"></span>
                    <?php if ($instance['show_likes'] == 'on') : ?>
                    <span class="hbg-social-feed-instagram-likes"><i class="fa fa-heart"></i> <span><?php echo $post->likes->count; ?></span></span>
                    <?php endif; ?>
                </a>
            </li>
            <?php $int++; if ($int == $instance['show_count']) break; endforeach; ?>
        </ul>
        <?php else : ?>
            <p>Inga pins att visa</p>
        <?php endif; ?>
        <div class="clearfix"></div>

        <?php if (isset($instance['show_visit_button']) && $instance['show_visit_button'] == 'on') : ?>
        <div class="text-center hbg-social-feed-actions">
            <a href="http://pinterest.com/<?php echo $instance['username']; ?>" target="_blank" class="button button-hbg">Besök oss på Pinterest</a>
        </div>
        <?php endif; ?>
    </div>
</div>