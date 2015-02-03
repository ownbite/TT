
</div><!-- /.main-site-container -->

<footer class="footer">
		<div class="row">
				<?php dynamic_sidebar("footer-area"); ?>
		</div>
</footer>

</div><!-- /.inner-wrap -->
</div><!-- /.off-canvas-wrap -->

<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/app.js"></script>
<script type="text/javascript">
$(document).ready( function() {
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	jQuery.post(ajaxurl, { action: 'big_notification' }, function(response) {
		jQuery('.alert').append(response);
	});
});
</script>
<script src="<?php echo get_stylesheet_directory_uri() ; ?>/js/helsingborg/vergic.js"></script>
<?php wp_footer(); ?>
</body>
</html>
