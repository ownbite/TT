
<div class="hbgllw-row">
    <label><b>Vilken design ska widgeten ha?</b></label><br>
    <label for="<?php echo $this->get_field_id('show_big_design'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_layout'); ?>" value="show_big_design" id="<?php echo $this->get_field_id('show_big_design'); ?>" <?php checked($show_placement, "show_big_design"); ?> />  <?php echo __("Stor design"); ?></label>
    <label for="<?php echo $this->get_field_id('show_thin_design'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_layout'); ?>" value="show_thin_design" id="<?php echo $this->get_field_id('show_thin_design'); ?>" <?php checked($show_placement, "show_thin_design"); ?> /> <?php echo __("Smal design"); ?></label>
</div>

<input id="<?php echo $this->get_field_id( 'post_id' ); ?>" name="<?php echo $this->get_field_name( 'post_id' ); ?>" type="hidden" value="<?php echo $post_id; ?>">

<ul class="hbgllw-instructions">
    <li><?php echo __('Lägg till den sida med sidmallen "Samling" som ni vill ska visas i Timeline-widget.'); ?></li>
</ul>
<div class="helsingborg-link-list">
     <?php
         if ($item_id) {
            $item_title = get_post($item_id)->post_title;
            echo '<p>Vald sida är: '.$item_title.'</p>';
         }
     ?>
    <p>
        <label for="<?php echo $this->get_field_id('item_id'); ?>"><?php echo __("Sida att söka efter: "); ?></label><br>
        <input id="input_<?php echo $this->get_field_id('item_id'); ?>" type="text" class="input-text" />
        <button id="button_<?php echo $this->get_field_id('item_id'); ?>" name="<?php echo $this->get_field_name('item_id'); ?>" type="button" class="button-secondary" onclick="load_page_containing(this.id, this.name)"><?php echo __("SÖK"); ?></button>
    </p>
    <div id="select_<?php echo $this->get_field_id('item_id'); ?>" style="display: none;">
        <select id="<?php echo $this->get_field_id('item_id'); ?>" name="<?php echo $this->get_field_name('item_id'); ?>">
            <option value="<?php echo $item_id; ?>"><?php echo $item_title; ?></option>
        </select>
    </div>
</div>


<script type="text/javascript">
    function load_page_containing(from, name) {
        var id = from.replace('button_', '');

        document.getElementById('select_' + id).style.display = "block";
        document.getElementById('select_' + id).innerHTML = "";

        var data = {
        action: 'load_pages',
        id: id,
        name: name,
        title: document.getElementById('input_' + id).value
        };

        jQuery.post(ajaxurl, data, function(response) {
        document.getElementById('select_' + id).innerHTML = response;
        console.log(response);
        });
    };
</script>