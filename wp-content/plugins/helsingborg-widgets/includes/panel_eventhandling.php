<?php
//Create an instance of our package class...
$listTable = new Helsingborg_Event_List_Table();
//Fetch, prepare, sort, and filter our data...
$listTable->prepare_items();
?>

<div class="wrap">
  <div id="icon-users" class="icon32"><br/></div>
    <h2>Evenemangshantering</h2>

    <?php
      // echo HelsingborgEventModel::get_administration_units_by_id(15);
     ?>

    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="movies-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
        <?php
        $listTable->display()
        ?>
    </form>
</div>
