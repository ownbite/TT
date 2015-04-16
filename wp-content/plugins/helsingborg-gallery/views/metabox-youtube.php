<style type="text/css">
    .youtube-link-list {
        padding: 0;
        margin: 0;
    }

    .youtube-link-list li {
        padding: 10px;
        margin: 0;
        list-style: decimal inside;
    }

    .youtube-link-list li:nth-child(odd) {
        background: #f9f9f9;
    }

    .youtube-link-list li input {
        margin-left: 10px;
        display: inline-block;
        width: 80%;
    }

    #youtube-urls .inside {
        margin: 0;
        padding: 0;
    }

    #youtube-urls .actions {
        padding: 10px 10px 10px 35px;
        border-top: 1px solid #E5E5E5;
    }

    #youtube-urls .btn-add-row .dashicons {
        font-size: 15px !important;
        display: inline-block;
        vertical-align: middle;
        margin-top: -2px;
        margin-left: 0;
        width: 15px;
        height: 15px;
    }

    #youtube-urls .btn-remove-row {
        color: #D54E21;
        text-decoration: none;
        position: relative;
        top: 4px;
        left: 10px;
    }

    #youtube-urls .btn-remove-row:hover {
        color: #9d3917;
    }
</style>
<script type="text/javascript">
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
    });
</script>

<input type="hidden" name="hbg-gallery" value="true">
<ol class="youtube-link-list">
    <?php if (is_array($youtubeLinks)) : foreach ($youtubeLinks as $key => $value) : ?>
    <li><input type="text" name="youtube-link[]" value="<?php echo $value; ?>"><a href="#" class="btn-remove-row"><span class="dashicons dashicons-trash"></span></a></li>
    <?php endforeach; endif; ?>
    <li><input type="text" name="youtube-link[]"><a href="#" class="btn-remove-row"><span class="dashicons dashicons-trash"></span></a></li>
</ol>
<div class="actions">
    <a href="#" class="button-secondary btn-add-row"><span class="dashicons dashicons-plus-alt"></span> Lägg till rad</a>
</div>