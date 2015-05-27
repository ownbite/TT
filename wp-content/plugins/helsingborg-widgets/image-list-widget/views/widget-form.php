<div class="hbgllw-row">
    <label>
        <b>OBS! Vart ska denna visas?</b>
    </label><br>
    <label for="<?php echo $this->get_field_id('show_in_content'); ?>">
        <input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_content" id="<?php echo $this->get_field_id('show_in_content'); ?>" <?php checked($show_placement, "show_in_content"); ?> />  <?php echo __("Under innehållet"); ?>
    </label>
    <label for="<?php echo $this->get_field_id('show_in_sidebar'); ?>">
        <input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_sidebar" id="<?php echo $this->get_field_id('show_in_sidebar'); ?>" <?php checked($show_placement, "show_in_sidebar"); ?> /> <?php echo __("I sidokolumn"); ?>
    </label>
    <label for="<?php echo $this->get_field_id('show_in_slider'); ?>">
        <input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_slider" id="<?php echo $this->get_field_id('show_in_slider'); ?>" <?php checked($show_placement, "show_in_slider"); ?> /> <?php echo __("I bildspel"); ?>
    </label>
</div>

<div class="hbgllw-instructions">
    <?php echo __("<b>Bildmått: 1024 x 400 pixlar.</b>"); ?>
</div>

<ul class="hbgllw-instructions">
    <li style="word-break: break-all;"><?php echo __("Notera att <b>minst</b> två bilder måste användas i denna <br> widget om den ska befinna sig under innehållet!"); ?></li>
</ul>

