<?php
/**
 * Customize the output of menus for Foundation top bar
 */

class sidebar_menu_walker extends Walker_Nav_Menu {

    function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        $element->has_children = !empty( $children_elements[$element->ID] );
        $element->classes[] = ( $element->current || $element->current_item_ancestor ) ? 'current' : '';
        $element->classes[] = ( $element->has_children && $max_depth !== 1 ) ? 'has-dropdown' : '';

        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }

    // Displays start of a level. E.g '<ul>'
    // @see Walker::start_lvl()
    function start_lvl(&$output, $depth=0, $args=array()) {
        $output .= "\n<ul>\n";
    }

    // Displays end of a level. E.g '</ul>'
    // @see Walker::end_lvl()
    function end_lvl(&$output, $depth=0, $args=array()) {
        $output .= "</ul>\n";
    }

    function walk( $elements, $max_depth) {

        $args = array_slice(func_get_args(), 2);
        $output = '';

        if ($max_depth < -1) //invalid parameter
            return $output;

        if (empty($elements)) //nothing to walk
            return $output;

        $id_field = $this->db_fields['id'];
        $parent_field = $this->db_fields['parent'];

        // flat display
        if ( -1 == $max_depth ) {
            $empty_array = array();
            foreach ( $elements as $e )
                $this->display_element( $e, $empty_array, 1, 0, $args, $output );
            return $output;
        }

        /*
         * need to display in hierarchical order
         * separate elements into two buckets: top level and children elements
         * children_elements is two dimensional array, eg.
         * children_elements[10][] contains all sub-elements whose parent is 10.
         */
        $top_level_elements = array();
        $children_elements  = array();
        foreach ( $elements as $e) {
            if ( 0 == $e->$parent_field )
                $top_level_elements[] = $e;
            else
                $children_elements[ $e->$parent_field ][] = $e;
        }

        /*
         * when none of the elements is top level
         * assume the first one must be root of the sub elements
         */
        if ( empty($top_level_elements) ) {

            $first = array_slice( $elements, 0, 1 );
            $root = $first[0];

            $top_level_elements = array();
            $children_elements  = array();
            foreach ( $elements as $e) {
                if ( $root->$parent_field == $e->$parent_field )
                    $top_level_elements[] = $e;
                else
                    $children_elements[ $e->$parent_field ][] = $e;
            }
        }

        $current_element_markers = array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' );  //added by continent7
        foreach ( $top_level_elements as $e ){  //changed by continent7
            // descend only on current tree
            $descend_test = array_intersect( $current_element_markers, $e->classes );
            if ( !empty( $descend_test ) )
                $this->display_element( $e, $children_elements, 5, 0, $args, $output );
            else
                $this->display_element( $e, $children_elements, 1, 0, $args, $output );
        }

        /*
         * if we are displaying all levels, and remaining children_elements is not empty,
         * then we got orphans, which should be displayed regardless
         */
         /* removed by continent7
        if ( ( $max_depth == 0 ) && count( $children_elements ) > 0 ) {
            $empty_array = array();
            foreach ( $children_elements as $orphans )
                foreach( $orphans as $op )
                    $this->display_element( $op, $empty_array, 1, 0, $args, $output );
         }
        */
         return $output;
    }

    // function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
    //     $item_html = '';
    //     parent::start_el( $item_html, $object, $depth, $args );
    //
    //     //$output .= ( $depth == 0 ) ? '<li class="divider"></li>' : '';
    //
    //     $classes = empty( $object->classes ) ? array() : (array) $object->classes;
    //
    //     if( in_array('label', $classes) ) {
    //         //$output .= '<li class="divider"></li>';
    //         $item_html = preg_replace( '/<a[^>]*>(.*)<\/a>/iU', '<label>$1</label>', $item_html );
    //     }
    //
    // // if ( in_array('divider', $classes) ) {
    // //     $item_html = preg_replace( '/<a[^>]*>( .* )<\/a>/iU', '', $item_html );
    // // }
    //
    //     $output .= $item_html;
    // }
    //
    // function start_lvl( &$output, $depth = 0, $args = array() ) {
    //     $output .= "\n<ul class=\"sub-menu dropdown\">\n";
    // }

}
?>
