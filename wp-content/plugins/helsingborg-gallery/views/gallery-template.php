<div class="hbg-gallery-container">
    <?php foreach ($galleryItems as $item) : $item = (object) $item; ?>
    <div class="large-3 medium-4 small-6 columns">
        <div class="hbg-gallery-item hbg-gallery-item-<?php echo $item->media; ?>">
            <div class="hbg-gallery-item-image-container" style="background-image:url('<?php echo $item->image_url?>');">
                <img src="<?php echo $item->image_url?>" alt="<?php echo $item->title; ?>" class="hbg-gallery-item-image">
            </div>
            <div class="hbg-gallery-item-title"><?php echo $item->title; ?></div>
            <div class="hbg-gallery-item-description"><?php echo $item->description; ?></div>

            <?php
                if ($item->media == 'youtube') {
                    $matches = null;
                    $rx = '~
                        ^(?:https?://)?              # Optional protocol
                         (?:www\.)?                  # Optional subdomain
                         (?:youtube\.com|youtu\.be)  # Mandatory domain name
                         /watch\?v=([^&]+)           # URI with video id as capture group 1
                         ~x';

                    preg_match($rx, $item->url, $matches);

                    //echo '<iframe src="https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1" frameborder="0" allowfullscreen></iframe>';
                }
            ?>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="clearfix"></div>
</div>