<?php
      echo $before_widget;
      echo $before_title . $title . $after_title;
?>
<div>
      <select id="municipality_multiselect">
            <option value="Bjuv">Bjuv</option>
            <option value="Helsingborg">Helsingborg</option>
            <option value="Höganäs">Höganäs</option>
            <option value="Klippan">Klippan</option>
            <option value="Landskrona">Landskrona</option>
            <option value="Åstorp">Åstorp</option>
            <option value="Ängelholm">Ängelholm</option>
            <option value="Örkelljunga">Örkelljunga</option>
      </select>
</div>

<ul class="alarm-list">
      <?php
            $today = date('Y-m-d');
            $number_of_alarms = count($alarms);
            $show = $number_of_alarms > $amount ? $amount : $number_of_alarms;
            for($i=0;$i<$show; $i++) :
      ?>
      <li>
            <span class="date"><?php echo $alarms[$i]->SentTime; ?></span>
            <a href="#" class="modalLinkAlarm" id="<?php echo $alarms[$i]->ID ?>" data-reveal-id="alarmModal"><?php echo $alarms[$i]->HtText ?></a>
      </li>
      <?php endfor; ?>

      <input type="text" id="selectedMunicipality" style="display: none;" />
</ul>

<a href="<?php echo $link; ?>" class="read-more">Till arkivet</a>

<div class="reveal-modal-bg"></div>
<div id="alarmModal" class="reveal-modal" data-reveal>
      <h2 class="section-title">Alarm</h2>

      <div class="divider fade">
            <div class="upper-divider"></div>
            <div class="lower-divider"></div>
      </div>

      <h1 class="main-title"></h1>

      <div class="row">
            <div class="small-12">
                  <ul class="modal-item-list">
                        <li>
                              <span class="item-label modalDateHeader">Tidpunkt:</span>
                              <span class="item-value modalDate"></span>
                        </li>
                        <li>
                              <span class="item-label modalEventHeader">Händelse:</span>
                              <span class="item-value modalEvent"></span>
                        </li>
                        <li>
                              <span class="item-label modalStationHeader">Station:</span>
                              <span class="item-value modalStation"></span>
                        </li>
                        <li>
                              <span class="item-label modalIDHeader">Ärendeid:</span>
                              <span class="item-value modalID"></span>
                        </li>
                        <li>
                              <span class="item-label modalStateHeader">Larmnivå:</span>
                              <span class="item-value modalState"></span>
                        </li>
                        <li>
                              <span class="item-label modalAddressHeader">Adress:</span>
                              <span class="item-value modalAddress"></span>
                        </li>
                        <li>
                              <span class="item-label modalLocationHeader">Plats:</span>
                              <span class="item-value modalLocation"></span>
                        </li>
                        <li>
                              <span class="item-label modalAreaHeader">Insatsområde:</span>
                              <span class="item-value modalArea"></span>
                        </li>
                        <li>
                              <span class="item-label modalMunicipalityHeader">Kommuner:</span>
                              <span class="item-value modalMunicipality"></span>
                        </li>
                        <li>
                              <span class="item-label modalMoreInfoHeader">Kompletterande information:</span>
                              <span class="item-value modalMoreInfo"></span>
                        </li>
                  </ul>
            </div>
      </div>

      <a class="close-reveal-modal">×</a>
</div>

<script>
      var _amount = <?php echo $amount; ?>;
      var _alarms = <?php echo json_encode($alarms); ?>;
</script>
<?php echo $after_widget; ?>