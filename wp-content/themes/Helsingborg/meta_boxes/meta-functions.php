<?php

define('helsingborg_WORDPRESS_FOLDER',$_SERVER['DOCUMENT_ROOT']);
define('helsingborg_THEME_FOLDER',str_replace("\\",'/',dirname(__FILE__)));
define('helsingborg_THEME_PATH','/' . substr(helsingborg_THEME_FOLDER,stripos(helsingborg_THEME_FOLDER,'wp-content')));

add_action('admin_init','helsingborg_meta_init');

function helsingborg_meta_init()
{
    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_script
    // http://codex.wordpress.org/Function_Reference/wp_enqueue_style

    //wp_enqueue_script('helsingborg_meta_js', helsingborg_THEME_PATH . '/custom/meta.js', array('jquery'));
    wp_enqueue_style('helsingborg_meta_css', helsingborg_THEME_PATH . '/meta_boxes/UI/meta.css');

    // review the function reference for parameter details
    // http://codex.wordpress.org/Function_Reference/add_meta_box
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    $template_file = get_post_meta($post_id,'_wp_page_template',TRUE);

    // add a meta box for each of the wordpress page types: posts and pages
    foreach (array('post','page') as $type)
    {

      // LIST PAGE
      if ($template_file == 'templates/list-page.php') {
        // TODO
      }

      // LIST PRESENTATION PAGE
      else if ($template_file == 'templates/list-presentation-page.php') {
        add_meta_box('helsingborg_all_meta', "Listpresentation", 'helsingborg_meta_List', $type, 'normal', 'high');
      }

      // RSS PAGE
      else if ($template_file == 'templates/rss-page.php') {
        remove_post_type_support('page', 'editor');
        remove_post_type_support('page', 'comments');
        remove_post_type_support('page', 'custom-fields');
        add_meta_box('helsingborg_all_meta', "RSS nod", 'helsingborg_meta_RSS', $type, 'normal', 'high');
      }
        //add_meta_box('helsingborg_all_meta', 'helsingborg Custom Meta Box', 'helsingborg_meta_setup', $type, 'normal', 'high');
    }

    // add a callback function to save any data a user enters in
    add_action('save_post','helsingborg_meta_save');
}

function helsingborg_meta_RSS()
{
    global $post;

    // using an underscore, prevents the meta variable
    // from showing up in the custom fields section
    $meta = get_post_meta($post->ID,'_helsingborg_meta',TRUE);

    // Get all pages available
    $args = array(
      'sort_order' => 'ASC',
      'sort_column' => 'post_title',
      'post_type' => 'page',
      'child_of' => 0,
	    'parent' => -1,
      'post_status' => 'publish'
    );

    $pages = get_pages($args);

    // Set previous selection
    $selected = $meta['rss_select'];

    // Fetch the HTML
    include(helsingborg_THEME_FOLDER . '/UI/meta-ui-rss.php');

    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="helsingborg_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function helsingborg_meta_List()
{
    global $post;

    // using an underscore, prevents the meta variable
    // from showing up in the custom fields section
    $meta = get_post_meta($post->ID,'_helsingborg_meta',TRUE);

    // Get all pages available
    $args = array(
      'sort_order' => 'ASC',
      'sort_column' => 'post_title',
      'post_type' => 'page',
      'child_of' => 0,
      'parent' => -1,
      'post_status' => 'publish'
    );

    $pages = get_pages($args);

    // Set previous selection
    $selected = $meta['list_select'];

    // Fetch the HTML
    include(helsingborg_THEME_FOLDER . '/UI/meta-ui-list.php');

    // create a custom nonce for submit verification later
    echo '<input type="hidden" name="helsingborg_meta_noncename" value="' . wp_create_nonce(__FILE__) . '" />';
}

function helsingborg_meta_save($post_id)
{
    // authentication checks

    // make sure data came from our meta box
    if (!wp_verify_nonce($_POST['helsingborg_meta_noncename'],__FILE__)) return $post_id;

    // check user permissions
    if ($_POST['post_type'] == 'page')
    {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    }
    else
    {
        if (!current_user_can('edit_post', $post_id)) return $post_id;
    }

    // authentication passed, save data

    // var types
    // single: _helsingborg_meta[var]
    // array: _helsingborg_meta[var][]
    // grouped array: _helsingborg_meta[var_group][0][var_1], _helsingborg_meta[var_group][0][var_2]

    $current_data = get_post_meta($post_id, '_helsingborg_meta', TRUE);
    $new_data = $_POST['_helsingborg_meta'];

    helsingborg_meta_clean($new_data);

    if ($current_data)
    {
        if (is_null($new_data)) delete_post_meta($post_id,'_helsingborg_meta');
        else update_post_meta($post_id,'_helsingborg_meta',$new_data);
    }
    elseif (!is_null($new_data))
    {
        add_post_meta($post_id,'_helsingborg_meta',$new_data,TRUE);
    }

    return $post_id;
}

function helsingborg_meta_clean(&$arr)
{
    if (is_array($arr))
    {
        foreach ($arr as $i => $v)
        {
            if (is_array($arr[$i]))
            {
                helsingborg_meta_clean($arr[$i]);

                if (!count($arr[$i]))
                {
                    unset($arr[$i]);
                }
            }
            else
            {
                if (trim($arr[$i]) == '')
                {
                    unset($arr[$i]);
                }
            }
        }

        if (!count($arr))
        {
            $arr = NULL;
        }
    }
}

?>
