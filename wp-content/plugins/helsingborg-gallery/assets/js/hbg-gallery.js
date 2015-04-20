jQuery(document).ready(function ($) {
    /**
     * Holds the item template
     */
    var templateHtml = $('#youtube-urls .youtube-link-list li:last')[0].outerHTML;

    /**
     * Adds a new list item on click
     */
    $('#youtube-urls .btn-add-row').on('click', function (e) {
        e.preventDefault();

        // Append correct node int to item row
        var num_rows = $('#youtube-urls .youtube-link-list li').length;
        var new_row_html = templateHtml.replace(/{node}/g, num_rows);

        // Set up template
        var template = $(new_row_html);
        template.find('input, textarea').val('');
        $('#youtube-urls .youtube-link-list').append(template);
    });

    /**
     * Removes the template node if there's any and then adds a correct node
     */
    $('#youtube-urls .youtube-link-list li:last').remove();
    if ($('#youtube-urls .youtube-link-list li').length == 0) {
        $('#youtube-urls .btn-add-row').trigger('click');
    }

    /**
     * If input is valid youtube-link, get the video details from youtube api with ajax
     */
    $(document).on('input paste', '#youtube-urls .youtube-link-list input', function (e) {
        e.stopPropagation();

        if (isValidYoutube($(this).val())) {
            // Get details and setup post data
            var ytId = isValidYoutube($(this).val());
            var $container = $(this).parents('li');
            var data = {
                action: 'hbg_gallery_get_video_info',
                id: ytId
            }

            // Do ajax
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: data,
                dataType: 'JSON',
                success: function (response) {
                    // Render the item details to html elements
                    var item = response.items[0].snippet;

                    $container.find('.item-thumbnail').attr('src', item.thumbnails.medium.url);
                    $container.find('.item-image-url').val(item.thumbnails.medium.url);
                    $container.find('.item-title').val(item.title);
                    $container.find('.item-description').val(item.description);

                    $container.find('.item-details').slideDown();
                },
                error: function (response) {
                    console.log('error', response);
                }
            });
        }
    });

    /**
     * Removes a list item
     */
    $(document).on('click', '#youtube-urls .youtube-link-list .btn-remove-row', function (e) {
        e.preventDefault();
        $(this).parents('li').remove();
    });

    /**
     * Activate sortable in the list
     */
    $('#youtube-urls .youtube-link-list').sortable({
        placeholder: "ui-state-highlight"
    });
});

/**
 * Function to validate youtube url
 */
function isValidYoutube(url) {
    var p = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
    return (url.match(p)) ? RegExp.$1 : false;
}