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
            add_action('admin_enqueue_scripts', array($this, 'enqueueAdminAssets'), 10, 1);

            /**
             * Add shortcode for displaying galleries
             */
            add_shortcode('hbg-gallery', array($this, 'renderGalleryFromShortcode'));
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
                'menu_name'          => __('Mediagallerier'),
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
            add_action('save_post', array($this, 'galleryItemsMetaBoxSave'));
        }

        /**
         * Enqueue required assets
         * @return void
         */
        public function enqueueAdminAssets($hook) {
            global $post_type;

            if ($post_type == 'hbggalleries' && ($hook == 'post-new.php' || $hook == 'post.php')) {
                wp_enqueue_script('jquery-ui-sortable');
                wp_enqueue_script('hbg-gallery-js', $this->_assetsPath . 'js/hbg-gallery.js');
                wp_enqueue_script('hbg-gallery-media-selector-js', $this->_assetsPath . 'js/hbg-gallery-media-selector.js');
                wp_enqueue_style('hbg-gallery-css', $this->_assetsPath . 'css/hbg-gallery.css' );
            }
        }

        /**
         * Registers metaboxes to display in the custom post type
         * @return void
         */
        public function registerMetaBoxes() {
            add_meta_box('youtube-urls', 'Youtube länkar', array($this, 'galleryItemsMetaBox'), 'hbgGalleries', 'normal', 'high');
            add_meta_box('shortcode', 'Shortcode', array($this, 'shortcodeMetaBox'), 'hbgGalleries', 'side', 'core');
        }

        /**
         * Handles the youtube meta box
         * @param  object $post  The post's data
         * @param  array $args   Callback arguments
         * @return void
         */
        public function galleryItemsMetaBox($post, $args) {
            $galleryItems = get_post_meta($post->ID, 'gallery-items')[0];
            //exit(var_dump($galleryItems));
            require($this->_viewsPath . 'metabox-gallery-items.php');
        }

        /**
         * Saves the youtube metabox
         * @param  integer $post The post id
         * @return void          Saves the metadata
         */
        public function galleryItemsMetaBoxSave($post) {
            /**
             * Update post meta if bgGallery is set
             */
            if (isset($_POST['hbg-gallery'])) {
                if (isset($_POST['gallery-items']) && is_array($_POST['gallery-items'])) {
                    update_post_meta($post, 'gallery-items', $_POST['gallery-items']);
                } else {
                    update_post_meta($post, 'gallery-items', '');
                }
            }
        }

        /**
         * Displays the shortcode to render the gallery in a post or page
         * @param  integer $post The post
         * @param  array   $args Arguments
         * @return void          Displays the shortcode
         */
        public function shortcodeMetaBox($post, $args) {
            echo "[hbg-gallery $post->ID]";
        }

        /**
         * Renders a gallery from the short code
         * @param  array $attr Given attributes
         * @return void        Renders/displays the gallery
         */
        public function renderGalleryFromShortcode($attr) {
            wp_enqueue_script('hbg-gallery-front-js', $this->_assetsPath . 'js/hbg-gallery-front.js');

            $galleryId = $attr[0];
            if (is_string(get_post_status($galleryId))) {
                $galleryItems = get_post_meta($galleryId, 'gallery-items')[0];
                ob_start();
                require($this->_viewsPath . 'gallery-template.php');
                return ob_get_clean();
            } else {
                return "Det finns inget galleri med det angivna ID-numret!";
            }
        }
    }

}