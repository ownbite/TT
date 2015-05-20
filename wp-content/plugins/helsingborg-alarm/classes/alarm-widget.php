<?php
if (!class_exists('AlarmList')) {
    class AlarmList
    {
        /**
        * Constructor
        */
        public function __construct()
        {
            add_action( 'widgets_init', array($this, 'add_widgets'));
        }

        /**
        * Add widget
        */
        public function add_widgets()
        {
            register_widget('AlarmListWidget');
        }
    }
}

if (!class_exists('AlarmListWidget')) {
    class AlarmListWidget extends WP_Widget {

        protected $_viewsPath;

        /**
         * Constructor
         */
        public function AlarmListWidget() {
            parent::WP_Widget(false, '* Alarmlista', array('description' => 'Skapar en lista med senaste alarmen.'));

            /**
             * Set the viewsPath
             */
            $this->_viewsPath = HELSINGBORG_ALARM_BASE_URI . 'views/';
        }

        /**
         * Displays the widget
         * @param  array $args     Arguments
         * @param  array $instance The data
         * @return void
         */
        public function widget( $args, $instance ) {
            extract($args);

            /**
             * Enqueue scripts and style
             */
            wp_enqueue_style('multiselect-css',       HELSINGBORG_ALARM_BASE .'/css/multiselect.css');
            wp_enqueue_script('multiselect-js',       HELSINGBORG_ALARM_BASE .'/js/multiselect.js');
            wp_enqueue_script('foundation-reveal-js', HELSINGBORG_ALARM_BASE .'/js/foundation.reveal.js');

            /**
             * Get necessary data from instance
             */
            $title     = empty($instance['title'])     ? __('Aktuella larm') : $instance['title'];
            $link      = empty($instance['link'])      ? '#'                 : $instance['link'];
            $amount    = empty($instance['amount'])    ? 10                  : $instance['amount'];
            $rss_link  = $instance['rss_link'];

            /**
             * Add RSS link to title if existing
             */
            if (strlen($rss_link) > 0) {
                $title .= '<a href="' . $rss_link . '" class="rss-link"><span class="icon"></span></a>';
                $widget_class = "alarm-widget ";
                $before_widget = str_replace('widget', $widget_class . 'widget', $before_widget);
            }

            /**
             * Get the alarms
             */
            $json = file_get_contents('http://alarmservice.helsingborg.se/AlarmServices.svc/GetLatestAlarms');
            $alarms = json_decode($json)->GetLatestAlarmsResult;

            require($this->_viewsPath . 'widget-view.php');
        }

        /**
         * Handles widget update
         * @param  array $new_instance The new instance
         * @param  array $old_instance The old instance
         * @return array the values to save
         */
        public function update( $new_instance, $old_instance) {
            $instance['title']     = strip_tags($new_instance['title']);
            $instance['link']      = strip_tags($new_instance['link']);
            $amount                = $new_instance['amount'];
            $instance['amount']    = $amount;
            $instance['rss_link']  = $new_instance['rss_link'];

            return $instance;
        }

        /**
         * The widget form
         * @param  array $instance The instance
         * @return void
         */
        public function form( $instance ) {
            $instance  = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'link' => '' ) );
            $title     = strip_tags($instance['title']);
            $link      = strip_tags($instance['link']);
            $amount    = empty($instance['amount']) ? 10 : $instance['amount'];
            $rss_link = $instance['rss_link'];

            require($this->_viewsPath . 'widget-form.php');
        }
    }
}
