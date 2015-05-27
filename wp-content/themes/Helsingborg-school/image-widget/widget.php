<?php
    if (!defined('ABSPATH')) die('-1');

    echo $before_widget;
    echo $this->get_image_html($instance, true);

?>

    <div class="widget-content-holder">
        <?php echo (!empty($title)) ? $before_title . $title . $after_title : ''; ?>

        <?php if (!empty($description)) : ?>
        <div class="<?php echo $this->widget_options['classname']; ?>-description">
            <?php echo wpautop($description); ?>
        </div>
        <?php endif; ?>
    </div>

<?php echo $after_widget; ?>