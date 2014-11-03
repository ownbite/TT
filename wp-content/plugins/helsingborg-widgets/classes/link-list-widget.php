<?php
if (!class_exists('SimpleLinkList')) {
  class SimpleLinkList
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
      register_widget( 'SimpleLinkListWidget' );
    }
  }
}

if (!class_exists('SimpleLinkListWidget')) {
  class SimpleLinkListWidget extends WP_Widget {

    /** constructor */
    function SimpleLinkListWidget() {
      parent::WP_Widget(false, '* Listor', array('description' => 'Lägg till de länkar som du vill visa.'));
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

      $widget_class = ($show_rss == 'rss_yes') ? 'news-widget ' : 'quick-links-widget ';
      // echo substr_replace($before_widget, $widget_class, strpos('widget'), 0);
      $before_widget = str_replace('widget', $widget_class . 'widget', $before_widget);


      if ($show_placement == 'show_in_sidebar') :
        echo $before_widget; ?>
            <h2 class="widget-title"><?php echo $title ?>
              <?php if ($show_rss == 'rss_yes') { echo('<span class="icon"></span>'); } ?>
            </h2>

            <div class="divider">
              <div class="upper-divider"></div>
              <div class="lower-divider"></div>
            </div>

            <ul class="quick-links-list">

            <?php
            $today = strtotime(date('Y-m-d'));

            foreach ($items as $num => $item) :
                $title;
                $item_id = $item_ids[$num];

                // Check if link should be opened in new window
                if ($item_targets[$num]) {
                  $target = 'target="_blank"';
                } else {
                  $target = '';
                }

                // Get the page
                $page = get_post($item_id, OBJECT, 'display');

                if (!empty($item_id)) {
                  $title = $page->post_title;
                  $link = get_permalink($page->ID);
                  echo('<li><a href="' . $link . '" ' . $target . '>' . $title . '</a></li>');

                  if ($show_dates) {
                    // Parse the dates presented in the event
                    $datetime_start = strtotime($page->post_date);
                    $datetime_end = strtotime($page->post_date);
                    $date = date('Y-m-d', $datetime_start);
                    $time = date('H:i', $datetime_start);

                    // Present 'Idag HH:ii' or 'YYYY-mm-dd'
                    if ($today > $datetime_start && $today < $datetime_end) {
                      echo('<span class="date">Idag ' . $time . '</span>');
                    } else {
                      echo('<span class="date">' . $date . '</span>');
                    }
                  }
                } else {
                  $title = $item;
                  $link = $item_links[$num];
                  echo('<li><a href="' . $link . '" ' . $target . '>' . $title . '</a></li>');
                }
            endforeach; ?>

            </ul>
      <?php

      else : ?>

        <section class="news-section">
          <h2 class="section-title"><?php echo $title; ?></h2>
          <div class="divider fade">
            <div class="upper-divider"></div>
            <div class="lower-divider"></div>
          </div>
          <ul class="news-list-small row"> <?php
            $today = strtotime(date('Y-m-d'));

            foreach ($items as $num => $item) :
                $item_id = $item_ids[$num];
                $page = get_post($item_id, OBJECT, 'display');

                // Check if link should be opened in new window
                if ($item_targets[$num]) {
                  $target = 'target="_blank"';
                } else {
                  $target = '';
                }

                $title;
                if (!empty($item_id)) {
                  $title = $page->post_title;
                  $link = get_permalink($page->ID);
                } else {
                  $title = $item;
                  $link = $item_links[$num];
                }

                echo('<li class="news-item large-12 columns">');
                  echo('<div class="row">');
                    echo('<div class="large-9 medium-9 small-9 columns news-content">');
                      echo('<h2 class="news-title"><a href="' . $link . '" ' . $target . '>' . $title . '</a></h2>');
                    echo('</div>');

                    echo('<div class="large-3 medium-3 small-3 columns">');
                      if ($show_dates) :
                        $datetime_start = strtotime($page->post_date);
                        $date = date('n M Y', $datetime_start);
                        echo('<span class="news-date">' . $date . '</span>');
                      endif;
                    echo('</div>');
                  echo('</div><!-- !row -->');
                echo('</li>');
            endforeach; ?>
          </ul>
        </section>

      <?php endif;

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
      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

      <div class="hbgllw-row">
        <label><b>OBS! Vart ska denna visas?  </b></label><br>
        <label for="<?php echo $this->get_field_id('show_in_content'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_content" id="<?php echo $this->get_field_id('show_in_content'); ?>" <?php checked($show_placement, "show_in_content"); ?> />  <?php echo __("Under innehållet"); ?></label>
        <label for="<?php echo $this->get_field_id('show_in_sidebar'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_placement'); ?>" value="show_in_sidebar" id="<?php echo $this->get_field_id('show_in_sidebar'); ?>" <?php checked($show_placement, "show_in_sidebar"); ?> /> <?php echo __("I högerkolumnen"); ?></label>
      </div>

      <ul class="hbgllw-instructions">
        <li><?php echo __("Titel är det som visas i widgetens header."); ?></li>
        <li><?php echo __("Om en länk refererar till en intern sida (dvs. dropdownen) så används den."); ?></li>
        <li><?php echo __("Om en länk vill hämta en extern länk, så måste 'Titel för objekt'."); ?></li>
        <li><?php echo __("Öppna i nytt fönster lägger till target='_blank' för länken."); ?></li>
        <li><?php echo __("Om 'Visa RSS' är satt till 'Ja', så syns en ikon uppe i headern."); ?></li>
        <li><?php echo __("'Visa datum' fungerar endast på interna sidor, då skrivs datumen ut under länken."); ?></li>
      </ul>
      <div class="helsingborg-link-list">
      <?php foreach ($items as $num => $item) :
        $item = esc_attr($item);
        $item_link = esc_attr($item_links[$num]);
        $checked = checked($item_targets[$num], 'on', false);
        $item_id = esc_attr($item_ids[$num]);
        $h5 = esc_attr($item);
        if (!empty($item_id)) {
          $h5 = get_post($item_id, OBJECT, 'display')->post_title;
        }
      ?>

        <div id="<?php echo $this->get_field_id($num); ?>" class="list-item">
          <h5 class="moving-handle"><span class="number"><?php echo $num; ?></span>. <span class="item-title"><?php echo $h5; ?></span><a class="hbgllw-action hide-if-no-js"></a></h5>
          <div class="hbgllw-edit-item">

            <label for="<?php echo $this->get_field_id('item'.$num); ?>"><?php echo __("Titel för objekt:"); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('item'.$num); ?>" name="<?php echo $this->get_field_name('item'.$num); ?>" type="text" value="<?php echo $item; ?>" />

            <label for="<?php echo $this->get_field_id('item_link'.$num); ?>"><?php echo __("Extern länk:"); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('item_link'.$num); ?>" name="<?php echo $this->get_field_name('item_link'.$num); ?>" type="text" value="<?php echo $item_link; ?>" />

            <p>
              <label for="<?php echo $this->get_field_id('item_id'.$num); ?>"><?php echo __("Sida:"); ?></label>
              <?php wp_dropdown_pages(array(
                      'show_option_none' => 'Ingen sida vald',
                      'selected' => $item_ids[$num],
                      'id' => $this->get_field_id('item_id'.$num),
                      'name' => $this->get_field_name('item_id'.$num)
                    )); ?>
            </p>

            <input type="checkbox" name="<?php echo $this->get_field_name('item_target'.$num); ?>" id="<?php echo $this->get_field_id('item_target'.$num); ?>" <?php echo $checked; ?> /> <label for="<?php echo $this->get_field_id('item_target'.$num); ?>"><?php echo __("Öppna i nytt fönster"); ?></label>
            <a class="hbgllw-delete hide-if-no-js"><img src="<?php echo plugins_url('../images/delete.png', __FILE__ ); ?>" /> <?php echo __("Remove"); ?></a>
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

        <div class="hbgllw-row">
          <input type="checkbox" name="<?php echo $this->get_field_name('new_item'); ?>" id="<?php echo $this->get_field_id('new_item'); ?>" /> <label for="<?php echo $this->get_field_id('new_item'); ?>"><?php echo __("Add New Item"); ?></label>
        </div>
      <?php endif; ?>

      </div>
      <div class="hbgllw-row hide-if-no-js">
        <a class="hbgllw-add button-secondary"><img src="<?php echo plugins_url('../images/add.png', __FILE__ )?>" /> <?php echo __("Lägg till länk"); ?></a>
      </div>

      <input type="hidden" id="<?php echo $this->get_field_id('amount'); ?>" class="amount" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $amount ?>" />
      <input type="hidden" id="<?php echo $this->get_field_id('order'); ?>" class="order" name="<?php echo $this->get_field_name('order'); ?>" value="<?php echo implode(',',range(1,$amount)); ?>" />

      <div class="hbgllw-row">
        <label>Visa RSS?  </label>
        <label for="<?php echo $this->get_field_id('rss_yes'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_rss'); ?>" value="rss_yes" id="<?php echo $this->get_field_id('rss_yes'); ?>" <?php checked($show_rss, "rss_yes"); ?> />  <?php echo __("Ja"); ?></label>
        <label for="<?php echo $this->get_field_id('rss_no'); ?>"><input type="radio" name="<?php echo $this->get_field_name('show_rss'); ?>" value="rss_no" id="<?php echo $this->get_field_id('rss_no'); ?>" <?php checked($show_rss, "rss_no"); ?> /> <?php echo __("Nej"); ?></label>
      </div>

      <p><label for="<?php echo $this->get_field_id('rss_link'); ?>"><?php _e('RSS Länk:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('rss_link'); ?>" name="<?php echo $this->get_field_name('rss_link'); ?>" type="text" value="<?php echo esc_attr($rss_link); ?>" /></p>

      <div class="hbgllw-row">
        <input type="checkbox" name="<?php echo $this->get_field_name('show_dates'); ?>" id="<?php echo $this->get_field_id('show_dates'); ?>" <?php checked($show_dates, 'on'); ?> /> <label for="<?php echo $this->get_field_id('show_dates'); ?>"><?php echo __("Visa datum?"); ?></label>
      </div>

<?php
    }
  }
}
