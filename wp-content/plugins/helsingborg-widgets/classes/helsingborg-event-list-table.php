<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

 /**
 * To display this on a page, you will first need to instantiate the class,
 * then call $Helsingborg_Event_List_Table->prepare_items() to handle any data manipulation, then
 * finally call $Helsingborg_Event_List_Table->display() to render the table to the page.
 */
class Helsingborg_Event_List_Table extends WP_List_Table {

    function __construct(){
        global $status, $page;
        parent::__construct( array(
            'singular'  => 'event',     //singular name of the listed records
            'plural'    => 'events',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
    }

    function column_default($item, $column_name){
        switch($column_name){
            case 'EventID':
            case 'Name':
            case 'Date':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }

    function column_EventID($item){

        $actions = array(
            'edit'      => sprintf('<a href="?page=helsingborg-event-details&id=%s">Ändra</a>',$item['EventID']),
            // 'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item['EventID']),
            // 'delete'    => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>',$_REQUEST['page'],'delete',$item['EventID']),
        );

        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['EventID'],
            /*$2%s*/ $item['EventID'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }

    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['EventID']                //The value of the checkbox should be the record's id
        );
    }

    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'EventID'     => 'ID',
            'Name'    => 'Namn',
            'Date'  => 'Datum'
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'EventID'     => array('EventID',false),     //true means it's already sorted
            'Name'    => array('Name',false),
            'Date'  => array('Date',false)
        );
        return $sortable_columns;
    }

    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }

    function process_bulk_action() {

        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }

    }

    function prepare_items() {
        global $wpdb;
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $user_meta = get_user_meta(get_current_user_id(), 'happy_user_id', TRUE );
        if (strlen($user_meta) > 0) {
          // echo $user_meta;
        //   $happy_user_unit = $user_meta
          $query = "SELECT AdministrationUnitID
                    FROM `happy_user_administration_unit` huau
                    WHERE huau.UserID=".$user_meta;
        } else {
          $user_meta = -1;
        }

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();

        //  $listTable = HelsingborgEventModel::load_unpublished_events($happy_user_id);
        //  $listTable->prepare_items();
         $data = HelsingborgEventModel::load_unpublished_events($user_meta);
        //  var_dump($data);
        // $data = $this->example_data;

        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'EventID'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'desc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');

        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}
