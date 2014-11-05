<?php
if (!class_exists('News_List_Widget')) {
  class News_List_Widget
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
      register_widget( 'News_List_Widget_Box' );
    }

  }
}

if (!class_exists('News_List_Widget_Box')) {
{
  class News_List_Widget_Box extends WP_Widget {

    /** constructor */
    function News_List_Widget_Box() {
      parent::WP_Widget(false, '* Nyhetsobjekt', array('description' => 'Renderar ut vald sida som nyhet.'));
    }

    /* Front-end display of widget*/
    /** @see WP_Widget::widget */
    function widget($args, $instance) {
      extract($args);
      $page_id = (int) apply_filters('widget_title', $instance['page_id']);

      if ( function_exists('icl_object_id') ) { $page_id = icl_object_id($page_id, "page"); }

      if(!$page_id){
        echo 'Ingen sida nyhetssida vald!';
        return;
      }

      // Get the page and it's link
      $page = get_page($page_id, OBJECT, 'display');

      // Get the content, see if <!--more--> is inserted
      $the_content = get_extended(strip_shortcodes($page->post_content));
      $main = $the_content['main'];
      $content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main

      $link = get_permalink($page->ID); ?>

      <li class="news-item large-12 columns">
        <div class="row">
          <div class="large-4 medium-4 small-4 columns news-image">
            <?php // Try to get the thumbnail for the page
            if (has_post_thumbnail( $page->ID ) ) :
              $image_id = get_post_thumbnail_id( $page->ID );
              $image = wp_get_attachment_image_src( $image_id, 'single-post-thumbnail' );
              $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
              ?>
              <img src="<?php echo $image[0]; ?>" alt="<?php echo $alt_text; ?>">
            <?php endif; ?>
          </div>

          <div class="large-8 medium-8 small-8 columns news-content">
            <h2 class="news-title"><?php echo $page->post_title ?></h2>
            <span class="news-date"><?php echo $page->post_date ?></span>
            <?php echo wpautop($main, true); ?>
            <a href='<?php echo $link ?>' class="read-more">Läs mer</a>
          </div>
        </div>
      </li>

    <?php }

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
}
