<?php

class HelsingborgSchoolWalker extends Walker {

    public $tree_type = 'page';
    public $db_fields = array(
        'parent' => 'post_parent',
        'id'     => 'ID'
    );

    /**
     * Walker
     * @param  object $elements  Items
     * @param  integer $max_depth Max depth
     * @return string            The menu to output
     */
    public function walk($elements, $max_depth) {
        $args = array_slice(func_get_args(), 2);
        $output = '';

        if ($max_depth < -1) //invalid parameter
            return $output;

        if (empty($elements)) //nothing to walk
            return $output;

        $parent_field = $this->db_fields['parent'];

        // flat display
        if (-1 == $max_depth) {
            $empty_array = array();
            foreach ( $elements as $e)
                $this->display_element($e, $empty_array, 1, 0, $args, $output);
            return $output;
        }

        /*
         * Need to display in hierarchical order.
         * Separate elements into two buckets: top level and children elements.
         * Children_elements is two dimensional array, eg.
         * Children_elements[10][] contains all sub-elements whose parent is 10.
         */
        $top_level_elements = array();
        $children_elements  = array();
        foreach ($elements as $e) {
            if (get_post_meta($e->$parent_field, '_wp_page_template', TRUE) == 'templates/list-page.php') continue;

            if (0 == $e->$parent_field)
                $top_level_elements[] = $e;
            else
                $children_elements[ $e->$parent_field ][] = $e;
        }

        /*
         * When none of the elements is top level.
         * Assume the first one must be root of the sub elements.
         */
        if (empty($top_level_elements)) {

            $first = array_slice( $elements, 0, 1 );
            $root = $first[0];

            $top_level_elements = array();
            $children_elements  = array();

            foreach ($elements as $e) {
                if (get_post_meta($e->$parent_field, '_wp_page_template', TRUE) == 'templates/list-page.php') continue;

                if ($root->$parent_field == $e->$parent_field)
                    $top_level_elements[] = $e;
                else
                    $children_elements[ $e->$parent_field ][] = $e;
            }
        }

        foreach ($top_level_elements as $e)
            $this->display_element($e, $children_elements, $max_depth, 0, $args, $output);

        /*
         * If we are displaying all levels, and remaining children_elements is not empty,
         * then we got orphans, which should be displayed regardless.
         */
        if (($max_depth == 0) && count($children_elements) > 0) {
            $empty_array = array();
            foreach ($children_elements as $orphans)
                foreach ($orphans as $op)
                    $this->display_element($op, $empty_array, 1, 0, $args, $output);
         }

         return $output;
    }

    /**
     * Markup for starting a sub menu level
     * @param  string  &$output
     * @param  integer $depth   [description]
     * @param  array   $args    [description]
     * @return void
     */
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class='sub-menu'>\n";
    }

    /**
     * Markup for closing a sub menu level
     * @param  string  &$output
     * @param  integer $depth   [description]
     * @param  array   $args    [description]
     * @return void
     */
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start an element
     * @param  string  &$output
     * @param  string  $page
     * @param  integer $depth
     * @param  array   $args
     * @param  integer $current_page
     * @return void
     */
    public function start_el(&$output, $page, $depth = 0, $args = array(), $current_page = 0) {

        /**
         * Element indentation
         * @var string
         */
        $indent = '';
        if (isset($depth)) {
            $indent = str_repeat("\t", $depth);
        }

        if (!empty($current_page)) {

            /**
             * Get current page object
             * @var object
             */
            $_current_page = get_post($current_page);

            /**
             * Holds list of css classes top apply to menu node
             * @var array
             */
            $css_class_list = array();

            /**
             * Add class "current-node" if this page is the current node
             */
            if (in_array($page->ID, $_current_page->ancestors) && $page->post_parent == get_option('page_on_front')) {
                array_push($css_class_list, 'current-node');
            }

            /**
             * Add class "current-ancestor" if this page is the current ancestor
             */
            if (in_array($page->ID, $_current_page->ancestors)) {
                array_push($css_class_list, 'current-ancestor');
            }

            /**
             * Add class "current-page" if this is the current page
             * @var [type]
             */
            if ($page->ID == $current_page) {
                array_push($css_class_list, 'current');
                if ($page->post_parent == get_option('page_on_front')) {
                    array_push($css_class_list, 'current-node');
                }
            }

            /**
             * Query for this page children
             * @var array
             */
            $args = array(
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_parent' => $page->ID,
            );

            $children = get_children($args);
            $has_children = !empty($children);

            /**
             * Check if page got childrens or not, if it does, add has-child class
             * - Exclude list page childrens
             */
            if (!in_array( $page->ID, $_current_page->ancestors ) && $has_children && ($page->post_parent != get_option('page_on_front')) && get_post_meta($page->ID,'_wp_page_template',TRUE) != 'templates/list-page.php') {
                array_push($css_class_list, 'has-childs');
            }

            /**
             * If article page parent is list page, then mark the parent as current -> since childs are hidden
             */
            if (in_array($page->ID, $_current_page->ancestors) && get_post_meta($page->ID,'_wp_page_template',TRUE) == 'templates/list-page.php') {
                array_push($css_class_list, 'current');
            }

            /**
             * If the current items parent is set as PRIVATE(and should not be visible in menus)
             * The private parent should be set as current instead.
             *
             * Example with ancestors:
             *     25    5220         5776            5781          5785
             *  (root)  (node)  (set to current)   (private)   (actual current)
             *
             * http://localhost/startsida/omsorg-och-stod/frivilligt-arbete-och-foreningar/info/las-mer-om-socialt-arbete-med-ersattning/
             */
            if (get_post_status($_current_page->post_parent) == 'private' && in_array($page->ID, $_current_page->ancestors)) {
                $_current_page_ansectors = $_current_page->ancestors;
                $last_element = count($_current_page_ansectors) - 1; // We want last index

                // -2 for seletion of grandparent
                $selector = $last_element > 1 ? $_current_page_ansectors[$last_element-2] : 0;

                if ($selector && $page->ID == $selector) {
                    array_push($css_class_list, 'current');
                }
            }
        }

        /**
         * $css_class_list into string
         * @var string
         */
        $css_classes = '';
        if (count($css_class_list) > 0) {
            $css_classes = 'class="' . implode(' ', $css_class_list) . '"';
        }

        /**
         * Build the item markup
         */
        $output .= sprintf(
            '<li %s><a href="%s">%s</a>',
            $css_classes,
            get_permalink($page->ID),
            apply_filters('the_title', $page->post_title, $page->ID)
        );
    }

    /**
     * End an element
     * @param  string  &$output
     * @param  string  $page
     * @param  integer $depth
     * @param  array   $args
     * @param  integer $current_page
     * @return void
     */
    public function end_el(&$output, $page, $depth = 0, $args = array()) {
        $output .= '</li>';
    }

}