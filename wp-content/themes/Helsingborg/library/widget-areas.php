<?php

function Helsingborg_sidebar_widgets() {

  register_sidebar(array(
      'id' => 'footer-widgets',
      'name' => __('Footerarea', 'Helsingborg'),
      'description' => __('Arean längst ner', 'Helsingborg'),
      'before_widget' => '<div class="large-3 medium-12 columns">',
      'after_widget' => '</div>',
      'before_title' => '',
      'after_title' => ''
  ));

  register_sidebar(array(
      'id' => 'content-area',
      'name' => __('Innehållsarea', 'Helsingborg'),
      'description' => __('Lägg till det som ska visas under innehållet.', 'Helsingborg')
  ));

  register_sidebar(array(
      'id' => 'content-area-bottom',
      'name' => __('Innehåll bottenarea', 'Helsingborg'),
      'description' => __('Lägg till det som ska visas under "Innehållsarea".', 'Helsingborg')
  ));

  register_sidebar(array(
      'id' => 'slider-area',
      'name' => __('Bildarea', 'Helsingborg'),
      'description' => __('Lägg till de sliders som ska visas på sidan.', 'Helsingborg'),
      'before_widget' => '<div class="large-12 columns slider-container"><div class="orbit-container"><ul class="example-orbit" data-orbit>',
      'after_widget' => '</ul></div></div>'
  ));

  register_sidebar(array(
      'id' => 'left-sidebar',
      'name' => __('Vänster area', 'Helsingborg'),
      'description' => __('Lägg till de widgets som ska visas i vänsta sidebaren.', 'Helsingborg'),
      'before_widget' => '<article id="%1$s" class="large-12 columns widget %2$s">',
      'after_widget' => '</article>',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'left-sidebar-bottom',
      'name' => __('Vänster bottenarea', 'Helsingborg'),
      'description' => __('Lägg till de widgets som ska visas i vänstra sidebaren längst ner.', 'Helsingborg'),
      'before_widget' => '',
      'after_widget' => '',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'right-sidebar',
      'name' => __('Höger area', 'Helsingborg'),
      'description' => __('Lägg till de widgets som ska visas i högra sidebaren.', 'Helsingborg'),
      'before_widget' => '',
      'after_widget' => '',
      'before_title' => '<h2>',
      'after_title' => '</h2>'
  ));
}

add_action( 'widgets_init', 'Helsingborg_sidebar_widgets' );

?>
