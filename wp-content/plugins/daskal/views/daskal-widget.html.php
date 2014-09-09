<form method="get" action="<?php echo site_url();?>">
<input type="hidden" name="post_type" value="daskal_tutorial">
<?php if(empty($instance['hide_search'])):?>
	<p><?php _e('Contains:', 'daskal')?> <input type="text" name="s" value="<?php if(!empty($_GET['s'])) echo $_GET['s'];?>"></p>
<?php endif;
if(empty($instance['hide_difficulty'])):?>
	<p><?php _e('Difficulty:', 'daskal')?> <select name="difficulty">
	<option value=""><?php _e('Any', 'daskal')?></option>
	<?php foreach($levels as $level):
		$level = trim($level);?>
		<option value="<?php echo $level?>" <?php if(!empty($_GET['difficulty']) and $_GET['difficulty']==$level) echo 'selected'?>><?php echo $level?></option>
	<?php endforeach;?>
	</select></p>
<?php endif;
if(empty($instance['hide_reading_time'])):?>
	<p><?php _e('Reading time:', 'daskal')?> <input type="text" name="reading_from" size="4" value="<?php echo @$_GET['reading_from']?>">
	- <input type="text" name="reading_to" size="4" value="<?php echo @$_GET['reading_to']?>"> <?php _e('min', 'daskal')?></p>
<?php endif;?>	
<p><input type="submit" value="<?php _e('Search', 'daskal')?>"></p>
</form>