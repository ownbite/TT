<p>
    <label>Välj typ av flöde: <?=$instance['feedType']?></label>
    <div class="button-group hbg-social-widget-type">
        <a href="#" class="button <?php if ($instance['feedType'] == 'facebook') echo 'active'; ?>" data-toggle=".hbg-social-widget-section-facebook" data-type="facebook"><i class="fa fa-facebook-square"></i> Facebook</a>
        <a href="#" class="button <?php if ($instance['feedType'] == 'instagram') echo 'active'; ?>" data-toggle=".hbg-social-widget-section-instagram" data-type="instagram"><i class="fa fa-instagram"></i> Instagram</a>
        <a href="#" class="button <?php if ($instance['feedType'] == 'twitter') echo 'active'; ?>" data-toggle=".hbg-social-widget-section-twitter" data-type="twitter"><i class="fa fa-twitter-square"></i> Twitter</a>
        <a href="#" class="button <?php if ($instance['feedType'] == 'pinterest') echo 'active'; ?>" data-toggle=".hbg-social-widget-section-pinterest" data-type="pinterest"><i class="fa fa-pinterest-square"></i> Pinterest</a>
    </div>
    <input type="hidden" name="<?php echo $this->get_field_name('feedType'); ?>" value="<?php echo $instance['feedType']; ?>">
