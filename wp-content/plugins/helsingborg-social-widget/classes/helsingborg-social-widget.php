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

            // Enqueue js
            add_action('admin_menu', array($this, 'addJs'));
            add_action('admin_menu', array($this, 'addSettingsMenu'));
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
         * Adds settings section
         */
        public function addSettingsMenu() {
            add_options_page('Sociala flöden', 'Sociala flöden', 'activate_plugins', 'hbg-social-widget-menu', array($this, 'settingsPage'));
        }

        /**
         * Outputs the settings page
         */
        public function settingsPage() {
            /**
             * Handle options save
             */
            if ($_POST['is_post'] == 'true') {
                update_option('hbgsf_facebook_app_id', esc_attr($_POST['facebook']['app_id']));
                update_option('hbgsf_facebook_app_secret', esc_attr($_POST['facebook']['app_secret']));
                update_option('hbgsf_instagram_client_id', esc_attr($_POST['instagram']['client_id']));
            }

            /**
             * Get options
             */
            $hbgsf_facebook_app_id     = get_option('hbgsf_facebook_app_id');
            $hbgsf_facebook_app_secret = get_option('hbgsf_facebook_app_secret');
            $hbgsf_instagram_client_id = get_option('hbgsf_instagram_client_id');

            /**
             * Require the view
             */
            require($this->_viewsPath . 'settings-page.php');
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
                    break;

                case 'pinterest':
                    $instance['username']   = $this->getFbUserFromUrl($newInstance[$type . '-url']);
                    $instance['show_count'] = $newInstance[$type . '-count'];
                    break;

                case 'instagram':
                    $instance['username']   = $newInstance[$type . '-user'];
                    $instance['show_count'] = $newInstance[$type . '-count'];
                    $instance['col_count']  = $newInstance[$type . '-col-count'];
                    $instance['show_likes'] = $newInstance[$type . '-show-likes'];
                    break;

                default:
                    $instance['username']   = $newInstance[$type . '-user'];
                    $instance['show_count'] = $newInstance[$type . '-count'];
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
                    $feed = $this->getInstagramFeed($instance['username'], $instance['show_count']);
                    require($this->_viewsPath . 'widget-instagram.php');
                    break;

                case 'facebook':
                    $feed = $this->getFacebookFeed($instance['username'], $instance['show_count']);
                    require($this->_viewsPath . 'widget-facebook.php');
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
        public function getInstagramFeed($username, $length) {
            /**
             * Get Instagram User ID from Username
             */
            $key = get_option('hbgsf_instagram_client_id');
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

        public function getFacebookFeed($username, $length) {
            /**
             * Get appId and appSecret from options
             */
            $appId     = get_option('hbgsf_facebook_app_id');
            $appSecret = get_option('hbgsf_facebook_app_secret');

            /**
             * Request a token from Facebook Graph API
             * @var string
             */
            $endpoint = 'https://graph.facebook.com/oauth/access_token';
            $data = array(
                'grant_type'    => 'client_credentials',
                'client_id'     => $appId,
                'client_secret' => $appSecret
            );
            $token = HbgCurl::request('GET', $endpoint, $data);
            $token = explode('=', $token)[1];

            $endpoint = 'https://graph.facebook.com/' . $username . '/posts';
            $data = array(
                'access_token' => $token,
                'fields'       =>'full_picture, picture, message, created_time, object_id, link, name, caption, description, icon, type, status_type, likes'
            );
            $feed = HbgCurl::request('GET', $endpoint, $data);
            $feed = json_decode($feed);

            return $feed->data;
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