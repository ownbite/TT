<div class="header-welcome">
    <div class="row">
        <div class="columns large-8 header-welcome-text">
            <?php
                the_post();
                $the_content = get_extended($post->post_content);
                $main = $the_content['main'];
                $content = $the_content['extended']; // If content is empty, no <!--more--> tag was used -> content is in $main
            ?>
            <article>
                <h1><?php the_title(); ?></h1>
                <?php echo apply_filters('the_content', $main); ?>
            </article>
        </div>

        <?php get_template_part('templates/partials/sidebar', 'right'); ?>
    </div>
</div>