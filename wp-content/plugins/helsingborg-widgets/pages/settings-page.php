<?php

// Check that the user is allowed to update options
if (!current_user_can('read_private_pages')) {
  wp_die('Du har inte behörighet att editera dessa inställningar. Var god kontakta administratören.');
}

if (isset($_POST["update_settings"])) {

  // Do the saving
  $color_code = esc_attr($_POST["color_code"]);
  update_option('helsingborg_color_code', $color_code);

  $event_form_id = esc_attr($_POST["event_form_id"]);
  update_option('helsingborg_event_form_id', $event_form_id);

  $big_disturbance_root = esc_attr($_POST["big_disturbance_root"]);
  update_option('helsingborg_big_disturbance_root', $big_disturbance_root);

  $big_information_root = esc_attr($_POST["big_information_root"]);
  update_option('helsingborg_big_information_root', $big_information_root);

  echo('<div id="message" class="updated">Dina inställningar är sparade!</div>');
}

$color_code           = get_option('helsingborg_color_code');
$event_form_id        = get_option('helsingborg_event_form_id');
$big_disturbance_root = get_option('helsingborg_big_disturbance_root');
$big_information_root = get_option('helsingborg_big_information_root');

?>
<div class="wrap">
  <h2>Inställningar</h2>
  Här finns inställningar specifikt för denna domän.

  <form method="POST" action="">
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <label for="color_code">
            Färgkod för domän:
          </label>
        </th>
        <td>
          <input type="text" name="color_code" value="<?php echo $color_code; ?>" />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">
          <label for="event_form_id">
            Formulär-id för evenemang:
          </label>
        </th>
        <td>
          <input type="number" name="event_form_id" value="<?php echo $event_form_id; ?>" />
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">
          <label for="big_disturbance_root">
            Storstörningar hämtas från:
          </label>
        </th>
        <td>
            <?php wp_dropdown_pages(array(
              'show_option_none' => 'Ingen sida vald',
              'child_of' => 0,
              'depth' => 1,
              'post_status'  => 'publish,private',
              'selected' => $big_disturbance_root,
              'id' => 'big_disturbance_root',
              'name' => 'big_disturbance_root'
            )); ?>
        </td>
      </tr>

      <tr valign="top">
        <th scope="row">
          <label for="big_information_root">
            Storinformation hämtas från:
          </label>
        </th>
        <td>
          <?php wp_dropdown_pages(array(
            'show_option_none' => 'Ingen sida vald',
            'child_of' => 0,
            'depth' => 1,
            'post_status'  => 'publish,private',
            'selected' => $big_information_root,
            'id' => 'big_information_root',
            'name' => 'big_information_root'
          )); ?>
        </td>
      </tr>
    </table>
    <input type="hidden" name="update_settings" value="Y" />
    <p>
      <input type="submit" value="Spara" class="button-primary"/>
    </p>
  </form>
</div>
