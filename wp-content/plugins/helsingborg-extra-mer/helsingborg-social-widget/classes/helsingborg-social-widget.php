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
            add_action('admin_enqueue_scripts', array($this, 'addJs'));


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
         * Adds settings section to either the Helsingborg submenu (if exists) or to the core settings submenu
         */
        public function addSettingsMenu() {
            if (!$this->menuExist('helsingborg')) {
                add_options_page('Sociala flöden', 'Sociala flöden', 'activate_plugins', 'hbg-social-widget-menu', array($this, 'settingsPage'));
            } else {
                add_submenu_page(
                    'helsingborg',
                    'Sociala flöden',
                    'Sociala flöden',
                    'read_private_pages',
                    'hbg-social-widget-menu',
                    array($this, 'settingsPage')
                );
            }
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
                update_option('hbgsf_twitter_consumer_key', esc_attr($_POST['twitter']['consumer_key']));
                update_option('hbgsf_twitter_consumer_secret', esc_attr($_POST['twitter']['consumer_secret']));
            }

            /**
             * Get options
             */
            $hbgsf_facebook_app_id         = get_option('hbgsf_facebook_app_id');
            $hbgsf_facebook_app_secret     = get_option('hbgsf_facebook_app_secret');
            $hbgsf_instagram_client_id     = get_option('hbgsf_instagram_client_id');
            $hbgsf_twitter_consumer_key    = get_option('hbgsf_twitter_consumer_key');
            $hbgsf_twitter_consumer_secret = get_option('hbgsf_twitter_consumer_secret');

            /**
             * Require the view
             */
            require($this->_viewsPath . 'settings-page.php');
        }

        /**
         * Enqueue js
         */
        public function addJs($hook = false) {
            wp_enqueue_script('helsingborg-social-widget-js', plugins_url('helsingborg-extra-mer/helsingborg-social-widget/assets/js/helsingborg-social-widget.js'), array('jquery'), false, true);
            wp_enqueue_style('helsingborg-social-widget-css', plugins_url('helsingborg-extra-mer/helsingborg-social-widget/assets/css/helsingborg-social-widget.css'), array(), '');
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
                    $instance['username']          = $this->getFbUserFromUrl($newInstance[$type . '-url']);
                    $instance['show_count']        = $newInstance[$type . '-count'];
                    $instance['show_visit_button'] = $newInstance[$type . '-show-visit-button'];
                    break;

                case 'pinterest':
                    $instance['username']          = $newInstance[$type . '-user'];
                    $instance['show_count']        = $newInstance[$type . '-count'];
                    $instance['show_visit_button'] = $newInstance[$type . '-show-visit-button'];
                    $instance['col_count']         = $newInstance[$type . '-col-count'];
                    break;

                case 'instagram':
                    $instance['username']          = $newInstance[$type . '-user'];
                    $instance['show_count']        = $newInstance[$type . '-count'];
                    $instance['col_count']         = $newInstance[$type . '-col-count'];
                    $instance['show_likes']        = $newInstance[$type . '-show-likes'];
                    $instance['show_visit_button'] = $newInstance[$type . '-show-visit-button'];
                    break;

                case 'twitter':
                    $instance['username']          = $newInstance[$type . '-user'];
                    $instance['show_count']        = $newInstance[$type . '-count'];
                    $instance['show_visit_button'] = $newInstance[$type . '-show-visit-button'];
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

            $this->addJs();

            if ($id != 'content-area' && $id != 'content-area-bottom') echo $before_widget;

            $view = 'widget-none.php';

            switch ($instance['feedType']) {
                case 'instagram':
                    $feed = $this->getInstagramFeed($instance['username'], $instance['show_count']);
                    $view = 'widget-instagram.php';
                    break;

                case 'facebook':
                    $feed = $this->getFacebookFeed($instance['username'], $instance['show_count']);
                    $view = 'widget-facebook.php';
                    break;

                case 'twitter':
                    $feed = $this->getTwitterFeed($instance['username'], $instance['show_count']);
                    $view = 'widget-twitter.php';
                    break;

                case 'pinterest':
                    $feed = $this->getPinterestFeed($instance['username'], $instance['show_count']);
                    $view = 'widget-pinterest.php';
                    break;
            }

            if (locate_template('templates/social/' . $view)) {
                locate_template('templates/social/' . $view, true);
            } else {
                require($this->_viewsPath . $view);
            }

            if ($id != 'content-area' && $id != 'content-area-bottom') echo $after_widget;
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

            $users = HbgCurl::request('GET', $endpoint, $data);
            $users = json_decode($users);

            $userId = null;

            foreach ($users->data as $user) {
                if ($user->username == $username) {
                    $userId = $user->id;
                    break;
                }
            }

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
         * Gets Facebook posts of a specified user from the Facebook Graph API
         * @param  string $username The user to get posts from
         * @param  integer $length  Max length (number of posts to show)
         * @return object           The loaded posts
         */
        public function getFacebookFeed($username, $length) {
            /**
             * Get appId and appSecret from options
             */
            $appId     = get_option('hbgsf_facebook_app_id');
            $appSecret = get_option('hbgsf_facebook_app_secret');

            /**
             * Request a token from Facebook Graph API
             */
            $endpoint = 'https://graph.facebook.com/oauth/access_token';
            $data = array(
                'grant_type'    => 'client_credentials',
                'client_id'     => $appId,
                'client_secret' => $appSecret
            );
            $token = HbgCurl::request('GET', $endpoint, $data);
            $token = explode('=', $token)[1];

            /**
             * Request the posts
             */
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
         * Gets a twitter feed of a specific username
         * @param  string $username  The twitter username to get
         * @param  integer $length   The max number of tweets to get
         * @return object            Object with the tweets
         */
        public function getTwitterFeed($username, $length) {
            /**
             * Get consumer key from options
             */
            $consumer_key    = get_option('hbgsf_twitter_consumer_key');
            $consumer_secret = get_option('hbgsf_twitter_consumer_secret');

            /**
             * Encode consumer key and secret
             */
            $consumer_key    = urlencode($consumer_key);
            $consumer_secret = urlencode($consumer_secret);

            /**
             * Concatenate key and secret and base64 encode
             */
            $bearer_token = $consumer_key . ':' . $consumer_secret;
            $base64_bearer_token = base64_encode($bearer_token);

            /**
             * Request access token
             */
            $endpoint = 'https://api.twitter.com/oauth2/token';

            // Request headers
            $headers = array(
                "POST /oauth2/token HTTP/1.1",
                "Host: api.twitter.com",
                "User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
                "Authorization: Basic " . $base64_bearer_token,
                "Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
            );

            // Postdata
            $data = array(
                'grant_type' => 'client_credentials'
            );

            // Curl and format response
            $response = HbgCurl::request('POST', $endpoint, $data, NULL, $headers);
            $response = json_decode($response);
            $access_token = $response->access_token;

            /**
             * Request statuses
             */
            $endpoint = 'https://api.twitter.com/1.1/statuses/user_timeline.json';

            // Postdata
            $data = array(
                'access_token'     => $access_token,
                'screen_name'      => $username,
                'count'            => $length,
                'exclude_replies ' => true,
                'include_rts '     => false
            );

            // Request headers
            $headers = array(
                "GET /1.1/search/tweets.json" . http_build_query($data) . " HTTP/1.1",
                "Host: api.twitter.com",
                "User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
                "Authorization: Bearer " . $access_token
            );

            // Curl
            $tweets = HbgCurl::request('GET', $endpoint, $data, 'JSON', $headers);

            return json_decode($tweets);
        }

        /**
         * Gets a Pinterest feed from a specific user
         * @param  string $username Pinterest username
         * @param  integer $length  Number of Pins to display
         * @return object           An object with the pins
         */
        function getPinterestFeed($username, $length) {
            $endpoint = 'https://api.pinterest.com/v3/pidgets/users/' . $username . '/pins/';
            $response = HbgCurl::request('GET', $endpoint);

            return json_decode($response)->data->pins;
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

        public function menuExist($handle, $sub = false){
            if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) return false;

            global $menu, $submenu;
            $check_menu = $sub ? $submenu : $menu;

            if (empty($check_menu)) return false;

            foreach($check_menu as $k => $item){
                if ($sub) {
                    foreach( $item as $sm ){
                        if ($handle == $sm[2]) return true;
                    }
                } else {
                    if ($handle == $item[2]) return true;
                }
            }

            return false;
        }

    }
}