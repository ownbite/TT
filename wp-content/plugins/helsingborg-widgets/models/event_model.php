<?php
/*
  Custom class for getting and setting events
*/

class HelsingborgEventModel {

  public static function load_events_simple($amount=5) {
    global $wpdb;

    $events = $wpdb->get_results('SELECT DISTINCT hE.EventID,
                                                  hE.Name,
                                                  hE.Description,
                                                  hETI.Date,
                                                  hIM.ImagePath,
                                                  hE.Location,
                                                  hETI.Date,
                                                  hETI.Time
                                  FROM happy_event hE,
                                       happy_event_times hETI,
                                       happy_event_administration_unit hEFE,
                                       happy_administration_unit hFE,
                                       happy_images hIM
                                  WHERE hE.Approved = 1
                                  AND hE.EventID = hETI.EventID
                                  AND hETI.Date >= CURDATE()
                                  AND hE.EventID = hEFE.EventID
                                  AND hE.EventID = hIM.EventID
                                  AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
                                  ORDER BY hETI.Date ASC
                                  LIMIT 0,' . $amount, OBJECT);

    return $events;
  }

  public static function load_events() {
    global $wpdb;

    $events = $wpdb->get_results('SELECT DISTINCT hE.EventID,
                                                  hE.Name,
                                                  hE.Description,
                                                  hETI.Date,
                                                  hIM.ImagePath,
                                                  hE.Location
                                  FROM happy_event hE,
                                       happy_event_times hETI,
                                       happy_event_administration_unit hEFE,
                                       happy_administration_unit hFE,
                                       happy_images hIM
                                  WHERE hE.Approved = 1
                                  AND hE.EventID = hETI.EventID
                                  AND hETI.Date >= CURDATE()
                                  AND hE.EventID = hEFE.EventID
                                  AND hE.EventID = hIM.EventID
                                  AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
                                  ORDER BY hETI.Date', OBJECT);

    foreach($events as $event) {
      $rows = $wpdb->get_results('SELECT DISTINCT hETG.EventTypesName
                                  FROM happy_event_types_group hETG
                                  WHERE hETG.EventID = ' . $event->EventID, ARRAY_A);

      $event_types = array();
      foreach($rows as $row) {
        foreach($row as $key => $value) {
          array_push($event_types, $value);
        }
      }
      $event_types_string = implode(',', $event_types);
      $event->EventTypesName = $event_types_string;
    }

    return $events;
  }

  public static function get_administration_units_by_id($happy_user_id) {
    global $wpdb;
    $administration_units = array();
    $units = $wpdb->get_results('SELECT AdministrationUnitID FROM happy_user_administration_unit WHERE UserID='.$happy_user_id, ARRAY_A);
    foreach($units as $unit) {
      $administration_units[] = $unit['AdministrationUnitID'];
    }
    $administration_units = implode(',',$administration_units);
    return $administration_units;
  }

  public static function get_event_ids_from_administration_unit_id($administration_units) {
    global $wpdb;
    $event_ids = $wpdb->get_results('SELECT EventID FROM happy_event_administration_unit WHERE AdministrationUnitID IN ('.$administration_units.')', ARRAY_A);
    return $event_ids;
  }

  public static function load_unpublished_events($happy_user_id) {
    global $wpdb;

    if ($happy_user_id == -1)
      return; // Escape

    $administration_units = self::get_administration_units_by_id($happy_user_id);
    if (strpos($administration_units, '0') !== false) {
      $and_units = '';
    } else {
      $and_units = 'AND hefe.AdministrationUnitID IN ('.$administration_units.')';
    }
    $query = 'SELECT DISTINCT he.EventID, he.Name, MIN(het.Date) AS Date
              FROM happy_event he, happy_event_times het, happy_event_administration_unit hefe
              WHERE het.Date >= CURDATE()
              AND he.Approved = 0
              AND he.EventID = het.EventID
              AND hefe.EventID= he.EventID
              '.$and_units.'
              Group by he.EventID, he.Name
              ORDER BY Date, he.EventID';

    $events = $wpdb->get_results($query, ARRAY_A);
    if (!$events || empty($events)) { $events = array(); }

    // foreach($events as $event) {
    //   $rows = $wpdb->get_results('SELECT DISTINCT hETG.EventTypesName
    //                               FROM happy_event_types_group hETG
    //                               WHERE hETG.EventID = ' . $event->EventID, ARRAY_A);
    //
    //   $event_types = array();
    //   foreach($rows as $row) {
    //     foreach($row as $key => $value) {
    //       array_push($event_types, $value);
    //     }
    //   }
    //   $event_types_string = implode(',', $event_types);
    //   $event->EventTypesName = $event_types_string;
    // }

    return $events;
  }

  public static function load_event_with_event_id($event_id) {
    global $wpdb;

    $events = 'SELECT hE.Name,
                      hE.Description,
                      hETI.Date,
                      hETI.Time,
                      hE.Location
               FROM happy_event_times hETI,
                    happy_event hE
               WHERE hETI.Date >= CURDATE()
               AND hE.EventID = hETI.EventID
               AND hE.EventID = ' . $event_id;

    return $wpdb->get_results($events, OBJECT)[0];
  }

  public static function get_units_with_event_id($event_id) {
    global $wpdb;

    $units = 'SELECT hFE.Name,
                     hFE.AdministrationUnitID
              FROM happy_event hE,
                   happy_event_administration_unit hEFE,
                   happy_administration_unit hFE
              WHERE hE.EventID = hEFE.EventID
              AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
              AND hE.EventID = ' . $event_id;

    return $wpdb->get_results($units, OBJECT);
  }

  public static function get_event_types_with_event_id($event_id) {
    global $wpdb;

    $event_types = 'SELECT hETG.EventTypesName
                    FROM happy_event_types_group hETG,
                         happy_event hE
                    WHERE hE.EventID = hETG.EventID
                    AND hE.EventID = ' . $event_id;

    return $wpdb->get_results($event_types, OBJECT);
  }

  public static function get_organizers_with_event_id($event_id) {
    global $wpdb;

    $organizer = 'SELECT hArr.Name,
                         hArr.OrganizerID,
                  FROM happy_organizers hArr,
                       happy_event hE
                  WHERE hE.OrganizerID = hArr.OrganizerID
                  AND hE.EventID = ' . $event_id;

    return $wpdb->get_results($organizer, OBJECT)[0];
  }

  public static function get_image_with_event_id($event_id) {
    global $wpdb;

    $image = 'SELECT hB.Author,
                     hB.ImageID,
                     hb.ImagePath
              FROM happy_images hB,
                   happy_event hE
              WHERE hE.EventID = hB.EventID
              AND hE.EventID = ' . $event_id . '
              ORDER BY hB.ImageID DESC
              LIMIT 1';

    return $wpdb->get_results($image, OBJECT)[0];
  }

  public static function get_event_image_url($event_id = -1) {
    $image_path = $wpdb->get_results('SELECT ImagePath
                                      FROM happy_images
                                      WHERE EventID = '. $event_id, OBJECT);
    return $image_path;
  }

  public static function load_event_types() {
    global $wpdb;
    $result_event_types = $wpdb->get_results('SELECT Name EventTypesName
                                              FROM happy_event_types
                                              ORDER BY Name', OBJECT);

    foreach ($result_event_types as $key => $value) {
      $result_event_types[$key]->ID = $key;
    }
    return $result_event_types;
  }

  public static function load_administration_units() {
    global $wpdb;
    $happy_administration_units = $wpdb->get_results('SELECT Name
                                                      FROM happy_administration_unit
                                                      ORDER BY Name ASC', OBJECT);
    return $happy_administration_units;
  }

  public static function load_organizers() {
    global $wpdb;
    $result_event_types = $wpdb->get_results('SELECT *
                                              FROM happy_organizers
                                              ORDER BY Name', OBJECT);
    return $result_event_types;
  }

  public static function load_organizer_values($organizerId = -1) {
    global $wpdb;

    // Escape
    if ($organizer == -1)
      return;

    $result_event_types = $wpdb->get_results( 'SELECT heO.Phone, heO.Email, heO.WebAddress
                                               FROM happy_organizers AS heO
                                               WHERE heO.OrganizerID = ' . $organizerId, OBJECT);
    return $result_event_types;
  }

  public static function load_event_times_with_event_id($event_id) {
    global $wpdb;
    $result_times = $wpdb->get_results( 'SELECT *
                                         FROM happy_event_times
                                         WHERE EventID = ' . $event_id, OBJECT);
    return $result_times;
  }

  public static function get_administration_id_from_name($name) {
    global $wpdb;

    $result_id = $wpdb->get_results("SELECT AdministrationUnitID
                                     FROM happy_administration_unit
                                     WHERE Name='" . $name . "'", OBJECT);
    return $result_id[0];
  }

  public static function create_event($event, $event_types, $administration_units, $image, $times) {
    global $wpdb;

    // Insert Event
    $wpdb->insert('happy_event', array( 'Name'            => $event['Name'],
                                        'Description'     => $event['Description'],
                                        'Approved'        => $event['Approved'],
                                        'OrganizerID'     => $event['Organizer_id'],
                                        'Location'        => $event['Location'],
                                        'ExternalEventID' => $event['External_id']));

    // Get the AUTO_INCREMENTED value and set to our event.
    $event['EventID'] = $wpdb->insert_id;

    // Add Event Types
    if ($event_types) {
      foreach($event_types as $event_type) {
        $wpdb->insert('happy_event_types_group', array('EventTypesName' => $event_type,
                                                       'EventID'        => $event['EventID']));
      }
    }

    // Add Administration Unit
    if ($administration_units) {
      foreach($administration_units as $administration_unit) {
        $wpdb->insert('happy_event_administration_unit', array('AdministrationUnitID' =>$administration_unit,
                                                               'EventID'              =>$event['EventID']));
      }
    }

    // Add Image
    if ($image) {
      $wpdb->insert('happy_images', array('EventID'   =>$event['EventID'],
                                          'ImagePath' =>$image['ImagePath'],
                                          'Author'    =>$image['Author']));
    }

    // Add time for event
    if ($times) {
      foreach($times as $time){
        $wpdb->insert('happy_event_times', array('Date'   =>$time['Date'],
                                                 'Time'   =>$time['Time'],
                                                 'Price'  =>$time['Price'],
                                                 'EventID'=>$event['EventID']));
      }
    }
  }

  public static function approve_event($event_id) {
    global $wpdb;
    if (!$event_id || $event_id == -1) { return FALSE; }      // Cannot proceed if no id was inserted
    $result = $wpdb->update('happy_event',                    // Table
                      array('Approved' => 1),                 // Update row
                      array('EventID'  => $event_id));        // Where
    return $result;
  }

  public static function deny_event($event_id) {
    global $wpdb;
    if (!$event_id || $event_id == -1) { return FALSE; }      // Cannot proceed if no id was inserted
    $result_update = $wpdb->update('happy_event',                    // Table
                             array('Approved' => 0),                 // Update row
                             array('EventID'  => $event_id));        // Where

    // Delete current set of times for this event
    $wpdb->delete('happy_event_administration_unit', array('EventID' => $event_id));
    // Add the new time with
    $result_insert = $wpdb->insert('happy_event_times', array('Date'    => '1968-10-24',
                                                              'Time'    => '00:00',
                                                              'Price'   => 0,
                                                              'EventID' => $event_id));
    return ($result_update && $result_insert);
  }

  public static function update_event(&$event, $event_types, $administration_units, $image, $times) {
    global $wpdb;

    if (!$event || !$event['EventID'])
      return;

    $wpdb->update("happy_event", array( 'Name'            => $event['Name'],
                                        'Description'     => $event['Description'],
                                        'Approved'        => $event['Approved'],
                                        'OrganizerID'     => $event['Organizer_id'],
                                        'Location'        => $event['Location'],
                                        'ExternalEventID' => $event['External_id'] ),
                                 array( 'EventID'         => $event['EventID'] ));

    // Add Event Types
    if ($event_types && !empty($event_types)) {
      // Delete the current set of types for this event
      $wpdb->delete('happy_event_types_group', array('EventID' => $event['EventID']));
      // Now add the new ones
      foreach($event_types as $event_type) {
        $wpdb->update('happy_event_types_group', array('EventTypesName' => $event_type['Name'],
                                                       'EventID'        => $event['EventID']));
      }
    }

    // Add Administration Unit
    if ($administration_units && count($administration_units) > 0) {
      // Delete current set of units for this event
      $wpdb->delete('happy_event_administration_unit', array('EventID' => $event['EventID']));
      // Add the new ones
      foreach($administration_units as $administration_unit) {
        $wpdb->insert('happy_event_administration_unit', array('AdministrationUnitID' => $administration_unit['ID'],
                                                               'EventID'              => $event['EventID']));
      }
    }

    // Add Image
    if ($image) {
      $wpdb->update('happy_images', array('ImageID'   => $image['Id'],
                                          'ImagePath' => $image['Path'],
                                          'Author'    => $image['Author']),
                                    array('EventID'   => $event['EventID']));
    }

    // Add time for event
    if ($times) {
      // Delete current set of times for this event
      $wpdb->delete('happy_event_administration_unit', array('EventID' => $event['EventID']));
      // Add the new times
      foreach($times as $time) {
          $wpdb->insert('happy_event_times', array('Date'    => $time['Date'],
                                                   'Time'    => $time['Time'],
                                                   'Price'   => $time['Price'],
                                                   'EventID' => $event['EventID']));
      }
    }
  }
}
?>
