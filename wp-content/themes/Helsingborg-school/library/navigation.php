<?php

    /**
     * Adds static menu items to the main menu
     * @return string The menu wrapper
     */
    function sidebarMenuStaticItems() {
        $wrap = '<ul class="%2$s">';
        $wrap .= '%3$s';
        $wrap .= '<li class="item-search">
                    <a href="#"><i class="fa fa-search"></i></a>
                    <div class="search-container">
                        <form id="searchform" class="search-inputs" action="' . get_search_link() . '" method="get" role="search">
                            <div class="row collapse">
                                <div class="small-10 columns">
                                    <input id="s" class="input-field" type="text" placeholder="Vad letar du efter?" name="s" value="">
                                </div>
                                <div class="small-2 columns">
                                    <button id="searchsubmit" class="button search" type="submit">Sök</button>
                                </div>
                            </div>
                        </form>
                    </div>
                  </li>';
        $wrap .= '</ul>';

        return $wrap;
    }

    /**
     * Adds static menu items to the mobile menu
     * @return string The menu wrapper
     */
    function mobileMenuStaticItems() {
        $wrap = '<ul class="%2$s">';
        $wrap .= '%3$s';
        $wrap .= '</ul>';

        return $wrap;
    }