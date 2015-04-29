<?php

if (!class_exists('HelsingborgGPostInheritCustomPostType')) {

    class HelsingborgGPostInheritCustomPostType {

        protected $_viewsPath;

        /**
         * Construct function
         */
        public function __construct() {

            /**
             * Set the viewsPath
             */
            $this->_viewsPath = HELSINGBORG_GALLERY_BASE . 'views/';

            /**
             * Runs the registerGalleryPostType() on init hook
             */
            add_action('init', array($this, 'registerPostInheritPostType'));
        }

        /**
         * Registers the custom post type
         * @return void
         */
        public function registerPostInheritPostType() {

            /**
             * Post type labels
             * @var array
             */
            $labels = array(
                'name'               => _x('Arvsinnehåll', 'post type name'),
                'singular_name'      => _x('Arvsinnehåll', 'post type singular name'),
                'menu_name'          => __('Arvsinnehåll'),
                'add_new'            => __('Skapa nytt'),
                'add_new_item'       => __('Skapa arvsinnehåll'),
                'edit_item'          => __('Redigera arvsinnehåll'),
                'new_item'           => __('Nytt arvsinnehåll'),
                'all_items'          => __('Allt arvsinnehåll'),
                'view_item'          => __('Visa arvsinnehåll'),
                'search_items'       => __('Sök arvsinnehåll'),
                'not_found'          => __('Inget arvsinnehåll att visa'),
                'not_found_in_trash' => __('Inget arvsinnehåll i soptunnan'),
            );

            /**
             * Post type arguments
             * @var array
             */
            $args = array(
                'labels'              => $labels,
                'description'         => 'Helsingborg Post Inherit',
                'public'              => false,
                'publicly_queriable'  => true,
                'show_ui'             => true,
                'exclude_from_search' => true,
                'show_in_nav_menus'   => false,
                'rewrite'             => false,
                'menu_position'       => 100,
                'supports'            => array('title', 'editor', 'revisions'),
                'has_archive'         => true,
                'menu_icon'           => 'dashicons-controls-repeat'
            );

            /**
             * Register post type
             */
            register_post_type('hbgInheristPosts', $args);
        }
    }
}