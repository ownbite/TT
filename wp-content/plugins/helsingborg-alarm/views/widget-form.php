<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Arkivlänk:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('amount'); ?>"><?php _e('Antal alarm:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="number" value="<?php echo esc_attr($amount); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('rss_link'); ?>"><?php _e('RSS Länk:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('rss_link'); ?>" name="<?php echo $this->get_field_name('rss_link'); ?>" type="text" value="<?php echo esc_attr($rss_link); ?>" />
</p>