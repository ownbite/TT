var _eventPageModel = null;
alert(ajaxurl);

jQuery(document).ready(function() {
    var events = {};
    var eventTypes = {};

    document.getElementById('loading-event').style.display = "block";
    document.getElementById('event-pager-top').style.display = "none";
    document.getElementById('event-pager-bottom').style.display = "none";

    document.getElementById('no-event').style.display = "none";

    ko.bindingHandlers.trimText = {
        init: function (element, valueAccessor, allBindingsAccessor, viewModel) {
            var trimmedText = ko.computed(function () {
                var untrimmedText = ko.utils.unwrapObservable(valueAccessor());
                var minLength = 5;
                var maxLength = 250;
                var text = untrimmedText.length > maxLength ? untrimmedText.substring(0, maxLength - 1) + '...' : untrimmedText;
                var text = text.replace(/&nbsp;/gi, ' ');
                var text = text.trim();
                return text;
            });

            ko.applyBindingsToNode(element, {
                text: trimmedText
            }, viewModel);

            return {
                controlsDescendantBindings: true
            }
        }
    };

    _eventPageModel = new EventPageModel(events, eventTypes);
    ko.applyBindings(_eventPageModel);

    jQuery(document).on('click', '.modal-link', function(event){
        event.preventDefault();
        var image = $('.modal-image');
        var title = $('.modal-title');
        var link = $('.modal-link-url');
        var date = $('.modal-date');
        var description = $('.modal-description');
        var time_list = $('#time-modal');
        var organizer_list = $('#organizer-modal');
        document.getElementById('event-times').style.display = 'none';
        document.getElementById('event-times').className = 'large-6 columns';
        document.getElementById('event-organizers').style.display = 'none';

        var events = _eventPageModel.events();
        var result;

        for (var i = 0; i < events.length; i++) {
            if (events[i].EventID === this.id) {
                result = events[i];
            }
        }

        var dates_data = { action: 'load_event_dates', id: this.id, location: result.Location };
        jQuery.post(ajaxurl, dates_data, function(response) {
            html = "<li>";
            var dates = JSON.parse(response);

            for (var i=0;i<dates.length;i++) {
                html += '<span>' + dates[i].Date + '</span>';
                html += '<span>' + dates[i].Time + '</span>';
                html += '<span>' + dates_data.location + '</span>';
            }

            html += '</li>';
            jQuery(time_list).html(html);

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

            jQuery(organizer_list).html(html);

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
        jQuery(description).html(result.Description);
    });

    var data = { action: 'load_events', ids: adminIDs };
    jQuery.post(ajaxurl, data, function(response) {
        _eventPageModel.events(ExtractModels(_eventPageModel, JSON.parse(response), EventModel));
        document.getElementById('loading-event').style.display = "none";
        document.getElementById('event-pager-top').style.display = "block";
        document.getElementById('event-pager-bottom').style.display = "block";
        document.getElementById('no-event').style.display = "block";
    });

    var data = { action: 'load_event_types' };
    jQuery.post(ajaxurl, data, function(response) {
        _eventPageModel.eventTypes(ExtractModels(_eventPageModel, JSON.parse(response), TypeModel));

        jQuery("select#municipality_multiselect").zmultiselect({
            live: "#selectedTypes",
            filter: true,
            filterPlaceholder: 'Filtrera...',
            filterResult: true,
            filterResultText: "Visar",
            selectedText: ['Valt','av'],
            selectAll: true,
            selectAllText: ['Markera alla','Avmarkera alla']
        });
    });

    jQuery(function() {
        var currentDate = new Date();
        currentDate.setDate(currentDate.getDate());

        jQuery('#datetimepickerstart').datetimepicker({
            minDate: currentDate,
            weeks: true,
            lang: 'se',
            timepicker: false,
            format: 'Y-m-d',
            formatDate: 'Y-m-d',
            onShow: function(ct) {
                this.setOptions({
                    maxDate: jQuery('#datetimepickerend').val() ? jQuery('#datetimepickerend').val() : false
                })
            }
        });

        jQuery('#datetimepickerend').datetimepicker({
            weeks: true,
            lang: 'se',
            timepicker: false,
            format: 'Y-m-d',
            formatDate: 'Y-m-d',
            onShow:function(ct) {
                this.setOptions({
                    minDate:jQuery('#datetimepickerstart').val() ? jQuery('#datetimepickerstart').val() : false
                })
            }
        });
    });
});

function updateEvents(checkbox) {
    if (checkbox.checked) {
        var data = { action: 'load_events', ids: '0' };
        jQuery.post(ajaxurl, data, function(response) {
            _eventPageModel.events(ExtractModels(_eventPageModel, JSON.parse(response), EventModel));
        });
    } else {
        var data = { action: 'load_events', ids: '<?php echo $administration_unit_ids; ?>' };
        jQuery.post(ajaxurl, data, function(response) {
            _eventPageModel.events(ExtractModels(_eventPageModel, JSON.parse(response), EventModel));
        });
    }
}