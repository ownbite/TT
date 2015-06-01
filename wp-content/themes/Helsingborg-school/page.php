<?php get_header(); ?>

<div class="content-container">
    <div class="row">
        <div class="columns large-8 medium-8">
            <?php

                /**
                 * Breadcrumb
                 */
                if (!is_front_page()) {
                    the_breadcrumb();
                }

                /**
                 * Show the content if this isnt the front page
                 * - If this is the front page, content will be shown in templates/partilas/header-welcome.php instead
                 */
                get_template_part('templates/partials/article', 'content');

                /**
                 * Widget content-area
                 */
                if ((is_active_sidebar('content-area') == true)) {
                    dynamic_sidebar("content-area");
                }
            ?>
        </div>

        <?php
            /**
             * Include sidebar here if welcome text does not exist
             * - If it's exists sidebar will be included in templates/partials/header-welcome.php instead
             */
            global $has_welcome_text;
            if (!$has_welcome_text) {
                get_template_part('templates/partials/sidebar', 'right');
            }
        ?>
    </div>
</div>

<?php get_footer(); ?>