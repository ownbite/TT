<!-- Facebook feed -->
<?php echo $before_widget; ?>
<h2><i class="fa fa-facebook-square"></i> Facebook</h2>
<div class="divider">
    <div class="upper-divider"></div>
    <div class="lower-divider"></div>
</div>
<div class="textwidget hbg-social-feed hbg-social-feed-facebook">
    <ul>
        <?php
            $int = 0;
            foreach ($feed as $post) :

            $date = new DateTime($post->created_time);
            $timeZone = new DateTimeZone('Europe/Stockholm');
            $date->setTimezone($timeZone);
        ?>
        <li>
            <span class="feed-date"><?php echo $date->format('Y-m-d H:i'); ?></span>
            <?php
                if (isset($post->picture)) :
                    $picture = parse_url($post->picture);
                    parse_str($picture['query'], $queryParam);
                    $picture = $queryParam['url'];
            ?>
                <img src="<?php echo $picture; ?>">
            <?php endif; ?>
            <article><?php echo $post->message; ?></article>
        </li>
        <?php $int++; if ($int == $intance['length']) break; endforeach; ?>
    </ul>
    <div class="clearfix"></div>
    <div class="text-center hbg-social-feed-actions">
        <a href="#" class="button button-hbg">Besök oss på Facebook</a>
    </div>
</div>
<?php echo $after_widget; ?>