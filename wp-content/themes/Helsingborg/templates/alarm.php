<?php
/*
Template Name: ALARM
*/
get_header();
global $wpdb;
$ftpUserName = "Telium";
$ftpPassword = "BraVA)aRb9!9Crake(";
$ftpFilePath = "87.251.206.99";
$kaka        = "/alarm/in/";
$downloadTo  = "alarm/in/";

if (!file_exists('alarm/in/')) {
  $old = umask(0);
  mkdir('alarm/in/', 0777);
  umask($old);
}

?>
<div class="article-page-layout row">
  <!-- main-page-layout -->
  <div class="main-area large-9 columns">

    <div class="main-content row">
      <!-- SIDEBAR LEFT -->
      <div class="sidebar sidebar-left large-4 medium-4 columns">

        <?php get_search_form(); ?>

        <div class="row">
          <?php dynamic_sidebar("left-sidebar"); ?>
          <?php get_template_part('templates/partials/sidebar','menu'); ?>
        </div><!-- /.row -->
      </div><!-- /.sidebar-left -->

      <div class="large-8 medium-8 columns article-column">
        <div class="alert row"></div>

        <?php

        // ----- STEP 1 ----- //
        // set up basic connection
        $conn_id = ftp_connect($ftpFilePath) or die("Couldn't connect to $ftpFilePath");
        $login_result = ftp_login($conn_id, $ftpUserName, $ftpPassword);
        $list = ftp_nlist($conn_id, $kaka);

        foreach($list as $item) {
          $local_file  = $downloadTo . $item;
          $server_file = $kaka . $item;
          $result = ftp_get($conn_id, $local_file, $server_file, FTP_ASCII);
          // echo ($result ? ('Klarade att skriva ' . $item . '<br>') : ('Misslyckades att skriva ' . $item . '<br>'));
        }
        ftp_close($conn_id);

        // ----- STEP 2 ----- //
        foreach(glob($downloadTo."*.{xml,XML}", GLOB_BRACE) as $filename) {
          $MESSAGE = simplexml_load_file($filename) or die("Error: Cannot create object");
          $ALARM   = $MESSAGE->Alarm;
          $HtText = $ALARM->HtText;
          if (strpos(strtolower($HtText),'provlarm') !== false ||
              strpos(strtolower($HtText),'suicid') !== false ||
              strpos(strtolower($HtText),'järnväg') !== false )
          {
            break;
          } else {

            if (!empty($ALARM->IDNumber)) {
              $name     = substr(strrchr($filename, "/"), 1);
              $IDnr     = substr($name, 0, strpos(strtolower($name), '.xml'));
              $date     = DateTime::CreateFromFormat('Y-m-d H:i:s', $MESSAGE->SendTime);
              $SentTime = $date->format('Y-m-d H:i');
            } else {
              $IDnr     = $ALARM->IDNumber;
              $SentTime = $MESSAGE->SendTime;
            }

            $CaseID  = $ALARM->CaseID;
            $PresGrp = $ALARM->PresGrp;
            $HtText  = $ALARM->HtText;
            $Address = $ALARM->Address;

            if (strpos(strtolower($HtText),'sjuk') !== false ||
                strpos(strtolower($HtText),'ivpa') !== false ||
                strpos(strtolower($HtText),'ambulansassistans') !== false ) {
              preg_match('/^\D*(?=\d)/', $HtText, $result);
              $firstDigitPosition = isset($result[0]) ? strlen($result[0]) : false;

              if ($firstDigitPosition) {
                $Address = substr($Address, 0, $firstDigitPosition);
              }
            }

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

            if (empty($County)) {
                $County = $ALARM->Zone . " ";
                if (empty($County)) {
                  $County = "Helsingborg ";
                }
                $County = substr($County, 0, strpos($County, ' '));
            }

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
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s',
                             '%s'
                           )
                         );
          }
        }

        // Now trigger the Store Procedure!
        // TODO: Not the prettiest solution, change when WP support calling SP
        $mysqli    = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $procedure = "CALL spInsertIntoAlarmAlarms();";
        $mysqli->real_query($procedure);

        // ----- STEP 3 ----- //
        /**
        * Remove the directory and its content (all files and subdirectories).
        * @param string $dir the directory name
        */
        // function rmrf($dir) {
        //     foreach (glob($dir) as $file) {
        //         if (is_dir($file)) {
        //             rmrf("$file/*");
        //             rmdir($file);
        //         } else {
        //             unlink($file);
        //         }
        //     }
        // }
        ?>

      </div><!-- /.columns -->
    </div><!-- /.main-content -->

    <div class="lower-content row">
      <div class="sidebar large-4 columns">
        <div class="row">
          <?php if ( (is_active_sidebar('left-sidebar-bottom') == TRUE) ) : ?>
            <?php dynamic_sidebar("left-sidebar-bottom"); ?>
          <?php endif; ?>
        </div><!-- /.row -->
      </div><!-- /.sidebar -->

      <?php if ( (is_active_sidebar('content-area-bottom') == TRUE) ) : ?>
        <?php dynamic_sidebar("content-area-bottom"); ?>
      <?php endif; ?>

    </div><!-- /.lower-content -->
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
</div><!-- /.main-site-container -->
<?php get_footer(); ?>
