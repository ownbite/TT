<?php
if (!class_exists('Image_List')) {
    class Image_List {
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

        private $_viewsPath;

        /**
         * Constructor
         */
        function Image_List_Widget() {
            // Register the widget
            parent::WP_Widget(false, '* Bildlistor', array('description' => 'Lägg till de bilder du vill rendera ut.'));
            $this->_viewsPath = plugin_dir_path(plugin_dir_path(__FILE__)) . 'views/';
        }

        /**
         * Shows the widget contant
         * @param  array $args     Arguments
         * @param  array $instance The instance
         * @return void
         */
        public function widget( $args, $instance ) {
            extract($args);

            // Get all the data saved
            $title          = apply_filters('widget_title', empty($instance['title']) ? __('List') : $instance['title']);
            $rss_link       = empty($instance['rss_link'])       ? '#' : $instance['rss_link']; // TODO: Proper default ?
            $show_rss       = empty($instance['show_rss'])       ? 'rss_no' : $instance['show_rss'];
            $show_placement = empty($instance['show_placement']) ? 'show_in_sidebar' : $instance['show_placement'];
            $show_dates     = isset($instance['show_dates'])     ? $instance['show_dates'] : false;
            $amount         = empty($instance['amount'])         ? 1 : $instance['amount'];

            // Retrieved all links
            for ($i = 1; $i <= $amount; $i++) {
                $items[$i-1]                    = $instance['item'.$i];
                $item_links[$i-1]               = $instance['item_link'.$i];
                $item_targets[$i-1]             = isset($instance['item_target'.$i]) ? $instance['item_target'.$i] : false;
                $item_ids[$i-1]                 = $instance['item_id'.$i];
                $item_attachement_id[$i-1]      = $instance['attachment_id'.$i];
                $item_imageurl[$i-1]            = $instance['imageurl'.$i];
                $item_alts[$i-1]                = $instance['alt'.$i];
                $item_texts[$i-1]               = $instance['item_text'.$i];
                $item_force_widths[$i-1]        = $instance['item_force_width'.$i];
                $item_force_margins[$i-1]       = $instance['item_force_margin'.$i];
                $item_force_margin_values[$i-1] = $instance['item_force_margin_value'.$i];
            }

            $view = 'widget-under.php';
            switch ($show_placement) {
                case 'show_in_sidebar':
                    $view = 'widget-sidebar.php';
                    break;

                case 'show_in_slider':
                    $view = 'widget-slider.php';
                    break;
            }

            if ($templatePath = locate_template('templates/plugins/hbg-image-list-widget/' . $view)) {
                require($templatePath);
            } else {
                require($this->_viewsPath . $view);
            }
        }

        /**
         * Updates the widget instance
         * @param  array $new_instance New instance
         * @param  array $old_instance Old instance
         * @return void
         */
        public function update( $new_instance, $old_instance) {
            // Save the data
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['rss_link'] = strip_tags($new_instance['rss_link']);
            $amount = $new_instance['amount'];
            $new_item = empty($new_instance['new_item']) ? false : strip_tags($new_instance['new_item']);

            // Make sure to pick up each new item created
            if (isset($new_instance['position1'])) {
                for ($i = 1; $i <= $new_instance['amount']; $i++) {
                    if ($new_instance['position'.$i] != -1) {
                        $position[$i] = $new_instance['position'.$i];
                    } else {
                        $amount--;
                    }
                }

                if ($position){
                    asort($position);
                    $order = array_keys($position);

                    if (strip_tags($new_instance['new_item'])) {
                        $amount++;
                        array_push($order, $amount);
                    }
                }

            } else {
                $order = explode(',',$new_instance['order']);

                foreach($order as $key => $order_str){
                    $num = strrpos($order_str,'-');
                    if ($num !== false) {
                        $order[$key] = substr($order_str,$num+1);
                    }
                }
            }

            // Go through each item created
            if ($order){
                foreach ($order as $i => $item_num) {
                    $instance['item'.($i+1)]                     = empty($new_instance['item'.$item_num])                    ? '' : strip_tags($new_instance['item'.$item_num]);
                    $instance['item_link'.($i+1)]                = empty($new_instance['item_link'.$item_num])               ? '' : strip_tags($new_instance['item_link'.$item_num]);
                    $instance['item_target'.($i+1)]              = empty($new_instance['item_target'.$item_num])             ? '' : strip_tags($new_instance['item_target'.$item_num]);
                    $instance['item_class'.($i+1)]               = empty($new_instance['item_class'.$item_num])              ? '' : strip_tags($new_instance['item_class'.$item_num]);
                    $instance['item_id'.($i+1)]                  = empty($new_instance['item_id'.$item_num])                 ? '' : strip_tags($new_instance['item_id'.$item_num]);
                    $instance['attachment_id'.($i+1)]            = empty($new_instance['attachment_id'.$item_num])           ? '' : strip_tags($new_instance['attachment_id'.$item_num]);
                    $instance['title'.($i+1)]                    = empty($new_instance['title'.$item_num])                   ? '' : strip_tags($new_instance['title'.$item_num]);
                    $instance['imageurl'.($i+1)]                 = empty($new_instance['imageurl'.$item_num])                ? '' : strip_tags($new_instance['imageurl'.$item_num]);
                    $instance['alt'.($i+1)]                      = empty($new_instance['alt'.$item_num])                     ? '' : strip_tags($new_instance['alt'.$item_num]);
                    $instance['item_text'.($i+1)]                = empty($new_instance['item_text'.$item_num])               ? '' : strip_tags($new_instance['item_text'.$item_num]);
                    $instance['item_force_width'.($i+1)]         = empty($new_instance['item_force_width'.$item_num])        ? '' : strip_tags($new_instance['item_force_width'.$item_num]);
                    $instance['item_force_margin'.($i+1)]        = empty($new_instance['item_force_margin'.$item_num])       ? '' : strip_tags($new_instance['item_force_margin'.$item_num]);
                    $instance['item_force_margin_value'.($i+1)]  = empty($new_instance['item_force_margin_value'.$item_num]) ? '' : strip_tags($new_instance['item_force_margin_value'.$item_num]);
                }
            }

            $instance['amount'] = $amount;
            $instance['show_placement'] = strip_tags($new_instance['show_placement']);

            return $instance;
        }

        /**
         * Displays the widget form
         * @param  array $instance The instance
         * @return void
         */
        public function form($instance) {

            // First retrieve all saved data from before, if any
            $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'title_link' => '' ) );
            $show_placement = empty($instance['show_placement']) ? 'show_in_slider' : $instance['show_placement'];
            $amount = empty($instance['amount']) ? 1 : $instance['amount'];

            for ($i = 1; $i <= $amount; $i++) {
                $items[$i]                    = empty($instance['item'.$i])                    ? '' : $instance['item'.$i];
                $item_links[$i]               = empty($instance['item_link'.$i])               ? '' : $instance['item_link'.$i];
                $item_targets[$i]             = empty($instance['item_target'.$i])             ? '' : $instance['item_target'.$i];
                $item_ids[$i]                 = empty($instance['item_id'.$i])                 ? '' : $instance['item_id'.$i];
                $item_titles[$i]              = empty($instance['title'.$i])                   ? '' : $instance['title'.$i];
                $item_imageurl[$i]            = empty($instance['imageurl'.$i])                ? '' : $instance['imageurl'.$i];
                $item_attachement_id[$i]      = empty($instance['attachment_id'.$i])           ? '' : $instance['attachment_id'.$i];
                $item_alts[$i]                = empty($instance['alt'.$i])                     ? '' : $instance['alt'.$i];
                $item_texts[$i]               = empty($instance['item_text'.$i])               ? '' : $instance['item_text'.$i];
                $item_force_widths[$i]        = empty($instance['item_force_width'.$i])        ? '' : $instance['item_force_width'.$i];
                $item_force_margins[$i]       = empty($instance['item_force_margin'.$i])       ? '' : $instance['item_force_margin'.$i];
                $item_force_margin_values[$i] = empty($instance['item_force_margin_value'.$i]) ? '' : $instance['item_force_margin_value'.$i];
            }

            require($this->_viewsPath . 'widget-form.php');
        }
  }
}
