<?php
// manage design templates
class DaskalTemplates {
   static function manage() {
   	global $wpdb;
   	
   	$_template = new DaskalTemplate();
		$do = empty($_GET['do']) ? 'list' : $_GET['do'];   	
		
   	switch($do) {
   		case 'add':
				$name = $content = ''; // initialize view vars   		
   		
   			if(!empty($_POST['ok'])) {
   				try {
   					$_template->add($_POST);
   					daskal_redirect("admin.php?page=daskal_templates");
   				} 
   				catch(Exception $e) {
   					$error_msg = $e->getMessage();
   					$name = $_POST['name'];
   					$content = $_POST['content'];
   				}
   			}
   			
   			include(DASKAL_PATH."/views/template.html.php");
   		break;
   		
   		case 'edit':   		
				if(!empty($_POST['del'])) {
					try {
   					$_template->delete($_GET['id']);
   					daskal_redirect("admin.php?page=daskal_templates");
   				} 
   				catch(Exception $e) {
   					$error_msg = $e->getMessage();
   				}
   				unset($_POST['ok']);
				}   		
   		
				if(!empty($_POST['ok'])) {
   				try {
   					$_template->edit($_POST, $_GET['id']);
   					daskal_redirect("admin.php?page=daskal_templates");
   				} 
   				catch(Exception $e) {
   					$error_msg = $e->getMessage();
   				}
   			}   		
   			
   			// select template
				$template = $wpdb -> get_row($wpdb->prepare("SELECT * FROM ".DASKAL_TEMPLATES." WHERE id=%d", $_GET['id']));
				$name = stripslashes($template->name);
				$content = stripslashes($template->content);
   		
   			include(DASKAL_PATH."/views/template.html.php");
   		break;
   		
   		case 'list':
   		default:
				// select all templates   	
				$templates = $wpdb -> get_results("SELECT * FROM ".DASKAL_TEMPLATES." ORDER BY name");	
   		
   			include(DASKAL_PATH."/views/templates.html.php");
   		break;
   	}
   }
}