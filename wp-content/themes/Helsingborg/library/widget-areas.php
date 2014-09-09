<?php

function Helsingborg_sidebar_widgets() {
  register_sidebar(array(
      'id' => 'sidebar-widgets',
      'name' => __('Sidebar widgets', 'Helsingborg'),
      'description' => __('Drag widgets to this sidebar container.', 'Helsingborg'),
      'before_widget' => '<article id="%1$s" class="row widget %2$s"><div class="small-12 columns">',
      'after_widget' => '</div></article>',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'footer-widgets',
      'name' => __('Footer widgets', 'Helsingborg'),
      'description' => __('Drag widgets to this footer container', 'Helsingborg'),
      'before_widget' => '<article id="%1$s" class="large-4 columns widget %2$s">',
      'after_widget' => '</article>',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));

  register_sidebar(array(
      'id' => 'news-listing-start-page',
      'name' => __('Nyhetslistning startsida', 'Helsingborg'),
      'description' => __('Lägg till de nyheter som ska visas på startsidan', 'Helsingborg'),
      'before_widget' => '<article id="%1$s" class="large-12 columns widget %2$s">',
      'after_widget' => '</article>',
      'before_title' => '<h6>',
      'after_title' => '</h6>'
  ));
}

add_action( 'widgets_init', 'Helsingborg_sidebar_widgets' );

?>
