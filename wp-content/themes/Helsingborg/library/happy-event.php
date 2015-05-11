<?php

/**
 * Add ID field for each user, which is used for listing events
 * @param  integer $user User
 * @return void
 */
function helsingborg_happy_user_id_field($user) { ?>
  <h3><?php _e("Evenemangshantering", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="happy_user_id"><?php _e("Användarens ID"); ?></label></th>
      <td>
        <input type="text" name="happy_user_id" id="happy_user_id" class="regular-text"
            value="<?php echo esc_attr( get_the_author_meta( 'happy_user_id', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Skriv in id som används för autentiering för evenemangshantering."); ?></span>
    </td>
    </tr>
  </table>
<?php
}
add_action('show_user_profile', 'helsingborg_happy_user_id_field');
add_action('edit_user_profile', 'helsingborg_happy_user_id_field');

/**
 * When the users ID is saved
 * @param  integer $user_id User ud
 * @return boolean
 */
function helsingborg_save_happy_user_id_field($user_id) {
    $saved = false;

    if ( current_user_can('edit_user', $user_id)) {
        update_user_meta($user_id, 'happy_user_id', $_POST['happy_user_id']);
        $saved = true;
    }

    return true;
}
add_action('personal_options_update', 'helsingborg_save_happy_user_id_field');
add_action('edit_user_profile_update', 'helsingborg_save_happy_user_id_field');

/**
 * Register event GFORM
 * Adds the event to the database, then deletes the entry
 * @return void
 */
function event_add_entry_to_db($entry, $form) {

    // Make sure to only hijack the event form -> set in settings
    if (strcmp($entry['form_id'], get_option('helsingborg_event_form_id')) === 0) {
        // Event
        $name         = $entry[1];
        $description  = $entry[15];
        $approved     = 0;
        $organizer    = $entry[11];
        $location     = $entry[7];
        $external_id  = null;

        // Event time
        $type_of_date  = $entry[4];
        $single_date   = $entry[5];
        $time          = $entry[6];
        $start_date    = $entry[8];
        $end_date      = $entry[9];

        // Link
        $link          = $entry[17];

        // Selected days
        for($e = 1; $e <= 7; $e++) {
            if (strlen($entry["10.$e"])>0) {
                $days_array[] = $e;
            }
        }

        // Create event
        $event = array(
            'Name'            => $name,
            'Description'     => $description,
            'Link'            => $link,
            'Approved'        => $approved,
            'OrganizerID'     => $organizer,
            'Location'        => $location,
            'ExternalEventID' => $external_id
        );

        // Administration units
        $administrations = explode(',', $entry[3]);
        foreach($administrations as $unit) {
            $administration_units[] = HelsingborgEventModel::get_administration_id_from_name($unit)->AdministrationUnitID;
        }

        // Event types
        $event_types = explode(',', $entry[2]);

        // Create time/times
        $event_times = array();
        if ($single_date) {
            // Single occurence
            $event_time = array(
                'Date'  => $single_date,
                'Time'  => $time,
                'Price' => 0
            );
            array_push($event_times, $event_time);
        } else {
            // Must be start and end then
            $dates_array = create_date_range_array($start_date, $end_date);
            $filtered_days = filter_date_array_by_days($dates_array, $days_array);

            foreach($filtered_days as $date) {
                $event_time = array(
                    'Date'  => $date,
                    'Time'  => $time,
                    'Price' => 0
                );

                array_push($event_times, $event_time);
            }
        }

        // Image
        if ($entry[16]) $image = handle_gf_image($entry[16], $entry[13]);

        // Now create the Event in DB
        HelsingborgEventModel::create_event($event, $event_types, $administration_units, $image, $event_times);

        // Now remove the entry from GF !
        delete_form_entry($entry);
    }
}
add_action('gform_after_submission', 'event_add_entry_to_db', 10, 2);

/**
 * Register event GFORM
 * Copies the image to the new location. Returns an array with the new image path and author
 * @param  string $path   Image path
 * @param  integer $author Author id
 * @return array
 */
function handle_gf_image($path, $author) {
    $image_path   = $path;
    $file_name    = basename($image_path);
    $uploads      = wp_upload_dir();
    $new_path     = $uploads['basedir'].'/eventimages/'.$file_name;
    $save_path     = $uploads['baseurl'].'/eventimages/'.$file_name;

    if (!file_exists($uploads['basedir'].'/eventimages/')) {
        mkdir($uploads['basedir'].'/eventimages/', 0777, true);
    }

    if (!copy($image_path, $new_path)) return false;

    return array(
        'ImagePath' => $save_path,
        'Author'    => $auther
    );
}

/**
 * Completely removes all trace of an entry
 * @param  array $entry
 * @return void
 */
function delete_form_entry($entry) {
    $delete = GFAPI::delete_entry($entry['id']);
    $result = ( $delete ) ? "entry {$entry['id']} successfully deleted." : $delete;
    GFCommon::log_debug("GFAPI::delete_entry() - form #{$form['id']}: " . print_r( $result, true ));
}