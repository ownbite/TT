<div class="hbgllw-row">
    <label>
        <b><?php echo __("OBS! Vart ska denna visas?"); ?></b>
    </label><br>
    <label for="<?php echo $this->get_field_id('show_in_content'); ?>">
            <input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_content" id="<?php echo $this->get_field_id('show_in_content'); ?>" <?php checked($show_placement, "show_in_content"); ?> />  <?php echo __("Under innehållet"); ?>
    </label>
    <label for="<?php echo $this->get_field_id('show_in_sidebar'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_sidebar" id="<?php echo $this->get_field_id('show_in_sidebar'); ?>" <?php checked($show_placement, "show_in_sidebar"); ?> /> <?php echo __("I högerkolumnen"); ?>
    </label>
</div>

<ul class="hbgllw-instructions">
    <li><?php echo __("Titel är det som visas i widgetens header."); ?></li>
</ul>

<p>
    <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

    <ul class="hbgllw-instructions">
        <li><?php echo __("Länktitel är det namn som visas för länken."); ?></li>
        <li><?php echo __("Länk är den URL som ska användas."); ?></li>
        <li><?php echo __("För att söka på interna sidor, skriv in det som söks (namn eller sid-id går bra) och klicka på sök."); ?></li>
        <li><?php echo __("Om något väljs i listan, så fylls de korrekta värdena in i 'Titel' och 'Länk', dessa kan sedan ändras efter behov."); ?></li>
        <li><?php echo __("Öppna i nytt fönster gör att länken öppnas i nytt fönster istället för i samma sida."); ?></li>
        <li><?php echo __("Visa som varning gör att länken får gul bakgrund och en varningsikon."); ?></li>
        <li><?php echo __("Visa som information gör att länken får blå bakgrund och en informationsikon."); ?></li>
    </ul>

    <div class="helsingborg-link-list">
    <?php
        foreach ($items as $num => $item) :
            $item      = esc_attr($item);
            $item_link = esc_attr($item_links[$num]);
            $checked   = checked($item_targets[$num],  'on', false);
            $checked_w = checked($item_warnings[$num], 'on', false);
            $checked_i = checked($item_infos[$num],    'on', false);
            $item_id   = esc_attr($item_ids[$num]);
            $item_date = esc_attr($item_dates[$num]);
            $name      = esc_attr($item);

            $page = get_post($item_id);
        ?>
        <div id="<?php echo $this->get_field_id($num); ?>" class="list-item">
            <h5 class="moving-handle">
                <span class="number"><?php echo $num; ?></span>.
                <span class="item-title"><?php echo $name; ?> <?php echo ($page->post_status !== 'publish') ? '<span style="color:#ff0000;font-weight:bold;font-style:italic;">(Ej publicerad)</span>' : ''; ?></span>
                <a class="hbgllw-action hide-if-no-js"></a>
            </h5>

            <div class="hbgllw-edit-item">

                <label for="<?php echo $this->get_field_id('item'.$num); ?>"><b><?php echo __("Länktitel:"); ?></b></label>
                <input  class="widefat" id="<?php echo $this->get_field_id('item'.$num); ?>" name="<?php echo $this->get_field_name('item'.$num); ?>" type="text" value="<?php echo $item; ?>" />

                <label for="<?php echo $this->get_field_id('item_link'.$num); ?>"><b><?php echo __("Länk:"); ?></b></label>
                <input  class="widefat" id="<?php echo $this->get_field_id('item_link'.$num); ?>" name="<?php echo $this->get_field_name('item_link'.$num); ?>" type="text" value="<?php echo $item_link; ?>" />

                <label for="<?php echo $this->get_field_id('item_search'.$num); ?>"><b><?php echo __("Sök efter sida: "); ?></b></label><br>
                <input style="width: 70%;" id="<?php echo $this->get_field_id('item_search'.$num); ?>" type="text" class="input-text" />

                <button style="width: 25%;" id="<?php echo $this->get_field_id('item_search_button'.$num); ?>" name="<?php echo $this->get_field_name('item_search'.$num); ?>" type="button" class="button-secondary" onclick="load_pages_with_update('<?php echo $this->get_field_id('item'); ?>', '<?php echo $num; ?>', 'update_list_item_cells')"><?php echo __("Sök"); ?></button>

                <p>
                    <div id="<?php echo $this->get_field_id('item_select'.$num); ?>" style="display: none;">
                    </div>
                </p>

                <table style="width: 100%;">
                    <tr style="width: 100%;">
                        <td>
                            <input type="checkbox" name="<?php echo $this->get_field_name('item_target'.$num); ?>" id="<?php echo $this->get_field_id('item_target'.$num); ?>" <?php echo $checked; ?> />
                            <label for="<?php echo $this->get_field_id('item_target'.$num); ?>"><?php echo __("Öppna i nytt fönster"); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" name="<?php echo $this->get_field_name('item_warning'.$num); ?>" id="<?php echo $this->get_field_id('item_warning'.$num); ?>" <?php echo $checked_w; ?> />
                            <label for="<?php echo $this->get_field_id('item_warning'.$num); ?>"><?php echo __("Visa som varning"); ?></label>
                        </td>
                        <td>
                            <input type="checkbox" name="<?php echo $this->get_field_name('item_info'.$num); ?>" id="<?php echo $this->get_field_id('item_info'.$num); ?>" <?php echo $checked_i; ?> />
                            <label for="<?php echo $this->get_field_id('item_info'.$num); ?>"><?php echo __("Visa som information"); ?></label>
                        </td>
                    </tr>
                </table>

                <input type="hidden" name="<?php echo $this->get_field_name('item_id'.$num); ?>" id="<?php echo $this->get_field_id('item_id'.$num); ?>" value="<?php echo $item_id; ?>" />

                <a class="hbgllw-delete hide-if-no-js"><img src="<?php echo plugins_url('../../assets/images/delete.png', __FILE__ ); ?>" /> <?php echo __("Remove"); ?></a>
            </div>
        </div>
    <?php
        endforeach;
        if (isset($_GET['editwidget']) && $_GET['editwidget']) :
    ?>
        <table class='widefat'>
            <thead>
                <tr>
                    <th><?php echo __("Item"); ?></th>
                    <th><?php echo __("Position/Action"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $num => $item) : ?>
                <tr>
                    <td><?php echo esc_attr($item); ?></td>
                    <td>
                        <select id="<?php echo $this->get_field_id('position'.$num); ?>" name="<?php echo $this->get_field_name('position'.$num); ?>">
                            <option><?php echo __('&mdash; Select &mdash;'); ?></option>
                            <?php
                                for ($i=1; $i<=count($items); $i++) {
                                    if ($i==$num) {
                                        echo "<option value='$i' selected>$i</option>";
                                    } else {
                                        echo "<option value='$i'>$i</option>";
                                    }
                                }
                            ?>
                            <option value="-1"><?php echo __("Delete"); ?></option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="hbgllw-row">
        <input type="checkbox" name="<?php echo $this->get_field_name('new_item'); ?>" id="<?php echo $this->get_field_id('new_item'); ?>" /> <label for="<?php echo $this->get_field_id('new_item'); ?>"><?php echo __("Add New Item"); ?></label>
        </div>
    <?php endif; ?>
    </div>

    <div class="hbgllw-row hide-if-no-js">
        <a class="hbgllw-add button-secondary"><img src="<?php echo plugins_url('../../assets/images/add.png', __FILE__ )?>" /> <?php echo __("Lägg till länk"); ?></a>
    </div>

    <input type="hidden" id="<?php echo $this->get_field_id('amount'); ?>" class="amount" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $amount ?>" />
    <input type="hidden" id="<?php echo $this->get_field_id('order'); ?>" class="order" name="<?php echo $this->get_field_name('order'); ?>" value="<?php echo implode(',',range(1,$amount)); ?>" />

    <ul class="hbgllw-instructions">
        <li><?php echo __("Om RSS-länk fylls i kommer en RSS-ikon visas i brevid widgetens titel och gå till denna länk."); ?></li>
    </ul>

    <p><label for="<?php echo $this->get_field_id('rss_link'); ?>"><?php _e('RSS Länk:'); ?></label>
    <input class="widefat" id="<?php echo $this->get_field_id('rss_link'); ?>" name="<?php echo $this->get_field_name('rss_link'); ?>" type="text" value="<?php echo esc_attr($rss_link); ?>" /></p>

    <div class="hbgllw-row">
    <input type="checkbox" name="<?php echo $this->get_field_name('show_dates'); ?>" id="<?php echo $this->get_field_id('show_dates'); ?>" <?php checked($show_dates, 'on'); ?> />
    <label for="<?php echo $this->get_field_id('show_dates'); ?>"><?php echo __("Visa datum?"); ?></label>
</div>
