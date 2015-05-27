<?php
    $grid_size = (count($items) >= 3) ? "3" : "2";
?>
<section class="large-8 columns">
    <ul class="block-list news-block large-block-grid-<?php echo $grid_size; ?> medium-block-grid-<?php echo $grid_size; ?> small-block-grid-2">
        <?php foreach ($items as $num => $item) : ?>
            <li>
                <a href="<?php echo $item_links[$num]; ?>"><img src="<?php echo $item_imageurl[$num]; ?>" alt="<?php echo $item_alts[$num]; ?>" /></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>