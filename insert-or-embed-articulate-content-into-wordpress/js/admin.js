/*
Using WordPress Media Uploader System with plugin settings
Author: oneTarek
Author URI: http://onetarek.com
Source: http://onetarek.com/wordpress/how-to-use-wordpress-media-uploader-in-plugin-or-theme-admin-page/
*/
// File uploader
var file_frame;

var articulatejq = jQuery.noConflict();
articulatejq(document).ready(function() { 

 	articulatejq("#add_new_button_btn").click(function(){
 		// If the media frame already exists, reopen it.
		if ( file_frame ) {
		  file_frame.open();
		  return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
		  title: 'Select an Image',
		  button: {
			text: 'Use this Image',
		  },
		  multiple: false  // only allow the one file to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
		  // We set multiple to false so only get one image from the uploader
		  attachment = file_frame.state().get('selection').first().toJSON(); 
		  var imgurl = attachment.url;
		  var HTML = '<div class="button_box" title="Delete"><div class="close_button"></div><div class="imgbox"><img src="'+imgurl+'"  /></div><input type="text" size="20" name="buttons[]" class="image_source validate" value="'+imgurl+'"></div>';
		  articulatejq("#button_area").append(HTML);

		});
		// Finally, open the modal
		file_frame.open();
 	});
	
	articulatejq(".close_button").live('click', function (){
			articulatejq(this).parent().remove();
		
		 });
		 
	
 
});
