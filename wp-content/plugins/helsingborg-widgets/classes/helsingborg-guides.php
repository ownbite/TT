<?php
/*
* This GUIDE is derived from the plugin: Step by Step, however heavily modified.
* Original plugin: http://kylembrown.com/step-by-step
*/
function hbg_custom_admin_head(){
  echo '<script type="text/javascript">
  jQuery(document).ready( function(){
    jQuery("#post").attr("enctype","multipart/form-data");
  } );
  </script>';
}
add_action('admin_head', 'hbg_custom_admin_head');

function custom_post_type()
{
  $labels = array(
    'name'               => _x( 'Guider', '' ),
    'singular_name'      => _x( 'Guide', '' ),
    'add_new'            => _x( 'Skapa ny', 'guide' ),
    'add_new_item'       => __( 'Skapa ny Guide' ),
    'edit_item'          => __( 'Ändra Guide' ),
    'new_item'           => __( 'Ny Guide' ),
    'all_items'          => __( 'Alla Guider' ),
    'view_item'          => __( 'Visa Guide' ),
    'search_items'       => __( 'Sök Guider' ),
    'not_found'          => __( 'Hittade inga guider' ),
    'not_found_in_trash' => __( 'Hittade inga guider i papperskorgen' ),
    'parent_item_colon'  => '',
    'menu_name'          => 'Guider'
  );
  $args = array(
    'labels'        => $labels,
    'description'   => 'Håller våra guider och dess data',
    'public'        => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'rewrite'            => array('slug'=>'guide','with_front'=>false),
    'query_var'          => true,
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 5,
    'menu_icon'          => 'dashicons-format-aside',
    'supports'      => array( 'title', 'editor', 'thumbnail')
  );
  register_post_type( 'hbg_guide', $args );
}
add_action( 'init', 'custom_post_type' );

