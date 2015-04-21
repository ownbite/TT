<?php

if (!class_exists('HelsingborgSocialWidget')) {
    class HelsingborgSocialWidget extends WP_Widget {

        protected $_viewsPath;

        /**
         * Constructor
         */
        public function __construct() {
            // Sets the views directory path
            $this->_viewsPath = plugin_dir_path(plugin_dir_path(__FILE__)) . 'views/';

            // Register the widget on widgets_init
            add_action('widgets_init', array($this, 'registerWidget'));

            // Enqueue js
            add_action('admin_menu', array($this, 'addJs'));

            // Widget arguments
            parent::__construct(
                'HelsingborgSocialWidget',
                '* Socialt flöde',
                array(
                    "description" => __('Visar ett socialt flöde')
                )
            );
        }

        /**
        * Registers the widget
        */
        public function registerWidget()
        {
            register_widget('HelsingborgSocialWidget');
        }

        /**
         * Enqueue js
         */
        public function addJs() {
            wp_enqueue_script('helsingborg-social-widget', plugins_url('helsingborg-social-widget/assets/js/helsingborg-social-widget.js'), array('jquery'), false, true);
            wp_enqueue_style('http//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css');
        }

        /**
         * Renders the text widget form
         * @param  object $instance The current widget instance
         * @return void
         */
        public function form($instance) {
            require($this->_viewsPath . 'widget-form.php');
        }

       /**
        * Prepare widget options for save
        * @param  array $newInstance The new widget options
        * @param  array $oldInstance The old widget options
        * @return array              The merged instande of new and old to be saved
        */
        public function update($newInstance, $oldInstance) {

        }

        /**
         * Display the widget markup
         * @param  array $args     The widget arguments
         * @param  array $instance The widget instance
         * @return void            The widget markup
         */
        public function widget($args, $instance) {
            extract($args);
            //require($this->_viewsPath . 'hbgtextwidget-widget.php');
        }

    }
}