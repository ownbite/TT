<ul class="hbgllw-instructions">
    <li><?php echo __("Lägg till de sidor som ni vill ska visas i listan."); ?></li>
</ul>

<div class="helsingborg-link-list">
<?php
foreach ($items as $num => $item) :
    $item = esc_attr($item);
    $item_id = esc_attr($item_ids[$num]);
    $h5 = esc_attr($item);

    if (!empty($item_id)) {
        $h5 = get_post($item_id, OBJECT, 'display')->post_title;
    }

    $page = get_post($item_id);
?>

<div id="<?php echo $this->get_field_id($num); ?>" class="list-item">
    <h5 class="moving-handle"><span class="number"><?php echo $num; ?></span>. <span class="item-title"><?php echo $h5; ?>
        <?php echo ($page->post_status !== 'publish') ? '<span style="color:#ff0000;font-weight:bold;font-style:italic;">(Ej publicerad)</span>' : ''; ?></span>
        <a class="hbgllw-action hide-if-no-js"></a>
    </h5>

    <div class="hbgllw-edit-item">
        <p>
            <label for="<?php echo $this->get_field_id('item_id'.$num); ?>"><?php echo __("Sida att söka efter: "); ?></label><br>
            <input id="input_<?php echo $this->get_field_id('item_id'.$num); ?>" type="text" class="input-text" />
            <button id="button_<?php echo $this->get_field_id('item_id'.$num); ?>" name="<?php echo $this->get_field_name('item_id'.$num); ?>" type="button" class="button-secondary" onclick="load_page_containing(this.id, this.name)"><?php echo __("SÖK"); ?></button>
        </p>

        <div id="select_<?php echo $this->get_field_id('item_id'.$num); ?>" style="display: none;">
            <select id="<?php echo $this->get_field_id('item_id'.$num); ?>" name="<?php echo $this->get_field_name('item_id'.$num); ?>">
                <option value="<?php echo $item_id; ?>"><?php echo $h5; ?></option>
            </select>
        </div>
        <a class="hbgllw-delete hide-if-no-js"><img src="<?php echo plugins_url('../../assets/images/delete.png', __FILE__ ); ?>" /> <?php echo __("Remove"); ?></a>
        <br>
    </div>
</div>

<?php
    endforeach;
    if ( isset($_GET['editwidget']) && $_GET['editwidget'] ) :
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
                            if ($i == $num){
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
    <a class="hbgllw-add button-secondary"><img src="<?php echo plugins_url('../../assets/images/add.png', __FILE__ )?>" /> <?php echo __("Lägg till indexobjekt"); ?></a>
</div>

<input type="hidden" id="<?php echo $this->get_field_id('amount'); ?>" class="amount" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $amount ?>" />
<input type="hidden" id="<?php echo $this->get_field_id('order'); ?>" class="order" name="<?php echo $this->get_field_name('order'); ?>" value="<?php echo implode(',',range(1,$amount)); ?>" />
