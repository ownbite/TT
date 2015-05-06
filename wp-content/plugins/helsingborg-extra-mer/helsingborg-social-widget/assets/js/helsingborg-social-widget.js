jQuery(document).ready(function ($) {

    $(document).on('click', '.hbg-social-widget-type a', function (e) {
        e.preventDefault();
        $container = $(this).parents('.widget-inside');
        $container.find('.hbg-social-widget-type a').removeClass('active');
        $(this).addClass('active').blur();

        var toggle = null;
        if ($(this).data('toggle')) toggle = $(this).data('toggle');
        $container.find('[class^=hbg-social-widget-section-]').slideUp(300).removeClass('active');
        $container.find(toggle).slideDown(300).addClass('active');

        $container.find('[name*=feedType]').val($(this).data('type'));
    });

});