// Commong functions in the Daskal plugin
Daskal = {};
Daskal.ajax_url = '';
Daskal.plugin_url = '';

// save the rating on server
Daskal.saveRating = function(rating, postID) {
	var data = {"rating" : rating, "post_id" : postID, 'action': 'daskal_ajax', 'do': 'store_rating'};
	jQuery.post(Daskal.ajax_url, data);
}

Daskal.thumbs = function(dir, postID) {	
	if(dir == 5) {		
		jQuery('#thumbsUp'+postID).attr('src', Daskal.plugin_url + "/img/thumbs-up-sel.png");
		jQuery('#thumbsDown'+postID).attr('src', Daskal.plugin_url + "/img/thumbs-down.png");
		this.saveRating(5, postID);
	} else {
		this.saveRating(1, postID);
		jQuery('#thumbsUp'+postID).attr('src', Daskal.plugin_url + "/img/thumbs-up.png");
		jQuery('#thumbsDown'+postID).attr('src', Daskal.plugin_url + "/img/thumbs-down-sel.png");
	}
}

Daskal.step = function(cnt, postID) {
	// hide all steps
	jQuery('.daskalParts-'+postID).hide();
	jQuery('#daskalPart-'+cnt+'-'+postID).show();
}