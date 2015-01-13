<?php

if (!function_exists('Helsingborg_scripts')) :
  function Helsingborg_scripts() {

    // deregister the jquery version bundled with wordpress
    wp_deregister_script( 'jquery' );

    // register scripts
    wp_register_script( 'modernizr', get_template_directory_uri() . '/js/modernizr/modernizr.min.js', array(), '1.0.0', false );
    wp_register_script( 'jquery', get_template_directory_uri() . '/js/jquery/dist/jquery.min.js', array(), '1.0.0', false );
    wp_register_script( 'jquery-ui', get_template_directory_uri() . '/js/jquery/dist/jquery-ui.min.js', array(), '1.0.0', false );
    wp_register_script( 'foundation', get_template_directory_uri() . '/js/app.js', array('jquery'), '1.0.0', true );

    // enqueue scripts
    wp_enqueue_script('modernizr');
    wp_enqueue_script('jquery');
    wp_enqueue_script('foundation');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('jquery-ui-sortable');

    /**
     * EVENT LIST PAGE
     **/
    if ( is_page_template( 'templates/event-list-page.php' )) {
      // Register script and styles
      wp_register_script( 'zurb5-multiselect',     get_template_directory_uri() . '/js/foundation-multiselect/zmultiselect/zurb5-multiselect.js', array(), '1.0.0', false );
      wp_register_script( 'jquery-datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.js', array(), '1.0.0', false );
      wp_register_script( 'knockout',              get_template_directory_uri() . '/js/knockout/dist/knockout.js', array(), '3.2.0', false );
      wp_register_script( 'event-list-model',      get_template_directory_uri() . '/js/helsingborg/event_list_model.js', array(), '1.0.0', false );

      wp_register_style( 'zurb5-multiselect',      get_template_directory_uri() . '/css/multiple-select.css', array(), '1.0.0', 'all' );
      wp_register_style( 'jquery-datetimepicker',  get_template_directory_uri() . '/js/jquery.datetimepicker.css', array(), '1.0.0', 'all' );

      // Enqueue script and styles
      wp_enqueue_script('zurb5-multiselect');
      wp_enqueue_script('jquery-datetimepicker');
      wp_enqueue_script('knockout');
      wp_enqueue_script('event-list-model');

      wp_enqueue_style('zurb5-multiselect');
      wp_enqueue_style('jquery-datetimepicker');
    }
  }
  add_action( 'wp_enqueue_scripts', 'Helsingborg_scripts' );

  function load_custom_wp_admin_style() {
    wp_register_style( 'custom_wp_admin_css', get_template_directory_uri() . '/css/admin-hbg.css', false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );

    // select2 CSS available in admin-hbg.css
    wp_register_script( 'select2' , (get_template_directory_uri() . '/js/helsingborg/select2.min.js'), array(), '1.0.0', false);
    wp_enqueue_script( 'select2' );

  }
  add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );



endif;

?>
