<p>
<link href="<?php echo get_stylesheet_directory_uri() ; ?>/css/multiple-select.css" rel="stylesheet"/>

<input type="hidden" id="_helsingborg_meta[list_options]" name="_helsingborg_meta[list_options]" />

<label for="helsingborg_meta_box_select_options">Välj kolumnen att lista: </label>
<select multiple="multiple" id="helsingborg_meta_box_select_options">
  <?php
  // List has been loaded in meta-functions.php
  $i=0;
  foreach ($list as $item) {
    echo('<option value="' . $i++ . '">' . $item . '</option>');
  } ?>
</select>

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/dev/jquery.multiple.select.js"></script>
<script>
        var $ =jQuery.noConflict();
        $(function() {
          $("#helsingborg_meta_box_select_options").multipleSelect({
              selectAll: false,
              multiple: true,
              multipleWidth: 250,
              width: '100%',
              onOpen: function() {
                $("#helsingborg_meta_box_select_options").multipleSelect("setSelects", [<?php Print($selected); ?>]);
                $("#helsingborg_meta_box_select_options").multipleSelect("update");
              },
              onClose: function() {
                document.getElementById('_helsingborg_meta[list_options]').value = $("#helsingborg_meta_box_select_options").multipleSelect("getSelects");
              },
              onClick: function() {
                document.getElementById('_helsingborg_meta[list_options]').value = $("#helsingborg_meta_box_select_options").multipleSelect("getSelects");
              }
          });
        });
</script>
</p>
