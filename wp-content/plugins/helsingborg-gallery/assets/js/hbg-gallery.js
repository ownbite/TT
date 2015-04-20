jQuery(document).ready(function ($) {
    $('#youtube-urls .btn-add-row').on('click', function (e) {
        e.preventDefault();
        var templateHtml = $('#youtube-urls .youtube-link-list li:last')[0].outerHTML;
        var template = $(templateHtml);
        template.find('input').val('');
        $('#youtube-urls .youtube-link-list').append(template);
    });

    if ($('#youtube-urls .youtube-link-list li').length > 1) {
        $('#youtube-urls .youtube-link-list li:last').remove();
    }

    $(document).on('keydown', '#youtube-urls .youtube-link-list input', function (e) {
        var key = e.keyCode;
        if (key == 8) {
            if ($(this).val().length == 0) $(this).parents('li').remove();
        }
    });

    $(document).on('click', '#youtube-urls .youtube-link-list .btn-remove-row', function (e) {
        e.preventDefault();
        $(this).parents('li').remove();
    });

    $('#youtube-urls .youtube-link-list').sortable({
        placeholder: "ui-state-highlight"
    });
});