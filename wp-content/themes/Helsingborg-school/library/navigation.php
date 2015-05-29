<?php

    /**
     * Adds static menu items to the main menu
     * @return string The menu wrapper
     */
    function sidebarMenuStaticItems() {
        $wrap = '<ul class="%2$s">';
        $wrap .= '%3$s';
        $wrap .= '<li class="item-search"><a href="#"><i class="fa fa-search"></i></a></li>';
        $wrap .= '</ul>';

        return $wrap;
    }