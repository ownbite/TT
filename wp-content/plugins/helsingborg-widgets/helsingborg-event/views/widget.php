<?php echo $before_widget; ?>
<h2 class="widget-title"><?php echo $title; ?></h2>

<div class="divider">
    <div class="upper-divider"></div>
    <div class="lower-divider"></div>
</div>

<ul class="calendar-list" style="min-height: 30px;">
    <div class="event-list-loader" id="loading-event" style="margin-top: -5px;"></div>
</ul><!-- .calendar-list -->

<a href="<?php echo $reference; ?>" class="read-more"><?php echo $link_text; ?></a>

<div id="eventModal" class="reveal-modal" data-reveal>
    <img class="modal-image"/>

    <div class="row">
        <div class="modal-event-info large-12 columns">
            <h2 class="modal-title"></h2>
            <p class="modal-description"></p>
            <p class="modal-link"></p>
            <!--<p class="modal-date"></p>-->
        </div>
    </div>

    <!-- IF arrangör exist -->
    <div class="row">
        <div class="large-6 columns" id="event-times">
            <h2 class="section-title">Datum, tid och plats</h2>

            <div class="divider fade">
                <div class="upper-divider"></div>
                <div class="lower-divider"></div>
            </div>

            <ul class="modal-list" id="time-modal"></ul>
        </div>

        <div class="large-6 columns" id="event-organizers">
            <h2 class="section-title">Arrangör</h2>

            <div class="divider fade">
                <div class="upper-divider"></div>
                <div class="lower-divider"></div>
            </div>

            <ul class="modal-list" id="organizer-modal"></ul>
        </div>
    </div>

    <a class="close-reveal-modal">&#215;</a>
</div>

<script type="text/javascript">
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>

<script>
    var events = {};
    jQuery(document).ready(function() {
        var data = { action: 'update_event_calendar', amount: '<?php echo $amount; ?>', ids: '<?php echo $administration_ids; ?>' };

        jQuery.post(ajaxurl, data, function(response) {
            var obj = JSON.parse(response);
            events = obj.events;
            jQuery('.calendar-list').html(obj.list);
        });

        jQuery(document).on('click', '.modalLink', function(event) {
            event.preventDefault();
            var image = $('.modal-image');
            var title = $('.modal-title');
            var link = $('.modal-link');
            var date = $('.modal-date');
            var description = $('.modal-description');
            var time_list = $('#time-modal');
            var organizer_list = $('#organizer-modal');

            document.getElementById('event-times').style.display = 'none';
            document.getElementById('event-times').className = 'large-6 columns';
            document.getElementById('event-organizers').style.display = 'none';

            var result;
            for (var i = 0; i < events.length; i++) {
                if (events[i].EventID === this.id) {
                    result = events[i];
                    break;
                }
            }

            var dates_data = { action: 'load_event_dates', id: this.id, location: result.Location };

            jQuery.post(ajaxurl, dates_data, function(response) {
                html = "";
                var dates = JSON.parse(response);

                for (var i=0;i<dates.length;i++) {
                    html += '<li>';
                    html += '<span>' + dates[i].Date + '</span>';
                    html += '<span>' + dates[i].Time + '</span>';
                    html += '<span>' + dates_data.location + '</span>';
                    html += '</li>';
                }

                jQuery('#time-modal').html(html);

                if (dates.length > 0) {
                document.getElementById('event-times').style.display = 'block';
                }
            });

            var organizers_data = { action: 'load_event_organizers', id: this.id };

            jQuery.post(ajaxurl, organizers_data, function(response) {
                var organizers = JSON.parse(response); html = '';

                for (var i=0;i<organizers.length;i++) {
                    html += '<li><span>' + organizers[i].Name + '</span></li>';
                }

                jQuery('#organizer-modal').html(html);
                if (organizers.length > 0) {
                    document.getElementById('event-organizers').style.display = 'block';
                } else {
                    document.getElementById('event-times').className = 'large-12 columns';
                }
            });

            jQuery(image).attr("src", result.ImagePath);
            jQuery(title).html(result.Name);

            if (result.Link) {
                jQuery(link).html('<a href="' + result.Link + '" target="blank">' + result.Link + '</a>').show();
            } else {
                jQuery(link).hide();
            }

            jQuery(date).html(result.Date);
            jQuery(description).html(nl2br(result.Description));
        });
    });

    function nl2br (str, is_xhtml) {
        var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }
</script>
<?php echo $after_widget; ?>