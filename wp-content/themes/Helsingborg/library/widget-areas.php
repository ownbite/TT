<?php

function Helsingborg_sidebar_widgets() {

  register_sidebar(array(
      'id' => 'footer-widgets',
      'name' => __('Footer widgets', 'Helsingborg'),
      'description' => __('Drag widgets to this footer container', 'Helsingborg'),
      'before_widget' => '<div class="large-3 medium-12 columns">',
      'after_widget' => '</div>',
      'before_title' => '',
      'after_title' => ''
  ));

  register_sidebar(array(
      'id' => 'start-page-news-listing',
      'name' => __('Startsida - nyhetslistning', 'Helsingborg'),
      'description' => __('Lägg till de nyheter som ska visas på startsidan', 'Helsingborg'),
      'before_widget' => '<ul class="news-list-large row">',
      'after_widget' => '</ul>',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'start-page-slider',
      'name' => __('Startsida - slider', 'Helsingborg'),
      'description' => __('Lägg till de sliders som ska visas på startsidan', 'Helsingborg'),
      'before_widget' => '',
      'after_widget' => ''
  ));

  register_sidebar(array(
      'id' => 'left-sidebar',
      'name' => __('Vänster sidebar', 'Helsingborg'),
      'description' => __('Lägg till de widgets som ska visas i vänsta sidebaren.', 'Helsingborg'),
      'before_widget' => '<article id="%1$s" class="large-12 columns widget %2$s">',
      'after_widget' => '</article>',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'left-sidebar-bottom',
      'name' => __('Vänster sidebar undertill', 'Helsingborg'),
      'description' => __('Lägg till de widgets som ska visas i vänstra sidebaren längst ner.', 'Helsingborg'),
      'before_widget' => '',
      'after_widget' => '',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'right-sidebar',
      'name' => __('Höger sidebar', 'Helsingborg'),
      'description' => __('Lägg till de widgets som ska visas i högra sidebaren.', 'Helsingborg'),
      'before_widget' => '',
      'after_widget' => '',
      'before_title' => '<h2>',
      'after_title' => '</h2>'
  ));
}

add_action( 'widgets_init', 'Helsingborg_sidebar_widgets' );

?>
