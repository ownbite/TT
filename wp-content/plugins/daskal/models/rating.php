<?php 
class DaskalRating {
	function get_widget($type, $post) {		   	
		switch($type) {
			case '5stars':
				return $this->five_stars($post);
			break;
			case 'hands':
			   return $this->hands($post);
			break;
			default:
				// no widget, do nothing
				return "";
			break;
		}
	}
	
	function five_stars($post) {
		global $wpdb, $user_ID;
		
		// logged in only?
		$rating_login = get_option('daskal_rating_login');
		if($rating_login and !is_user_logged_in()) return __('Login to rate this tutorial', 'daskal');
		
		// already rated?		
		if($user_ID) $rating_value = $wpdb->get_var($wpdb->prepare("SELECT rating FROM ".DASKAL_RATINGS." WHERE tutorial_id=%d AND user_id=%d", $post->ID, $user_ID));
		else $rating_value = $wpdb->get_var($wpdb->prepare("SELECT rating FROM ".DASKAL_RATINGS." WHERE tutorial_id=%d AND ip=%s AND user_id=0", $post->ID, $_SERVER['REMOTE_ADDR']));		
		if(empty($rating_value)) $rating_value = 0;
		
		$content = '<div class="rating" id="ratingWidget'.$post->ID.'" data-rating-max="5"></div>';
		$content .= '<script type="text/javascript">
		    jQuery(function(){
			    jQuery("#ratingWidget'.$post->ID.'").starRating({
			    	postID : "'.$post->ID.'",
			    	initVal: '.($rating_value-1).'			    	 
			    });
			 });   
		</script>';
		return $content;
	}
	
	function hands($post) {
		global $wpdb, $user_ID;
		
		// logged in only?
		$rating_login = get_option('daskal_rating_login');
		if($rating_login and !is_user_logged_in()) return __('Login to rate this tutorial', 'daskal');
		
		// already rated?		
		if($user_ID) $rating_value = $wpdb->get_var($wpdb->prepare("SELECT rating FROM ".DASKAL_RATINGS." WHERE tutorial_id=%d AND user_id=%d", $post->ID, $user_ID));
		else $rating_value = $wpdb->get_var($wpdb->prepare("SELECT rating FROM ".DASKAL_RATINGS." WHERE tutorial_id=%d AND ip=%s AND user_id=0", $post->ID, $_SERVER['REMOTE_ADDR']));		
		if(empty($rating_value)) $rating_value = 0;
		
		$upsell = $downsell = '';
		if($rating_value >=2) $upsell = '-sel';
		else $downsell = '-sel';
		
		$content = '<div class="rating">
		<table cellspacing="5"><tr><td>
			<a href="#" onclick="Daskal.thumbs(5, '.$post->ID.');return false;"><img src="'.plugins_url('/daskal/img/thumbs-up'.$upsell.'.png').'" id="thumbsUp'.$post->ID.'"></a>
			</td><td><a href="#" onclick="Daskal.thumbs(1, '.$post->ID.');return false;"><img src="'.plugins_url('/daskal/img/thumbs-down'.$downsell.'.png').'" id="thumbsDown'.$post->ID.'"></a>
			</td></tr></table>
		</div>';
		
		return $content;
	}
}