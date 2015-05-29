jQuery(document).ready(function ($) {

    $('.item-search a').on('click', function (e) {
        e.preventDefault;
        $(this).parent('.item-search').toggleClass('show-search');
    });

});