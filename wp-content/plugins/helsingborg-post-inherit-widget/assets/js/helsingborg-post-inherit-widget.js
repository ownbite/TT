jQuery(document).ready(function ($) {

    $(document).on('click', '.hbg-post-inherit-search', function (e) {
        e.preventDefault();
        var $form = $(this).parents('form').first();
        var searchString = $form.find('input.hbg-post-inherit-search-string').val();

        var data = {
            action: 'hbgPostInheritLoadPosts',
            q: searchString
        };

        $form.find('.hbg-post-inherit-post-id select').html('');
        $.post(ajaxurl, data, function (response) {
            $form.find('.hbg-post-inherit-post-id select').html(response);
            $form.find('.hbg-post-inherit-post-id').show();
            $form.find('.hbg-post-inherit-post-title').val($form.find('.hbg-post-inherit-post-id select option').first().text());
        });
    });

    $(document).on('change', '.hbg-post-inherit-post-id select', function (e) {
        var $form = $(this).parents('form').first();
        $form.find('.hbg-post-inherit-post-title').val($(this).find('option:selected').text());
    });

});