<div class="helsingborg-link-list">
    <?php
        // Now render each item
        foreach ($items as $num => $item) :
            $item               = esc_attr($item);
            $item_link          = esc_attr($item_links[$num]);
            $item_id            = esc_attr($item_ids[$num]);
            $image_title        = esc_attr($item_titles[$num]);
            $image_url          = esc_attr($item_imageurl[$num]);
            $attachement_id     = esc_attr($item_attachement_id[$num]);
            $item_text          = esc_attr($item_texts[$num]);
            $force_margin_value = esc_attr($item_force_margin_values[$num]);
            $force_width        = checked($item_force_widths[$num],  'on', false);
            $force_margin       = checked($item_force_margins[$num], 'on', false);
            $checked            = checked($item_targets[$num],       'on', false);
            $button_click       = "helsingborgMediaSelector.create('" . $this->get_field_id($num) . "', '" . $this->get_field_id('') . "', '" . $num . "' ); return false;";
    ?>

            <div id="<?php echo $this->get_field_id($num); ?>" class="list-item">
                <h5 class="moving-handle">
                    <span class="number"><?php echo $num; ?></span>. <span class="item-title"><?php echo $image_title; ?></span>
                    <a class="hbgllw-action hide-if-no-js"></a>
                </h5>

                <div class="hbgllw-edit-item" style="display: table;margin: auto;">
                    <div class="uploader" style="display: table;margin: auto;">
                        <br>
                        <div class="widefat" id="<?php echo $this->get_field_id('preview'.$num); ?>">
                        <img src="<?php echo $image_url; ?>" style="max-width: 80%;display: table;margin:auto;"/>
                        </div>
                        <br>
                        <input type="submit" class="button" style="display: table; margin: auto;" name="<?php echo $this->get_field_name('uploader_button'.$num); ?>" id="<?php echo $this->get_field_id('uploader_button'.$num); ?>" value="Välj bild" onclick="<?php echo $button_click; ?>" />
                        <input type="hidden" id="<?php echo $this->get_field_id('title'.$num); ?>" name="<?php echo $this->get_field_name('title'.$num); ?>" value="<?php echo $instance['title'.$num]; ?>" />
                        <input type="hidden" id="<?php echo $this->get_field_id('imageurl'.$num); ?>" name="<?php echo $this->get_field_name('imageurl'.$num); ?>" value="<?php echo $instance['imageurl'.$num]; ?>" />
                        <input type="hidden" id="<?php echo $this->get_field_id('alt'.$num); ?>" name="<?php echo $this->get_field_name('alt'.$num); ?>" value="<?php echo esc_attr(strip_tags($instance['alt'])); ?>" />
                    </div>
                    <br clear="all" />

                    <label for="<?php echo $this->get_field_id('item_link'.$num); ?>"><?php echo __("Länk:"); ?></label>
                    <input class="widefat" id="<?php echo $this->get_field_id('item_link'.$num); ?>" name="<?php echo $this->get_field_name('item_link'.$num); ?>" type="text" value="<?php echo $item_link; ?>" />

                    <label for="<?php echo $this->get_field_id('item_search'.$num); ?>"><b><?php echo __("Sök efter sida: "); ?></b></label><br>
                    <input style="width: 70%;" id="<?php echo $this->get_field_id('item_search'.$num); ?>" type="text" class="input-text" />
                    <button style="width: 25%;" id="<?php echo $this->get_field_id('item_search_button'.$num); ?>" name="<?php echo $this->get_field_name('item_search'.$num); ?>" type="button" class="button-secondary" onclick="load_pages_with_update('<?php echo $this->get_field_id('item'); ?>', '<?php echo $num; ?>', 'update_list_item_cells')"><?php echo __("Sök"); ?></button>

                    <p>
                        <div id="<?php echo $this->get_field_id('item_select'.$num); ?>" style="display: none;"></div>
                    </p>

                    <label for="<?php echo $this->get_field_id('item_text'.$num); ?>"><?php echo __("Bildspelstext:"); ?></label>
                    <textarea rows="4" cols="30" id="<?php echo $this->get_field_id('item_text'.$num); ?>" name="<?php echo $this->get_field_name('item_text'.$num); ?>" type="text" style="width:100%;"><?php echo $item_text; ?></textarea>

                    <ul class="hbgllw-instructions">
                        <li><?php echo __("<b>Bildinställningar</b>"); ?></li>
                    </ul>

                    <input type="checkbox" name="<?php echo $this->get_field_name('item_force_width'.$num); ?>" id="<?php echo $this->get_field_id('item_force_width'.$num); ?>" value="on" data-clear="false" <?php echo $force_width; ?> />
                    <label for="<?php echo $this->get_field_id('item_force_width'.$num); ?>"><?php echo __("Tvinga bilden att anpassa i bredd (endast bildspel)"); ?></label>
                    <br>

                    <input type="checkbox" data-clear="false" value="on" name="<?php echo $this->get_field_name('item_force_margin'.$num); ?>" id="<?php echo $this->get_field_id('item_force_margin'.$num); ?>" <?php echo $force_margin; ?> />
                    <label for="<?php echo $this->get_field_id('item_force_margin'.$num); ?>"><?php echo __("Tvinga förskjutning i Y-led med "); ?></label>
                    <input maxlength="4" size="4" id="<?php echo $this->get_field_id('item_force_margin_value'.$num); ?>" name="<?php echo $this->get_field_name('item_force_margin_value'.$num); ?>" type="text" value="<?php echo $force_margin_value; ?>" />
                    <label for="<?php echo $this->get_field_id('item_force_margin_value'.$num); ?>"><?php echo __(" pixlar. (endast bildspel)"); ?></label>
                    <br>

                    <input type="checkbox" name="<?php echo $this->get_field_name('item_target'.$num); ?>" id="<?php echo $this->get_field_id('item_target'.$num); ?>" <?php echo $checked; ?> />
                    <label for="<?php echo $this->get_field_id('item_target'.$num); ?>"><?php echo __("Öppna i nytt fönster"); ?></label>
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
                        for ($i = 1; $i <= count($items); $i++) {
                            if ($i == $num) {
                                echo "<option value='$i' selected>$i</option>";
                            } else {
                                echo "<option value='$i'>$i</option>";
                            }
                        } ?>
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
    <a class="hbgllw-add button-secondary"><img src="<?php echo plugins_url('../../assets/images/add.png', __FILE__ )?>" /> <?php echo __("Lägg till bild"); ?></a>
</div>

<input type="hidden" id="<?php echo $this->get_field_id('amount'); ?>" class="amount" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $amount ?>" />
<input type="hidden" id="<?php echo $this->get_field_id('order'); ?>" class="order" name="<?php echo $this->get_field_name('order'); ?>" value="<?php echo implode(',',range(1,$amount)); ?>" />
