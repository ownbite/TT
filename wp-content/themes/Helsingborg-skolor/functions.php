<?php
  add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
  function theme_enqueue_styles() {
      //wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/css/normalize.css' );
      wp_enqueue_style( 'child-style',
          get_stylesheet_directory_uri() . '/css/app.css',
          array('parent-style')
      );
  }
?>
