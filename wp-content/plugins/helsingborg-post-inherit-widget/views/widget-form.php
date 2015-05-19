<p class="hbg-post-inherit-post-id" <?php if (!isset($instance['post_id'])) : ?>style="display:none;"<?php endif; ?>>
    <label for="<?php echo $this->get_field_id('post_id'); ?>">Välj sida att visa</label><br>
    <select name="<?php echo $this->get_field_name('post_id'); ?>" id="<?php echo $this->get_field_id('post_id'); ?>" class="widefat">
        <?php if (isset($instance['post_id'])) : ?>
        <option value="<?php echo $instance['post_id']; ?>"><?php echo $instance['post_title']; ?></option>
        <?php endif; ?>
    </select>
    <input type="hidden" name="<?php echo $this->get_field_name('post_title'); ?>" value="" class="hbg-post-inherit-post-title">
</p>
<p>
    <label for="<?php echo $this->get_field_id('search'); ?>"><b><?php echo __("Sök efter arvsinnehåll att visa: "); ?></b></label><br>
    <input style="width: 70%;" id="<?php echo $this->get_field_id('search'); ?>" type="text" class="input-text hbg-post-inherit-search-string" />
    <button type="button" class="button-secondary hbg-post-inherit-search"><?php echo __("Sök"); ?></button>
</p>