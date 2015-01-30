<?php
/*
Template Name: Alarm
*/
get_header(); ?>
<div class="row">
  <div class="small-8 large-8 columns" role="main">

    <div class="alert row"></div>
    <?php get_template_part('templates/partials/header','image'); ?>

  <div style="padding-top: 10px; padding-bottom: 10px">
    <div id="map_canvas" style="height: 350px; position: inherit">
    </div>
  </div>

  <script type="text/javascript">
      jQuery(document).ready(function ($) {
          initialize();
      });
  </script>

  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCRqng-FstEvhzxl_3NqoXXL3dO28vxEuc&sensor=false"></script>

  <script type="text/javascript">

      var infowindow = null;
      var bounds = new google.maps.LatLngBounds();
      var markers = null;

      function initialize() {

        jQuery.getJSON('http://alarmservice.helsingborg.se/AlarmServices.svc/GetAlarmMarkers/', function(data) {
          var mapOptions = { zoom: 10, center: new google.maps.LatLng(56.100769, 12.854576), mapTypeId: google.maps.MapTypeId.ROADMAP }
          var map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

          infowindow = new google.maps.InfoWindow({
              content: ""
          });

          setMarkers(map, data);
          map.fitBounds(bounds);
        });

      }

      function setMarkers(map, locations) {
          for (var i = 0; i < locations.length; i++) {
              var alarm = locations[i];
              var myLatLng = new google.maps.LatLng(alarm.Latitude, alarm.Longitude);
              bounds.extend(myLatLng);
              var marker = new google.maps.Marker({
                  position: myLatLng,
                  map: map,
                  html: '<div id="infowindow" style="height:50px;"><div><b>' + alarm.Time + '</b></div><div>' + alarm.Information + '</div></div>',
                  title: alarm.Information,
                  icon: alarm.Icon
              });

              google.maps.event.addListener(marker, 'click', function () {
                  infowindow.setContent(this.html);
                  infowindow.open(map, this);
              });
          }
      }
  </script>

  <!-- STARTA LOOPEN -->

  <?php /* Start loop */ ?>
  <?php while (have_posts()) : the_post(); ?>
    <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
      <header>
        <h1 class="entry-title"><?php the_title(); ?></h1>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <footer>
      </footer>
    </article>
  <?php endwhile; // End the loop ?>

  </div>

  <div class="small-4 large-4 columns">
    <select id="s10" multiple>
      <option value="a">A</option>
      <option value="b">B</option>
      <option value="c">C</option>
      <option value="d">D</option>
    </select>
  </div>

</div>

<?php get_footer(); ?>
