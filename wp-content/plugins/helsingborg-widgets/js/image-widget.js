/**
 * Helsingborg Image Widget Javascript
 */
jQuery(document).ready(function($){

	helsingborgImageWidget = {

		// Call this from the upload button to initiate the upload frame.
		uploader : function( widget_id, widget_id_string, num ) {

			var frame = wp.media({
				title : "Helsingborg",
				multiple : false,
				library : { type : 'image' },
				button : { text : "Infoga till item" }
			});

			// Handle results from media manager.
			frame.on('close',function( ) {
				var attachments = frame.state().get('selection').toJSON();
				helsingborgImageWidget.render( widget_id, widget_id_string, attachments[0], num );
			});

			frame.open();
			return false;
		},

		// Output Image preview and populate widget form.
		render : function( widget_id, widget_id_string, attachment, num ) {

			$("#" + widget_id_string + 'preview' + num).html(helsingborgImageWidget.imgHTML( attachment ));

			$("#" + widget_id_string + 'fields' + num).slideDown();

			$("#" + widget_id_string + 'attachment_id' + num).val(attachment.id);
			$("#" + widget_id_string + 'imageurl' + num).val(attachment.url);
			$("#" + widget_id_string + 'aspect_ratio' + num).val(attachment.width/attachment.height);
			$("#" + widget_id_string + 'width' + num).val(attachment.width);
			$("#" + widget_id_string + 'height' + num).val(attachment.height);

			$("#" + widget_id_string + 'size' + num).val('full');
			$("#" + widget_id_string + 'custom_size_selector' + num).slideDown();
			helsingborgImageWidget.toggleSizes( widget_id, widget_id_string );

			helsingborgImageWidget.updateInputIfEmpty( widget_id_string, 'title' + num, attachment.title );
			helsingborgImageWidget.updateInputIfEmpty( widget_id_string, 'alt' + num, attachment.alt );
			helsingborgImageWidget.updateInputIfEmpty( widget_id_string, 'description' + num, attachment.description );
			// attempt to populate 'description' with caption if description is blank.
			helsingborgImageWidget.updateInputIfEmpty( widget_id_string, 'description' + num, attachment.caption );
		},

		// Update input fields if it is empty
		updateInputIfEmpty : function( widget_id_string, name, value ) {
			var field = $("#" + widget_id_string + name);
			if ( field.val() == '' ) {
				field.val(value);
			}
		},

		// Render html for the image.
		imgHTML : function( attachment ) {
			var img_html = '<img src="' + attachment.url + '" ';
			img_html += 'width="' + attachment.width * 0,5  + '" ';
			img_html += 'height="' + attachment.height * 0,5 + '" ';
			if ( attachment.alt != '' ) {
				img_html += 'alt="' + attachment.alt + '" ';
			}
			img_html += '/>';
			return img_html;
		},

		// Hide or display the WordPress image size fields depending on if 'Custom' is selected.
		toggleSizes : function( widget_id, widget_id_string ) {
			if ( $( '#' + widget_id_string + 'size' ).val() == 'tribe_image_widget_custom' ) {
				$( '#' + widget_id_string + 'custom_size_fields').slideDown();
			} else {
				$( '#' + widget_id_string + 'custom_size_fields').slideUp();
			}
		},

		// Update the image height based on the image width.
		changeImgWidth : function( widget_id, widget_id_string ) {
			var aspectRatio = $("#" + widget_id_string + 'aspect_ratio').val();
			if ( aspectRatio ) {
				var width = $( '#' + widget_id_string + 'width' ).val();
				var height = Math.round( width / aspectRatio );
				$( '#' + widget_id_string + 'height' ).val(height);
				//imageWidget.changeImgSize( widget_id, widget_id_string, width, height );
			}
		},

		// Update the image width based on the image height.
		changeImgHeight : function( widget_id, widget_id_string ) {
			var aspectRatio = $("#" + widget_id_string + 'aspect_ratio').val();
			if ( aspectRatio ) {
				var height = $( '#' + widget_id_string + 'height' ).val();
				var width = Math.round( height * aspectRatio );
				$( '#' + widget_id_string + 'width' ).val(width);
				//imageWidget.changeImgSize( widget_id, widget_id_string, width, height );
			}
		}

	};

});
