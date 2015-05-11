<section class="small-12 medium-12 large-12 columns clearfix timelinewidget show_thin_design">
    <div class="timeline flexslider clearfix">
        <ul class="slides">
            <?php
                // Current date
                $timezone = new DateTimeZone('Europe/Stockholm');
                $currentDate = new DateTime();
                $currentDate->setTimezone($timezone);
                $currentDate->modify('first day of');

                // Number of months (counted from current) to display in the slider
                $lenMonths = 12;

                // Loop months
                for ($i = 0; $i < $lenMonths; $i++) :

                    if ($i > 0) {
                        $currentDate->modify('first day of');
                        $currentDate->add(new DateInterval('P1M'));
                    }

                    $monthNum = $currentDate->format('m');
                    $monthName = $currentDate->format('F');
                    $year = $currentDate->format('Y');
                    $daysInMonth = $currentDate->format('t');
            ?>
                <li>
                    <div class="month-year-nav"><?php echo __($monthName, 'helsingborg') . ' ' . $year; ?></div>
                    <div class="timeline-cal">
                        <ul>
                            <?php
                                for ($j = 1; $j <= $daysInMonth; $j++) :
                                    if ($j > 1) $currentDate->add(new DateInterval('P1D'));
                                    $weekday = '';
                                    if ($currentDate->format('l') == 'Sunday' ||  $currentDate->format('l') == 'Saturday') $weekday = 'redday';

                                    // Search for events occuring this day
                                    $eventsToday = $this->arrSearch($events, 'datum', $currentDate->format('Y-m-d'));
                            ?>
                                <li class="date">
                                    <?php if ($eventsToday) : ?>
                                        <a class="hasevents <?php echo $weekday; ?> popover" href="#"><?php echo $currentDate->format('d'); ?></a>
                                        <div class="eventlist">
                                            <ul class="popup">
                                                <?php
                                                    foreach ($eventsToday as $event) {
                                                        echo '<li><a href="' . get_permalink($event['post_id']) . '"><span class="event-time">' . $event['tid'] . '</span> <span class="event-title">' . $event['title'] . '</span></a></li>';
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    <?php else : ?>
                                        <span class="<?php echo $weekday; ?>"><?php echo $currentDate->format('d'); ?></span>
                                    <?php endif; ?>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </div>
                </li>
            <?php endfor; ?>
        </ul>
    </div>
</section>

<script type="text/javascript">
/* TimeLIne flexslider */
$(window).load(function() {
    jQuery('.flexslider').flexslider({
        slideshow: false,
        controlNav: false
    });
});

//popup
jQuery('.no-touch .hasevents').on("mouseenter", function () {
    jQuery(this).next('.eventlist').fadeIn( 400 );
});
jQuery('.no-touch .hasevents').on("mouseleave", function () {
    jQuery(this).next('.eventlist').delay(1000).fadeOut( 400 );
});
jQuery('.touch .hasevents').click(function() {
  jQuery(this).next('.eventlist').fadeToggle( "slow", "linear" );
});

</script>
