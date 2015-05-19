<?php
/*
Plugin Name: HbgTimelineWidget
Custom Timeline Widget
*/
// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');
/**
 * Adds HbgBookingWidget widget.
 */
class HbgTimelineWidget extends WP_Widget {

	/**
	 * __construct
	 */
	function __construct() {
		// Register the widget on widgets_init
        add_action('widgets_init', array($this, 'registerWidget'));

		parent::__construct(
			'hbgtimelinewidget', // Base ID
			__('* Timeline', 'helsingborg'), // Name
			array( 'description' => __( 'Timelinewidget description', 'helsingborg' ), ) // Args
		);
	}

    /**
     * Registers the widget
     * @return void
     */
    public function registerWidget() {
        register_widget('hbgtimelinewidget');
    }

    /**
     * Displays the widget on front-end
     * @param  array $args
     * @param  array $instance
     * @return void
     */
	public function widget($args, $instance) {
		global $post;

		// Enqueue flexslider js
		wp_enqueue_script('flexslider', get_template_directory_uri() . '/js/flexslider/jquery.flexslider-min.js', array('jquery'), '1.0.0', false);

		// Get layout type
		$show_layout = empty($instance['show_layout']) ? 'show_thin_design' : $instance['show_layout'];

		// Get item_id (parent node id)
		$item_id = empty($instance['item_id']) ? '' : $instance['item_id'];

		// Get the child pages
		$pages = get_pages(array(
			'sort_order'  => 'DESC',
			'sort_column' => 'post_modified',
			'child_of'    => $instance[ 'item_id' ],
			'post_type'   => 'page',
			'post_status' => 'publish')
		);

		// Create events array from $pages
		$events = $this->createTimelineDataFromPages($pages);

		// Show selected layout
		switch ($show_layout) {
			case 'show_thin_design':
				require(HELSINGBORG_KULTUR_WIDGET_BASE . 'views/thin_design.php');
				break;

			case 'show_big_design':
				require(HELSINGBORG_KULTUR_WIDGET_BASE . 'views/thin_design.php');
				break;;
		}
	}

	/**
	 * The widget form on back-end
	 * @param  array $instance
	 * @return void
	 */
	public function form($instance) {
		global $post;

		// Put instance in variables or use defaults if unset
		$show_placement = $instance['show_layout'] ?: __('show_thin_design', 'helsingborg');
		$post_id = $instance['post_id'] ?: $post->ID;
		$item_id = $instance['item_id'] ?: '';

		require(HELSINGBORG_KULTUR_WIDGET_BASE . 'views/timeline-form.php');
	}

	/**
	 * Handle widget save
	 * @param  array $new_instance
	 * @param  array $old_instance
	 * @return void
	 */
	public function update($new_instance, $old_instance) {
		$instance = array();
		$instance['show_layout'] = strip_tags($new_instance['show_layout']);
		$instance['post_id'] = $new_instance['post_id'];
		$instance[ 'item_id' ] = $new_instance['item_id'];

		return $instance;
	}

	/**
	 * Checks if a given page has customized sidebars or not
	 * @param  integer  $id The id of the page to check
	 * @return boolean
	 */
	public function hasCustomSidebars($id) {
		global $wpdb;
		$result = $wpdb->get_row("SELECT meta_value FROM $wpdb->postmeta WHERE meta_key = '_customize_sidebars' AND post_id = '$id'", OBJECT);

		if (isset($result->meta_value) && $result->meta_value == 'yes') {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Finds the first bookingwidget in an sidebar area
	 * @param  array $sidebar_widgets Widget list
	 * @return object        Returns false if not found, if found return widget_id
	 */
	public function findFirstBookingWidget($sidebar_widgets){
		foreach ($sidebar_widgets as $dd) {
			if ($dd != null){
				foreach ($dd as $d) {
					$string = 'hbgbookingwidget';
					if (stripos($d,$string) !== false) {
					    $data = $d;
						$widget_id = substr($data, strpos($data, "-") + 1);
					    return $widget_id;
					}
				}
			}
		}

		return false;
	}

	/**
	 * Loops pages and fetches the first bookingwidget on the page.
	 * From the widget data it creates an array with all data that
	 * needs to be displayed in the timeline.
	 * @param  object $pages The pages to use
	 * @return array         The fetched data
	 */
	public function createTimelineDataFromPages($pages) {
		global $wpdb;

		$nodes = array();

		foreach ($pages as $page) {
			if ($this->hasCustomSidebars($page->ID)) {

				// Get widgets
				$sidebar_widgets = $wpdb->get_row("SELECT * FROM $wpdb->postmeta WHERE meta_key = '_sidebars_widgets' AND post_id ='$page->ID'", OBJECT);
				$sidebar_widgets = unserialize($sidebar_widgets->meta_value);
				$widget_id = $this->findFirstBookingWidget($sidebar_widgets);

				// If has widget
				if ($widget_id) {

					// Find all bookingwidgets belonging to this page in the db
					$widget_uuid = 'widget_' . $page->ID . '_hbgbookingwidget';
					$result = $wpdb->get_row("SELECT * FROM $wpdb->options WHERE option_name = '$widget_uuid'", OBJECT);
					$result = unserialize($result->option_value);

					// The widget data
					$widgetData = $result[$widget_id];

					// Look for post thumbnail
					if (has_post_thumbnail($page->ID)) {
						$image_id = get_post_thumbnail_id($page->ID);
						$widgetData['image'] = wp_get_attachment_image_src($image_id, 'thumbnail')[0];
						$widgetData['image_alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
					}
				}

				$nodes[] = $widgetData;
			}
		}

		return $nodes;
	}

	public function arrSearch($array, $key, $value) {
		$results = array();

		if (is_array($array))
		{
			if (isset($array[$key]) && $array[$key] == $value)
				$results[] = $array;

			foreach ($array as $subarray)
				$results = array_merge($results, $this->arrSearch($subarray, $key, $value));
		}

		return $results;
	}

}