</p>
<p>
    <!-- ## FACEBOOK ## -->
    <section class="hbg-social-widget-section-facebook <?php if ($instance['feedType'] == 'facebook') echo 'active'; ?>">
        <p>
            <label>URL till Facebook-sida:</label>
            <input type="text" id="<?php echo $this->get_field_id('facebook-url'); ?>" name="<?php echo $this->get_field_name('facebook-url'); ?>" class="widefat" value="<?php if ($instance['feedType'] == 'facebook') : ?>https://facebook.com/<?php echo $instance['username']; ?><?php endif; ?>">
        </p>
        <p>
            <label>Antal poster att visa:</label>
            <input type="number" id="<?php echo $this->get_field_id('facebook-count'); ?>" name="<?php echo $this->get_field_name('facebook-count'); ?>" class="widefat" value="<?php if ($instance['feedType'] == 'facebook') : ?><?php echo $instance['show_count']; ?><?php endif; ?>">
        </p>
        <p>
            <label>
                <input type="checkbox" id="<?php echo $this->get_field_id('facebook-show-visit-button'); ?>" name="<?php echo $this->get_field_name('facebook-show-visit-button'); ?>" class="widefat"  value="on" <?php checked('on', $instance['show_visit_button'], true); ?>>
                Visa "Besök oss på Facebook" knapp
            </label>
        </p>
    </section>

    <!-- ## INSTAGRAM ## -->
    <section class="hbg-social-widget-section-instagram <?php if ($instance['feedType'] == 'instagram') echo 'active'; ?>">
        <p>
            <label>Användare att hämta:</label>
            <input type="text" id="<?php echo $this->get_field_id('instagram-user'); ?>" name="<?php echo $this->get_field_name('instagram-user'); ?>" class="widefat"  value="<?php if ($instance['feedType'] == 'instagram') : ?><?php echo $instance['username']; ?><?php endif; ?>">
        </p>
        <p>
            <label>Antal poster att visa:</label>
            <input type="number" id="<?php echo $this->get_field_id('instagram-count'); ?>" name="<?php echo $this->get_field_name('instagram-count'); ?>" class="widefat"  value="<?php if ($instance['feedType'] == 'instagram') : ?><?php echo $instance['show_count']; ?><?php endif; ?>">
        </p>
        <p>
            <label>Antal poster per rad:</label>
            <input type="number" id="<?php echo $this->get_field_id('instagram-col-count'); ?>" name="<?php echo $this->get_field_name('instagram-col-count'); ?>" class="widefat"  value="<?php if ($instance['feedType'] == 'instagram') : ?><?php echo $instance['col_count']; ?><?php endif; ?>">
            <p class="description">Kan komma att justeras automatiskt för att passa in i Helsingborg.se's gridsystem.</p>
        </p>
        <p>
            <label>
                <input type="checkbox" id="<?php echo $this->get_field_id('instagram-show-likes'); ?>" name="<?php echo $this->get_field_name('instagram-show-likes'); ?>" class="widefat"  value="on" <?php checked('on', $instance['show_likes'], true); ?>>
                Visa antal likes
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" id="<?php echo $this->get_field_id('instagram-show-visit-button'); ?>" name="<?php echo $this->get_field_name('instagram-show-visit-button'); ?>" class="widefat"  value="on" <?php checked('on', $instance['show_visit_button'], true); ?>>
                Visa "Besök oss på Instgram" knapp
            </label>
        </p>
    </section>

    <!-- ## TWITTER ## -->
    <section class="hbg-social-widget-section-twitter <?php if ($instance['feedType'] == 'twitter') echo 'active'; ?>">
        <p>
            <label>Användare att hämta:</label>
            <input type="text" id="<?php echo $this->get_field_id('twitter-user'); ?>" name="<?php echo $this->get_field_name('twitter-user'); ?>" class="widefat"  value="<?php if ($instance['feedType'] == 'twitter') : ?><?php echo $instance['username']; ?><?php endif; ?>">
        </p>
        <p>
            <label>Antal poster att visa:</label>
            <input type="number" id="<?php echo $this->get_field_id('twitter-count'); ?>" name="<?php echo $this->get_field_name('twitter-count'); ?>" class="widefat" value="<?php if ($instance['feedType'] == 'twitter') : ?><?php echo $instance['show_count']; ?><?php endif; ?>">
        </p>
        <p>
            <label>
                <input type="checkbox" id="<?php echo $this->get_field_id('twitter-show-visit-button'); ?>" name="<?php echo $this->get_field_name('twitter-show-visit-button'); ?>" class="widefat"  value="on" <?php checked('on', $instance['show_visit_button'], true); ?>>
                Visa "Besök oss på Twitter" knapp
            </label>
        </p>
    </section>

    <!-- ## PINTEREST ## -->
    <section class="hbg-social-widget-section-pinterest <?php if ($instance['feedType'] == 'pinterest') echo 'active'; ?>">
        <p>
            <label>Användare att hämta:</label>
            <input type="text" id="<?php echo $this->get_field_id('pinterest-user'); ?>" name="<?php echo $this->get_field_name('pinterest-user'); ?>" class="widefat"  value="<?php if ($instance['feedType'] == 'pinterest') : ?><?php echo $instance['username']; ?><?php endif; ?>">
        </p>
        <p>
            <label>Antal poster att visa:</label>
            <input type="number" id="<?php echo $this->get_field_id('pinterest-count'); ?>" name="<?php echo $this->get_field_name('pinterest-count'); ?>" class="widefat" value="<?php if ($instance['feedType'] == 'pinterest') : ?><?php echo $instance['show_count']; ?><?php endif; ?>">
        </p>
        <p>
            <label>Antal poster per rad:</label>
            <input type="number" id="<?php echo $this->get_field_id('pinterest-col-count'); ?>" name="<?php echo $this->get_field_name('pinterest-col-count'); ?>" class="widefat"  value="<?php if ($instance['feedType'] == 'pinterest') : ?><?php echo $instance['col_count']; ?><?php endif; ?>">
            <p class="description">Kan komma att justeras automatiskt för att passa in i Helsingborg.se's gridsystem.</p>
        </p>
        <p>
            <label>
                <input type="checkbox" id="<?php echo $this->get_field_id('pinterest-show-visit-button'); ?>" name="<?php echo $this->get_field_name('pinterest-show-visit-button'); ?>" class="widefat"  value="on" <?php checked('on', $instance['show_visit_button'], true); ?>>
                Visa "Besök oss på Pinterest" knapp
            </label>
        </p>
    </section>
</p>