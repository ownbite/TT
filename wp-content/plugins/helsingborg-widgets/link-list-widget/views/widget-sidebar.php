<?php
    $today = date('Y-m-d');
    echo $before_widget;
?>

<h2 class="widget-title">
    <?php echo $title ?>
    <?php if ($show_rss == 'rss_yes') { echo('<a href="'.$rss_link.'"><span class="icon"></span></a>'); } ?>
</h2>

<div class="divider">
    <div class="upper-divider"></div>
    <div class="lower-divider"></div>
</div>

<ul class="quick-links-list">
<?php
    foreach ($items as $num => $item) :
        $title;
        $item_id   = $item_ids[$num];   // Use the ID
        $item_date = $item_dates[$num]; // Backward compability

        // Check if link should be opened in new window
        $target = $item_targets[$num] ? 'target="_blank"' : '';

        // Check if warning of info
        $class = '';
        if ($item_warnings[$num] == 'on') {
            $class = ' class="alert-msg warning"';
        } else if ($item_infos[$num] == 'on') {
            $class = ' class="alert-msg info"';
        }

        // Get the page
        $page = get_post($item_id, OBJECT, 'display');

        // Continue if not published
        if ($page->post_status !== 'publish') continue;

        // Get title and link
        $title = $item;
        $link = $item_links[$num];

        // Set up/get date
        if (!empty($item_id)) {
            $datetime = strtotime($page->post_modified);
        } else if (!empty($item_date)) {
            $datetime = strtotime($item_date);
        }

        $date = date_i18n('Y-m-d', $datetime);
        $time = date('H:i',   $datetime);
?>
    <li <?php echo $class; ?>><a href="<?php echo $link; ?>" <?php echo $target; ?>><?php echo $title; ?></a></li>
    <?php
        if ($show_dates) :
        if ($today == $date) :
    ?>
        <span class="date">Idag <?php echo $time; ?></span>
    <?php else : ?>
        <span class="date"><?php echo $date; ?></span>
    <?php
        endif;
        endif;
    ?>
<?php endforeach; ?>
</ul>
<?php echo $after_widget; ?>