<?php 
class DaskalTutorial {
	// register the custom post type
	static function register_tutorial_type() {
		$args=array(
			"label" => __("Tutpress Tutorial", 'daskal'),
			"labels" => array
				(
					"name"=>__("Tutorials", 'daskal'), 
					"singular_name"=>__("Tutorial", 'daskal'),
					"add_new_item"=>__("Add New Tutorial", 'daskal')
				),
			"public"=> true,
			"show_ui"=>true,
			"has_archive"=>true,			
			"description"=>__("This will create a new tutorial from Daskal.",'daskal'),
			"supports"=>array("title", 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'post-formats'),
			"taxonomies"=>array("category"),
			"show_in_nav_menus"=>'true',
			"rewrite" => "daskal",
			'publicly_queryable' => true,
			'exclude_from_search' => false,
			"register_meta_box_cb"=>array(__CLASS__,"meta_boxes")
		);
		register_post_type( 'daskal_tutorial', $args );
	}
	
	static function meta_boxes() {
		add_meta_box("daskal_meta", __("Daskal Tutorial Settings", 'daskal'), 
							array(__CLASS__, "print_meta_box"), "daskal_tutorial", 'normal', 'high');
	}
	
	// output meta boxes
	static function print_meta_box($post) {
		global $wpdb;
		
		// difficulty levels
		$levels = get_option('daskal_levels');
		
		// design templates
		$templates = $wpdb->get_results("SELECT * FROM ".DASKAL_TEMPLATES." ORDER BY name");
		
		$tutorial_level = get_post_meta($post->ID, 'daskal_level', true);
		$tutorial_reading_time = get_post_meta($post->ID, 'daskal_reading_time', true);
		$paginate = get_post_meta($post->ID, 'daskal_paginate', true);
		$url = get_post_meta($post->ID, 'daskal_url', true);
		$url_type = get_post_meta($post->ID, 'daskal_url_type', true);
		$template_id = get_post_meta($post->ID, 'daskal_template_id', true);
		require(DASKAL_PATH."/views/meta-box.html.php");
	}
	
	// save tutorial meta
	static function save_meta($post_id) {
		global $wpdb;		
		
		if ( defined( 'DOING_AUTOSAVE' ) and DOING_AUTOSAVE )  return;
	  	if ( !current_user_can( 'edit_post', $post_id ) ) return;
	  	if ('daskal_tutorial' != @$_POST['post_type']) return;
	  	
	  	update_post_meta($post_id, "daskal_level", $_POST['daskal_level']);
	  	update_post_meta($post_id, "daskal_reading_time", $_POST['daskal_reading_time']);
		update_post_meta($post_id, "daskal_paginate", @$_POST['daskal_paginate']);
		update_post_meta($post_id, "daskal_url", @$_POST['daskal_url']);
		update_post_meta($post_id, "daskal_url_type", @$_POST['daskal_url_type']);
		update_post_meta($post_id, "daskal_template_id", @$_POST['daskal_template_id']);
	}
	
	// load tutorial using the template
	static function template_redirect() {
		// going to tutorial URL
		if(!empty($_GET['daskal_go'])) {
			$url = get_post_meta($_GET['urlid'], 'daskal_url', true);
			if(!empty($url)) header('Location: '.$url);
		}
	}
	
