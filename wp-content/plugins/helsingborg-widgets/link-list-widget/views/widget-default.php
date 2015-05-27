<?php echo $before_widget; ?>
<section class="news-section news-widget">
      <h2 class="section-title"><?php echo $title; ?>
            <?php if ($show_rss == 'rss_yes') { echo('<a href="'.$rss_link.'" class="rss-link"><span class="icon"></span></a>'); } ?>
      </h2>

      <div class="divider fade">
            <div class="upper-divider"></div>
            <div class="lower-divider"></div>
      </div>

      <ul class="news-list-small row">
      <?php
            foreach ($items as $num => $item) :
                  $item_id = $item_ids[$num];
                  $page = get_post($item_id, OBJECT, 'display');

                  // Continue if not published
                  if ($page->post_status !== 'publish') continue;

                  // Check if link should be opened in new window
                  $target = $item_targets[$num] ? 'target="_blank"' : '';

                  $class = '';
                  if ($item_warnings[$num]) {
                        $class = ' alert-msg warning';
                  } else if ($item_infos[$num]) {
                        $class = ' alert-msg info';
                  }

                  $title = $item;
                  $link = $item_links[$num];

                  // Backward compability
                  if (!empty($item_id)) {
                        $datetime = strtotime($page->post_modified);
                  } else if (!empty($item_dates[$num])){
                        $datetime = strtotime($item_dates[$num]);
                  } else {
                        $datetime = '';
                  }
            ?>

            <li class="news-item large-12 columns<?php echo $class; ?>">
                  <div class="row">
                        <div class="large-9 medium-9 small-9 columns news-content">
                              <h2 class="news-title"><a href="<?php echo $link; ?>" <?php echo $target; ?>><?php echo $title; ?></a></h2>
                        </div>

                        <div class="large-3 medium-3 small-3 columns">
                              <?php
                                    if ($show_dates && !empty($datetime)) :
                                    $date = date_i18n('d M Y', $datetime ); 
                              ?>
                                    <span class="news-date"><?php echo $date; ?></span>
                              <?php endif; ?>
                        </div>
                  </div>
            </li>
            <?php endforeach; ?>
      </ul>
</section>
<?php echo $after_widget; ?>
