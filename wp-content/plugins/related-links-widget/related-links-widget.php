<?php
/*
Plugin Name: Related links widget
Plugin URI: -
Description: Custom widget for related links
Version: 1.0
Author: Henric Lind
Author URI: -
License: GPL2
*/

class rlw extends WP_Widget {

	// constructor
	function rlw() {
    parent::WP_Widget(false, $name = "Hitta snabbt länkar" );

	}

	// widget form creation
	function form($instance) {
    if ( isset( $instance[ 'title' ] ) )
    {
      $title = $instance[ 'title' ];
    }
    else {
      $title = 'Relaterade lankar';
    }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php
	}

	// widget update
	function update($new_instance, $old_instance) {
    $instance = array();
    $instance['title'] = strip_tags( $new_instance['title'] );
    return $instance;
	}

	// widget display
	function widget($args, $instance) {
    extract( $args );

    $title = apply_filters( 'widget_title', $instance['title'] );
    $links = get_related_links();
    ?>

		<div class="quick-links-widget widget large-12 medium-6 columns">
			<div class="widget-content">
				<h2 class="widget-title"><?php echo $title ?></h2>

				<div class="divider">
					<div class="upper-divider"></div>
					<div class="lower-divider"></div>
				</div>

				<?php if ( !empty( $links ) ) : ?>
				<ul class="quick-links-list">
				<?php foreach ( $links as $link ): ?>
					<li><a href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a></li>
				<?php endforeach; ?>
				</ul>
				<?php endif; ?>

				</div><!-- /.widget-content -->
		</div><!-- /.widget -->

    <?php
	}
}

function load_rlw() {
  return register_widget('rlw');
}

// register widget
add_action('widgets_init', 'load_rlw');

?>
