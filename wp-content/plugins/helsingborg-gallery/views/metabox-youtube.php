<input type="hidden" name="hbg-gallery" value="true">
<ol class="youtube-link-list">
    <?php if (is_array($youtubeLinks)) : foreach ($youtubeLinks as $key => $value) : ?>
    <li><input type="text" name="youtube-link[]" value="<?php echo $value; ?>"><a href="#" class="btn-remove-row"><span class="dashicons dashicons-trash"></span></a></li>
    <?php endforeach; endif; ?>
    <li><input type="text" name="youtube-link[]"><a href="#" class="btn-remove-row"><span class="dashicons dashicons-trash"></span></a></li>
</ol>
<div class="actions">
    <a href="#" class="button-secondary btn-add-row"><span class="dashicons dashicons-plus-alt"></span> Lägg till rad</a>
</div>