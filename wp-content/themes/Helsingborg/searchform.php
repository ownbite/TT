<?php do_action('Helsingborg_before_searchform'); ?>
<form role="search" method="get" id="searchform" action="<?php echo home_url('/'); ?>">
	<div class="row collapse">
		<?php do_action('Helsingborg_searchform_top'); ?>
		<div class="search-inputs large-12 columns">
			<input type="text" value="" class="input-field" name="s" id="s" placeholder="<?php esc_attr_e('Vad letar du efter&#63;', 'Helsingborg'); ?>">
			<?php do_action('Helsingborg_searchform_before_search_button'); ?>
			<input type="submit" id="searchsubmit" value="<?php esc_attr_e('S&ouml;k', 'Helsingborg'); ?>" class="button search">
			<a href="#" class="archive-search-link">S&ouml;k i arkivet</a>
		</div>
		<?php do_action('Helsingborg_searchform_after_search_button'); ?>
	</div>
</form>
<?php do_action('Helsingborg_after_searchform'); ?>
