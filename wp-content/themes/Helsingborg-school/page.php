<?php get_header(); ?>

<div class="content-container">
    <div class="row">
        <div class="columns large-8">
            <?php

                /**
                 * Breadcrumb
                 */
                the_breadcrumb();

                /**
                 * Widget content-area
                 */
                if ((is_active_sidebar('content-area') == true)) {
                    dynamic_sidebar("content-area");
                }

                /**
                 * Show the content if this isnt the front page
                 * - If this is the front page, content will be shown in templates/partilas/header-welcome.php instead
                 */
                if (!is_front_page()) {
                    the_post();
                    get_template_part('templates/partials/article', 'content');
                }
            ?>
        </div>

        <?php
            /**
             * Include sidebar here if this is not the front page
             * - If it's the front page, the sidebar will be included in templates/partials/header-welcome.php instead
             */
            if (!is_front_page()) {
                get_template_part('templates/partials/sidebar', 'right');
            }
        ?>
    </div>
</div>

<?php get_footer(); ?>