<?php
/*
Template Name: Lista
*/

get_header();

// Load the list used by List pages and childs
define('LIST_ARRAY', get_template_directory() . '/meta_boxes/UI/list-array.php');
include_once(LIST_ARRAY);

// Lets see which headers the user wants to use
$headers = [];
$header_keys = [];
$fields = get_fields($post->ID);

// Get the list options for this page and create array with the values which we can use later on
$meta = get_post_meta($post->ID,'_helsingborg_meta',TRUE);
$selected_list_options_meta = array();
$selected_list_options = array();

// Check if page has any data
if (is_array($meta)) {
    $selected_list_options_meta = $meta['list_options'];
    $selected_list_options = explode(",",$selected_list_options_meta);
}

// Prepare the list and headers
foreach($selected_list_options as $option) {
    array_push($header_keys, $option);
    array_push($headers, $list[$option]);
}

// Get the child pages
$pages = get_pages(array(
    'sort_order'  => 'DESC',
    'sort_column' => 'post_modified',
    'child_of'    => $post->ID,
    'post_type'   => 'page',
    'post_status' => 'publish'
));

// Create empty array that will hold our items
$list_items = array();

// Go through all childs and compare with selected keys from page
for ($i = 0; $i < count($pages); $i++) {

    // Create new empty object
    $item = array();

    // Go through all header_keys so we kan try to pick up all saved meta data
    for ($j = 0; $j < count($header_keys); $j++) {

        // Get the meta data from child
        $child_meta = get_post_meta($pages[$i]->ID,'_helsingborg_meta',TRUE);
        if (is_array($child_meta)) {
            $data = $child_meta['article_options_' . $header_keys[$j]];
        }

        // We dont want empty data, show "-" instead !
        if (empty($data)) $data = " - ";

        // Save this data as keyX->value
        $arr = array(
            strval('item' . $j) => $data
        );

        // Add the data to our item
        $item = array_merge($item, $arr);
    }

    // Build the content and add as array item
    $content = '<h2>' . esc_attr($pages[$i]->post_title) . '</h2>
                <div class="td-content">
                <p>' . apply_filters('the_content', $pages[$i]->post_content) . '</p>
                </div>
                <span class="icon"></span>';

    $item_content = array('content' => $content);

    // Add the content to the current item
    $item = array_merge($item, $item_content);

    // Add the item to the list
    array_push($list_items, $item);
}

// Make sure to sort the list by first column value
usort( $list_items, create_function('$a,$b', 'return strcmp($a["item0"], $b["item0"]);'));

// JSON encode the current data for usage with knockout!
$json_items = json_encode($list_items);

// Get the content, see if <!--more--> is inserted
$the_content = get_extended($post->post_content);
$main = $the_content['main'];
$content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main

?>

<script src="http://knockoutjs.com/downloads/knockout-3.0.0.debug.js" type="text/javascript"></script>

