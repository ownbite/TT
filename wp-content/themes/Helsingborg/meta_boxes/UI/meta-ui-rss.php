<div class="helsingborg_meta_control">
  <p>
      <label for="helsingborg_meta_box_select">Välj nod att bygga RSS från: </label>
      <select name="_helsingborg_meta[rss_select]" id="helsingborg_meta_box_select">
        <?php foreach($pages as $page) : ?>
          <option value="<?php echo $page->ID; ?>" <?php selected($selected, $page->ID); ?>><?php echo $page->post_title; ?></option>
        <?php endforeach; ?>
      </select>
  </p>
  <p>
    <label for="helsingborg_meta_box_check">Ska undersidor medfölja </label>
    <input type="checkbox" name="_helsingborg_meta[rss_check]" id="helsingborg_meta_box_check" value="rss_check" <?php echo (in_array('rss_check', $meta)) ? 'checked="checked"' : ''; ?> />
  </p>
</div>
