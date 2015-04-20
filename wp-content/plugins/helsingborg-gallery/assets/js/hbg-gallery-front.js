jQuery(document).ready(function ($) {

    $('.hbg-gallery-container .hbg-gallery-item').on('click', function (e) {
        e.preventDefault();

        $('.hbg-gallery-item').removeClass('active');

        // Check if youtube
        if ($(this).data('youtube').length > 0) {
            var youtube_url = $(this).data('youtube');
            var pattern = /^(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?(?=.*v=((\w|-){11}))(?:\S+)?$/;
            var youtube_id = (youtube_url.match(pattern)) ? RegExp.$1 : false;

            $('.hbg-gallery-item-youtube-embed').remove();

            if (youtube_id) {
                $(this).append('\
                    <div class="hbg-gallery-item-youtube-embed">\
                        <iframe src="https://www.youtube.com/embed/' + youtube_id + '?autoplay=1" frameborder="0" allowfullscreen></iframe>\
                    </div>\
                ');
            }
        }

        $(this).addClass('active');
    })

});