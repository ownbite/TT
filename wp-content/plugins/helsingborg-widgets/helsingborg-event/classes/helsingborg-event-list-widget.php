<?php
if (!class_exists('EventList')) {
    class EventList {
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
            register_widget( 'EventListWidget' );
        }
    }
}

if (!class_exists('EventListWidget')) {
    class EventListWidget extends WP_Widget {

        private $_viewsPath;

        /**
         * Constructor
         */
        function EventListWidget() {
            parent::WP_Widget(false, '* Evenemangskalender', array('description' => 'Listar de senaste evenemangen.'));
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

            $title                = empty($instance['title'])                ? __('Kommande evenemang') : $instance['title'];
            $link_text            = empty($instance['link_text'])            ? __('Fler evenemang')     : $instance['link_text'];
            $link                 = empty($instance['link'])                 ? '#'                      : $instance['link'];
            $amount               = empty($instance['amount'])               ? 5                        : $instance['amount'];
            $administration_units = empty($instance['administration_units']) ? 'helsingborgsstad'       : $instance['administration_units'];

            $ids = array();

            foreach(explode(',',$administration_units) as $value) {
                $id = HelsingborgEventModel::get_administration_id_from_name(trim($value));
                array_push($ids, $id->AdministrationUnitID);
            }

            $administration_ids = implode(',',$ids);
            $reference = $link . "?q=" . $administration_ids;

            $view = 'widget.php';
            if ($templatePath = locate_template('templates/plugins/hbg-event-list/' . $view)) {
               require($templatePath);
            } else {
                require($this->_viewsPath . $view);
            }
        }

        /**
         * Saves a new instance of the widget
         * @param  array $new_instance The new instance
         * @param  array $old_instance The old instance
         * @return void
         */
        public function update($new_instance, $old_instance) {
            $instance['title']                = strip_tags($new_instance['title']);
            $instance['link']                 = strip_tags($new_instance['link']);
            $instance['link_text']            = strip_tags($new_instance['link_text']);
            $instance['amount']               = $new_instance['amount'];
            $instance['administration_units'] = strip_tags($new_instance['administration_units']);

            return $instance;
        }

        /**
         * Displays the widget for
         * @param  array $instance The widget instance
         * @return void
         */
        public function form($instance) {
            $instance             = wp_parse_args((array) $instance, array( 'title' => '', 'text' => '', 'link' => '' ));
            $title                = strip_tags($instance['title']);
            $link                 = strip_tags($instance['link']);
            $link_text            = strip_tags($instance['link_text']);
            $amount               = empty($instance['amount']) ? 1 : $instance['amount'];
            $administration_units = strip_tags($instance['administration_units']);

            require($this->_viewsPath . 'widget-form.php');
        }
    }
}