	// preprocess the post content accordinly to a template defined in admin
	static function preprocess_tutorial(&$post) {
		global $wpdb;
				
		// for now let's use hardcoded template
		$template_id = get_post_meta($post->ID, 'daskal_template_id', true);		
		if($template_id) $template = $wpdb->get_var($wpdb->prepare("SELECT content FROM ".DASKAL_TEMPLATES." WHERE id=%d", $template_id));
		
		if(empty($template)) $template =__("<div class='daskal_tutorial'>
				<p>Difficulty level: {{daskal-difficulty-level}}</p>
				<p>Approx reading time: {{daskal-reading-time}}</p>
				{{daskal-tutorial}}
				<p>Read full tutorial at <a href='{{daskal-url}}' target='_blank'>{{daskal-url}}</a></p>
				<p>Rating widget: {{daskal-rating-widget}}</p>", 'daskal');
				
		$inside_content = $post->post_content;		
		$inside_content = self :: break_in_steps($inside_content, $post);		
		$new_content = str_replace('{{daskal-tutorial}}', $inside_content, $template);

		// now replace the dynamic content
		$tutorial_level = get_post_meta($post->ID, 'daskal_level', true);
		$tutorial_reading_time = get_post_meta($post->ID, 'daskal_reading_time', true);
		$paginate = get_post_meta($post->ID, 'daskal_paginate', true);
		$url = get_post_meta($post->ID, 'daskal_url', true);
		$url_type = get_post_meta($post->ID, 'daskal_url_type', true);
		
		$new_content = str_replace('{{daskal-difficulty-level}}', $tutorial_level, $new_content);
		$new_content = str_replace('{{daskal-reading-time}}', $tutorial_reading_time, $new_content);
		
		// prepare and replace URL
		if($url_type == 'trackable') $url = site_url("?daskal_go=1&urlid=".$post->ID);
		$new_content = str_replace('{{daskal-url}}', $url, $new_content);
		
		// prepare and replace rating widget
		if(strstr($new_content, '{{daskal-rating-widget}}')) {			
			$rating = get_option('daskal_ratings');
			
			$_rating = new DaskalRating();
			$widget = $_rating->get_widget($rating, $post);
			
			$new_content = str_replace('{{daskal-rating-widget}}', $widget, $new_content);
		}		
		
		// add common JS
		$new_content.='<script type="text/javascript">
		jQuery(function(){
			if(Daskal.ajax_url == "") Daskal.ajax_url = "'.admin_url("admin-ajax.php").'";
			if(Daskal.plugin_url == "") Daskal.plugin_url = "'.plugins_url("/daskal").'";
		});
		</script>';
				
		$post->post_content = $new_content;		 
	}
	
	static function break_in_steps($content, $post) {
		$parts = explode("{{step}}", $content);
		
		if(sizeof($parts) == 1) return $content;
		
		$final_content = '';
		
		$num_parts = sizeof($parts);
		foreach($parts as $cnt=>$part) {
			$display = ($cnt == 0) ? 'block' : 'none';
			$final_content .= "<div id='daskalPart-$cnt-{$post->ID}' class='daskalParts-{$post->ID}' style='display:$display;'>\n";
			$final_content .= $part;
			$final_content .= "<p align='center'>";
			
			if($cnt >0) $final_content .= "<input type='button' value='".__('Previous step', 'daskal')."' onclick='Daskal.step(".($cnt-1).", {$post->ID});'>";
			if($cnt+1 < $num_parts) $final_content .= "<input type='button' value='".__('Next step', 'daskal')."' onclick='Daskal.step(".($cnt+1).", {$post->ID});'>";			
			
			$final_content .= "</p></div>\n";
		}
		
		return $final_content;
	}
	
	// shows tutorials on home
	static function pre_get_posts($query) {
		if ( (is_home() or is_archive()) and $query->is_main_query() ) {
			$post_types = @$query->query_vars['post_type'];

			// empty, so we'll have to create post_type setting			
			if(empty($post_types)) {
				if(is_home()) $post_types = array('post', 'nav_menu_item', 'daskal_tutorial');
				else $post_types = array('post', 'nav_menu_item', 'daskal_tutorial');
			}
			
			// not empty, so let's just add
			if(!empty($post_types) and is_array($post_types)) {				
				$post_types[] = 'daskal_tutorial';
				$query->set( 'post_type', $post_types );
			}
		}		
		
		if (!empty($query->query_vars['post_type']) and $query->query_vars['post_type'] == 'daskal_tutorial') {
			// add_filter('posts_where', array(__CLASS__, 'filter_where'));
	      add_filter('posts_join', array(__CLASS__, 'filter_join'));	      
	    }		
		
		return $query;
	}
	
	// prepares the orering of tutorials
	static function orderby($orderby) {
		// here in the next versions we'll need to allow sorting by rating, views or date 
		if(!empty($_GET['post_type']) and $_GET['post_type'] == 'daskal_tutorial') {
			// but for the first version just return the default orderby
			return $orderby;			
			// return "post_title ASC";
		}
		
		return $orderby;
	}
	
	// filter post results so tutorials are always 
	// displayed properly, even in search
	static function post_results($posts) {
		foreach($posts as $post) {
			if($post->post_type == 'daskal_tutorial') {
				self::preprocess_tutorial($post);
			}
		}
		return $posts;
	}	
	
	// filter search results
	// right now do nothing
	static function filter_where($where) {
		 global $wpdb; 
  		 return $where;
	}
	
	// the searches go in the join
	static function filter_join($join) {
		global $wp_query, $wpdb;

		if(empty($_GET['difficulty']) and empty($_GET['reading_from']) and empty($_GET['reading_to'])) return $join;		
		
	   if(!empty($_GET['difficulty'])) {
	   	$join .= "/* daskal join by difficulty*/ INNER JOIN {$wpdb->postmeta} daskalMeta1 ON {$wpdb->posts}.ID = daskalMeta1.post_id 
	   	AND daskalMeta1.meta_key = 'daskal_level' AND daskalMeta1.meta_value LIKE '".$_GET['difficulty']."'";
	   }
	   
	   if(!empty($_GET['reading_from']) or !empty($_GET['reading_to'])) {
			$meta_value_sql = "";
			if(!empty($_GET['reading_from'])) $meta_value_sql .= $wpdb->prepare(" AND daskalMeta2.meta_value >= %d ", $_GET['reading_from']);
			if(!empty($_GET['reading_to'])) $meta_value_sql .= $wpdb->prepare(" AND daskalMeta2.meta_value <= %d ", $_GET['reading_to']);
	   	
	   	$join .= "/* daskal join by reading time */ INNER JOIN {$wpdb->postmeta} daskalMeta2 ON {$wpdb->posts}.ID = daskalMeta2.post_id 
	   	AND daskalMeta2.meta_key = 'daskal_reading_time' $meta_value_sql";
	   }
	   	
	   return $join;
	}
}