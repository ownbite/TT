<div class="wrap">
	<h1><?php _e('Daskal Options', 'daskal')?></h1>
	
	<form method="post">	
	<div class="postbox daskal-wrap">
		<div><h3><?php _e('Difficulty levels:', 'daskal')?></h3> <textarea name="daskal_levels" rows="7" cols="50"><?php echo @implode(PHP_EOL, get_option('daskal_levels'))?></textarea>
		<p><?php _e('Please enter one diffculty level per line.', 'daskal')?></p>		
		</div>
		<p>&nbsp;</p>
		<div><h3><?php _e('Rating widget:', 'daskal')?></h3> <select name="daskal_ratings">
		<option value="none" <?php if( empty($ratings) or $ratings == 'none' ) echo 'selected'?>><?php _e('None', 'daskal')?></option>		
		<option value="5stars" <?php if( !empty($ratings) and $ratings == '5stars' ) echo 'selected'?>><?php _e('Five stars widget', 'daskal')?></option>
		<option value="hands" <?php if( !empty($ratings) and $ratings == 'hands' ) echo 'selected'?>><?php _e('Hads up / Hands down widget', 'daskal')?></option>
		</select> 
		<p><input type="checkbox" name="daskal_rating_login" value="1" <?php if($rating_login) echo 'checked'?>> <?php _e('Only logged in users can rate tutorials', 'daskal')?></p>
		</div>
		<p><input type="submit" value="<?php _e('Save Options', 'daskal')?>" name="ok"></p>
	</div>
	</form>
	
	<h2><?php _e('How To Use Daskal', 'daskal')?></h2>
	
	<p>Daskal is a plugin that lets you create tutorials in your site, let users rate them, search and sort them. You can even let authors publish their tutorials in your site.</p>
	
	<h3>After Installation</h3>
	
	<p>After you have installed and activated the plugin it's recommended to go to the Options page (which is essentially this one). There you can create your own difficulty levels and select several other global settings.</p>
	
	<h3>Publishing a Tutorial</h3>
	
	<p>There is a Tutorials link added to your Wordpress administration menu. Go there or straight to the <a href="post-new.php?post_type=daskal_tutorial">add new tutorial page</a>. There you can write the tutorial content. If you want to make it paginated step-by-step tutorial you can use the {{step}} tag to split the steps. There are also settings for difficulty level, reading time, design template etc.</p>
	
	<p>You can also post remotely hosted tutorials and track clicks on their links (coming soon!).</p>
	
	<h3>Tutorials Search</h3>
	
	<p>Daskal creates a search widget that lets your users browse and search the tutorials. Go to <a href="widgets.php">Appearance -&gt; Widgets</a> to enable the widget.</p>
	
	<h3>Design Templates</h3>
	
	<p>There are two ways to customize the default design of your tutorials template:</p>
	
	<p>1. By going to <a href="themes.php?page=daskal_templates">Appearance -&gt; Daskal Templates</a> where you can define the portion of the content that gets shown in the default post template in your theme.</p>
	
	<p>2. By designing custom tutorials template in your theme. You need to create a file called <b>single-daskal_tutorial.php</b>.</p>
	
	<h2><?php _e('Other Recommended Plugins', 'daskal')?></h2>
	
	<p>Daskal is standalone plugin and does not need other plugins to work. However we have created two other related education plugins that can complement Daskal and help you further with building your knowledge portal:</p>
	
	<ol
		<li><a href="http://namaste-lms.org/" target="_blank">Namaste! LMS </a>is a free learning management system for creating courses, lessons, giving homeworks etc.</li>
		<li><a href="http://wordpress.org/plugins/watu/" target="_blank">Watu</a> / <a href="http://calendarscripts.info/watupro/" target="_blank">WatuPRO</a> is a plugin for creating quizzes and exams. It's a great idea to add quizzes so your users can test what they learned by a given tutorial or category of tutorials.</li>
	</ol>
	
	<p>In the next releases Daskal is going to be closely integrated to these plugins while still being perfectly usable on its own.</p>
</div>