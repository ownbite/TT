jQuery(document).ready(function ($) {

    $('.submitdelete.deletion').on('click', function (e) {
        return confirm('Är du säker på att du vill flytta denna sida till papperskorgen?');
    });

});