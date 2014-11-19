<?php
/*
  Custom class for getting and setting events
*/

class HelsingborgEventModel {

  public static function load_events() {
    global $wpdb;

    $events = $wpdb->get_results('SELECT DISTINCT hE.EventID,
                                                  hE.Name,
                                                  hE.Description,
                                                  hETid.Date,
                                                  hIM.ImagePath,
                                                  hE.Location
                                  FROM happy_event hE,
                                       happy_event_times hETid,
                                       happy_event_administration_unit hEFE,
                                       happy_administration_unit hFE,
                                       happy_images hIM
                                  WHERE hE.Approved = 1
                                  AND hE.EventID = hETid.EventID
                                  AND hETid.Date >= CURDATE()
                                  AND hE.EventID = hEFE.EventID
                                  AND hE.EventID = hIM.EventID
                                  AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
                                  ORDER BY hETid.Date', OBJECT);

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

  public static function load_unpublished_events($happy_user_id = -1) {
    global $wpdb;

    if ($happy_user_id == -1)
      return; // Escape

    $events = $wpdb->get_results('SELECT *
                                  FROM happy_event hE,
                                       happy_event_times hETid,
                                       happy_event_administration_unit hEFE,
                                       happy_administration_unit hFE,
                                       happy_images hIM,
                                       happy_user_administration_unit hUA
                                  WHERE hE.Approved = 0
                                  AND hE.EventID = hETid.EventID
                                  AND hETid.Date >= CURDATE()
                                  AND hE.EventID = hEFE.EventID
                                  AND hE.EventID = hIM.EventID
                                  AND hEFE.AdministrationUnitID = hFE.AdministrationUnitID
                                  AND hFE.AdministrationUnitID = hUA.AdministrationUnitID
                                  AND hUA.UserID = ' . $happy_user_id . '
                                  ORDER BY hETid.Date', OBJECT);

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

  public static function load_event($event_id = -1) {
    global $wpdb;

    // Event ID wasn't included -> just escape
    if ($event_id == -1)
      return;

    $result_events = $wpdb->get_results('SELECT he.Name,
                                                he.Description,
                                                he.Location,
                                                heT.Date,
                                                heT.Time
                                         FROM happy_event_times heT,
                                              happy_event he
                                         WHERE heT.Date >= convert(VARCHAR(10), GETDATE(), 120)
                                         AND he.EventID = heT.EventID
                                         AND he.EventID = ' . $event_id . '
                                         ORDER BY heT.Date', OBJECT);

    $result_web_addresses = $wpdb->get_results('SELECT heO.WebAddress
                                                FROM happy_event he,
                                                     happy_event_organizers heO
                                                WHERE he.OrganizerID = heO.OrganizerID
                                                AND he.EventID = ' . $event_id, OBJECT);
  }

  public static function get_event_image_url($event_id = -1) {
    $image_path = $wpdb->get_results('SELECT ImagePath
                                      FROM happy_images
                                      WHERE EventID = '. $event_id, OBJECT);
    return $image_path;
  }

  public static function load_event_types() {
    global $wpdb;
    $result_event_types = $wpdb->get_results('SELECT Name
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
    $result_event_types = $wpdb->get_results('SELECT Name, OrganizerID
                                              FROM happy_event_organizers
                                              ORDER BY Name', OBJECT);
    return $result_event_types;
  }

  public static function load_organizer_values($organizerId = -1) {
    global $wpdb;

    // Escape
    if ($organizer == -1)
      return;

    $result_event_types = $wpdb->get_results( 'SELECT heO.Phone, heO.Email, heO.WebAddress
                                               FROM happy_event_organizers AS heO
                                               WHERE heO.OrganizerID = ' . $organizerId, OBJECT);
    return $result_event_types;
  }

  public static function create_event($event, $event_types, $administration_units, $image, $times) {
    global $wpdb;

    // Insert Event
    $wpdb->insert('happy_event', array( 'Name'            => $event['name'],
                                        'Description'     => $event['description'],
                                        'Approved'        => $event['approved'],
                                        'OrganizerID'     => $event['organizer_id'],
                                        'Location'        => $event['location'],
                                        'ExternalEventID' => $event['external_id']));

    // Get the AUTO_INCREMENTED value and set to our event.
    $event['EventID'] = $wpdb->insert_id;

    // Add Event Types
    if ($event_types) {
      foreach($event_types as $event_type) {
        $wpdb->insert('happy_event_types_group', array('EventTypesName' =>$event_type['Name'],
                                                       'EventID'        =>$event['EventID']));
      }
    }

    // Add Administration Unit
    if ($administration_units) {
      foreach($administration_units as $administration_unit) {
        $wpdb->insert('happy_event_administration_unit', array('AdministrationUnitID' =>$administration_unit['ID'],
                                                               'EventID'              =>$event['EventID']));
      }
    }

    // Add Image
    if ($image) {
      $wpdb->insert('happy_images', array('ImageID'   =>$image['id'],
                                          'EventID'   =>$event['EventID'],
                                          'ImagePath' =>$image['path'],
                                          'Author'    =>$image['author']));
    }

    // Add time for event
    if ($times) {
      $wpdb->insert('happy_event_times', array('Date'=>$times['date'],
                                               'Time'=>$times['time'],
                                               'Price'=>$times['price'],
                                               'EventID'=>$event['EventID']));
    }
  }

  public static function approve_event($event) {
    global $wpdb;
    $wpdb->update('happy_event',                          // Table
                  array('Approved' => 1),                 // Update row
                  array('EventID'  =>$event['EventID'])); // Where
  }

  public static function unapprove_event($event) {
    global $wpdb;
    $wpdb->update('happy_event',                          // Table
                  array('Approved' => 0),                 // Update row
                  array('EventID'  =>$event['EventID'])); // Where
  }

  public static function update_event($event, $event_types, $administration_units, $image, $times) {
    global $wpdb;

    if (!$event || !$event['EventID'])
      return;

    $wpdb->update("happy_event", array( 'Name'            => $event['name'],
                                        'Description'     => $event['description'],
                                        'Approved'        => $event['approved'],
                                        'OrganizerID'     => $event['organizer_id'],
                                        'Location'        => $event['location'],
                                        'ExternalEventID' => $event['external_id'] ),
                                 array( 'EventID'         => $event['EventID'] ));

    // Add Event Types
    if ($event_types) {
      // Delete the current set of types for this event
      $wpdb->delete('happy_event_types_group', array('EventID'=>$event['EventID']));
      // Now add the new ones
      foreach($event_types as $event_type) {
        $wpdb->update('happy_event_types_group', array('EventTypesName' =>$event_type['Name'],
                                                       'EventID'        =>$event['EventID']));
      }
    }

    // Add Administration Unit
    if ($administration_units) {
      // Delete current set of units for this event
      $wpdb->delete('happy_event_administration_unit', array('EventID'=>$event['EventID']));
      // Add the new ones
      foreach($administration_units as $administration_unit) {
        $wpdb->insert('happy_event_administration_unit', array('AdministrationUnitID' =>$administration_unit['ID'],
                                                               'EventID'              =>$event['EventID']));
      }
    }

    // Add Image
    if ($image) {
      $wpdb->update('happy_images', array('ImageID'   =>$image['id'],
                                          'ImagePath' =>$image['path'],
                                          'Author'    =>$image['author']),
                                    array('EventID'   =>$event['EventID']));
    }

    // Add time for event
    if ($times) {
      $wpdb->update('happy_event_times', array('Date'=>$times['date'],
                                               'Time'=>$times['time'],
                                               'Price'=>$times['price']),
                                         array('EventID'=>$event['EventID']));
    }
  }
}
?>
