<?php

if (!class_exists('HelsingborgGalleryCustomPost')) {

    class HelsingborgGalleryCustomPost {

        protected $_viewsPath;
        protected $_assetsPath;

        /**
         * Construct function
         */
        public function __construct() {

            /**
             * Set the viewsPath
             */
            $this->_viewsPath = HELSINGBORG_GALLERY_BASE . 'views/';

            /**
             * Set the assetsPath
             */
            $this->_assetsPath = HELSINGBORG_GALLERY_URL . 'assets/';

            /**
             * Runs the registerGalleryPostType() on init hook
             */
            add_action('init', array($this, 'registerGalleryPostType'));

            /**
             * Run enqueue assets
             */
            add_action('admin_enqueue_scripts', array($this, 'enqueueAssets'));
        }

        /**
         * Registers the custom post type
         * @return void
         */
        public function registerGalleryPostType() {

            /**
             * Post type labels
             * @var array
             */
            $labels = array(
                'name'               => _x('Gallerier', 'post type name'),
                'singular_name'      => _x('Galleri', 'post type singular name'),
                'menu_name'          => __('Video galleri'),
                'add_new'            => __('Skapa nytt'),
                'add_new_item'       => __('Skapa galleri'),
                'edit_item'          => __('Redigera galleri'),
                'new_item'           => __('Nytt galleri'),
                'all_items'          => __('Alla gallerier'),
                'view_item'          => __('Visa galleri'),
                'search_items'       => __('Sök galleri'),
                'not_found'          => __('Inga gallerier att visa'),
                'not_found_in_trash' => __('Inga gallerier i soptunnan'),
            );

            /**
             * Post type arguments
             * @var array
             */
            $args = array(
                'labels'               => $labels,
                'description'          => 'Helsinborg galleries',
                'public'               => true,
                'menu_position'        => 100,
                'supports'             => array('title'),
                'has_archive'          => true,
                'menu_icon'            => 'dashicons-format-video',
                'register_meta_box_cb' => array($this, 'registerMetaBoxes')
            );

            /**
             * Register post type
             */
            register_post_type('hbgGalleries', $args);

            /**
             * Action to handle custom meta box save
             */
            add_action('save_post', array($this, 'youtubeMetaBoxSave'));
        }

        /**
         * Enqueue required assets
         * @return void
         */
        public function enqueueAssets() {
            global $post_type;

            if ($post_type == 'hbggalleries') {
                wp_enqueue_script('jquery-ui-sortable');
                wp_enqueue_script('hbg-gallery-js', $this->_assetsPath . 'js/hbg-gallery.js');
                wp_enqueue_style('hbg-gallery-css', $this->_assetsPath . 'css/hbg-gallery.css' );
            }
        }

        /**
         * Registers metaboxes to display in the custom post type
         * @return void
         */
        public function registerMetaBoxes() {
            add_meta_box('youtube-urls', 'Youtube länkar', array($this, 'youtubeMetaBox'), 'hbgGalleries', 'normal', 'high');
        }

        /**
         * Handles the youtube meta box
         * @param  object $post  The post's data
         * @param  array $args   Callback arguments
         * @return void
         */
        public function youtubeMetaBox($post, $args) {
            $youtubeLinks = json_decode(get_post_meta($post->ID, 'youtube-links')[0]);
            require($this->_viewsPath . 'metabox-youtube.php');
        }

        public function youtubeMetaBoxSave($post) {

            // Youtube link regex
            $rx = '~
                ^(?:https?://)?              # Optional protocol
                 (?:www\.)?                  # Optional subdomain
                 (?:youtube\.com|youtu\.be)  # Mandatory domain name
                 /watch\?v=([^&]+)           # URI with video id as capture group 1
                 ~x';

            // If youtube-link exists
            if (isset($_POST['hbg-gallery'])) {
                if (isset($_POST['youtube-link']) && is_array($_POST['youtube-link'])) {

                    // Remove invalid keys
                    foreach ($_POST['youtube-link'] as $key => $value) {
                        if (preg_match($rx, $value, $matches) == 0) unset($_POST['youtube-link'][$key]);
                    }

                    // Update the post meta
                    update_post_meta($post, 'youtube-links', json_encode($_POST['youtube-link']));
                } else {
                    update_post_meta($post, 'youtube-links', '');
                }
            }
        }
    }

}