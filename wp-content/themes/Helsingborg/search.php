<?php
get_header();

$query = $_GET['s'];
?>

<div class="article-page-layout row">
	<!-- main-page-layout -->
	<div class="main-area large-9 columns">

		<div class="main-content row">
			<!-- SIDEBAR LEFT -->
			<div class="sidebar sidebar-left large-4 medium-4 columns">

				<div class="row">
					<?php dynamic_sidebar("left-sidebar"); ?>
					<?php get_template_part('templates/partials/sidebar','menu'); ?>
				</div><!-- /.row -->
			</div><!-- /.sidebar-left -->

			<div class="large-8 medium-8 columns article-column">

				<div id="result" style="padding-bottom: 10px;"></div>

				<?php get_search_form(); ?>

				<ul id="search" class="block-list page-block-list search-list large-block-grid-3 medium-block-grid-3 small-block-grid-2"></ul>

				<div class="Pager"><ul class="pagination"></ul></div>

			</div><!-- /.columns -->

		</div><!-- /.main-content -->

	</div>  <!-- /.main-area -->

	<div class="sidebar sidebar-right large-3 columns">
		<div class="row">

			<?php /* Add the page's widgets */ ?>
			<?php if ( (is_active_sidebar('right-sidebar') == TRUE) ) : ?>
				<?php dynamic_sidebar("right-sidebar"); ?>
			<?php endif; ?>

		</div><!-- /.rows -->
	</div><!-- /.sidebar -->
</div><!-- /.article-page-layout -->

<script>
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var query = '<?php echo $query; ?>';
var initial_data = { action: 'search', keyword: query, index: '1' };
var next_data = null;
var prev_data = null;
jQuery.post(ajaxurl, initial_data, function(response) {
	updateSearch(JSON.parse(response));
});

function next() {
	jQuery.post(ajaxurl, next_data, function(response) {
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		updateSearch(JSON.parse(response));
	});
}

function previous() {
	jQuery.post(ajaxurl, prev_data, function(response) {
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		updateSearch(JSON.parse(response));
	});
}

function updateSearch(data) {
		var next     = data.queries.nextPage 	   !== undefined ? data.queries.nextPage[0] 		: undefined;
		var prev     = data.queries.previousPage !== undefined ? data.queries.previousPage[0] : undefined;
		var request  = data.queries.request 		 !== undefined ? data.queries.request[0] 			: undefined;
		var total = '<b>' + data.searchInformation.formattedTotalResults + '</b> träffar på <b>' + data.queries.request[0].searchTerms + '</b> inom hela webbplatsen';

		jQuery('#result').html("");
		jQuery('#search').html("");
		jQuery('.pagination').html("");
		jQuery('#result').append(total);

		for (var i = 0; i < data.items.length; i++) {
			var meta = data.items[i].pagemap.metatags[0];
			var item = '<li>';

			item += '<a href="' + data.items[i].link + '" desc="link-desc">';
			item += '<h2 class="list-title">' + data.items[i].title + '</h2></a>';
			if (meta['creationdate'] !== undefined) {
				item += '<span class="news-date">' + meta['creationdate'].substring(2,10) + '</span>';
			} else if (meta['last-modified'] !== undefined){
				item += '<span class="news-date">' + meta['epi.published'].substring(5,16) + '</span>';
			}
			item += '<div class="list-content">' + data.items[i].htmlSnippet + '<div>';
			item += '</li>';

			jQuery('#search').append(item);
		}

		if (prev !== undefined) {
			var prevPage = '<li class="arrow" style="float: left;"><a onclick="previous()">Föregående</a></li>';
			prev_data = { action: 'search', keyword: query, index: prev['startIndex'].toString() };
			jQuery('.pagination').append(prevPage);
		}

		if (next !== undefined) {
			var nextPage = '<li class="arrow" style="float: right;"><a onclick="next()">Nästa</a></li>';
			next_data = { action: 'search', keyword: query, index: next['startIndex'].toString() };
			jQuery('.pagination').append(nextPage);
		}
}
</script>

<?php get_footer(); ?>
