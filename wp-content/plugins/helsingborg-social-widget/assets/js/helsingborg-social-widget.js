jQuery(document).ready(function ($) {

    $(document).on('click', '.hbg-social-widget-type a', function (e) {
        e.preventDefault();
        $('.hbg-social-widget-type a').removeClass('active');
        $(this).addClass('active').blur();
    });

});