<?php
    $defaults = array(
        'theme_location'  => 'sidebar-menu',
        'container'       => '',
        'menu_class'      => 'menu',
        'echo'            => true,
        'fallback_cb'     => 'wp_page_menu',
        'items_wrap'      => sidebarMenuStaticItems(),
    );

    wp_nav_menu($defaults);
?>
