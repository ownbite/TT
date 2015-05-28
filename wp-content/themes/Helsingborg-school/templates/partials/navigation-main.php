<?php
    /* Generates the page tree, skips drafted pages */
    require_once(get_stylesheet_directory() . '/library/helsingborg-school-walker.php');
    $walker_page = new HelsingborgSchoolWalker();
?>
<ul>
    <li class="nav-home"><a href="<?php echo get_site_url();?>">Startsida</a></li>
    <?php
        $menu = wp_cache_get('menu_' . $post->ID);

        if ($menu) {
            $menu = wp_list_pages(array(
                'title_li' => '',
                'echo'     => 0,
                'walker'   => $walker_page,
                //'include'  => get_included_pages($post),
                'depth'     => 5,
                'child_of'  => get_option('page_on_front')
            ));

            wp_cache_set('menu_' . $post->ID , $menu);
        }

        echo $menu;
    ?>
    <li><a href="#"><i class="fa fa-search"></i></a></li>
</ul>
