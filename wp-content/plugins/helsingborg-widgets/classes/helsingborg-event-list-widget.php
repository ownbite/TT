<?php
if (!class_exists('EventList')) {
  class EventList
  {
    /**
     * Constructor
     */
    public function __construct()
    {
      add_action( 'widgets_init', array( $this, 'add_widgets' ) );
    }

    /**
     * Add widget
     */
    public function add_widgets()
    {
      register_widget( 'EventListWidget' );
    }
  }
}

if (!class_exists('EventListWidget')) {
  class EventListWidget extends WP_Widget {

    /** constructor */
    function EventListWidget() {
      parent::WP_Widget(false, '* Evenemangspuff', array('description' => 'Listar de senaste evenemangen.'));
    }

    public function widget( $args, $instance ) {
      extract($args);

      $title     = empty($instance['title'])     ? __('Kommande evenemang') : $instance['title'];
      $link_text = empty($instance['link_text']) ? __('Fler evenemang') : $instance['link_text'];
      $link      = empty($instance['link'])      ? '#' : $instance['link'];
      $amount    = empty($instance['amount'])    ? 5 : $instance['amount'];

      // Get the events
      $events = HelsingborgEventModel::load_events_simple($amount);
      $json_items = json_encode($events); // Used by modal view

      echo $before_widget; ?>

      <h2 class="widget-title"><?php echo $title; ?></h2>

      <div class="divider">
          <div class="upper-divider"></div>
          <div class="lower-divider"></div>
      </div>

      <ul class="calendar-list">

      <?php
      $today = date('Y-m-d');
      foreach( $events as $event ) : ?>
        <li>
          <?php // Present 'Idag HH:ii' och 'YYYY-mm-dd'
          if ($today == $event->Date) { ?>
            <span class="date">Idag <?php echo $event->Time; ?></span>
          <?php } else { ?>
            <span class="date"><?php echo $event->Date; ?></span>
          <?php } ?>

          <a href="#" class="modalLink" id="<?php echo $event->EventID ?>" data-reveal-id="eventModal"><?php echo $event->Name ?></a>
        </li>
      <?php endforeach; ?>

      </ul><!-- .calendar-list -->

      <a href="<?php echo $link; ?>" class="read-more"><?php echo $link_text; ?></a>


      <div id="eventModal" class="reveal-modal" data-reveal>
        <img class="modalImage"/>
        <h2 class="modalTitle"></h2>
        <p class="modalDate"></p>
        <p class="modalDescription"></p>
        <a class="close-reveal-modal">&#215;</a>
      </div>

      <script>
        var _events = <?php echo $json_items; ?>;
      </script>

      <script>
        jQuery(document).ready(function() {
          jQuery(document).on('click', '.modalLink', function(event){
              event.preventDefault();
              var image = $('.modalImage');
              var title = $('.modalTitle');
              var date = $('.modalDate');
              var description = $('.modalDescription');
              var result;

              for (var i = 0; i < _events.length; i++) {
                if (_events[i].EventID === this.id) {
                  result = _events[i];
                }
              }

              jQuery(image).attr("src", result.ImagePath);
              jQuery(title).html(result.Name);
              jQuery(date).html(result.Date);
              jQuery(description).html(result.Description);
          });
        });
      </script>

      <?php
      echo $after_widget;
    }

    public function update( $new_instance, $old_instance) {
      $instance['title']     = strip_tags($new_instance['title']);
      $instance['link']      = strip_tags($new_instance['link']);
      $instance['link_text'] = strip_tags($new_instance['link_text']);
      $amount                = $new_instance['amount'];
      $instance['amount']    = $amount;
      return $instance;
    }

    public function form( $instance ) {
      $instance  = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'link' => '' ) );
      $title     = strip_tags($instance['title']);
      $link      = strip_tags($instance['link']);
      $link_text = strip_tags($instance['link_text']);
      $amount    = empty($instance['amount']) ? 1 : $instance['amount'];
  ?>

      <ul class="hbgllw-instructions">
        <li><?php echo __("<b>OBS!</b> Denna widget bör endast användas i <b>Höger area</b> !"); ?></li>
      </ul>

      <ul class="hbgllw-instructions">
        <li><?php echo __("<b>Titel</b> är det som visas i widgetens header."); ?></li>
        <li><?php echo __("<b>Evenemangslänk</b> är länken till sidan som listar alla evenemang."); ?></li>
        <li><?php echo __("<b>Länktext</b> är texten på länken som går till alla evenemang."); ?></li>
        <li><?php echo __("<b>Antal evenemang</b> är hur många widgeten ska visa"); ?></li>
      </ul>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Evenemangslänk:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id('link_text'); ?>"><?php _e('Länktext:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr($link_text); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id('amount'); ?>"><?php _e('Antal evenemang:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="number" value="<?php echo esc_attr($amount); ?>" /></p>
<?php
    }
  }
}
