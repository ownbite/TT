 <h4><?php _e('Tutorial Settings (Daskal):', 'daskal')?></h4>
 
 <p><?php _e('Difficulty level:', 'daskal')?> <select name="daskal_level">
 <?php foreach($levels as $level):
 	$level = trim($level);?>
 	<option value="<?php echo $level?>" <?php if($tutorial_level == $level) echo 'selected'?>><?php echo $level?></option>
 <?php endforeach;?> 
 </select></p>
 
 <p><?php _e('Approximate reading/learning time required:','daskal')?> <input type="text" size="5" name="daskal_reading_time" value="<?php echo $tutorial_reading_time?>"> <?php _e('minutes. (Leave blank to not include time-related info in this tutorial.)', 'daskal')?></p>
 
 <p><input type="checkbox" name="daskal_paginate" value="1" <?php if($paginate) echo 'checked'?>> <?php _e('Paginate the different steps in this tutorial', 'daskal')?> 
  <i><?php _e('(You can use the tag ', 'daskal')?></i> {{step}} <i><?php _e('to split your tutorial on different steps)', 'daskal')?></i></p>
  
  <p><?php _e('Design template:', 'daskal')?> <select name="daskal_template_id">
  		<option value="0" <?php if(empty($template_id)) echo 'selected'?>><?php _e('- Default template -', 'daskal')?></option>
  		<?php foreach($templates as $template):?>
  			<option value="<?php echo $template->id?>" <?php if($template->id == $template_id) echo 'selected'?>><?php echo $template->name?></option>
  		<?php endforeach;?>
  </select> <a href="admin.php?page=daskal_templates" target="_blank"><?php _e('(Manage templates)', 'daskal')?></a></p>
  
 <h4><?php _e('Remotely Hosted Tutorials:', 'daskal')?></h4>
 
 <p><?php _e('Use the following settings only if you are publishing an excerpt and want the user to go to read the tutorial on another site.', 'daskal')?></p>
 
 <p><?php _e('Remote URL', 'daskal')?> <input type="text" name="daskal_url" value="<?php echo $url?>" size="60"></p>
 <p><?php _e('This link will be:', 'daskal')?> <select name="daskal_url_type">
<option value="direct" <?php if($url_type == 'direct') echo 'selected'?>><?php _e('Direct', 'daskal')?></option> 
<option value="trackable" <?php if($url_type == 'trackable') echo 'selected'?>><?php _e('Indirect / Trackable', 'daskal')?></option>
 </select></p>