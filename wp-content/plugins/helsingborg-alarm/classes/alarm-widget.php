<?php
if (!class_exists('AlarmList')) {
  class AlarmList
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
      register_widget( 'AlarmListWidget' );
    }
  }
}

if (!class_exists('AlarmListWidget')) {
  class AlarmListWidget extends WP_Widget {

    /** constructor */
    function AlarmListWidget() {
      parent::WP_Widget(false, '* Alarmlista', array('description' => 'Skapar en lista med senaste alarmen.'));
    }

    public function widget( $args, $instance ) {
      extract($args);

      // Only load scripts and styles if widget is used
      wp_enqueue_style('multiselect-css',       HELSINGBORG_ALARM_BASE .'/css/multiselect.css');
      wp_enqueue_script('multiselect-js',       HELSINGBORG_ALARM_BASE .'/js/multiselect.js');
      wp_enqueue_script('foundation-js',        HELSINGBORG_ALARM_BASE .'/js/foundation.min.js');
      wp_enqueue_script('foundation-reveal-js', HELSINGBORG_ALARM_BASE .'/js/foundation.reveal.js');

      $title     = empty($instance['title'])     ? __('Aktuella larm') : $instance['title'];
      $link      = empty($instance['link'])      ? '#'                 : $instance['link'];
      $amount    = empty($instance['amount'])    ? 10                  : $instance['amount'];

      // Get the default values
      $json = file_get_contents('http://alarmservice.helsingborg.se/AlarmServices.svc/GetAlarmsForCities/Helsingborg');
      $alarms = json_decode($json)->GetAlarmsForCitiesResult;

      // Print surrounding
      echo $before_widget;

      // Title
      echo $before_title . $title . $after_title;
      ?>

      <div>
        <select id="municipality_multiselect">
          <option value="Bjuv">Bjuv</option>
          <option value="Helsingborg" data-selected>Helsingborg</option>
          <option value="Höganäs">Höganäs</option>
          <option value="Klippan">Klippan</option>
          <option value="Landskrona">Landskrona</option>
          <option value="Åstorp">Åstorp</option>
          <option value="Ängelholm">Ängelholm</option>
          <option value="Örkelljunga">Örkelljunga</option>
        </select>
      </div>

      <ul class="alarm-list">

      <?php
      $today = date('Y-m-d');
      $number_of_alarms = count($alarms);
      $show = $number_of_alarms > $amount ? $amount : $number_of_alarms;
      for($i=0;$i<$show; $i++) : ?>
        <li>
          <span class="date"><?php echo $alarms[$i]->SentTime; ?></span>
          <a href="#" class="modalLinkAlarm" id="<?php echo $alarms[$i]->ID ?>" data-reveal-id="alarmModal"><?php echo $alarms[$i]->HtText ?></a>
        </li>
      <?php endfor; ?>

      <input type="text" id="selectedMunicipality" style="display: none;" />

      </ul>

      <a href="<?php echo $link; ?>" class="read-more">Till arkivet</a>

      <div class="reveal-modal-bg"></div>
      <div id="alarmModal" class="reveal-modal" data-reveal>
        <h2 class="section-title">Alarm</h2>

        <div class="divider fade">
          <div class="upper-divider"></div>
          <div class="lower-divider"></div>
        </div>

        <h1 class="main-title"></h1>

        <div class="row">
            <div class="small-12">
                <ul class="modal-item-list">
                  <li>
                      <span class="item-label modalDateHeader">Tidpunkt:</span>
                      <span class="item-value modalDate"></span>
                  </li>
                  <li>
                      <span class="item-label modalEventHeader">Händelse:</span>
                      <span class="item-value modalEvent"></span>
                  </li>
                  <li>
                      <span class="item-label modalStationHeader">Station:</span>
                      <span class="item-value modalStation"></span>
                  </li>
                  <li>
                      <span class="item-label modalIDHeader">Ärendeid:</span>
                      <span class="item-value modalID"></span>
                  </li>
                  <li>
                      <span class="item-label modalStateHeader">Larmnivå:</span>
                      <span class="item-value modalState"></span>
                  </li>
                  <li>
                      <span class="item-label modalAddressHeader">Adress:</span>
                      <span class="item-value modalAddress"></span>
                  </li>
                  <li>
                      <span class="item-label modalLocationHeader">Plats:</span>
                      <span class="item-value modalLocation"></span>
                  </li>
                  <li>
                      <span class="item-label modalAreaHeader">Insatsområde:</span>
                      <span class="item-value modalArea"></span>
                  </li>
                  <li>
                      <span class="item-label modalMunicipalityHeader">Kommuner:</span>
                      <span class="item-value modalMunicipality"></span>
                  </li>
                </ul>
            </div>
        </div>
        <a class="close-reveal-modal">×</a>
      </div>

      <script>
        var _amount = <?php echo $amount; ?>;
        var _alarms = <?php echo $json; ?>;
      </script>

      <?php
      echo $after_widget;
    }

    public function update( $new_instance, $old_instance) {
      $instance['title']     = strip_tags($new_instance['title']);
      $instance['link']      = strip_tags($new_instance['link']);
      $amount                = $new_instance['amount'];
      $instance['amount']    = $amount;
      return $instance;
    }

    public function form( $instance ) {
      $instance  = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '', 'link' => '' ) );
      $title     = strip_tags($instance['title']);
      $link      = strip_tags($instance['link']);
      $amount    = empty($instance['amount']) ? 10 : $instance['amount'];
  ?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Titel:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Arkivlänk:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo esc_attr($link); ?>" /></p>

      <p><label for="<?php echo $this->get_field_id('amount'); ?>"><?php _e('Antal alarm:'); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" type="number" value="<?php echo esc_attr($amount); ?>" /></p>
<?php
    }
  }
}
