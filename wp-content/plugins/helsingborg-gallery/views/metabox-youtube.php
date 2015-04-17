<input type="hidden" name="hbg-gallery" value="true">
<ul class="youtube-link-list">
    <?php
        $node = 0;
        if (is_array($galleryItems)) : foreach ($galleryItems as $item) :
    ?>
    <li>
        <input type="text" name="youtube-link[<?php echo $node; ?>][url]" class="widefat" value="<?php echo $item['url']; ?>" placeholder="Ange YouTube-url">
        <div class="item-details" style="display:block;">
            <img src="<?php echo $item['image_url']; ?>" class="item-thumbnail" width="320" height="180">
            <input type="hidden" class="item-image-url" name="youtube-link[<?php echo $node; ?>][image_url]" value="<?php echo $item['image_url']; ?>" >
            <div class="item-info">
                <p>
                    <label>Titel:</label>
                    <input type="text" class="widefat item-title" name="youtube-link[<?php echo $node; ?>][title]" value="<?php echo $item['title']; ?>">
                </p>
                <p>
                    <label>Beskrivning:</label>
                    <textarea class="widefat item-description" name="youtube-link[<?php echo $node; ?>][description]"><?php echo $item['description']; ?></textarea>
                </p>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="row-actions">
            <a href="#" class="submitdelete btn-remove-row">Ta bort</a>
        </div>
    </li>
    <?php
        $node++;
        endforeach; endif;
    ?>
    <li>
        <input type="text" name="youtube-link[{node}][url]" class="widefat" placeholder="Ange YouTube-url">
        <div class="item-details">
            <img src="http://placehold.it/320x180&amp;text=Thumbnail" class="item-thumbnail" width="320" height="180">
            <input type="hidden" class="item-image-url" name="youtube-link[{node}][image_url]">
            <div class="item-info">
                <p>
                    <label>Titel:</label>
                    <input type="text" class="widefat item-title" name="youtube-link[{node}][title]">
                </p>
                <p>
                    <label>Beskrivning:</label>
                    <textarea class="widefat item-description" name="youtube-link[{node}][description]"></textarea>
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
    <a href="#" class="button-secondary btn-add-row"><span class="dashicons dashicons-plus-alt"></span> Lägg till rad</a>
</div>