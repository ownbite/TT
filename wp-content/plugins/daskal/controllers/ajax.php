<?php
function daskal_ajax() {
	global $wpdb, $user_ID;
	
	switch($_POST['do']) {
		case 'store_rating':
			$rating_login = get_option('daskal_rating_login');
			if($rating_login and !is_user_logged_in()) return __('Login to rate this tutorial', 'daskal');
			
			if($user_ID) $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".DASKAL_RATINGS." WHERE tutorial_id=%d AND user_id=%d", $_POST['post_id'], $user_ID));
			else $exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM ".DASKAL_RATINGS." WHERE tutorial_id=%d AND ip=%s AND user_id=0", $_POST['post_id'], $_SERVER['REMOTE_ADDR']));
			
			if($exists) {
				$wpdb->query($wpdb->prepare("UPDATE ".DASKAL_RATINGS." SET
					rating=%d WHERE id=%d", $_POST['rating'], $exists));
			} 
			else {
				$wpdb->query( $wpdb->prepare("INSERT INTO ".DASKAL_RATINGS." SET
					tutorial_id=%d, user_id=%d, ip=%s, rating=%d", $_POST['post_id'], $user_ID, $_SERVER['REMOTE_ADDR'], $_POST['rating']));
			}	
		break;
	}
	exit;
}