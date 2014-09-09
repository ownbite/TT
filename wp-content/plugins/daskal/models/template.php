<?php
class DaskalTemplate {
	function add($vars) {
		global $wpdb;
		
		if(!$this->check_tags($vars['content'])) throw new Exception(__('You have missed to include required template tags', 'daskal'));
		
		$result = $wpdb->query( $wpdb->prepare("INSERT INTO ".DASKAL_TEMPLATES." SET
			name=%s, content=%s", $vars['name'], $vars['content']));
			
		if($result == false) return false;
		return true;	
	}
	
	function edit($vars, $id) {
		global $wpdb;
		
		if(!$this->check_tags($vars['content'])) throw new Exception(__('You have missed to include required template tags', 'daskal'));
		
		$result = $wpdb->query( $wpdb->prepare("UPDATE ".DASKAL_TEMPLATES." SET
			name=%s, content=%s WHERE id=%d", $vars['name'], $vars['content'], $id));
			
		if($result == false) return false;
		return true;	
	}
	
	function delete($id) {
		global $wpdb;
		
		$result = $wpdb->query($wpdb->prepare("DELETE FROM ".DASKAL_TEMPLATES." WHERE id=%d", $_GET['id']));
		
		if($result == false) return false;
		return true;
	}

	// check for required tags	
	private function check_tags($content) {
		if(!strstr($content, '{{daskal-tutorial}}')) return false;		
		
		return true;
	}
}