add_filter( 'post_updated_messages', 'hbg_updated_messages' );
function hbg_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['hbg_guide'] = array(
    0 => '',
    1 => sprintf( __('Guide uppdaterad. <a href="%s">Visa guide</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('Eget fält uppdaterat.'),
    3 => __('Eget fält borttaget.'),
    4 => __('Guide uppdaterad.'),
    5 => isset($_GET['revision']) ? sprintf( __('Guide återställd till granskning från %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('Guide publiserad. <a href="%s">Visa guide</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('Guide sparad.'),
    8 => sprintf( __('Guide inlagd. <a target="_blank" href="%s">Förhandsgranska guide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('Guide schedulerad för: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Förhandsgranska guide</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('Utkast uppdaterad. <a target="_blank" href="%s">Preview guide</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );
  return $messages;
}

//adding the meta box when the admin panel initialises
add_action("admin_init", "add_article_metabox");
function add_article_metabox(){
  add_meta_box('manage_steps', 'Hantera steg i guiden', 'steps_meta_box', 'hbg_guide', 'normal', 'default');
}


function steps_meta_box($post, $args) {
  $steps_meta = get_post_meta($post->ID, 'meta-step', true); ?>
  <div id="mainstep">
  <?php if(isset($steps_meta['step'])) {
    $i = 1;
    if(is_array($steps_meta['step']) ) {
      foreach($steps_meta['step'] as $key => $value) { ?>
      <div id='step<?php echo $i;?>' style="background: -moz-linear-gradient(center top , #F5F5F5, #FCFCFC) repeat scroll 0 0 rgba(0, 0, 0, 0);">
        <p style="text-align:right"><a href="javascript:void(0);" onclick="return removeDiv('<?php echo 'step'.$i;?>');">- Ta bort steg</a></p>
        <p>Steg <span style="color:red;">*</span><br> <span><input type="text" size="40" value="<?php echo  $value;?>" name="step[]" id="step"></span></p>
        <p>Innehåll <span style="color:red;">*</span><br> <span><textarea name="step_title[]" id="step_title<?php echo $i;?>" rows="4" cols="40"><?php echo  $steps_meta['step_title'][$key];?></textarea></span></p>
        <p>Notering<br> <span><input type="text" size="40" value="<?php echo $steps_meta['note'][$key];?>" name="note[]" id="note"></span></p>
        <p>
          <?php
          if(isset($steps_meta['step_image'][$key]) && !empty($steps_meta['step_image'][$key])) {
            $image_attributes = wp_get_attachment_image_src( $steps_meta['step_image'][$key],array(100,100) );
            $attr = get_the_post_thumbnail($steps_meta['step_image'][$key], 'thumbnail');

            ?>
            <img style="vertical-align: middle;" src="<?php echo $image_attributes[0]; ?>" width="<?php echo $image_attributes[1]; ?>" height="<?php echo $image_attributes[2]; ?>">&nbsp;&nbsp;<a href="javascript:void(0);" alt="Ta bort" title="Ta bort" onclick="return remove_attachement('<?php echo $steps_meta['step_image'][$key];?>',<?php echo $post->ID;?>,'<?php echo $i;?>')">Ta bort</a>
            <img id="loader" style="display: none;margin: 0 auto;text-align: center;" src="<?php echo plugins_url()?>/helsingborg-widgets/images/loader.gif" />
            <p>Bild<br> <span><input type="file" size="60" value="" name="step_image[]" id="step_image"></span></p>
            <?php } else { ?>
            </p>
            <p>Bild<br> <span><input type="file" size="60" value="" name="step_image[]" id="step_image"></span></p>
            <?php } ?>
          </div>
          <?php
          $i++;
      }
    }
  } else { ?>
    <div id='step1' style="background: -moz-linear-gradient(center top , #F5F5F5, #FCFCFC) repeat scroll 0 0 rgba(0, 0, 0, 0);">
      <p>Steg <span style="color:red;">*</span><br> <span><input type="text" size="40" value="" name="step[]" id="step"></span></p>

      <p>Innehåll <span style="color:red;">*</span><br> <span><textarea name="step_title[]" id="step_title1" rows="4" cols="40"></textarea></span></p>

      <p>Notering<br> <span><input type="text" size="40" value="" name="note[]" id="note"></span></p>
      <p>Bild<br> <span><input type="file" size="60" value="" name="step_image[]" id="step_image"></span></p>
    </div>
  <?php } ?>
  </div>

  <div style="clear:both;"></div>
  <div style="padding-bottom:5px;text-align:right;color:#fff;"><a href="javascript:void(0);" onClick="addmorediv()">+ Lägg till steg</a></div>
  <input type="hidden" name="step_count" id="step_count" value="<?php echo count($steps_meta['step']); ?>">

  <?php if(isset($steps_meta['step']) && count($steps_meta['step'])>0) { ?>
  <div style="background: -moz-linear-gradient(center top , #F5F5F5, #FCFCFC) repeat scroll 0 0 rgba(0, 0, 0, 0);">
    <p>Hämta shortcode</span><br> <span>
      <textarea rows="3" cols="40" readonly>[display_hbg_guide id='<?php echo $post->ID; ?>' type='hbg_guide']</textarea>
    </span>
  </div>
<?php } ?>
  <script>
    jQuery(document).ready(function() {
      var num = <?php echo count($steps_meta['step']); ?>;
      for (i = 1; i <= num; i++) {
        jQuery('#step_title'+i).wp_editor();
      }
    });

    function toggleEditor(id, state)
    {
      if (!tinyMCE.get(id)) {
        if (state == "tmce") {
          // Text -> Visual
          tinyMCE.EditorManager.execCommand('mceAddEditor',true, id);
        } else {
          // Text -> Text
        }
      } else {
        if (state == "tmce") {
          // Visual -> Visual
          tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, id);
          tinyMCE.EditorManager.execCommand('mceAddEditor',true, id);
        } else {
          // Visual -> Text
          tinyMCE.EditorManager.execCommand('mceRemoveEditor',true, id);
        }
      }
    }

    function remove_attachement(attachementID, postID,stepId)
    {
      var data = {
        action: 'custom_delete_attachement',
        attachement_ID:attachementID,
        post_ID:postID
      };

      jQuery.post(ajaxurl, data, function(response) {
        jQuery("#loader"+stepId).css({'display':'inline-block'});
        jQuery("#step_img").hide();
        window.setTimeout('location.reload()', 1000);
      });

    }

    function addmorediv()
    {
      var cnt = jQuery('#step_count').val();
      cnt = parseInt(cnt)+1;
      jQuery('#mainstep').append(
        '<div id="step'+cnt+'" style="padding-top:10px;background: -moz-linear-gradient(center top , #F5F5F5, #FCFCFC) repeat scroll 0 0 rgba(0, 0, 0, 0);">' +
        '<p style="text-align:right"><a href="javascript:void(0);" onclick="return removeDiv(\'step'+cnt+'\');">- Ta bort steg</a></p>' +
        '<p>Steg<span style="color:red;">*</span><br><span><input type="text" size="40" value="" name="step[]" id="step"></span></p>' +
        '<p>Innehåll<span style="color:red;">*</span><br><span>' +
        '<textarea name="step_title[]'+cnt+'" id="step_title'+cnt+'" rows="4" cols="40"></textarea></span></p>' +
        '<p>Notering<br><span><input type="text" size="40" value="" name="note[]" id="note"></span></p>' +
        '<p>Bild<br> <span><input type="file" size="60" value="" name="step_image[]" id="step_image"></span></p></div>');
      jQuery('#step_count').val(cnt);
      jQuery('#step_title'+cnt).wp_editor();
        }

        function removeDiv(divId)
        {
          jQuery('#'+divId).remove();
          var cnt = jQuery('#step_count').val();
          cnt = parseInt(cnt)-1;
          if (cnt < 0) {cnt = 0;}
          jQuery('#step_count').val(cnt);
        }
  </script>
<?php }

add_action( 'wp_ajax_custom_delete_attachement', 'remove_attachement_image' );
function remove_attachement_image() {
  global $wpdb;

  $attachement_ID = intval( $_POST['attachement_ID'] );
  $post_ID = intval( $_POST['post_ID'] );

  if(isset($attachement_ID) && isset($post_ID))
  {

    wp_delete_attachment( $attachement_ID);
    $steps_meta_data = get_post_meta($post_ID, 'meta-step', true);

    if(($key = array_search($attachement_ID, $steps_meta_data['step_image'])) !== false) {
      unset($steps_meta_data['step_image'][$key]);
      update_post_meta( $post_ID, 'meta-step',  $steps_meta_data );
    }
    $msg = 'bilaga har tagits bort.';
  }
}

add_action( 'save_post', 'prfx_meta_save' );
function prfx_meta_save( $post_id ) {
  if ( !wp_is_post_revision( $post_id ))
  {
    $attached_file_array='';
    $steps_meta = get_post_meta($post_id, 'meta-step', true);
    if(isset($steps_meta['step_image']))
    $attached_file_array = $steps_meta['step_image'];

    if(!is_array($attached_file_array)) { $attached_file_array = array(); }

    require_once( ABSPATH . 'wp-admin/includes/file.php' );
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    $upload_overrides = array( 'test_form' => FALSE );
    $uploads = wp_upload_dir();

    if(!empty($_FILES['step_image']['name'])){//Kmb to fix array error

      foreach($_FILES['step_image']['name'] as $key => $filenamevalue) {

        $attach_id = '';
        if(isset($_FILES['step_image']['name'][$key]) && $_FILES['step_image']['error'][$key]!='4'){

          if(isset($steps_meta['step_image'][$key]) && !empty($steps_meta['step_image'][$key])) {
            wp_delete_attachment( $steps_meta['step_image'][$key] );
            if (in_array($steps_meta['step_image'][$key], $attached_file_array)) {
              unset($attached_file_array[array_search($steps_meta['step_image'][$key],$attached_file_array)]);
            }
          }

          $file_array = array(
          'name' => $filenamevalue,
          'type' => $_FILES['step_image']['type'][$key],
          'tmp_name' => $_FILES['step_image']['tmp_name'][$key],
          'error' => $_FILES['step_image']['error'][$key],
          'size' => $_FILES['step_image']['size'][$key],
          );

          // check to see if the file name is not empty
          if ( !empty( $file_array['name'] ) ) {

            // upload the file to the server
            $uploaded_file = wp_handle_upload( $file_array, $upload_overrides );

            // Check the type of tile. We'll use this as the 'post_mime_type'.
            $filetype = wp_check_filetype( basename( $uploaded_file['file'] ), null );

            // Prepare an array of post data for the attachment.
            $attachment = array(
            'guid'           => $uploads['url'] . '/' . basename( $uploaded_file['file'] ),
            'post_mime_type' => $filetype['type'],
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $uploaded_file['file'] ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
            );

            $imagename =  $uploads['path'] . '/' . basename($uploaded_file['file'] );

            // Insert the attachment.
            $attach_id = wp_insert_attachment( $attachment, $imagename, $post_id );

            // get the attachemnet image relative path for genrate the image metadata
            $attachment_image_path = $uploads['path'] . '/' . basename($uploaded_file['file'] );

            // Generate the metadata for the attachment, and update the database record.
            $attach_data = wp_generate_attachment_metadata( $attach_id, $attachment_image_path );

            wp_update_attachment_metadata( $attach_id, $attach_data );
            $attached_file_array[$key] =  $attach_id;
          }
        }
      }
    }//	End if(!empty ->

    $step='';
    $step_title='';
    $note='';

    if(isset($_POST['step']))
    $step=$_POST['step'];
    if(isset($_POST['step_title']))
    $step_title=$_POST['step_title'];
    if(isset($_POST['note']))
    $note=$_POST['note'];

    if(isset($_POST['publish']) || isset($_POST['save']))
    {
      $result = array('step'=>$step, 'step_title'=>$step_title,'note'=>$note,'step_image'=>$attached_file_array);
      update_post_meta( $post_id, 'meta-step',  $result );
    }
  }
}

add_action( 'admin_init', 'disable_autosave' );
function disable_autosave() {
  wp_deregister_script( 'autosave' );
}

add_shortcode( 'display_hbg_guide', 'hbg_guide_func' );
function hbg_guide_func( $atts ) {
  if(isset($atts['id']))
  {
    $post_id = sanitize_text_field( $atts['id'] );
    $post_type= sanitize_text_field( $atts['type'] );
    $post = get_post( $post_id );

    $thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium');
    $article_steps_meta = get_post_meta($post_id, 'meta-step', true);

    $guide = '<section class="guide-section">';
      $guide .= '<h2 class="section-title">' . $post->post_title . '</h2>';

      $guide .= '<div class="divider fade">';
        $guide .= '<div class="upper-divider"></div>';
        $guide .= '<div class="lower-divider"></div>';
      $guide .= '</div>';

      $guide .= '<ul class="guide-list">';

      if(count($article_steps_meta["step"])) {
        for($i=0;$i<count($article_steps_meta["step"]);$i++){
          if ($i==0) {$guide .= '<li class="current">';} else {$guide .= '<li>';}

          if(isset($article_steps_meta["step_image"][$i])){
            $kk=wp_get_attachment_image_src( $article_steps_meta["step_image"][$i], 'full', true );
            $guide .= '<img src="'.$kk[0].'" alt="" >';
          }

          $guide .= '<span class="title">' . $article_steps_meta["step"][$i] . ' </span>';
          $guide .= '<p class="description">' . $article_steps_meta["step_title"][$i] . '</p>';
          $guide .= '<p class="notes">' . $article_steps_meta["note"][$i] . '</p>';
          $guide .= '</li>';
        }
      }
      $guide .= '</ul>'; //<!-- /.guide-list -->

      $guide .= '<ul class="pagination" role="menubar" aria-label="Pagination">';
      $guide .= '<li><a href="#" class="button radius guide-button prev-step">' . __(Föregående) . '</a></li>';
      for($i=0;$i<count($article_steps_meta["step"]);$i++){
        $guide .= '<li' . ($i==0?' class="current-pager"':'') . '><a href="#">' . ($i+1) . '</a></li>';
      }
      $guide .= '<li><a href="#" class="button radius guide-button next-step">' . __ (Nästa) . '</a></li>';
      $guide .= '</ul>';
      $guide .= '</section>';

      return $guide;
  }
}


define( 'FB_BASENAME', plugin_basename( __FILE__ ) );
define( 'FB_BASEFOLDER', plugin_basename( dirname( __FILE__ ) ) );
define( 'FB_FILENAME', str_replace( FB_BASEFOLDER.'/', '', plugin_basename(__FILE__) ) );

function filter_plugin_meta($links, $file) {

  if ( $file == FB_BASENAME ) {
    array_unshift(
      $links,
      sprintf( '<a href="edit.php?post_type=hbg_guide">Guider</a>', FB_FILENAME, __('Guider') )
    );
  }
  return $links;
}

        global $wp_version;
        if ( version_compare( $wp_version, '2.8alpha', '>' ) )
        //	add_filter( 'plugin_row_meta', 'filter_plugin_meta', 10, 2 ); // only 2.8 and higher
        add_filter( 'plugin_action_links', 'filter_plugin_meta', 10, 2 );
?>
