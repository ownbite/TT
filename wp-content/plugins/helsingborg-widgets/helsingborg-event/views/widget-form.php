<ul class="hbgllw-instructions">
    <li><?php echo __("<b>OBS!</b> Denna widget bör endast användas i <b>Höger area</b> !"); ?></li>
</ul>

<ul class="hbgllw-instructions">
    <li><?php echo __("<b>Titel</b> är det som visas i widgetens header."); ?></li>
    <li><?php echo __("<b>Evenemangslänk</b> är länken till sidan som listar alla evenemang."); ?></li>
    <li><?php echo __("<b>Länktext</b> är texten på länken som går till alla evenemang."); ?></li>
    <li><?php echo __("<b>Antal evenemang</b> är hur många widgeten ska visa"); ?></li>
</ul>

<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Evenemangslänk:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Länktext:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr($link_text); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('amount'); ?>"><?php _e('Antal evenemang:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="number" value="<?php echo esc_attr($amount); ?>" />
</p>

<p>
    <label for="<?php echo $this->get_field_id('administration_units'); ?>"><?php _e('Förvaltningsenheter:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('administration_units'); ?>" name="<?php echo $this->get_field_name('administration_units'); ?>" type="text" value="<?php echo esc_attr($administration_units); ?>" />
</p>