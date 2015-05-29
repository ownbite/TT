<?php
    $the_content = get_extended($post->post_content);
    $main = $the_content['main'];
    $extended = $the_content['extended'];
?>
<article>
    <header>
        <h1 class="article-title"><?php the_title(); ?></h1>
    </header>

    <main>
        <?php if (!empty($extended) && strlen($main) > 0) : ?>
        <section class="article-ingress">
            <?php echo apply_filters('the_content', $main); ?>
        </section>
        <?php endif; ?>

        <section class="article-body">
            <?php
                if (!empty($extended)) {
                    echo apply_filters('the_content', $extended);
                } else {
                    echo apply_filters('the_content', $main);
                }
            ?>
        </section>
    </main>
</article>