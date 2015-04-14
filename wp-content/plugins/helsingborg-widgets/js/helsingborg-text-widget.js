jQuery('.widget-control-save').on('click', function () {
    var saveId = jQuery(this).attr('id');
    var id = saveId.replace( /-savewidget/, '' );
    var randNum = jQuery('#' + id + '-rand').val();
    var textareaId = id + '-hbgtexteditor_' + randNum;

    tinyMCE.triggerSave();

    setTimeout(function () {
        location.reload();
    }, 2000);
});

// widget-hbgtextwidget-5-hbgtexteditor_176