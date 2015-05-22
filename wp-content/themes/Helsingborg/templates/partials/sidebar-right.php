<div class="sidebar sidebar-right large-3 columns">
    <div class="row">
    <?php
        if ( (is_active_sidebar('right-sidebar') == TRUE) ) {
            dynamic_sidebar("right-sidebar");
        }
    ?>
    </div>
</div>