<div class="list-page-layout no-page-image row">

    <!-- main-page-layout -->
    <div class="main-area large-12 columns">
        <div class="main-content row">

            <?php get_template_part('templates/partials/sidebar-left'); ?>

            <div class="large-9 medium-8 columns article-column">
                <div class="alert row"></div>
                <?php get_template_part('templates/partials/header','image'); ?>

                <!-- Slider -->
                <?php $hasSlides = (is_active_sidebar('slider-area') == TRUE) ? '' : 'no-image'; ?>
                <div class="row <?php echo $hasSlides; ?>">
                    <?php dynamic_sidebar("slider-area"); ?>
                </div>

                <?php /* Start loop */ ?>
                <?php while (have_posts()) : the_post(); ?>
                <article class="article">
                    <header>
                        <?php the_breadcrumb(); ?>
                        <h1 class="article-title"><?php the_title(); ?></h1>
                        <?php get_template_part('templates/partials/accessability','menu'); ?>
                    </header>

                    <?php if (!empty($content)) : ?>
                        <div class="ingress">
                            <?php echo apply_filters('the_content', $main); ?>
                        </div>
                    <?php endif; ?>

                    <div class="article-body">
                        <?php
                            if (!empty($content)) {
                                echo apply_filters('the_content', $content);
                            } else {
                                echo apply_filters('the_content', $main);
                            }
                        ?>
                    </div>

                    <?php get_template_part('templates/partials/share'); ?>

                    <div class="filter-search">
                        <input type="text" placeholder="Sök i listan..." data-bind="value: query, valueUpdate: 'keyup'" autocomplete="off"/>
                        <input type="submit" value="sök" class="button">
                    </div>

                    <table class="table-list">
                        <thead>
                            <tr>
                                <th></th>
                                <?php
                                    $int = 0;

                                    foreach ($headers as $header) {
                                        $int++;
                                        echo('<th class="header" data-bind="click: sort">' . $header . '</th>');
                                    }
                                ?>
                            </tr>
                        </thead>

                        <!-- ko foreach: { data: itemstoshow } -->
                        <tbody>
                            <tr class="table-item">
                                <?php
                                    foreach ($header_keys as $key => $value) {
                                        echo('<td data-bind="text: item' . strval($key) . '"></td>');
                                    }
                                ?>
                            </tr>
                            <tr class="table-content">
                                <td colspan="<?php echo count($headers); ?>" data-bind="html: content"></td>
                            </tr>
                        </tbody>
                        <!-- /ko -->
                    </table>

                    <footer>
                        <script type="text/javascript" language="javascript">
                            function ListViewModel() {
                                var self = this;
                                var itemjson = <?php echo $json_items; ?>;

                                /**
                                * Observables
                                */
                                self.itemjson = ko.observable(itemjson);
                                self.query = ko.observable('');
                                self.sortBy = ko.observable('');

                                /**
                                * Items to show
                                */
                                self.itemstoshow = ko.computed(function () {
                                    // Search filter items
                                    var sort = self.sortBy();
                                    var search = self.query().toLowerCase();

                                    var items = ko.utils.arrayFilter(self.itemjson(), function(item) {
                                        return ((item.content.toLowerCase().indexOf(search) >= 0)
                                            <?php
                                                if (count($header_keys) > 0) {
                                                    echo '||';
                                                }

                                                foreach ($header_keys as $key => $value) {
                                                    echo '(item.item' . strval($key) . '.toLowerCase().indexOf(search) >= 0)';

                                                    if ($key != (count($header_keys) - 1)) {
                                                        echo ' || ';
                                                    }
                                                }
                                            ?>
                                        );
                                    });

                                    return items;
                                }, self);

                                /**
                                * Triggering the sorting by click on column headers
                                */
                                self.sort = function (item, event) {
                                    var el = $(event.target);
                                    var thead = el.parents('table').find('thead');

                                    if (el.hasClass('sorting-desc') || (!el.hasClass('sorting-desc') && !el.hasClass('sorting-asc'))) {
                                        // Sort asc
                                        self.resetSort(thead);
                                        el.addClass('sorting-asc headerSortDown');
                                        self.sortList('asc', el);
                                    } else {
                                        // Sort desc
                                        self.resetSort(thead);
                                        el.addClass('sorting-desc headerSortUp');
                                        self.sortList('desc', el);
                                    }
                                }

                                /**
                                * Do the actual sorting
                                */
                                self.sortList = function(order, el) {
                                    var columnNum = (el.index() - 1);

                                    if (order.toLowerCase() == 'asc') {
                                        // Sort asc
                                        self.itemjson().sort(function (a, b) {
                                            var a = a['item' + columnNum].toLowerCase();
                                            var b = b['item' + columnNum].toLowerCase();

                                            if (a < b) {
                                                return -1;
                                            }
                                            else if (a > b) {
                                                return 1;
                                            }
                                            else {
                                                return 0;
                                            }
                                        });
                                    } else if (order.toLowerCase() == 'desc') {
                                        // Sort desc
                                        self.itemjson().sort(function (a, b) {
                                            var a = a['item' + columnNum].toLowerCase();
                                            var b = b['item' + columnNum].toLowerCase();

                                            if (a > b) {
                                                return -1;
                                            }
                                            else if (a < b) {
                                                return 1;
                                            }
                                            else {
                                                return 0;
                                            }
                                        });
                                    }

                                    self.sortBy(columnNum + order);
                                }

                                /**
                                * Reset the sorting classes on the table header
                                */
                                self.resetSort = function(thead) {
                                    thead.find('th').removeClass('sorting-asc sorting-desc headerSortDown headerSortUp');
                                }
                            }

                            ko.applyBindings(new ListViewModel());
                        </script>
                    </footer>
                </article>
                <?php endwhile; // End the loop ?>
            </div><!-- /.columns -->
        </div><!-- /.main-content -->

        <div class="lower-content row">
            <div class="sidebar large-4 columns">
                <div class="row"></div><!-- /.row -->
            </div><!-- /.sidebar -->

            <?php
                if ( (is_active_sidebar('content-area-bottom') == TRUE) ) {
                    dynamic_sidebar("content-area-bottom");
                }
            ?>
        </div><!-- /.lower-content -->
    </div>  <!-- /.main-area -->
</div><!-- /.article-page-layout -->

<?php get_footer(); ?>
