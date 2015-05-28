<!-- Twitter feed -->
<div class="widget-content-holder">
    <h2><i class="fa fa-twitter"></i> Twitter</h2>
    <div class="divider">
        <div class="upper-divider"></div>
        <div class="lower-divider"></div>
    </div>
    <div class="textwidget hbg-social-feed hbg-social-feed-twitter">
        <?php if ($feed && count($feed) > 0) : ?>
        <ul>
            <?php
                $int = 0;
                foreach ($feed as $post) :

                $date = new DateTime($post->created_at);
                $timeZone = new DateTimeZone('Europe/Stockholm');
                $date->setTimezone($timeZone);
            ?>
            <li>
                <div class="hbg-social-feed-post-content">
                    <span class="hbg-social-feed-post-date"><?php echo $date->format('Y-m-d H:i'); ?></span>
                    <article>
                        <?php echo $post->text; ?>
                    </article>
                    <?php if ($post->status_type == 'shared_story') : ?>
                    <a href="<?php echo $post->link; ?>" target="_blank" class="hbg-social-feed-post-attachment">
                        <span class="hbg-social-feed-post-attachment-title"><?php echo $post->name; ?></span>
                        <span class="hbg-social-feed-post-attachment-description"><?php echo wp_trim_words($post->description, 10, $more = '…'); ?></span>
                        <span class="hbg-social-feed-post-attachment-caption"><?php echo $post->caption; ?></span>
                    </a>
                    <?php endif; ?>
                </div>
                <div class="clearfix"></div>
            </li>
            <?php $int++; if ($int == $instance['show_count']) break; endforeach; ?>
        </ul>
        <?php else : ?>
            <p>Inga tweets att visa</p>
        <?php endif; ?>
        <div class="clearfix"></div>

        <?php if (isset($instance['show_visit_button']) && $instance['show_visit_button'] == 'on') : ?>
        <div class="text-center hbg-social-feed-actions">
            <a href="https://www.twitter.com/<?php echo $instance['username']; ?>" target="_blank" class="button button-hbg">Besök oss på Twitter</a>
        </div>
        <?php endif; ?>
    </div>
</div>