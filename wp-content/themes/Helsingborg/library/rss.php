<?php

    /**
     * Formats timestamp to RSS format
     * @param  String $timestamp Unformatted timestamp
     * @return String            Formatted timestamp
     */
    function helsingborg_rss_date($timestamp = null) {
        $timestamp = ($timestamp == null) ? time() : strtotime($timestamp);
        return date(DATE_RSS, $timestamp);
    }

    /**
     * Limits a text to given parameters
     * @param  string $string    The original string
     * @param  integer $length   Target length
     * @param  string $replacer  Suffix
     * @return string            The limited string
     */
    function helsingborg_rss_text_limit($string, $length, $replacer = '...') {
        $string = strip_tags($string);
        if(strlen($string) > $length) {
            return (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
        } else {
            return $string;
        }
    }