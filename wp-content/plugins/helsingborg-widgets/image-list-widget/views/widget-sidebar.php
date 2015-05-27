<div class="push-links-widget widget large-12 columns">
    <ul class="push-links-list">
        <?php foreach ($items as $num => $item) : ?>
            <li>
                <a href="<?php echo $item_links[$num]; ?>"><img src="<?php echo $item_imageurl[$num]; ?>" alt="<?php echo $item_alts[$num]; ?>" /></a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>