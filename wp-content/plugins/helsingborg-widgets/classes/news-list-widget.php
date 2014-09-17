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
      add_action('admin_enqueue_scripts', array($this,'sllw_load_scripts'));
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
      $title = apply_filters('widget_title', $instance['title']);
      $page_id = (int) $instance['page_id'];

      if ( function_exists('icl_object_id') ) { $page_id = icl_object_id($page_id, "page"); }

      echo $before_widget;

      if(!$page_id){
        echo 'Ingen sida nyhetssida vald!';
        echo $after_widget;
        return;
      }

      // Get the page and it's link
      $page = get_page($page_id, OBJECT, 'display');
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
            <?php echo $this->fr_excerpt_by_id($page); ?>
            <a href='<?php echo $link ?>' class="read-more">Läs mer</a>
          </div>
        </div>
      </li>

      <?php echo $after_widget;
    }

    // Function for retrieving the excerpt from page OR part of content if no excerpt was found
    function fr_excerpt_by_id($the_post, $excerpt_length = 35, $line_breaks = TRUE){
      $the_excerpt = $the_post->post_excerpt ? $the_post->post_excerpt : $the_post->post_content; //Gets post_excerpt or post_content to be used as a basis for the excerpt
      $the_excerpt = apply_filters('the_excerpt', $the_excerpt);
      $the_excerpt = $line_breaks ? strip_tags(strip_shortcodes($the_excerpt), '<p><br>') : strip_tags(strip_shortcodes($the_excerpt)); //Strips tags and images
      $words = explode(' ', $the_excerpt, $excerpt_length + 1);
      if(count($words) > $excerpt_length) :
        array_pop($words);
        array_push($words, '…');
        $the_excerpt = implode(' ', $words);
        $the_excerpt = $line_breaks ? $the_excerpt . '</p>' : $the_excerpt;
      endif;
      $the_excerpt = trim($the_excerpt);
      return $the_excerpt;
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
  }
}
