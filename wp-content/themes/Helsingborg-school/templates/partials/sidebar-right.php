<aside class="widgets-test-color sidebar sidebar-right columns large-4 medium-4">
    <div class="row">
        <?php
            if ((is_active_sidebar('right-sidebar') == TRUE)) {
                dynamic_sidebar("right-sidebar");
            }
        ?>
    </div>
</aside>