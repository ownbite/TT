<?php

    function textWidgetYouTube($text) {
        $text = preg_replace('#(<iframe[^>]+>.*?</iframe>)#is', '<div class="flex-video">$1</div>', $text);
        return $text;
    }
    add_filter('widget_text', 'textWidgetYouTube');