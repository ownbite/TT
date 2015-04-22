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
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" value="Spara ändringar" class="button button-primary" id="submit" name="submit">
        </p>
    </form>
</div>