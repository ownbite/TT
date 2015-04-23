<div class="wrap">
    <h2>Sociala flöden</h2>
    Här kan du ställa in applikationsinställningar för sociala flöden.

    <form method="post" action="">
        <input type="hidden" name="is_post" value="true">
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th>Facebook App ID:</th>
                    <td>
                        <input type="text" name="facebook[app_id]" value="<?php echo $hbgsf_facebook_app_id; ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th>Facebook App Secret:</th>
                    <td>
                        <input type="text" name="facebook[app_secret]"  value="<?php echo $hbgsf_facebook_app_secret; ?>">
                        <p class="description">Hämtas på <a href="https://developers.facebook.com">developers.facebook.com</a></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th>Instagram Client ID:</th>
                    <td>
                        <input type="text" name="instagram[client_id]"  value="<?php echo $hbgsf_instagram_client_id; ?>">
                        <p class="description">Hämtas på <a href="https://instagram.com/developer">instagram.com/developer</a></p>
                    </td>
                </tr>
                <tr valign="top">
                    <th>Twitter Consumer Key:</th>
                    <td>
                        <input type="text" name="twitter[consumer_key]"  value="<?php echo $hbgsf_twitter_consumer_key; ?>">
                    </td>
                </tr>
                <tr valign="top">
                    <th>Twitter Consumer Secret:</th>
                    <td>
                        <input type="text" name="twitter[consumer_secret]"  value="<?php echo $hbgsf_twitter_consumer_secret; ?>">
                        <p class="description">Hämtas på <a href="https://apps.twitter.com">apps.twitter.com</a></p>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" value="Spara ändringar" class="button button-primary" id="submit" name="submit">
        </p>
    </form>
</div>