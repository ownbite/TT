jQuery(document).ready(function ($) {

    /**
     * Initializes a media selector frame
     * @type {[type]}
     */
    wp.media.frames.hbgMediaFrame = wp.media({
        title: 'Välj bild…',
        multiple: false,
        library: {
            type: 'image'
        },
        button: {
            text: 'Använd vald bild'
        }
    });

    wp.media.frames.hbgMediaFrame.on('close', function () {
        hbgMediaSetImage();
    });

    wp.media.frames.hbgMediaFrame.on('select', function () {
        hbgMediaSetImage();
    });

    // Holds current node which image should "save" to
    var openNode = null;

    /**
     * Handles click on select image button
     */
    $(document).on('click', '.open-media-selector', function (e) {
        e.preventDefault();
        openNode = $(this).parents('li');

        if (wp.media.frames.hbgMediaFrame) {
            wp.media.frames.hbgMediaFrame.open();
        } else {
            alert("Det finns ingen instans av media väljaren. Kontakta utvecklare.");
        }
    });

    var hbgMediaSetImage = function () {
        var selection = wp.media.frames.hbgMediaFrame.state().get('selection');

        // If no selection made, return
        if (!selection) return;

        selection.each(function (attachment) {
            console.log(attachment.attributes);
            var url = attachment.attributes.url;
            openNode.find('.item-image-url').val(url);
            openNode.find('.item-thumbnail').attr('src', url);
            openNode.find('.item-title').val(attachment.attributes.title);

            openNode.find('.item-details').slideDown();
        });
    }

});