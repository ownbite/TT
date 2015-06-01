<?php

    define('helsingborg_THEME_FOLDER',str_replace("\\",'/',dirname(__FILE__)));

    /**
     * Welcome text
     */

    add_action('admin_init', 'helsingborgMetaWelcomeText');
    function helsingborgMetaWelcomeText() {
        add_meta_box('helsingborgMetaWelcomeText', 'Välkomsttext', 'helsingborgMetaFrontPageTextDisplay', 'page', 'normal', 'core');
        add_action('save_post', 'helsingborgMetaSaveFrontPageText');
    }

    function helsingborgMetaFrontPageTextDisplay() {
        global $post;
        $frontPageText = get_post_meta($post->ID, 'hbgWelcomeText', TRUE);

        $templatePath = locate_template('meta_boxes/UI/welcome-text.php');
        require($templatePath);
    }

    function helsingborgMetaSaveFrontPageText() {
        if (isset($_POST['hbg_easy_to_read_link']) && filter_var($_POST['hbgWelcomeText'], FILTER_VALIDATE_URL)) {
            update_post_meta($post_id, 'hbgWelcomeText', $_POST['hbgWelcomeText']);
        } else {
            update_post_meta($post_id, 'hbgWelcomeText', '');
        }
    }