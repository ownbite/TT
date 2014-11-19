<?php
function my_css_attributes_filter($var) {
  return is_array($var) ? array_intersect($var, array('current-menu-item')) : '';
}

add_action( 'show_user_profile', 'helsingborg_happy_user_id_field' );
add_action( 'edit_user_profile', 'helsingborg_happy_user_id_field' );
function helsingborg_happy_user_id_field( $user ) {
?>
  <h3><?php _e("Evenemangshantering", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="happy_user_id"><?php _e("Evenemangs ID"); ?></label></th>
      <td>
        <input type="text" name="happy_user_id" id="happy_user_id" class="regular-text"
            value="<?php echo esc_attr( get_the_author_meta( 'happy_user_id', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Skriv in id som används för autentiering för evenemangshantering."); ?></span>
    </td>
    </tr>
  </table>
<?php
}

add_action( 'personal_options_update', 'helsingborg_save_happy_user_id_field' );
add_action( 'edit_user_profile_update', 'helsingborg_save_happy_user_id_field' );
function helsingborg_save_happy_user_id_field( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, 'happy_user_id', $_POST['happy_user_id'] );
    $saved = true;
  }
  return true;
}

add_filter("gform_after_update_entry", "post_save", 10, 2);
function post_save($lead, $form)
{
  ?>
  <script>console.log("POST SAVE !")</script>
  <?php
    $lead["1"] = $lead["1"] . " testing";
    return $lead;
}

add_action('gform_after_submission', 'event_add_entry_to_db', 10, 2);
function event_add_entry_to_db($entry, $form) {

    // uncomment to see the entry object
    echo '<pre>';
    var_dump($entry);
    echo '</pre>';

    // EVENT
    $name         = 	$entry[1];
    $description  = 	$entry[4];
    $approved     = 	false; // Not approved as default
    $organizer_id = 	$entry[12];
    $location     = 	$entry[8];
    $external_id  = 	NULL;
    $event        = 	array ( 'name' => $name,
                              'description' => $description,
                              'approved' => $approved,
                              'organizer_id' => $organizer_id,
                              'location' => $location,
                              'external_id' => $external_id );

    // TYPE GROUP
    $event_types_name = 	$entry[99];
    //$event_id -> get
    $group            = 	array ($event_types_name);

    // ADMINISTRATION UNIT
    $administration_unit_id  = 	$entry[2];
    //$event_id -> get
    $administration          = 	array ($administration_unit_id);

    // IMAGE
    $image_id   = 	$entry[3];
    // $event_id -> get
    $image_path = 	$entry[3];
    $autor      = 	$entry[3];
    $image      = 	$entry[3];


}

/**
 * trims text to a space then adds ellipses if desired
 * @param string $input text to trim
 * @param int $length in characters to trim to
 * @param bool $ellipses if ellipses (...) are to be added
 * @param bool $strip_html if html tags are to be stripped
 * @param bool $strip_style if css style are to be stripped
 * @return string
 */
function trim_text($input, $length, $ellipses = true, $strip_tag = true,$strip_style = true) {
    //strip tags, if desired
    if ($strip_tag) {
        $input = strip_tags($input);
    }

    //strip tags, if desired
    if ($strip_style) {
        $input = preg_replace('/(<[^>]+) style=".*?"/i', '$1',$input);
    }

    if($length=='full')
    {

        $trimmed_text=$input;

    }
    else
    {
        //no need to trim, already shorter than trim length
        if (strlen($input) <= $length) {
        return $input;
        }

        //find last space within length
        $last_space = strrpos(substr($input, 0, $length), ' ');
        $trimmed_text = substr($input, 0, $last_space);

        //add ellipses (...)
        if ($ellipses) {
        $trimmed_text .= '...';
        }
    }

    return $trimmed_text;
}

function the_breadcrumb() {
    global $post;
    $title = get_the_title();
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
                    $output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a></li></li>' . $output;
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


add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
?>
