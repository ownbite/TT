jQuery(document).ready(function ($) {
    /**
     * Holds the item template
     */
    var templateYoutube = $('#youtube-urls .gallery-items-list li.item-youtube')[0].outerHTML;
    var templateImage = $('#youtube-urls .gallery-items-list li.item-image')[0].outerHTML;

    /**
     * Removes the template node if there's any and then adds a correct node
     */
    $('#youtube-urls .gallery-items-list li.item-template').remove();

    /**
     * Adds a new list item on click
     */
    $('#youtube-urls .btn-add-row').on('click', function (e) {
        e.preventDefault();

        // Find which template to use
        var template = $(this).data('template');
        var templateHtml = null;

        switch (template) {
            case 'item-youtube':
                templateHtml = templateYoutube;
                break;

            case 'item-image':
                templateHtml = templateImage;
                break;
        }

        // Append correct node int to item row
        var num_rows = $('#youtube-urls .gallery-items-list li').length;
        var new_row_html = templateHtml.replace(/{node}/g, num_rows);

        // Set up template
        var template = $(new_row_html);
        template.find('input:not([name*=media]), textarea').val('');
        $('#youtube-urls .gallery-items-list').append(template);
    });

    /**
     * If input is valid youtube-link, get the video details from youtube api with ajax
     */
    $(document).on('input paste', '#youtube-urls .gallery-items-list input', function (e) {
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

                    console.log(item.thumbnails.high);

                    $container.find('.item-thumbnail').attr('src', 'http://img.youtube.com/vi/' + ytId + '/maxresdefault.jpg');
                    $container.find('.item-image-url').val('http://img.youtube.com/vi/' + ytId + '/maxresdefault.jpg');
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
    $(document).on('click', '#youtube-urls .gallery-items-list .btn-remove-row', function (e) {
        e.preventDefault();
        $(this).parents('li').remove();
    });

    /**
     * Activate sortable in the list
     */
    $('#youtube-urls .gallery-items-list').sortable({
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