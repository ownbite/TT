<?php
    $data_options = (count($items) == 1) ? 'data-options="navigation_arrows:false;slide_number:false;timer:false;"' : '';
?>

<div class="large-12 columns slider-container">
    <ul class="helsingborg-orbit" data-orbit="<?php echo $data_options; ?>">
        <?php
            foreach ($items as $num => $item) :
                $force_width  = (!empty($item_force_widths[$num])) ? 'width:100%;' : '';
                $force_margin = (!empty($item_force_margins[$num]) && !empty($item_force_margin_values[$num])) ? ' margin-top:-' . $item_force_margin_values[$num] . 'px;' : '';
        ?>
            <li>
                <?php if (!empty($item_links[$num])) : ?>
                    <a href="<?php echo $item_links[$num]; ?>">
                <?php endif; ?>

                <img class="img-slide" src="<?php echo $item_imageurl[$num]; ?>" alt="<?php echo $item_alts[$num]; ?>" style="<?php echo $force_width . $force_margin; ?>" />

                <?php if (!empty($item_links[$num])) : ?>
                    </a>
                <?php
                    endif;
                    if (!empty($item_texts[$num])) :
                ?>
                    <div class="orbit-caption show-for-medium-up">
                        <?php echo $item_texts[$num]; ?>
                    </div>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>