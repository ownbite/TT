<div class="wrap">
	<h1><?php _e('Daskal Tutorial Templates', 'daskal')?></h1>
	
	<p><?php _e('Here you can design templates which define how your tutorials created by the Daskal plugin will look. Advanced users can also use CSS or design their own theme page. For more about this see the information on the <a href="options-general.php?page=daskal_options">options page</a>.', 'daskal')?></p>
	
	<p><?php _e("If you don't create any templates or don't assign template to a tutorial, the default one can be used. The default template can be edited only by <a href='http://blog.calendarscripts.info/how-to-translate-a-wordpress-plugin/' target='_blank'>translating the plugin</a>.", 'daskal')?></p>
	
	<p><a href="admin.php?page=daskal_templates&do=add"><?php _e('Create new template', 'watupro')?></a></p>
	
	<?php if(sizeof($templates)):?>
		<table class="widefat">
			<tr><th><?php _e('Template name', 'daskal')?></th><th><?php _e('Edit/Delete', 'daskal')?></th></tr>
			<?php foreach($templates as $template):?>
				<tr><td><?php echo $template->name?></td><td><a href="admin.php?page=daskal_templates&do=edit&id=<?php echo $template->id?>"><?php _e('Edit/Delete', 'daskal')?></a></td></tr>
			<?php endforeach;?>
		</table>
	<?php endif;?>
</div>