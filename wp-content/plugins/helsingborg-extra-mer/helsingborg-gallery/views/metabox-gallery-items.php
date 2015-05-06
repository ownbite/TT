<input type="hidden" name="hbg-gallery" value="true">
<ul class="gallery-items-list">
    <?php
        $node = 0;
        if (is_array($galleryItems)) : foreach ($galleryItems as $item) :
    ?>
        <?php if ($item['media'] == 'youtube') : ?>
        <li>
            <input type="hidden" name="gallery-items[<?php echo $node; ?>][media]" value="youtube">
            <input type="text" name="gallery-items[<?php echo $node; ?>][url]" class="widefat" value="<?php echo $item['url']; ?>" placeholder="Ange YouTube-url">
            <div class="item-details" style="display:block;">
                <img src="<?php echo $item['image_url']; ?>" class="item-thumbnail" width="320" height="180">
                <input type="hidden" class="item-image-url" name="gallery-items[<?php echo $node; ?>][image_url]" value="<?php echo $item['image_url']; ?>">
                <div class="item-info">
                    <p>
                        <label>Titel:</label>
                        <input type="text" class="widefat item-title" name="gallery-items[<?php echo $node; ?>][title]" value="<?php echo $item['title']; ?>">
                    </p>
                    <p>
                        <label>Beskrivning:</label>
                        <textarea class="widefat item-description" name="gallery-items[<?php echo $node; ?>][description]"><?php echo $item['description']; ?></textarea>
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row-actions">
                <a href="#" class="submitdelete btn-remove-row">Ta bort</a>
            </div>
        </li>
        <?php elseif ($item['media'] == 'image') : ?>
        <li>
            <input type="hidden" name="gallery-items[<?php echo $node; ?>][media]" value="image">
            <button class="button-secondary widefat open-media-selector">Välj bild…</button>
            <div class="item-details" style="display:block;">
                <img src="<?php echo $item['image_url']; ?>" class="item-thumbnail" width="320" height="180">
                <input type="hidden" class="item-image-url" name="gallery-items[<?php echo $node; ?>][image_url]"  value="<?php echo $item['image_url']; ?>">
                <div class="item-info">
                    <p>
                        <label>Titel:</label>
                        <input type="text" class="widefat item-title" name="gallery-items[<?php echo $node; ?>][title]" value="<?php echo $item['title']; ?>">
                    </p>
                    <p>
                        <label>Beskrivning:</label>
                        <textarea class="widefat item-description" name="gallery-items[<?php echo $node; ?>][description]"><?php echo $item['description']; ?></textarea>
                    </p>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="row-actions">
                <a href="#" class="submitdelete btn-remove-row">Ta bort</a>
            </div>
        </li>
        <?php endif; ?>
    <?php
        $node++;
        endforeach; endif;
    ?>

    <!-- ## YouTube item template ## -->
    <li class="item-youtube item-template">
        <input type="hidden" name="gallery-items[{node}][media]" value="youtube">
        <input type="text" name="gallery-items[{node}][url]" class="widefat" placeholder="Ange YouTube-url…">
        <div class="item-details">
            <img src="http://placehold.it/320x180&amp;text=Thumbnail" class="item-thumbnail" width="320" height="180">
            <input type="hidden" class="item-image-url" name="gallery-items[{node}][image_url]">
            <div class="item-info">
                <p>
                    <label>Titel:</label>
                    <input type="text" class="widefat item-title" name="gallery-items[{node}][title]">
                </p>
                <p>
                    <label>Beskrivning:</label>
                    <textarea class="widefat item-description" name="gallery-items[{node}][description]"></textarea>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row-actions">
            <a href="#" class="submitdelete btn-remove-row">Ta bort</a>
        </div>
    </li>

    <!-- ## Image item template ## -->
    <li class="item-image item-template">
        <input type="hidden" name="gallery-items[{node}][media]" value="image">
        <button class="button-secondary widefat open-media-selector">Välj bild…</button>
        <div class="item-details">
            <img src="http://placehold.it/320x180&amp;text=Thumbnail" class="item-thumbnail" width="320" height="180">
            <input type="hidden" class="item-image-url" name="gallery-items[{node}][image_url]">
            <div class="item-info">
                <p>
                    <label>Titel:</label>
                    <input type="text" class="widefat item-title" name="gallery-items[{node}][title]">
                </p>
                <p>
                    <label>Beskrivning:</label>
                    <textarea class="widefat item-description" name="gallery-items[{node}][description]"></textarea>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row-actions">
            <a href="#" class="submitdelete btn-remove-row">Ta bort</a>
        </div>
    </li>
</ul>
<div class="actions">
    <a href="#" class="button-secondary btn-add-row" data-template="item-youtube"><span class="dashicons dashicons-video-alt3"></span> Lägg till YouTube</a>
    <a href="#" class="button-secondary btn-add-row" data-template="item-image"><span class="dashicons dashicons-format-image"></span> Lägg till bild</a>
</div>