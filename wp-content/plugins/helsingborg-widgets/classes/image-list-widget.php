<?php
if (!class_exists('Image_List')) {
  class Image_List
  {
    /**
     * Constructor
     */
    public function __construct()
    {
      add_action( 'widgets_init', array( $this, 'add_widgets' ) );
    }

    /**
     * Add widget
     */
    public function add_widgets()
    {
      register_widget( 'Image_List_Widget' );
    }
  }
}

if (!class_exists('Image_List_Widget')) {
  class Image_List_Widget extends WP_Widget {

    /** constructor */
    function Image_List_Widget() {
      parent::WP_Widget(false, '* Bildlistor', array('description' => 'Lägg till de bilder du vill rendera ut.'));
    }

    public function widget( $args, $instance ) {
      extract($args);

      // Get all the data saved
      $title = apply_filters('widget_title', empty($instance['title']) ? __('List') : $instance['title']);
      $rss_link = empty($instance['rss_link']) ? '#' : $instance['rss_link']; // TODO: Proper default ?
      $show_rss = empty($instance['show_rss']) ? 'rss_no' : $instance['show_rss'];
      $show_placement = empty($instance['show_placement']) ? 'show_in_sidebar' : $instance['show_placement'];
      $show_dates = isset($instance['show_dates']) ? $instance['show_dates'] : false;
      $amount = empty($instance['amount']) ? 1 : $instance['amount'];

      // Retrieved all links
      for ($i = 1; $i <= $amount; $i++) {
        $items[$i-1] = $instance['item'.$i];
        $item_links[$i-1] = $instance['item_link'.$i];
        $item_targets[$i-1] = isset($instance['item_target'.$i]) ? $instance['item_target'.$i] : false;
        $item_ids[$i-1] = $instance['item_id'.$i];
      }

      if ($show_placement == 'show_in_sidebar') :

      else :

      endif;


      echo $after_widget;
    }

    public function update( $new_instance, $old_instance) {

      // Save the data
      $instance['title'] = strip_tags($new_instance['title']);
      $instance['rss_link'] = strip_tags($new_instance['rss_link']);
      $amount = $new_instance['amount'];
      $new_item = empty($new_instance['new_item']) ? false : strip_tags($new_instance['new_item']);

      if ( isset($new_instance['position1'])) {
        for($i=1; $i<= $new_instance['amount']; $i++){
          if($new_instance['position'.$i] != -1){
            $position[$i] = $new_instance['position'.$i];
          }else{
            $amount--;
          }
        }
        if($position){
          asort($position);
          $order = array_keys($position);
          if(strip_tags($new_instance['new_item'])){
            $amount++;
            array_push($order, $amount);
          }
        }

      }else{
        $order = explode(',',$new_instance['order']);
        foreach($order as $key => $order_str){
          $num = strrpos($order_str,'-');
          if($num !== false){
            $order[$key] = substr($order_str,$num+1);
          }
        }
      }

      if($order){
        foreach ($order as $i => $item_num) {
          $instance['item'.($i+1)] = empty($new_instance['item'.$item_num]) ? '' : strip_tags($new_instance['item'.$item_num]);
          $instance['item_link'.($i+1)] = empty($new_instance['item_link'.$item_num]) ? '' : strip_tags($new_instance['item_link'.$item_num]);
          $instance['item_class'.($i+1)] = empty($new_instance['item_class'.$item_num]) ? '' : strip_tags($new_instance['item_class'.$item_num]);
          $instance['item_target'.($i+1)] = empty($new_instance['item_target'.$item_num]) ? '' : strip_tags($new_instance['item_target'.$item_num]);
          $instance['item_id'.($i+1)] = empty($new_instance['item_id'.$item_num]) ? '' : strip_tags($new_instance['item_id'.$item_num]);
        }
      }

      $instance['amount'] = $amount;
      $instance['show_rss'] = strip_tags($new_instance['show_rss']);
      $instance['show_placement'] = strip_tags($new_instance['show_placement']);
      $instance['show_dates'] = empty($new_instance['show_dates']) ? '' : strip_tags($new_instance['show_dates']);

      return $instance;
    }

    public function form( $instance ) {
      $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'title_link' => '' ) );
      $title = strip_tags($instance['title']);
      $rss_link = strip_tags($instance['rss_link']);
      $amount = empty($instance['amount']) ? 1 : $instance['amount'];

      for ($i = 1; $i <= $amount; $i++) {
        $items[$i] = empty($instance['item'.$i]) ? '' : $instance['item'.$i];
        $item_links[$i] = empty($instance['item_link'.$i]) ? '' : $instance['item_link'.$i];
        $item_targets[$i] = empty($instance['item_target'.$i]) ? '' : $instance['item_target'.$i];
        $item_ids[$i] = empty($instance['item_id'.$i]) ? '' : $instance['item_id'.$i];
      }

      $title_link = $instance['title_link'];
      $show_rss = empty($instance['show_rss']) ? 'rss_no' : $instance['show_rss'] ;
      $show_placement = empty($instance['show_placement']) ? 'show_in_sidebar' : $instance['show_placement'];
      $show_dates = empty($instance['show_dates']) ? '' : $instance['show_dates'];
  ?>
      <div class="sllw-row">
        <label><b>OBS! Vart ska denna visas?  </b></label><br>
        <label for="<?php echo $this->get_field_id('show_in_content'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_content" id="<?php echo $this->get_field_id('show_in_content'); ?>" <?php checked($show_placement, "show_in_content"); ?> />  <?php echo __("Under innehållet"); ?></label>
        <label for="<?php echo $this->get_field_id('show_in_sidebar'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_sidebar" id="<?php echo $this->get_field_id('show_in_sidebar'); ?>" <?php checked($show_placement, "show_in_sidebar"); ?> /> <?php echo __("I sidokolumn"); ?></label>
      </div>

      <ul class="sllw-instructions">
        <li><?php echo __(""); ?></li>
      </ul>

      <div class="simple-link-list">
      <?php foreach ($items as $num => $item) :
        $item = esc_attr($item);
        $item_link = esc_attr($item_links[$num]);
        $checked = checked($item_targets[$num], 'on', false);
        $item_id = esc_attr($item_ids[$num]);
        $h5 = esc_attr($item);
        if (!empty($item_id)) {
          $h5 = get_post($item_id, OBJECT, 'display')->post_title;
        }
        $id_prefix = $this->get_field_id('');
      ?>

        <div id="<?php echo $this->get_field_id($num); ?>" class="list-item">
          <h5 class="moving-handle"><span class="number"><?php echo $num; ?></span>. <span class="item-title"><?php echo $h5; ?></span><a class="sllw-action hide-if-no-js"></a></h5>
          <div class="sllw-edit-item">

            <div class="uploader" style="display: table;margin: auto;">
              <br>
              <div class="widefat" id="<?php echo $this->get_field_id('preview'.$num); ?>">
                <?php // echo $this->get_image_html($instance, false); ?>
              </div>
              <br>
              <input type="submit" class="button" style="display: table; margin: auto;" name="<?php echo $this->get_field_name('uploader_button'.$num); ?>" id="<?php echo $this->get_field_id('uploader_button'.$num); ?>" value="Välj bild" onclick="helsingborgImageWidget.uploader( '<?php echo $this->get_field_id($num); ?>', '<?php echo $id_prefix; ?>', '<?php echo $num; ?>' ); return false;" />
              <input type="hidden" id="<?php echo $this->get_field_id('attachment_id'.$num); ?>" name="<?php echo $this->get_field_name('attachment_id'.$num); ?>" value="<?php echo abs($instance['attachment_id'.$num]); ?>" />
              <input type="hidden" id="<?php echo $this->get_field_id('imageurl'.$num); ?>" name="<?php echo $this->get_field_name('imageurl'.$num); ?>" value="<?php echo $instance['imageurl'.$num]; ?>" />
            </div>
            <br clear="all" />

            <div id="<?php echo $this->get_field_id('fields'.$num); ?>" <?php if ( empty($instance['attachment_id'.$num]) && empty($instance['imageurl'.$num]) ) { ?>style="display:none;"<?php } ?>>

              <p><label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size', 'image_widget'); ?>:</label>
                <select name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>" onChange="helsingborgImageWidget.toggleSizes( '<?php echo $this->id; ?>', '<?php echo $id_prefix; ?>' );">
                  <?php
                  // Note: this is dumb. We shouldn't need to have to do this. There should really be a centralized function in core code for this.
                  $possible_sizes = apply_filters( 'image_size_names_choose', array(
                    'thumbnail' => __('Thumbnail', 'image_widget'),
                    'full'      => __('Full Size', 'image_widget'),
                    'medium'    => __('Medium', 'image_widget'),
                    'large'     => __('Large', 'image_widget'),
                  ) );

                  foreach( $possible_sizes as $size_key => $size_label ) { ?>
                    <option value="<?php echo $size_key; ?>"<?php selected( $instance['size'], $size_key ); ?>><?php echo $size_label; ?></option>
                    <?php } ?>
                </select>
              </p>
            </div>

            <label for="<?php echo $this->get_field_id('item_link'.$num); ?>"><?php echo __("Länk:"); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('item_link'.$num); ?>" name="<?php echo $this->get_field_name('item_link'.$num); ?>" type="text" value="<?php echo $item_link; ?>" />

            <input type="checkbox" name="<?php echo $this->get_field_name('item_target'.$num); ?>" id="<?php echo $this->get_field_id('item_target'.$num); ?>" <?php echo $checked; ?> /> <label for="<?php echo $this->get_field_id('item_target'.$num); ?>"><?php echo __("Öppna i nytt fönster"); ?></label>
            <a class="sllw-delete hide-if-no-js"><img src="<?php echo plugins_url('../images/delete.png', __FILE__ ); ?>" /> <?php echo __("Remove"); ?></a>
          </div>
        </div>

      <?php endforeach;

      if ( isset($_GET['editwidget']) && $_GET['editwidget'] ) : ?>
        <table class='widefat'>
          <thead><tr><th><?php echo __("Item"); ?></th><th><?php echo __("Position/Action"); ?></th></tr></thead>
          <tbody>
            <?php foreach ($items as $num => $item) : ?>
            <tr>
              <td><?php echo esc_attr($item); ?></td>
              <td>
                <select id="<?php echo $this->get_field_id('position'.$num); ?>" name="<?php echo $this->get_field_name('position'.$num); ?>">
                  <option><?php echo __('&mdash; Select &mdash;'); ?></option>
                  <?php for($i=1; $i<=count($items); $i++) {
                    if($i==$num){
                      echo "<option value='$i' selected>$i</option>";
                    }else{
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

        <div class="sllw-row">
          <input type="checkbox" name="<?php echo $this->get_field_name('new_item'); ?>" id="<?php echo $this->get_field_id('new_item'); ?>" /> <label for="<?php echo $this->get_field_id('new_item'); ?>"><?php echo __("Add New Item"); ?></label>
        </div>
      <?php endif; ?>

      </div>
      <div class="sllw-row hide-if-no-js">
        <a class="sllw-add button-secondary"><img src="<?php echo plugins_url('../images/add.png', __FILE__ )?>" /> <?php echo __("Lägg till bild"); ?></a>
      </div>

      <input type="hidden" id="<?php echo $this->get_field_id('amount'); ?>" class="amount" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $amount ?>" />
      <input type="hidden" id="<?php echo $this->get_field_id('order'); ?>" class="order" name="<?php echo $this->get_field_name('order'); ?>" value="<?php echo implode(',',range(1,$amount)); ?>" />

<?php
    }
  }
}
