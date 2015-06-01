<div class="header-welcome">
    <div class="row">
        <div class="columns large-8 medium-8 header-welcome-text">
            <article>
                <h1><?php echo apply_filters('the_title', $welcomeText['title']); ?></h1>
                <?php echo apply_filters('the_content', $welcomeText['content']); ?>
            </article>
        </div>

        <?php get_template_part('templates/partials/sidebar', 'right'); ?>
    </div>
</div>