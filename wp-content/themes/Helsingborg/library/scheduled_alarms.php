<?php
/*
 * Alarms scheduled event.
 * This should occur each 3rd minutes.
 * This file is included from functions.php
 */

/* Function to execute as event, from setup above */
add_action( 'scheduled_alarms', 'alarms_event' );
function alarms_event() {
  // Retrieve values
  $ftpUserName  = get_option('helsingborg_alarm_user_name');
  $ftpPassword  = get_option('helsingborg_alarm_password');
  $ftpLocation  = get_option('helsingborg_alarm_location');
  $ftpDirectory = "/alarm/in/";
  $downloadTo   = "alarm/in/";

  // Create download directory
  create_directory($downloadTo);

  // Download xml files from ftp to local directory
  download_alarms_from_ftp($ftpLocation, $ftpUserName, $ftpPassword, $ftpDirectory, $downloadTo);

  // Update the alarms from files to db
  update_alarms_in_database($downloadTo);

  // Remove the directory and all downloaded files
  remove_directory($downloadTo);
}

/**
* Create the directory
* @param string $directory the directory name
*/
function create_directory($directory) {
  if (!file_exists("$directory")) {
    $old = umask(0);
    mkdir("$directory", 0777, true);
    umask($old);
  }
}

/**
* Downloads files from $ftpLocation + $ftpDirectory to $downloadTo directory
* @param string $ftpLocation the ftp location
* @param string $ftpUserName the username for ftp connection
* @param string $ftpPassword the password for ftp connection
* @param string $ftpDirectory the location inside ftp structure
* @param string $downloadTo the local location where files should be put
*/
function download_alarms_from_ftp($ftpLocation, $ftpUserName, $ftpPassword, $ftpDirectory, $downloadTo) {
  // Connect to ftp
  $conn_id = ftp_connect($ftpLocation) or die("Couldn't connect to $ftpLocation");

  // Login to the specific connection
  $login_result = ftp_login($conn_id, $ftpUserName, $ftpPassword);
  ftp_pasv($conn_id, true);
  // Make sure login was successful
  if ($login_result) {
    // Retrieve complete list of files from location
    $list = ftp_nlist($conn_id, $ftpDirectory);

    // Make sure data is recieved
    if ($list && is_array($list)) {

      // Parse through all the files and download each to the local location
      foreach($list as $item) {
        $local_file  = $downloadTo . $item;
        $server_file = $ftpDirectory . $item;
        ftp_get($conn_id, "$local_file", "$server_file", FTP_ASCII);
      }
    }
  }

  // Close the connection
  ftp_close($conn_id);
}

/**
* Updates the database with data from xml files into temporary location
* Triggers a store procedure in the end to move from temporary to proper
* database and clears the temporary location.
*/
function update_alarms_in_database($downloadTo) {
  global $wpdb;
  // Loop through each xml file
  foreach(glob("$downloadTo"."*.{xml,XML}", GLOB_BRACE) as $filename) {
    // Load the xml as local object to fetch stuff from
    $MESSAGE = simplexml_load_file("$filename") or error_log("Could not read file: " . $filename, 0);;
    $ALARM   = $MESSAGE->Alarm;
    $HtText = $ALARM->HtText;

    // Make sure to not insert certain alarms
    if (strpos(strtolower($HtText),'provlarm') !== false ||
        strpos(strtolower($HtText),'suicid') !== false ||
        strpos(strtolower($HtText),'järnväg') !== false )
    {
      continue; // Skip this alarm !
    } else {
      // Check ID is present in the data, otherwise create it
      if (!isset($ALARM->IDNumber) || strlen($ALARM->IDNumber) < 1) {
        // Get the string from the last '/' and forward
        $name     = substr(strrchr($filename, "/"), 1);

        // Remove the xml part, now this is the ID for this alarm
        $IDnr     = substr($name, 0, strpos(strtolower($name), '.xml'));

        // Build the date
        $SentTime = date('Y-m-d H:i:s', strtotime($MESSAGE->SendTime));
      } else {
        $IDnr     = $ALARM->IDNumber;
        $SentTime = $MESSAGE->SendTime;
      }

      // Fetch values
      $CaseID             = $ALARM->CaseID;
      $PresGrp            = $ALARM->PresGrp;
      $HtText             = $ALARM->HtText;
      $Address            = $ALARM->Address;
      $AddressDescription = $ALARM->AddressDescription;
      $Name               = $ALARM->Name;
      $Zone               = $ALARM->Zone;
      $Position           = $ALARM->Position;
      $Comment            = $ALARM->Comment;
      $MoreInfo           = $ALARM->MoreInfo;
      $Place              = $ALARM->Place;
      $BigDisturbance     = $ALARM->Bigdisturbance;
      $SmallDisturbance   = $ALARM->Smalldisturbance;
      $ChangeDate         = date("Y-m-d H:i:s");
      $Station            = $ALARM->Station;
      $County             = $ALARM->County;

      // Check if custom behavior should be used for address
      if (strpos(strtolower($HtText),'sjuk') !== false ||
          strpos(strtolower($HtText),'ivpa') !== false ||
          strpos(strtolower($HtText),'ambulansassistans') !== false ) {
        // Get string value before first digit, if present
        preg_match('/^\D*(?=\d)/', $HtText, $result);
        $firstDigitPosition = isset($result[0]) ? strlen($result[0]) : false;

        if ($firstDigitPosition) {
          $Address = substr($Address, 0, $firstDigitPosition);
        }
      }

      // If county is not set, then we build that value
      if (empty($County)) {
          $County = $ALARM->Zone . " ";
          if (empty($County)) {
            $County = "Helsingborg ";
          }
          $County = substr($County, 0, strpos($County, ' '));
      }

      // Ready to be inserted to temporary location!
      $wpdb->insert('alarm_alarms_temp',
                     array(
                       'IDnr'               => $IDnr,
                       'CaseID'             => $CaseID,
                       'SentTime'           => $SentTime,
                       'PresGrp'            => $PresGrp,
                       'HtText'             => $HtText,
                       'Address'            => $Address,
                       'AddressDescription' => $AddressDescription,
                       'Name'               => $Name,
                       'Zone'               => $Zone,
                       'Position'           => $Position,
                       'Comment'            => $Comment,
                       'MoreInfo'           => $MoreInfo,
                       'Place'              => $Place,
                       'BigDisturbance'     => $BigDisturbance,
                       'SmallDisturbance'   => $SmallDisturbance,
                       'ChangeDate'         => $ChangeDate,
                       'Station'            => $Station
                     ),
                     array(
                       '%s','%s','%s','%s','%s','%s','%s','%s','%s',
                       '%s','%s','%s','%s','%s','%s','%s','%s'
                     )
                   );
    }
  }

  // Now trigger the Store Procedure so real values are added and
  // temporary alarms are removed!
  // TODO: Not the prettiest solution, change when WP support calling SP
  $mysqli    = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  $procedure = "CALL spInsertIntoAlarmAlarms();";
  $mysqli->real_query($procedure);

  updateDisturbances();
}

function updateDisturbances() {
  require_once('scheduled_alarms_disturbance.php');
  $hbgDistrubance = new HbgScheduledAlarmsDisturbance();
  $hbgDistrubance->createAlarmPages();
}

/**
* Remove the directory and its content (all files and subdirectories).
* @param string $directory the directory name
*/
function remove_directory($directory) {
    foreach (glob("$directory") as $file) {
        if (is_dir("$file")) {
            remove_directory("$file/*");
            rmdir("$file");
        } else {
            unlink("$file");
        }
    }
}