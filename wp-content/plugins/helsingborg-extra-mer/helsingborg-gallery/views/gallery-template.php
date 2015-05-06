<div class="hbg-gallery-container">
    <?php foreach ($galleryItems as $item) : $item = (object) $item; ?>
        <a href="#" class="hbg-gallery-item hbg-gallery-item-<?php echo $item->media; ?>" <?php if ($item->media == 'youtube') : ?>data-youtube="<?php echo $item->url; ?>"<?php endif; ?>>
            <div class="hbg-gallery-item-image-container" style="background-image:url('<?php echo $item->image_url?>');">
                <img src="<?php echo $item->image_url?>" alt="<?php echo $item->title; ?>" class="hbg-gallery-item-image">
                <?php if ($item->media == 'youtube') : ?>
                <span class="hbg-gallery-media-icon dashicons dashicons-video-alt3"></span>
                <?php elseif ($item->media == 'image') : ?>
                <span class="hbg-gallery-media-icon dashicons dashicons-format-image"></span>
                <?php endif; ?>
            </div>
            <div class="hbg-gallery-item-title"><?php echo $item->title; ?></div>

            <?php if (isset($item->description) && strlen($item->description) > 0) : ?>
            <div class="hbg-gallery-item-description"><?php echo $item->description; ?></div>
            <?php endif; ?>
        </a>
    <?php endforeach; ?>
    <div class="clearfix"></div>
</div>