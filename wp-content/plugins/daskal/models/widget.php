<?php
class DaskalWidget extends WP_Widget {
	public function __construct() {
		parent::__construct(
	 		'daskal_widget', // Base ID
			'Daskal Search &amp; Sort', // Name
			array( 'description' => __( 'Daskal Tutorials Search n Sort', 'daskal' ), )
		);
	}
	
	/**
	 * Front-end display of widget.	
	 */
	public function widget( $args, $instance ) {		
		extract( $args );
		
		$levels = get_option('daskal_levels');
		
		echo $before_widget;			
		echo $before_title . $instance['title'] . $after_title;	
		include(DASKAL_PATH."/views/daskal-widget.html.php");					
		echo $after_widget;
	}	
	
	// does nothing in this version  but in the future will allow customizing
	// of what to display
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['hide_search'] = $new_instance['hide_search'];
		$instance['hide_difficulty'] = $new_instance['hide_difficulty'];
		$instance['hide_reading_time'] = $new_instance['hide_reading_time'];
		return $instance;
	}	
	
	public function form( $instance ) {	
		if ( isset( $instance[ 'title' ]) ) $title = $instance[ 'title' ];
		else $title=__("Search &amp; Sort Tutorials", 'daskal');			
	
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' , 'daskal'); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( @$title ); ?>" />	</p>
		<p><input type="checkbox" name="<?php echo $this->get_field_name( 'hide_search' ); ?>" value="1" <?php if(!empty($instance['hide_search'])) echo 'checked'?>> <?php _e('Hide search field', 'daskal');?></p>
		<p><input type="checkbox" name="<?php echo $this->get_field_name( 'hide_difficulty' ); ?>" value="1" <?php if(!empty($instance['hide_difficulty'])) echo 'checked'?>> <?php _e('Hide difficulty selector', 'daskal');?></p>
		<p><input type="checkbox" name="<?php echo $this->get_field_name( 'hide_reading_time' ); ?>" value="1" <?php if(!empty($instance['hide_reading_time'])) echo 'checked'?>> <?php _e('Hide reading time selector', 'daskal');?></p>
		<p><?php _e('This widget will display search and sorting tutorials control.', 'daskal')?></p>
		<?php 
	}
}