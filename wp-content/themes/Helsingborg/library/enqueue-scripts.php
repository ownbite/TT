<?php

if (!function_exists('hbgScripts')) {

    /**
     * Scripts to enqueue globally
     * @return void
     */
    function hbgScripts() {

        // Deregister jquery
        wp_deregister_script('jquery');
        wp_deregister_script('jquery-ui');
        wp_dequeue_script('jquery-ui');

        // Register app.jquery (includes jquery, jquery ui)
        wp_register_script('jquery', get_template_directory_uri() . '/js/app.jquery.min.js', array(), '1.0.0', false);
        wp_enqueue_script('jquery');

        // Do page specific enqueues
        hbgPageSpecificScripts();

        // App.js
        wp_register_script( 'foundation', get_template_directory_uri() . '/js/app.min.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('foundation');

        // Readspeaker
        wp_register_script('readspeaker', 'http://f1.eu.readspeaker.com/script/5507/ReadSpeaker.js?pids=embhl', array(), '1.0.0', false);
        wp_enqueue_script('readspeaker');

        // App.css
        wp_enqueue_style('style-app', get_template_directory_uri() . '/css/app.css');

    }
    add_action('wp_enqueue_scripts', 'hbgScripts');

    /**
     * Page specific scripts to include
     * @return void
     */
    function hbgPageSpecificScripts() {
        /**
        * EVENT LIST PAGE
        **/
        if ( is_page_template( 'templates/event-list-page.php' )) {
            // Register scripts
            wp_register_script( 'zurb5-multiselect', get_template_directory_uri() . '/js/foundation-multiselect/zmultiselect/zurb5-multiselect.js', array(), '1.0.0', false );
            wp_register_script( 'jquery-datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.js', array(), '1.0.0', false );
            wp_register_script( 'knockout', get_template_directory_uri() . '/js/knockout/dist/knockout.js', array(), '3.2.0', false );
            wp_register_script( 'event-list-model', get_template_directory_uri() . '/js/helsingborg/event_list_model.js', array(), '1.0.0', false );

            // Register styles
            wp_register_style( 'zurb5-multiselect', get_template_directory_uri() . '/css/multiple-select.css', array(), '1.0.0', 'all' );
            wp_register_style( 'jquery-datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.css', array(), '1.0.0', 'all' );

            // Enqueue scripts
            wp_enqueue_script('zurb5-multiselect');
            wp_enqueue_script('jquery-datetimepicker');
            wp_enqueue_script('knockout');
            wp_enqueue_script('event-list-model');

            // Enqueue styles
            wp_enqueue_style('zurb5-multiselect');
            wp_enqueue_style('jquery-datetimepicker');
        }

        /**
        * ALARM LIST PAGE
        **/
        if ( is_page_template('templates/alarm-list-page.php')) {
            // Register scripts
            wp_register_script('zurb5-multiselect', get_template_directory_uri() . '/js/foundation-multiselect/zmultiselect/zurb5-multiselect.js', array(), '1.0.0', false);
            wp_register_script('jquery-datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.js', array(), '1.0.0', false);
            wp_register_script('knockout', get_template_directory_uri() . '/js/knockout/dist/knockout.js', array(), '3.2.0', false);
            wp_register_script('alarm-list-model', get_template_directory_uri() . '/js/helsingborg/alarm_list_model.js', array(), '1.0.0', false);

            // Register styles
            wp_register_style('zurb5-multiselect', get_template_directory_uri() . '/css/multiple-select.css', array(), '1.0.0', 'all');
            wp_register_style('jquery-datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.css', array(), '1.0.0', 'all');

            // Enqueue scripts
            wp_enqueue_script('zurb5-multiselect');
            wp_enqueue_script('jquery-datetimepicker');
            wp_enqueue_script('knockout');
            wp_enqueue_script('alarm-list-model');

            // Enqueue styles
            wp_enqueue_style('zurb5-multiselect');
            wp_enqueue_style('jquery-datetimepicker');
        }
    }

    /**
     * Admin specific scripts to enqueue
     * @return void
     */
    function load_custom_wp_admin_style() {
        wp_register_style('custom_wp_admin_css', get_template_directory_uri() . '/css/admin-hbg.css', false, '1.0.0');
        wp_enqueue_style('custom_wp_admin_css');

        wp_register_script('jquery-ui', get_template_directory_uri() . '/js/jquery/dist/jquery-ui.min.js', array(), '1.0.0', false);
        wp_register_script('select2' , (get_template_directory_uri() . '/js/helsingborg/select2.min.js'), array(), '1.0.0', false);

        wp_enqueue_script('jquery-ui');
        wp_enqueue_script('select2');
    }
    add_action('admin_enqueue_scripts', 'load_custom_wp_admin_style');

}


?>
