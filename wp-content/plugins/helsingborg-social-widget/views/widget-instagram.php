<?php
    $columns = 6;
    if (isset($instance['col_count']) && $instance['col_count'] > 0) {
        $columns = 12/$instance['col_count'];
    }

?>

<!-- Instagram feed -->
<?php echo $before_widget; ?>
<h2><i class="fa fa-instagram"></i> Instagram</h2>
<div class="divider">
    <div class="upper-divider"></div>
    <div class="lower-divider"></div>
</div>
<div class="textwidget hbg-social-feed hbg-social-feed-instagram">
    <ul>
        <?php $int = 0; foreach ($feed as $post) : ?>
        <li class="large-<?php echo $columns; ?> medium-<?php echo $columns; ?> small-6 columns left <?php echo $columns; ?>">
            <a href="<?php echo $post->link; ?>" target="_blank" style="background-image:url('<?php echo $post->images->low_resolution->url; ?>');">
                <span class="zoom-icon dashicons dashicons-visibility"></span>
                <img src="<?php echo $post->images->low_resolution->url; ?>" class="instagram-image">
            </a>
        </li>
        <?php $int++; if ($int == $instance['show_count']) break; endforeach; ?>
    </ul>
    <div class="clearfix"></div>
    <div class="text-center hbg-social-feed-actions">
        <a href="http://instagram.com/<?php echo $instance['username']; ?>" target="_blank" class="button button-hbg">Besök oss på Instagram</a>
    </div>
</div>
<?php echo $after_widget; ?>