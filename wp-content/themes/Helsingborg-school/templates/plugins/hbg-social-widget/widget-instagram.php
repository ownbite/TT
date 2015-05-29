<?php
    $columns = 6;
    if (isset($instance['col_count']) && $instance['col_count'] > 0) {
        $columns = ceil(12/$instance['col_count']);
    }

?>

<!-- Instagram feed -->
<div class="widget-content-holder">
    <h2><i class="fa fa-instagram"></i> Instagram</h2>
    <div class="textwidget hbg-social-feed hbg-social-feed-instagram">
        <?php if ($feed && count($feed) > 0) : ?>
        <ul>
            <?php $int = 0; foreach ($feed as $post) : ?>
            <li class="large-<?php echo $columns; ?> medium-6 small-6 columns left <?php echo $columns; ?>">
                <a href="<?php echo $post->link; ?>" target="_blank" style="background-image:url('<?php echo $post->images->low_resolution->url; ?>');">
                    <i class="zoom-icon fa fa-eye"></i>
                    <?php if ($instance['show_likes'] == 'on') : ?>
                    <span class="hbg-social-feed-instagram-likes"><i class="fa fa-heart"></i> <span><?php echo $post->likes->count; ?></span></span>
                    <?php endif; ?>
                    <img src="<?php echo $post->images->low_resolution->url; ?>" class="instagram-image">
                </a>
            </li>
            <?php $int++; if ($int == $instance['show_count']) break; endforeach; ?>
        </ul>
        <?php else : ?>
            <p>Inga bilder att visa</p>
        <?php endif; ?>
        <div class="clearfix"></div>

        <?php if (isset($instance['show_visit_button']) && $instance['show_visit_button'] == 'on') : ?>
        <div class="text-center hbg-social-feed-actions">
            <a href="http://instagram.com/<?php echo $instance['username']; ?>" target="_blank" class="button button-hbg">Besök oss på Instagram</a>
        </div>
        <?php endif; ?>
    </div>
</div>