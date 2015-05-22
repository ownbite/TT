<div class="sidebar sidebar-left large-4 medium-4 columns">
    <div class="row">
        <?php
            get_search_form();
            dynamic_sidebar("left-sidebar");
            get_template_part('templates/partials/sidebar','menu');

            if ((is_active_sidebar('left-sidebar-bottom') == TRUE)) {
                dynamic_sidebar("left-sidebar-bottom");
            }
        ?>
    </div>
</div>