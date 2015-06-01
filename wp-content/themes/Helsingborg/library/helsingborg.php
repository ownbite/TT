<?php
/* Function for displaying proper IDs depending on current location,
 * used by wp_include_pages for the menus.
*/
function get_included_pages($post) {
    $includes = array();
    $args = array(
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_parent' => get_option('page_on_front'),
    );

    $base_pages = get_children( $args );
    foreach($base_pages as $page) {
        array_push($includes, $page->ID);
    }

    if ($post) {
        $ancestors = get_post_ancestors($post);
        array_push($ancestors, strval($post->ID));

        foreach ($ancestors as $ancestor) {
            $args = array(
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_parent' => $ancestor,
            );

            $childs = get_children( $args );

            foreach ($childs as $child) {
                array_push($includes, $child->ID);
            }

            array_push($includes, $ancestor);
        }
    }

    return implode(',', $includes);
}

/**
 * Flush cache when page is updated
 * @param  integer $post_id The updated post id
 * @return void
 */
function cache_flush_on_page_update($post_id) {

    // Remove the cached menu
    wp_cache_delete('menu_' . $post_id);

    // Remove the W3TC for this specific page
    if(function_exists('w3tc_pgcache_flush_post')){
        w3tc_pgcache_flush_post($post_id);

        // If page parent is list page, then flush that cache as well
        $parent = wp_get_post_parent_id($post_id);
        if ($parent) {
            $template_file = get_post_meta($parent,'_wp_page_template',TRUE);
            if ($template_file == 'templates/list-page.php') {
                w3tc_pgcache_flush_post($parent);
            }
        }
    }
}
add_filter('save_post', 'cache_flush_on_page_update', 10, 1);

/**
 * Update the default maximum number of redirects to 250
 * @return void
 */
function dbx_srm_max_redirects() {
    return 250;
}
add_filter('srm_max_redirects', 'dbx_srm_max_redirects');

/**
 * We need to insert empty spans in content.
 * Make sure html content isnt altered with when switching between Visual and Text.
 * This is due to our "listen" icon after documents, sent to readspeaker docreader.
 * @param  array $initArray
 * @return array
 */
function override_mce_options($initArray) {
    $opts = '*[*]';
    $initArray['valid_elements'] = $opts;
    $initArray['extended_valid_elements'] = $opts;

    return $initArray;
}
add_filter('tiny_mce_before_init', 'override_mce_options');

/**
 * Include JavaScript WordPress editor functions, used for guides
 */
if (file_exists(get_template_directory() . '/includes/js-wp-editor.php')) {
    require_once(get_template_directory() . '/includes/js-wp-editor.php');
}

/**
 * Remove Medium image size, only need full and thumbnail
 * @return void
 */
function remove_medium_image_size() {
    remove_image_size('medium');
}
add_action('init', 'remove_medium_image_size');

/**
 * Allow script & iframe tag within posts
 * @param  array $allowedposttags
 * @return array
 */
function allow_post_tags( $allowedposttags ){
    $allowedposttags['script'] = array(
        'type'   => true,
        'src'    => true,
        'height' => true,
        'width'  => true,
    );

    $allowedposttags['iframe'] = array(
        'src'                   => true,
        'width'                 => true,
        'height'                => true,
        'class'                 => true,
        'frameborder'           => true,
        'webkitAllowFullScreen' => true,
        'mozallowfullscreen'    => true,
        'allowFullScreen'       => true
    );

    return $allowedposttags;
}
add_filter('wp_kses_allowed_html','allow_post_tags', 1);

/**
 * Add supported mime-types for upload
 * @param array $mimes Allowed mimes
 */
