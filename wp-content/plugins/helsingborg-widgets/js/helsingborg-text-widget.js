/**
 * Scroll to saved widget after page reload
 */
jQuery(document).ready(function ($) {
    // Read the hash from url
    var hash = location.hash.replace('#', '').split('|');
    var sidebar = hash[1];
    var widget = hash[0];

    // Open and scroll to the widget (found in the hash)
    $('#' + sidebar).parents('.widgets-holder-wrap').addClass('open').removeClass('closed');
    $('[id*=_' + widget + '] .widget-inside').show();
    var scrollTo = $('[id*=_' + widget + ']').offset().top;
    $('html, body').animate({
        scrollTop: scrollTo
    }, 1000);
});

/**
 * Save widget fix
 */
jQuery(document).on('click', '.widget-control-save', function () {
    // Find tinymce textarea id
    var saveId = jQuery(this).attr('id');
    var id = saveId.replace( /-savewidget/, '' );
    var randNum = jQuery('#' + id + '-rand').val();
    var textareaId = id + '-hbgtexteditor_' + randNum;

    // Trigger tinymce save on widget save
    tinyMCE.triggerSave();
});

/**
 * Redirect/reload page after widget ajax update complete
 */
jQuery(document).ajaxSuccess(function (e, xhr, settings) {
    var widgetBaseId = 'hbgtextwidget';

    if(settings.data && settings.data.search('action=pw-save-widget') != -1 && settings.data.search('id_base=' + widgetBaseId) != -1) {
        var setArr = decodeURIComponent(settings.data).split('&');
        var parameters = new Array();

        for (var i = setArr.length - 1; i >= 0; i--) {
            var keyval = setArr[i].split('=');
            parameters[keyval[0]] = keyval[1];
        };

        window.location.href = window.location.href.substr(0, window.location.href.indexOf('#')) + '#' + parameters['widget-id'] + '|' + parameters['sidebar'];
        location.reload();
    }
});