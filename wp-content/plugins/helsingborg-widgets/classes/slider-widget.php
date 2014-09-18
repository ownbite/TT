<?php
if (!class_exists('Slider_Widget')) {
  class Slider_Widget
  {
    /**
     * Constructor
     */
    public function __construct()
    {
      // Set hooks
      add_action( 'widgets_init', array( $this, 'add_widgets' ) );
    }

    /**
     * Add widget
     */
    public function add_widgets()
    {
      register_widget( 'Slider_Widget_Box' );
    }

  }
}

if (!class_exists('Slider_Widget_Box')) {
  class Slider_Widget_Box extends WP_Widget {

    /** constructor */
    function Slider_Widget_Box() {
      parent::WP_Widget(false, '* Sliderobjekt', array('description' => 'Renderar ut vald sida som slider.'));
    }

    /* Front-end display of widget*/
    /** @see WP_Widget::widget */
    function widget($args, $instance) {
      extract($args);
      $page_id = (int) apply_filters('widget_title', $instance['page_id']);

      if ( function_exists('icl_object_id') ) { $page_id = icl_object_id($page_id, "page"); }

      echo $before_widget;

      if(!$page_id){
        echo 'Ingen sida vald!';
        echo $after_widget;
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
      $instance['page_id'] = (int) $new_instance['page_id'];
      return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
      $page_id = 0;

      if (isset($instance['page_id'])) {
        $page_id = (int) esc_attr($instance['page_id']);
      }

      $pageIdArgs = array(
        'selected' => $page_id,
        'name' => $this->get_field_name('page_id'),
      ); ?>

      <p><?php wp_dropdown_pages($pageIdArgs); ?></p>

  <?php

    }
  }
}
