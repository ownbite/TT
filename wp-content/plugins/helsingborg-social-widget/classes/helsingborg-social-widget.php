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
            add_action('wp_enqueue_scripts', array($this, 'addJs'));

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
        public function addJs($hook) {
            wp_enqueue_script('helsingborg-social-widget-js', plugins_url('helsingborg-social-widget/assets/js/helsingborg-social-widget.js'), array('jquery'), false, true);
            wp_enqueue_style('helsingborg-social-widget-css', plugins_url('helsingborg-social-widget/assets/css/helsingborg-social-widget.css'));
            wp_enqueue_style('helsingborg-social-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.3.0');
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

            $instance = array();
            $instance['feedType'] = $newInstance['feedType'];

            switch ($type = $instance['feedType']) {
                case 'facebook':
                    $instance['username']   = $this->getFbUserFromUrl($newInstance[$type . '-url']);
                    $instance['show_count'] = $newInstance[$type . '-count'];
                    $instance['key']        = null;
                    break;

                case 'pinterest':
                    $instance['username']   = $this->getFbUserFromUrl($newInstance[$type . '-url']);
                    $instance['show_count'] = $newInstance[$type . '-count'];
                    $instance['key']        = null;
                    break;

                case 'instagram':
                    $instance['username']   = $newInstance[$type . '-user'];
                    $instance['show_count'] = $newInstance[$type . '-count'];
                    $instance['key']        = $newInstance[$type . '-key'];
                    $instance['col_count']  = $newInstance[$type . '-col-count'];
                    break;

                default:
                    $instance['username']   = $newInstance[$type . '-user'];
                    $instance['show_count'] = $newInstance[$type . '-count'];
                    $instance['key']        = $newInstance[$type . '-key'];
                    break;
            }

            return $instance;
        }

        /**
         * Display the widget markup
         * @param  array $args     The widget arguments
         * @param  array $instance The widget instance
         * @return void            The widget markup
         */
        public function widget($args, $instance) {
            extract($args);

            switch ($instance['feedType']) {
                case 'instagram':
                    $feed = $this->getInstagramFeed($instance['key'], $instance['username'], $instance['show_count']);
                    require($this->_viewsPath . 'widget-instagram.php');
                    break;

                default:
                    require($this->_viewsPath . 'widget-none.php');
                    break;
            }
        }

        /**
         * Gets a Instagram users feed (if public)
         * @param  string $key      Instagram App Clinet ID
         * @param  string $username The username to get
         * @param  integer $length  Length of the feed
         * @return object           The instgram posts
         */
        public function getInstagramFeed($key, $username, $length) {
            /**
             * Get Instagram User ID from Username
             */
            $endpoint = 'https://api.instagram.com/v1/users/search';
            $data = array(
                'q' => $username,
                'client_id' => $key
            );
            $user = HbgCurl::request('GET', $endpoint, $data);
            $user = json_decode($user);

            $userId = $user->data[0]->id;

            /**
             * Get the users feed
             * @var string
             */
            $endpoint = 'https://api.instagram.com/v1/users/' . $userId . '/media/recent/';
            $data = array(
                'client_id' => $key
            );
            $recent = HbgCurl::request('GET', $endpoint, $data);
            $recent = json_decode($recent);

            return $recent->data;
        }

        /**
         * Gets the username from a Facebook page URL
         * @param  string $url The url
         * @return string      The username
         */
        public function getFbUserFromUrl($url) {
            $matches = null;
            preg_match_all('/([A-Z1-9-_])\w+/', $url, $matches);
            $username = $matches[0][0];
            return $username;
        }

    }
}