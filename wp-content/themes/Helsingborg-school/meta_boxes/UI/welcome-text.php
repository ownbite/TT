<p>
    <label>Rubrik:</label>
    <input type="text" class="widefat" name="hbgWelcomeText[title]" value="<?php echo (isset($frontPageText['title'])) ? $frontPageText['title'] : '' ; ?>">
</p>
<p>
    <label>Brödtext:</label>
    <textarea class="widefat" name="hbgWelcomeText[content]"><?php echo (isset($frontPageText['content'])) ? $frontPageText['content'] : '' ; ?></textarea>
</p>
<p>
    <label><input type="checkbox" name="hbgWelcomeText[display]" value="true" <?php echo (isset($frontPageText['display'])) ? 'checked' : '' ; ?>> Visa välkomsttexten på startsidan</label>
</p>