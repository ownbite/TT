<div class="wrap">
	<h1><?php _e('Create/Edit Tutorial Template', 'daskal')?></h1>
	
	<?php if(!empty($error_msg)):?>
		<p class="daskal-error"><?php echo $error_msg;?></p>
	<?php endif;?>
	
	<p><?php _e('The tutorial template contains the HTML code of a tutorial shown inside the default post template of your blog theme. It lets you define where the special turorial elements like rating widget, difficulty level, etc are shown', 'daskal')?></p>
	
	<h3><?php _e('Required tags', 'daskal')?></h3>
	
	<p><?php _e('Every tutorial template must contain the tag', 'daskal')?> <b>{{daskal-tutorial}}</b><?php _e('. It is where your tutorial content will be placed. The other tutorial elements are usually placed above or under it.', 'daskal')?></p>
	
	<h3><?php _e('Optional tags', 'daskal')?></h3>
	
	<ul>
		<li><b>{{daskal-difficulty-level}}</b> <?php _e('- This will display the selected difficulty level.', 'daskal')?></li>
		<li><b>{{daskal-reading-time}}</b> <?php _e('- This will display the reading time if such is entered.', 'daskal')?></li>
		<li><b>{{daskal-url}}</b> <?php _e('- This will display go-to URL in case you have such', 'daskal')?></li>
		<li><b>{{daskal-rating-widget}}</b> <?php _e('- This will display the rating widgets if you have selected any in the Daskal options page.', 'daskal')?></li>
	</ul>
	
	<div class="postbox daskal-wrap">
	<form method="post" action="#" onsubmit="return DaskalValidate(this);">
		<div><label><?php _e('Template name', 'daskal')?></label> <input type="text" name="name" size="60" value="<?php echo $name?>"></div>	
		<div><label><?php _e('Template content (include the tags)', 'daskal')?></label> <?php echo wp_editor($content, 'content')?></div>
		<p><input type="submit" name="ok" value="<?php _e('Save template', 'daskal')?>">
		<?php if(!empty($template->id)):?>
		<input type="hidden" name="del" value="0">
		<input type="button" value="<?php _e('Delete', 'daskal')?>" onclick="DaskalConfirmDelete(this.form);">
		<?php endif;?>		
		</p>
	</form>	
	</div>
</div>

<script type="text/javascript" >
function DaskalValidate(frm) {
	if(frm.name.value == '') {
		alert("<?php _e('Please enter template name', 'daskal')?>");
		frm.name.focus();
		return false;
	}
	
	return true;
}

function DaskalConfirmDelete(frm) {
	if(confirm("<?php _e('Are you sure?', 'daskal')?>")) {
		frm.del.value=1;
		frm.submit();
	}
}
</script>