<?php
if (!class_exists('SimpleLinkList')) {
    class SimpleLinkList {
        /**
         * Constructor
         */
        public function __construct() {
            add_action( 'widgets_init', array( $this, 'add_widgets' ) );
        }

        /**
         * Registers the widget
         */
        public function add_widgets() {
            register_widget( 'SimpleLinkListWidget' );
        }
    }
}

if (!class_exists('SimpleLinkListWidget')) {
    class SimpleLinkListWidget extends WP_Widget {

        private $_viewsPath;

        /**
         * Constructor
         */
        function SimpleLinkListWidget() {
            parent::WP_Widget(false, '* Listor', array('description' => 'Lägg till de länkar som du vill visa.'));
            $this->_viewsPath = plugin_dir_path(plugin_dir_path(__FILE__)) . 'views/';
        }

        /**
         * Displays the widget
         * @param  [type] $args     [description]
         * @param  [type] $instance [description]
         * @return [type]           [description]
         */
        public function widget( $args, $instance ) {
            extract($args);

            // Get all the data saved
            $title = apply_filters('widget_title', empty($instance['title']) ? __('List') : $instance['title']);
            $rss_link = empty($instance['rss_link']) ? '' : $instance['rss_link'];
            $show_rss = !empty($rss_link);
            $show_placement = empty($instance['show_placement']) ? 'show_in_sidebar' : $instance['show_placement'];
            $show_dates = isset($instance['show_dates']) ? $instance['show_dates'] : false;
            $amount = empty($instance['amount']) ? 1 : $instance['amount'];

            // Retrieved all links
            for ($i = 1; $i <= $amount; $i++) {
                $items[$i-1]         = $instance['item'.$i];
                $item_links[$i-1]    = $instance['item_link'.$i];
                $item_targets[$i-1]  = isset($instance['item_target'.$i])  ? $instance['item_target'.$i]  : false;
                $item_warnings[$i-1] = isset($instance['item_warning'.$i]) ? $instance['item_warning'.$i] : false;
                $item_infos[$i-1]    = isset($instance['item_info'.$i])    ? $instance['item_info'.$i]    : false;
                $item_ids[$i-1]      = $instance['item_id'.$i];
                $item_dates[$i-1]    = isset($instance['item_date'.$i]) ? $instance['item_date'.$i] : null;
            }

            $widget_class = ($show_rss == 'rss_yes') ? 'news-widget ' : 'quick-links-widget ';
            $before_widget = str_replace('widget', $widget_class . 'widget', $before_widget);

            $view = 'widget-default.php';
            switch ($show_placement) {
                case 'show_in_sidebar':
                    $view = 'widget-sidebar.php';
                    break;
            }

            if ($templatePath = locate_template('templates/plugins/hbg-link-list-widget/' . $view)) {
                require($templatePath);
            } else {
                require($this->_viewsPath . $view);
            }
        }

        /**
         * Saves a new instance
         * @param  array $new_instance The new instance
         * @param  array $old_instance The old instance
         * @return void
         */
        public function update($new_instance, $old_instance) {

            // Save the data
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['rss_link'] = strip_tags($new_instance['rss_link']);
            $amount = $new_instance['amount'];
            $new_item = empty($new_instance['new_item']) ? false : strip_tags($new_instance['new_item']);

            if (isset($new_instance['position1'])) {
                for ($i=1; $i<= $new_instance['amount']; $i++) {

                    if ($new_instance['position'.$i] != -1) {
                        $position[$i] = $new_instance['position'.$i];
                    } else {
                        $amount--;
                    }

                }

                if ($position) {
                    asort($position);
                    $order = array_keys($position);

                    if (strip_tags($new_instance['new_item'])) {
                        $amount++;
                        array_push($order, $amount);
                    }
                }

            } else {
                $order = explode(',',$new_instance['order']);
                foreach ($order as $key => $order_str) {
                    $num = strrpos($order_str,'-');
                    if ($num !== false) {
                        $order[$key] = substr($order_str,$num+1);
                    }
                }
            }

            if ($order) {
                foreach ($order as $i => $item_num) {
                    $instance['item'.($i+1)]         = empty($new_instance['item'.$item_num])         ? '' : strip_tags($new_instance['item'.$item_num]);
                    $instance['item_link'.($i+1)]    = empty($new_instance['item_link'.$item_num])    ? '' : strip_tags($new_instance['item_link'.$item_num]);
                    $instance['item_class'.($i+1)]   = empty($new_instance['item_class'.$item_num])   ? '' : strip_tags($new_instance['item_class'.$item_num]);
                    $instance['item_target'.($i+1)]  = empty($new_instance['item_target'.$item_num])  ? '' : strip_tags($new_instance['item_target'.$item_num]);
                    $instance['item_warning'.($i+1)] = empty($new_instance['item_warning'.$item_num]) ? '' : strip_tags($new_instance['item_warning'.$item_num]);
                    $instance['item_info'.($i+1)]    = empty($new_instance['item_info'.$item_num])    ? '' : strip_tags($new_instance['item_info'.$item_num]);
                    $instance['item_id'.($i+1)]      = empty($new_instance['item_id'.$item_num])      ? '' : strip_tags($new_instance['item_id'.$item_num]);
                    $instance['item_date'.($i+1)]    = empty($new_instance['item_date'.$item_num])    ? '' : strip_tags($new_instance['item_date'.$item_num]);
                }
            }

            $instance['amount']         = $amount;
            $instance['show_rss']       = strip_tags($new_instance['show_rss']);
            $instance['show_placement'] = strip_tags($new_instance['show_placement']);
            $instance['show_dates']     = empty($new_instance['show_dates']) ? '' : strip_tags($new_instance['show_dates']);

            return $instance;
        }

        /**
         * Displays the widget form content
         * @param  array $instance The current instance
         * @return void
         */
        public function form($instance) {
            $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'title_link' => '' ) );
            $title = strip_tags($instance['title']);
            $rss_link = strip_tags($instance['rss_link']);
            $amount = empty($instance['amount']) ? 1 : $instance['amount'];

            for ($i = 1; $i <= $amount; $i++) {
                $items[$i]         = empty($instance['item'.$i])         ? '' : $instance['item'.$i];
                $item_links[$i]    = empty($instance['item_link'.$i])    ? '' : $instance['item_link'.$i];
                $item_targets[$i]  = empty($instance['item_target'.$i])  ? '' : $instance['item_target'.$i];
                $item_warnings[$i] = empty($instance['item_warning'.$i]) ? '' : $instance['item_warning'.$i];
                $item_infos[$i]    = empty($instance['item_info'.$i])    ? '' : $instance['item_info'.$i];
                $item_ids[$i]      = empty($instance['item_id'.$i])      ? '' : $instance['item_id'.$i];
                $item_dates[$i]    = empty($instance['item_date'.$i])    ? '' : $instance['item_date'.$i];;
            }

            $title_link = $instance['title_link'];
            $show_rss = empty($instance['show_rss']) ? 'rss_no' : $instance['show_rss'] ;
            $show_placement = empty($instance['show_placement']) ? 'show_in_sidebar' : $instance['show_placement'];
            $show_dates = empty($instance['show_dates']) ? '' : $instance['show_dates'];

            require($this->_viewsPath . 'widget-form.php');
        }
    }
}
