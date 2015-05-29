<?php
    if (!defined('ABSPATH')) die('-1');

    echo $before_widget;

    preg_match_all('#<a([^>])+>(.*?)</a>#i', $instance['description'], $matches);
    $description = $instance['description'];

    if (isset($matches[2][0])) {
        $description = $matches[2][0];
    }

?>
    <?php if (!empty($instance['link'])) : ?>
    <a href="<?php echo $instance['link']; ?>" target="<?php echo $instance['linktarget']; ?>">
    <?php endif; ?>

        <img src="<?php echo $instance['imageurl']; ?>" alt="<?php echo $instance['alt']; ?>">

        <?php if (!empty($title) && !empty($description)) : ?>
        <div class="widget-content-holder">
            <?php echo (!empty($title)) ? $before_title . $title . $after_title : ''; ?>

            <?php if (!empty($description)) : ?>
            <div class="<?php echo $this->widget_options['classname']; ?>-description">
                <?php echo wpautop($description); ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php if (!empty($instance['link'])) : ?>
    </a>
    <?php endif; ?>

<?php echo $after_widget; ?>