function add_mime_types($mimes) {
  $mimes = array(
    'dwg' => 'application/dwg',
    'tfw' => 'application/tfw',

    // Image formats
    'jpg|jpeg|jpe'                 => 'image/jpeg',
    'gif'                          => 'image/gif',
    'png'                          => 'image/png',
    'bmp'                          => 'image/bmp',
    'tif|tiff'                     => 'image/tiff',
    'ico'                          => 'image/x-icon',
    'svg'                          => 'image/svg+xml',

    // Video formats
    'asf|asx'                      => 'video/x-ms-asf',
    'wmv'                          => 'video/x-ms-wmv',
    'wmx'                          => 'video/x-ms-wmx',
    'wm'                           => 'video/x-ms-wm',
    'avi'                          => 'video/avi',
    'divx'                         => 'video/divx',
    'flv'                          => 'video/x-flv',
    'mov|qt'                       => 'video/quicktime',
    'mpeg|mpg|mpe'                 => 'video/mpeg',
    'mp4|m4v'                      => 'video/mp4',
    'ogv'                          => 'video/ogg',
    'webm'                         => 'video/webm',
    'mkv'                          => 'video/x-matroska',

    // Text formats
    'txt|asc|c|cc|h'               => 'text/plain',
    'csv'                          => 'text/csv',
    'tsv'                          => 'text/tab-separated-values',
    'ics'                          => 'text/calendar',
    'rtx'                          => 'text/richtext',
    'css'                          => 'text/css',
    'htm|html'                     => 'text/html',

    // Audio formats
    'mp3|m4a|m4b'                  => 'audio/mpeg',
    'ra|ram'                       => 'audio/x-realaudio',
    'wav'                          => 'audio/wav',
    'ogg|oga'                      => 'audio/ogg',
    'mid|midi'                     => 'audio/midi',
    'wma'                          => 'audio/x-ms-wma',
    'wax'                          => 'audio/x-ms-wax',
    'mka'                          => 'audio/x-matroska',

    // Misc application formats
    'rtf'                          => 'application/rtf',
    'js'                           => 'application/javascript',
    'pdf'                          => 'application/pdf',
    'swf'                          => 'application/x-shockwave-flash',
    'class'                        => 'application/java',
    'tar'                          => 'application/x-tar',
    'zip'                          => 'application/zip',
    'gz|gzip'                      => 'application/x-gzip',
    'rar'                          => 'application/rar',
    '7z'                           => 'application/x-7z-compressed',
    'exe'                          => 'application/x-msdownload',
    'swf'                          => 'application/x-shockwave-flash',

    // MS Office formats
    'doc'                          => 'application/msword',
    'pot|pps|ppt'                  => 'application/vnd.ms-powerpoint',
    'wri'                          => 'application/vnd.ms-write',
    'xla|xls|xlt|xlw'              => 'application/vnd.ms-excel',
    'mdb'                          => 'application/vnd.ms-access',
    'mpp'                          => 'application/vnd.ms-project',
    'docx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'docm'                         => 'application/vnd.ms-word.document.macroEnabled.12',
    'dotx'                         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
    'dotm'                         => 'application/vnd.ms-word.template.macroEnabled.12',
    'xlsx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'xlsm'                         => 'application/vnd.ms-excel.sheet.macroEnabled.12',
    'xlsb'                         => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
    'xltx'                         => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
    'xltm'                         => 'application/vnd.ms-excel.template.macroEnabled.12',
    'xlam'                         => 'application/vnd.ms-excel.addin.macroEnabled.12',
    'pptx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    'pptm'                         => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
    'ppsx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
    'ppsm'                         => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
    'potx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.template',
    'potm'                         => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
    'ppam'                         => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
    'sldx'                         => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
    'sldm'                         => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
    'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',

    // OpenOffice formats
    'odt'                          => 'application/vnd.oasis.opendocument.text',
    'odp'                          => 'application/vnd.oasis.opendocument.presentation',
    'ods'                          => 'application/vnd.oasis.opendocument.spreadsheet',
    'odg'                          => 'application/vnd.oasis.opendocument.graphics',
    'odc'                          => 'application/vnd.oasis.opendocument.chart',
    'odb'                          => 'application/vnd.oasis.opendocument.database',
    'odf'                          => 'application/vnd.oasis.opendocument.formula',

    // WordPerfect formats
    'wp|wpd'                       => 'application/wordperfect',

    // iWork formats
    'key'                          => 'application/vnd.apple.keynote',
    'numbers'                      => 'application/vnd.apple.numbers',
    'pages'                        => 'application/vnd.apple.pages',
  );

  return $mimes;
}
add_filter('upload_mimes','add_mime_types');

/**
 * Fix to rename Default Template text to 'Artikel', since this page is default
 * @param  string $translation
 * @param  string $text
 * @param  string $domain
 * @return string
 */
function change_default_template_to_artikel( $translation, $text, $domain ) {
    if ( $text == 'Default Template' ) {
        return _('Artikel');
    }

    return $translation;
}
add_filter('gettext', 'change_default_template_to_artikel', 10, 3);

/**
 * Filter the days so only those in $days_array is returned
 * @return array
 */
