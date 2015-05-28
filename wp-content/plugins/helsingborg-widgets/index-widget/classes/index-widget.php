<?php
if (!class_exists('Index_Widget')) {
    class Index_Widget {

        /**
        * Constructor
        */
        public function __construct() {
            add_action( 'widgets_init', array( $this, 'add_widgets' ) );
        }

        /**
        * Add widget
        */
        public function add_widgets() {
            register_widget( 'Index_Widget_Box' );
        }
    }
}

if (!class_exists('Index_Widget_Box')) {
    class Index_Widget_Box extends WP_Widget {

        private $_viewsPath;

        /**
         * Constructor
         */
        function Index_Widget_Box() {
            parent::WP_Widget(false, '* Index', array('description' => 'Lägg till de index som du vill visa.'));
            $this->_viewsPath = plugin_dir_path(plugin_dir_path(__FILE__)) . 'views/';
        }

        /**
         * Displays the widget
         * @param  array $args     Arguments
         * @param  array $instance Instance
         * @return void
         */
        public function widget( $args, $instance ) {
            extract($args);

            // Get all the data saved
            $amount = empty($instance['amount']) ? 1 : $instance['amount'];
            $page_list = isset($instance['page_list']) ? $instance['page_list'] : false;
            $list_class = $page_list !== 'on' ? ' ' : ' page-list ';

            for ($i = 1; $i <= $amount; $i++) {
                $items[$i-1] = $instance['item'.$i];
                $item_ids[$i-1] = $instance['item_id'.$i];
            }

            // View
            $view = 'widget.php';
            if ($templatePath = locate_template('templates/plugins/hbg-index-widget/' . $view)) {
                require($templatePath);
            } else {
                require($this->_viewsPath . $view);
            }
        }

        /**
         * Updates a widget instance
         * @param  array $new_instance New instance
         * @param  array $old_instance Old instance
         * @return void
         */
        public function update($new_instance, $old_instance) {

            // Save the data
            $amount = $new_instance['amount'];
            $new_item = empty($new_instance['new_item']) ? false : strip_tags($new_instance['new_item']);

            if (isset($new_instance['position1'])) {
                for($i=1; $i<= $new_instance['amount']; $i++) {
                    if ($new_instance['position'.$i] != -1) {
                        $position[$i] = $new_instance['position'.$i];
                    } else {
                        $amount--;
                    }
                }

                if ($position){
                    asort($position);
                    $order = array_keys($position);
                    if (strip_tags($new_instance['new_item'])){
                        $amount++;
                        array_push($order, $amount);
                    }
                }
            } else {
                $order = explode(',',$new_instance['order']);

                foreach($order as $key => $order_str){
                    $num = strrpos($order_str,'-');
                    if($num !== false){
                        $order[$key] = substr($order_str,$num+1);
                    }
                }
            }

            if ($order){
                foreach ($order as $i => $item_num) {
                    $instance['item'.($i+1)] = empty($new_instance['item'.$item_num]) ? '' : strip_tags($new_instance['item'.$item_num]);
                    $instance['item_id'.($i+1)] = empty($new_instance['item_id'.$item_num]) ? '' : strip_tags($new_instance['item_id'.$item_num]);
                    $instance['headline'.($i+1)] = empty($new_instance['headline'.$item_num]) ? '' : strip_tags($new_instance['headline'.$item_num]);
                }
            }

            $instance['amount'] = $amount;
            $instance['page_list'] = empty($new_instance['page_list']) ? '' : strip_tags($new_instance['page_list']);

            return $instance;
        }

        /**
         * Displays widget form
         * @param  array $instance The instance
         * @return void
         */
        public function form ($instance) {
            $amount = empty($instance['amount']) ? 1 : $instance['amount'];
            $page_list = empty($instance['page_list']) ? '' : $instance['page_list'];

            for ($i = 1; $i <= $amount; $i++) {
                $items[$i] = empty($instance['item'.$i]) ? '' : $instance['item'.$i];
                $item_ids[$i] = empty($instance['item_id'.$i]) ? '' : $instance['item_id'.$i];
            }

            require($this->_viewsPath . 'widget-form.php');
        }
    }
}
