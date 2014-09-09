<div class="helsingborg_meta_control">

    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras orci lorem, bibendum in pharetra ac, luctus ut mauris. Phasellus dapibus elit et justo malesuada eget <code>functions.php</code>.</p>

    <label>Name</label>

    <p>
        <input type="text" name="_helsingborg_meta[name]" value="<?php if(!empty($meta['name'])) echo $meta['name']; ?>"/>
        <span>Enter in a name</span>
    </p>

    <label>Description <span>(optional)</span></label>

    <p>
        <textarea name="_helsingborg_meta[description]" rows="3"><?php if(!empty($meta['description'])) echo $meta['description']; ?></textarea>
        <span>Enter in a description</span>
    </p>

</div>