function filter_date_array_by_days($dates_array, $days_array) {
    $return_array=array();

    foreach($dates_array as $date_string) {
        if (in_array(date('N', strtotime($date_string)), $days_array)) {
            array_push($return_array, $date_string);
        }
    }

    return $return_array;
}

/**
 * Creates an Array with strings with all dates between the from and to dates inserted
 * @param  string $strDateFrom Date from
 * @param  string $strDateTo   Date to
 * @return array               Date range
 */
function create_date_range_array($strDateFrom, $strDateTo)
{
    $aryRange=array();
    $iDateFrom = mktime(1,0,0, substr($strDateFrom,5,2), substr($strDateFrom,8,2), substr($strDateFrom,0,4));
    $iDateTo = mktime(1,0,0, substr($strDateTo,5,2), substr($strDateTo,8,2), substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom) {
        array_push($aryRange, date('Y-m-d', $iDateFrom)); // first entry
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom += 86400; // add 24 hours
            array_push($aryRange, date('Y-m-d', $iDateFrom));
        }
    }

    return $aryRange;
}

/**
 * Trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @param bool $strip_style if css style are to be stripped
 * @return string
 */
function trim_text($input, $length, $ellipses = true, $strip_tag = true,$strip_style = true) {
    //strip tags, if desired
    if ($strip_tag) { $input = strip_tags($input); }

    //strip tags, if desired
    if ($strip_style) { $input = preg_replace('/(<[^>]+) style=".*?"/i', '$1',$input); }

    if ($length == 'full') {
        $trimmed_text=$input;
    } else {
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) { return $input; }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) { $trimmed_text .= '...'; }
    }

    return $trimmed_text;
}

/**
 * Prints the breadcrumb
 * @return [type] [description]
 */
function the_breadcrumb() {
    global $post;

    $title = get_the_title();
    $output = '';

    echo '<ul class="breadcrumbs">';

    if (!is_front_page()) {
        if (is_category() || is_single()) {
            echo '<li>';
            the_category(' <li> ');

            if (is_single()) {
                echo '<li>';
                the_title();
                echo '</li>';
            }
        } elseif (is_page()) {
            if($post->post_parent){
                $anc = get_post_ancestors( $post->ID );
                $title = get_the_title();

                foreach ( $anc as $ancestor ) {
                    if (get_post_status($ancestor) != 'private') {
                        $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li></li>' . $output;
                    }
                }

                echo $output;
                echo '<strong title="'.$title.'"> '.$title.'</strong>';
            } else {
                echo '<li><strong> '.get_the_title().'</strong></li>';
            }
        }
    }
    echo '</ul>';
}


/**
 * Override of Page Attribute meta box
 * This is because the load time of wp_dropdown_pages(), which is removed in our version.
 * (original: page_attributes_meta_box() in wp-admin/includes/meta-boxes.php)
 */

// Remove the original meta box
function helsingborg_remove_meta_box(){
    remove_meta_box('pageparentdiv', 'page', 'side');
}
add_action('admin_menu', 'helsingborg_remove_meta_box');

// Add our own meta box instead
function helsingborg_add_meta_box() {
    add_meta_box('pageparentdiv', __('Page Attributes') , 'helsingborg_page_attributes_meta_box', 'page', 'side');
}
add_action('add_meta_boxes', 'helsingborg_add_meta_box');

// Use custom page attributes meta box, no need to load dropdown with pages!
function helsingborg_page_attributes_meta_box($post) {
    if ('page' == $post->post_type && 0 != count(get_page_templates($post))) :
        $template = !empty($post->page_template) ? $post->page_template : false;
?>
        <p><strong><?php _e('Template') ?></strong></p>
        <label class="screen-reader-text" for="page_template"><?php _e('Page Template') ?></label><select name="page_template" id="page_template">
        <option value='default'><?php _e('Default Template'); ?></option>
        <?php page_template_dropdown($template); ?>
        </select>
    <?php endif; ?>

    <p><strong><?php _e('Order') ?></strong></p>
    <p><label class="screen-reader-text" for="menu_order"><?php _e('Order') ?></label><input name="menu_order" type="text" size="4" id="menu_order" value="<?php echo esc_attr($post->menu_order) ?>" /></p>
    <p><?php if ( 'page' == $post->post_type ) _e( 'Need help? Use the Help tab in the upper right of your screen.' ); ?></p>
<?php
}
?>
