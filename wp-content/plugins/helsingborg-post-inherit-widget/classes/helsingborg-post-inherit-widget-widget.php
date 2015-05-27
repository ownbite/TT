<?php

if (!class_exists('HelsingborgPostInheritWidget')) {

    class HelsingborgPostInheritWidget extends WP_Widget {

        protected $_viewsPath;

        /**
         * Constructor
         */
        public function __construct() {
            // Sets the views directory path
            $this->_viewsPath = plugin_dir_path(plugin_dir_path(__FILE__)) . 'views/';

            // Enqueue javascript
            add_action('admin_enqueue_scripts', array($this, 'enqueueFiles'));

            // Widget arguments
            parent::__construct(
                'HelsingborgPostInheritWidget',
                '* Arvsinnehåll',
                array(
                    "description" => __('Visar innehåll från en specifik arvspost')
                )
            );
        }

        public function enqueueFiles($hook) {
            wp_enqueue_script('helsingborg-post-inherit-widget-javascript', plugins_url('helsingborg-post-inherit-widget/assets/js/helsingborg-post-inherit-widget.js'), array('jquery'), false, true);
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
            return $newInstance;
        }

        /**
         * Display the widget markup
         * @param  array $args     The widget arguments
         * @param  array $instance The widget instance
         * @return void            The widget markup
         */
        public function widget($args, $instance) {
            extract($args);
            $post = get_post($instance['post_id']);
            setup_postdata($post);

            $view = 'widget-content.php';
            if (locate_template('templates/plugins/hbg-inherit-widget/' . $view)) {
                locate_template('templates/plugins/hbg-inherit-widget/' . $view, true);
            } else {
                require($this->_viewsPath . $view);
            }
        }

    }

    add_action('wp_ajax_hbgPostInheritLoadPosts', 'hbgPostInheritLoadPosts_callback');
    function hbgPostInheritLoadPosts_callback() {
        global $wpdb;

        $title = $_POST['q'];
        $list  = '';

        $posts = $wpdb->get_results("
            SELECT ID, post_title
            FROM $wpdb->posts
            WHERE post_type = 'hbgInheristPosts'
            AND NOT post_title = 'Automatiskt utkast'
            AND post_title LIKE '%" . $title . "%'
        ");

        foreach ($posts as $post) {
            $list .= '<option value="' . $post->ID . '">';
            $list .= $post->post_title . ' (' . $post->ID . ')';
            $list .= '</option>';
        }

        echo $list;
        die();
    }

}