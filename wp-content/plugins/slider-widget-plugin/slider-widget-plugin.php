<?php
/**
 * @package Sliderwidget
 * @version 1.0
 */
/*
  Plugin Name: Sliderwidget
  Plugin URI: none
  Description: Väljer ut sida för slider
  Version: 1.0
  Author: Henric Lind
  Author URI: none
  License: GPL
 */

/*
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


/**
 * slider-widget-plugin Class
 */
class swp extends WP_Widget {

  /** constructor */
  function swp() {
    parent::WP_Widget(false, 'Slide', array('description' => 'Renderar ut vald sida som slider.'));
  }

  /* Front-end display of widget*/
  /** @see WP_Widget::widget */
  function widget($args, $instance) {
    extract($args);
    $title = apply_filters('widget_title', $instance['title']);
    $page_id = (int) $instance['page_id'];

    if ( function_exists('icl_object_id') ) { $page_id = icl_object_id($page_id, "page"); }

    echo $before_widget;

    if(!$page_id){
      echo 'Ingen sida vald!';
      //echo $after_widget;
      return;
    }

    $page = get_page($page_id, OBJECT, 'display');
    $link = get_permalink($page->ID);
    $image_meta = get_field('slider_image', $page->ID);
    $image_alt = '';
    $image = null;
    $caption_meta = get_field('slider_image_text', $page->ID);

    // Make sure there is an slider image on the page
    if ($image_meta && array_key_exists('url', $image_meta)) {
      $image = $image_meta['url'];
      $image_alt = $image_meta['alt'];
    } else {
      $image = 'http://www.placehold.it/1024x400&text=Slider';
    }

    ?>
    <li class="active">
      <img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>" class="img-slide" />
      <div class="orbit-caption"><?php echo $caption_meta; ?></div>
    </li>

    <?php //echo $after_widget;
  }

  /** @see WP_Widget::update */
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['page_id'] = (int) $new_instance['page_id'];
    return $instance;
  }

  /** @see WP_Widget::form */
  function form($instance) {
    $title = '';
    $page_id = 0;
    $checked = '';

    if (isset($instance['title'])) {
      $title = esc_attr($instance['title']);
    }

    if (isset($instance['page_id'])) {
      $page_id = (int) esc_attr($instance['page_id']);
    }

    $pageIdArgs = array(
      'selected' => $page_id,
      'name' => $this->get_field_name('page_id'),
    ); ?>

    <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
    <p><?php wp_dropdown_pages($pageIdArgs); ?></p>

<?php

  }
}

function slider_widget_plugin_load() {
  register_widget('swp');
}

add_action('widgets_init', 'slider_widget_plugin_load');
