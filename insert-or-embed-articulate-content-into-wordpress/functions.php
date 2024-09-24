<?php
/*
 * Update php ini if necessary
 */
function articulate_setup_php_ini() {
	@ini_set( 'max_execution_time', 0 );
	@ini_set( 'max_input_time', 0 );
	@ini_set( 'memory_limit', '-1' );
	@ini_set( 'post_max_size', 0 );
}
/**
 * Pantheon server does not support php rename function.
 * so we use custom rename function if server is panthoen server
 * https://pantheon.io/docs/platform-considerations/#renamemove-files-or-directories
 * We will try the default rename function first . If that fails then we will try custom function.
 */
function articulate_custom_rename( $oldname, $newname ) {

	$success = rename( $oldname, $newname );
	if ( ! $success ) {
		Custom_FS_Functions::rename( $oldname, $newname );
	}

}

require_once 'articulaterules.php';
function get_slug_from_string( $string ) {
	$string          = strtolower( $string );
	$filtered_string = preg_replace( '/[^a-zA-Z0-9\s]/', '', $string );
	$filtered_string = str_replace( ' ', '_', $filtered_string );
	return $filtered_string;
}
// ********************************************************************************************************************************
function print_page_navi( $num_records ) {
	// $num_records; #holds total number of record
	$page_size = '';     // holds how many items per page
	$page      = 1;          // holds the curent page index
	$num_pages = '';     // holds the total number of pages
	$page_size = 15;
	// get the page index
	if ( empty( $_GET['npage'] ) || ! is_numeric( $_GET['npage'] ) ) {
		$page = 1;
	} else {
		$page = $_GET['npage'];}
	// caluculate number of pages to display
	if ( $num_records % $page_size ) {
		$num_pages = ( floor( $num_records / $page_size ) + 1 );
	} else {
		$num_pages = ( floor( $num_records / $page_size ) );
	}
	if ( $num_pages != 1 ) {
		for ( $i = 1; $i <= $num_pages; ++$i ) {
			// if page is the same as the page being written to screen, don't write the link
			// page navigation logic is developed by "oneTarek" http://onetarek.com
			if ( $i == $page ) {
				echo "$i";
			} else {
				echo "<a href=\"media-upload.php?type=articulate-upload&tab=articulate-quiz&npage=$i\">$i</a>";
			}
			if ( $i != $num_pages ) {
				echo ' | ';
			}
		}
	}
	// calculate boundaries for limit query
	$upper_bound = ( ( $page_size * ( $page - 1 ) ) + $page_size );/*$page_size;*/
	$lower_bound = ( $page_size * ( $page - 1 ) );
	$bound       = array( $lower_bound, $upper_bound );
	return $bound;
}
function print_detail_form( $num, $tab = 'articulate-upload', $file_url = '', $dirname = '' ) {
	 $opt = get_quiz_embeder_options();
	$rand = mt_rand( 0, 9999999 );
	?>
<div id="upload_detail_<?php echo $num; ?>" style="display:none; margin-bottom:30px;">
<input type="hidden" size="40" name="file_url_<?php echo $num; ?>" id="file_url_<?php echo $num; ?>" value="<?php echo articulate_link_relative( $file_url ); ?>" />
<input type="hidden" size="40" name="dir_name_<?php echo $num; ?>" id="dir_name_<?php echo $num; ?>" value="<?php echo $dirname; ?>" />
	<?php if ( $tab == 'articulate-upload' ) { ?> 
<input type="hidden" name="file_name_<?php echo $num; ?>" id="file_name_<?php echo $num; ?>" value="" />
<br /><label for="title"><strong><?php _e( 'Title', 'insert-or-embed-articulate-content-into-wordpress' ); ?>:</strong></label> <input type="text" size="20" name="title" id="title" value="" />
<?php } ?>		
<h3 class="header"><?php _e( 'Insert As', 'insert-or-embed-articulate-content-into-wordpress' ); ?>:</h3>
	<?php $rand = mt_rand( 0, 9999999 ); ?>
<input type="radio" name="insert_as_<?php echo $num; ?>" value="1" checked="checked" onclick="insert_as_clicked(<?php echo $num; ?>)" id="test_<?php echo $rand; ?>" /> <label for="test_<?php echo $rand; ?>"><?php _e( 'IFrame', 'insert-or-embed-articulate-content-into-wordpress' ); ?></label><br />
	<?php $rand = mt_rand( 0, 9999999 ); ?>
<input type="radio" name="insert_as_<?php echo $num; ?>" value="2" disabled="disabled" onclick="insert_as_clicked(<?php echo $num; ?>)" id="test_<?php echo $rand; ?>" /> <label for="test_<?php echo $rand; ?>"><?php _e( 'Lightbox', 'insert-or-embed-articulate-content-into-wordpress' ); ?> (<?php _e( 'Paid Feature', 'insert-or-embed-articulate-content-into-wordpress' ); ?>)</label><br />
	<?php $rand = mt_rand( 0, 9999999 ); ?>
<input type="radio" name="insert_as_<?php echo $num; ?>" value="3" disabled="disabled" onclick="insert_as_clicked(<?php echo $num; ?>)" id="test_<?php echo $rand; ?>"/> <label for="test_<?php echo $rand; ?>"><?php _e( 'Link that opens in a new window', 'insert-or-embed-articulate-content-into-wordpress' ); ?> (<?php _e( 'Paid Feature', 'insert-or-embed-articulate-content-into-wordpress' ); ?>)</label><br />
	<?php $rand = mt_rand( 0, 9999999 ); ?>
<input type="radio" name="insert_as_<?php echo $num; ?>" value="4" disabled="disabled" onclick="insert_as_clicked(<?php echo $num; ?>)" id="test_<?php echo $rand; ?>" /> <label for="test_<?php echo $rand; ?>"><?php _e( 'Link that opens in the same window', 'insert-or-embed-articulate-content-into-wordpress' ); ?> (<?php _e( 'Paid Feature', 'insert-or-embed-articulate-content-into-wordpress' ); ?>)</label><br />
<br />								  
<div>
<button type="button" class="waves-effect waves-light btn" name="insert_<?php echo $num; ?>" id="insert_<?php echo $num; ?>"  onclick="add_to_post(<?php echo $num; ?>)"><?php _e( 'Insert Into Post', 'insert-or-embed-articulate-content-into-wordpress' ); ?></button> &nbsp;&nbsp;&nbsp;&nbsp;
<span id="delete_<?php echo $num; ?>" onclick="delete_dir(<?php echo $num; ?>)" /><i class="material-icons pointercur">delete</i></span> &nbsp; &nbsp;
<span id="insert_msg_<?php echo $num; ?>"></span>
<p/>
<iframe src="https://www.elearningfreak.com/wordpresspluginlatesttrial500.html?v=43000000024&editor=classic" width="600px" title="Upgrade to the premium plugin"></iframe>
</div>		
</div>
	<?php
}//end print_detail_form()
function printInsertForm() {
	wp_enqueue_style( 'materialize-css', WP_QUIZ_EMBEDER_PLUGIN_URL . 'css/materialize.css' );
	wp_enqueue_script( 'materializejs', WP_QUIZ_EMBEDER_PLUGIN_URL . 'js/materialize.js' );
	// echo "<h3>Start printInsertForm</h3>";
	$dirs = getDirs();
	if ( count( $dirs ) > 0 ) {
		print_js( 'quiz' );
		?>
<title><?php _e( 'Media Library', 'insert-or-embed-articulate-content-into-wordpress' ); ?></title>
		<?php
		$uploadDirUrl = getUploadsUrl();
		// START PAGIGNATION
		?>
<div style="text-align:right; padding:5px; padding-right:10px; margin:5px 20px;"> 
		<?php $bound = print_page_navi( count( $dirs ) ); // print the pagignation and return upper and lower bound ?>
</div>
		<?php
		$lower_bound = $bound[0];
		$upper_bound = $bound[1];
		echo '<div style="text-align:right; margin:5px 20px;padding-right:10px;">' . __( 'Showing Content', 'insert-or-embed-articulate-content-into-wordpress' ) . ' ' . $lower_bound . ' - ' . $upper_bound . ' of ' . count( $dirs );
		echo '</div>';
		// $dirs = array_slice($dirs, $lower_bound, $upper_bound);
		$dirs = array_slice( $dirs, $lower_bound, 15 );
		// END PAGIGNATION
		echo '
<ul class="collection with-header">
<li class="collection-header linowrap"><h4>' . __( 'Content', 'insert-or-embed-articulate-content-into-wordpress' ) . '</h4></li>
';
		foreach ( $dirs as $i => $dir ) :
			extract( $dir );
			$dir1 = str_replace( '_', ' ', $dir );
			echo '<li class="collection-header linowrap" id="content_item_' . $i . '">
<div>';
			echo $dir1;
			echo '<span style="float:right">';
			// echo '<span id="show_button_'.$i.'" flag="1" onclick="show_hide_detail( '.$i.' )" style="text-decoration:underline; color:#000099; cursor:pointer;">Show</span> | ';
			// echo '<span id="delete_button_'.$i.'"  onclick="delete_dir( '.$i.' )" style="text-decoration:underline; color:#990000; cursor:pointer;">Delete</span>';
			echo '<a onclick="delete_dir( ' . $i . ' )"  class="secondary-content pointercur"><i class="material-icons">delete</i></a>&nbsp;&nbsp;';
			echo '<a onclick="show_hide_detail( ' . $i . ' )" id="show_button_' . $i . '" flag="1" class="secondary-content pointercur"><i class="material-icons">visibility</i></a>';
			echo '<span id="loading_box_' . $i . '"></span>';
			echo '</span>';
			echo '</div>';
			print_detail_form( $i, 'quiz', $uploadDirUrl . $dir . '/' . $file, $dir );
			echo '
</li>';
	endforeach;
		echo '</ul>';
	} else {
		echo __( 'No directories available', 'insert-or-embed-articulate-content-into-wordpress' );
	}
	// echo "<h3>End printInsertForm</h3>";
}
function getUploadsPath() {
	 $dir = wp_upload_dir();
	return ( $dir['basedir'] . '/' . WP_QUIZ_EMBEDER_UPLOADS_DIR_NAME . '/' );
}
function getPluginUrl() {
	// return WP_PLUGIN_URL."/insert-or-embed-articulate-content-into-wordpress/"; #oneTarek says: This line is wrong because you are unable to rename the plugin directory
	return plugin_dir_url( __FILE__ ); // chaned by oneTarek # The URL of the directory that contains the plugin, including a trailing slash ("/")
}
function articulate_link_relative( $link ) {

	 return preg_replace( '|^(https?:)?//[^/]+(/.*)|i', '$2', $link );
}


function getUploadsUrl() {
	$dir = wp_upload_dir();
	return articulate_link_relative( $dir['baseurl'] . '/' . WP_QUIZ_EMBEDER_UPLOADS_DIR_NAME . '/' );
}

function getDirs() {
	$upload_dir = quiz_embeder_create_upload_dir();

	$myDirectory = opendir( $upload_dir );
	$dirArray    = array();
	$i           = 0;
	// get each entry
	while ( $entryName = readdir( $myDirectory ) ) {
		if ( $entryName != '.' && $entryName != '..' && is_dir( $upload_dir . $entryName ) ) :
			$dirArray[ $i ]['dir'] = $entryName;
			// store the filenames - need to iterate to get story.html or player.html
			$dirArray[ $i ]['file'] = getFile( $upload_dir . $entryName );
			$dirArray[ $i ]['path'] = getUploadsUrl();
			$i++;
	endif;
	}

	// close directory
	closedir( $myDirectory );
	global $quiz_dir_count;
	$quiz_dir_count = count( $dirArray );
	return $dirArray;
}
function print_js( $tab = 'articulate-upload' ) {
	// added by oneTarek
	wp_enqueue_script( 'jquery' );
	?>
<script>
var  ARTICULATE_DEL_DIR_NOCE = "<?php echo wp_create_nonce( 'articulate_del_dir' ); ?>";
var  ARTICULATE_RENAME_DIR_NONCE = "<?php echo wp_create_nonce( 'articulate_rename_dir' ); ?>";
var articulatejq = jQuery.noConflict();										 
articulatejq(document).ready(function() { 
articulatejq("#media_loading").hide();
}); // end articulatejq(document).ready()
function show_detail(number)
{
articulatejq("#upload_detail_"+number+"").show('slow');
}
function show_hide_detail(number)
{
var flag=articulatejq("#show_button_"+number+"").attr("flag");
if(flag==="1")
{
articulatejq("#show_button_"+number+"").attr("flag", "2");
articulatejq("#show_button_"+number+"").html("<i class=\"material-icons\">launch</i>");
articulatejq("#upload_detail_"+number+"").show('slow');
}
else
{
articulatejq("#show_button_"+number+"").attr("flag", "1");
articulatejq("#show_button_"+number+"").html("<i class=\"material-icons\">visibility</i>");
articulatejq("#upload_detail_"+number+"").hide('slow');
}
}
function show_box(box, number)
{
articulatejq("#"+box+"_"+number+"").show('slow');
}
function hide_box(box, number)
{
articulatejq("#"+box+"_"+number+"").hide();
}
function insert_as_clicked(number)
{
var insert_as= parseInt(articulatejq('input[name=insert_as_'+number+']:checked').val());
switch(insert_as)
{
case 1:
{
hide_box("lightbox_option_box", number);
hide_box("new_window_option_box", number);
hide_box("same_window_option_box", number);										
break;
}
case 2:
{
show_box("lightbox_option_box", number);
hide_box("new_window_option_box", number);
hide_box("same_window_option_box", number);
break;
}
case 3:
{
hide_box("lightbox_option_box", number);
show_box("new_window_option_box", number);
hide_box("same_window_option_box", number);
break;
}
case 4:
{
hide_box("lightbox_option_box", number);
hide_box("new_window_option_box", number);
show_box("same_window_option_box", number);
break;
}	  
}// end switch
}
function lightbox_option_clicked(number)
{
var lightbox_option= parseInt(articulatejq('input[name=lightbox_option_'+number+']:checked').val());
switch(lightbox_option)
{
case 1:
{
show_box("lightbox_title", number);
break;
}
case 2:
{
hide_box("lightbox_title", number);
break;
}
}
}
function more_lightbox_option_clicked(number)
{
var more_lightbox_option= parseInt(articulatejq('input[name=more_lightbox_option_'+number+']:checked').val());
switch(more_lightbox_option)
{
case 1:
{
show_box("lightbox_link_text", number);
hide_box("custom_button_area", number);
break;
}
case 2:
{
hide_box("lightbox_link_text", number);
hide_box("custom_button_area", number);
break;
}
case 3:
{
hide_box("lightbox_link_text", number);
show_box("custom_button_area", number);
break;
}	  
}
}
function show_button(number)
{
var btn_src=articulatejq("#buttons_"+number).val();
if(btn_src==='0')
articulatejq("#button_view_"+number).html('');
else
articulatejq("#button_view_"+number).html('<img src="'+btn_src+'" />');
}
function open_new_window_option_clicked(number)
{
var open_new_window_option= parseInt(articulatejq('input[name=open_new_window_option_'+number+']:checked').val());
switch(open_new_window_option)
{
case 1:
{
show_box("open_new_window_link_text", number);
break;
}
case 2:
{
hide_box("open_new_window_link_text", number);
break;
}
}
}
function open_same_window_option_clicked(number)
{
var open_same_window_option= parseInt(articulatejq('input[name=open_same_window_option_'+number+']:checked').val());
switch(open_same_window_option)
{
case 1:
{
show_box("open_same_window_link_text", number);
break;
}
case 2:
{
hide_box("open_same_window_link_text", number);
break;
}
}
}
function show_hide_custom_size_area(number)
{
var size_opt=articulatejq("#size_opt_"+number).val();
if(size_opt==="custom")
{
articulatejq("#custom_size_area_"+number).show();
}
else
{
jarticulatejq("#custom_size_area_"+number).hide();
}
}
function add_to_post(number)
{
	<?php if ( $tab == 'articulate-upload' ) { ?>
//rename action will fired.
var old_name=articulatejq("#dir_name_1").val();
var regex = new RegExp('_', 'g');
var temp=old_name.replace(regex," ");
var new_name=articulatejq.trim(articulatejq("#title").val());
if(new_name!=="" && new_name!==temp)
{
rename_dir(old_name, new_name);
}
else
{
insert_into_post(number);
}
		<?php
	} else {
		?>
		insert_into_post(number);<?php } ?>
}
function insert_into_post(number)
{
	<?php $opt = get_quiz_embeder_options(); ?>
var lightbox_script="<?php echo $opt['lightbox_script']; ?>";
var link_text='';															 
var uploaded_file_url=articulatejq("#file_url_"+number+"").val();
if(uploaded_file_url===""){alert("Please Upload A Zip File"); return;}
var win = window.dialogArguments || opener || parent || top; 
var insert_as= parseInt(articulatejq('input[name=insert_as_'+number+']:checked').val());
var restrict_access_option=parseInt(articulatejq("input[name=restrict_access_option_"+number+"]:checked").val());
var shortCode;
var shortCodeType="";
var shortCodeOptions="";
switch(insert_as)
{
case 1:
{
shortCodeType="iframe";
shortCodeOptions=" width='100%' height='600px' frameborder='0' scrolling='no' src='"+uploaded_file_url+"'";
break;
}  
case 2:
{
shortCodeType="lightbox";
shortCodeOptions=" href='"+uploaded_file_url+"'";
var more_lightbox_option= parseInt(articulatejq('input[name=more_lightbox_option_'+number+']:checked').val());
if(more_lightbox_option==1)
{
link_text=articulatejq('#lightbox_link_text_'+number+'').val();
shortCodeOptions=shortCodeOptions+" link_text='"+link_text+"'";
}
else if(more_lightbox_option===3)
{
var btn_src=articulatejq("#buttons_"+number).val();
if(btn_src !=='0')
shortCodeOptions=shortCodeOptions+" button='"+btn_src+"'";
}
var lightbox_option= parseInt(articulatejq('input[name=lightbox_option_'+number+']:checked').val());
if(lightbox_option===1)
{
var lightbox_title= articulatejq('#lightbox_title_'+number+'').val();
shortCodeOptions=shortCodeOptions+" title='"+lightbox_title+"'";
}
//MORE NEW SETTINGS
if(lightbox_script==="colorbox")
{
var colorbox_theme=articulatejq("#colorbox_theme_"+number).val();
if(colorbox_theme !=="default_from_dashboard")
shortCodeOptions=shortCodeOptions+" colorbox_theme='"+colorbox_theme+"'";
}
//SCROLLBAR OPTIONS		
var scrollbar= articulatejq('input[name=scrollbar_'+number+']:checked').val();
if(scrollbar==='no'){shortCodeOptions=shortCodeOptions+" scrollbar='no'";}
//SIZE OPTIONS
var size_opt=articulatejq("#size_opt_"+number).val();
shortCodeOptions=shortCodeOptions+" size_opt='"+size_opt+"'";
if(size_opt==="custom")
{
var w=parseInt(articulatejq("#width_"+number).val());
var wt=articulatejq("#width_type_"+number).val();
var h=parseInt(articulatejq("#height_"+number).val());
var ht=articulatejq("#height_type_"+number).val();
var width=""+w+wt; var height=""+h+ht;
shortCodeOptions=shortCodeOptions+" width='"+width+"'";
shortCodeOptions=shortCodeOptions+" height='"+height+"'";
}
break;
}
case 3:
{
shortCodeType="open_link_in_new_window";
shortCodeOptions=" href='"+uploaded_file_url+"'";
var open_new_window_option= parseInt(articulatejq('input[name=open_new_window_option_'+number+']:checked').val());
if(open_new_window_option===1)
{
link_text=articulatejq('#open_new_window_link_text_'+number+'').val();
shortCodeOptions=shortCodeOptions+" link_text='"+link_text+"'";
}
break;
}
case 4:
{
shortCodeType="open_link_in_same_window";
shortCodeOptions=" href='"+uploaded_file_url+"'";
var open_same_window_link_text= parseInt(articulatejq('input[name=open_same_window_option_'+number+']:checked').val());
//var link_text="";
if(open_same_window_link_text===1)
{
link_text=articulatejq('#open_same_window_link_text_'+number+'').val();
shortCodeOptions=shortCodeOptions+" link_text='"+link_text+"'";
}
break;
}	   
}
shortCode="[iframe_loader type='"+shortCodeType+"' " +shortCodeOptions+"]";
win.send_to_editor(shortCode);
}// end insert_into_post()
function rename_dir(old_name, new_name)
{
var translatedText = <?php echo json_encode( array( 'saving' => __( 'Saving', 'insert-or-embed-articulate-content-into-wordpress' ) ) ); ?>;
var loading_text='<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>loading_16x16.gif" alt="Loading" /> '+translatedText.saving+'....';
articulatejq('#insert_msg_1').html(loading_text);	
jQuery.getJSON("<?PHP bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php?action=rename_dir&_ajax_nonce="+ARTICULATE_RENAME_DIR_NONCE+"&dir_name="+old_name+"&title="+new_name, function(data) {
if(data[0]==="success")
{
var new_renamed_dir_name=data[1];
var old_file_name = articulatejq('#file_name_1').val();
articulatejq('#file_url_1').val("<?php echo getUploadsUrl(); ?>"+new_renamed_dir_name+"/"+old_file_name);	
articulatejq('#insert_msg_1').html("");
insert_into_post(1);
}
else
{
articulatejq('#insert_msg_1').html("");
alert(data[1])
}
});
}
function delete_dir(number)
{
var translatedText = 
	<?php
	echo json_encode(
		array(
			'deleting'      => __( 'Deleting', 'insert-or-embed-articulate-content-into-wordpress' ),
			'no_data_found' => __( 'No Data Found To Delete', 'insert-or-embed-articulate-content-into-wordpress' ),
			'are_you_sure'  => __(
				'Are you sure?',
				'insert-or-embed-articulate-content-into-wordpress'
			),
		)
	);
	?>
						;
var dir_name=articulatejq("#dir_name_"+number+"").val();
var loading_image='&nbsp;&nbsp;<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>loading_16x16.gif" alt="Launch Presentation" />&nbsp;'+translatedText.deleting+'..'
var loading_text='<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>loading_16x16.gif" alt="Loading" /> '+translatedText.deleting+'....';
if(dir_name!=="")
{			
if (confirm(translatedText.are_you_sure))
{
articulatejq("#delete_button_"+number+"").hide();
articulatejq("#loading_box_"+number+"").html(loading_image);
articulatejq("#insert_msg_"+number+"").html(loading_text);
jQuery.post("admin-ajax.php",{dir : dir_name,action:'del_dir', _ajax_nonce : ARTICULATE_DEL_DIR_NOCE },function(data){
	<?php if ( $tab == 'articulate-upload' ) { ?>
articulatejq("#insert_msg_"+number+"").html("");
articulatejq("#upload_detail_"+number+"").remove();
location.reload();
<?php } else { ?>
articulatejq("#content_item_"+number+"").remove();
<?php } ?>
});
}
}else{alert(translatedText.no_data_found);}
}// end delete_dir()
</script>
	<?php
}//end print_js()

/*
If wp max upload size < 1024kb, use 100kb chunking
If wp max upload size is less than 20mb, use 1mb chunking
If wp max upload size is greater than 20mb, use 2mb chunking
If wp max upload size is greater than 50mb, use 3mb chunking
If wp max upload size is greater than 80mb, use 4mb chunking
If wp max upload size is greater than 100mb, use 5mb chunking
*/

function articulate_get_upload_chunk_size() {

	$max_upload_size = wp_max_upload_size();
	$chunk_size      = '512kb';
	if ( $max_upload_size <= 1024 * KB_IN_BYTES ) {
		$chunk_size = '200kb';
	} elseif ( $max_upload_size <= 20 * MB_IN_BYTES ) {
		$chunk_size = '2mb';
	} elseif ( $max_upload_size <= 50 * MB_IN_BYTES ) {
		$chunk_size = '4mb';
	} elseif ( $max_upload_size <= 80 * MB_IN_BYTES ) {
		$chunk_size = '6mb';
	} elseif ( $max_upload_size <= 100 * MB_IN_BYTES ) {
		$chunk_size = '8mb';
	} elseif ( $max_upload_size > 100 * MB_IN_BYTES ) {
		$chunk_size = '10mb';
	}
	return $chunk_size;
}

function print_upload() {
	// echo "<h3>Start print_upload</h3>";
	wp_enqueue_style( 'material-icons', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEICONS, array(), PLUGINVERSION, 'all' );
	print_js();
	// following codes SOURCE wp-admin/includes/media.php
	$chunk_size = articulate_get_upload_chunk_size();
	$dirs       = getDirs();
	?>
<form enctype="multipart/form-data" id="myForm1" action="admin-ajax.php" method="POST">
<div id="container">
<a id="pickfiles" href="javascript:;" class="waves-effect waves-light btn grey">Choose your zip file</a>
<a id="uploadfiles" href="javascript:;" class="waves-effect waves-light btn"><i class="material-icons left">call_made</i> Upload!</a>
</div>
<div id="filelist"><?php _e( "Your browser doesn't have Flash, Silverlight or HTML5 support.", 'insert-or-embed-articulate-content-into-wordpress' ); ?></div>
<div id="fileerror"></div>
<br />
<div id="console"></div>
</form>
<script type="text/javascript">
// Custom example logic


var art_uploader = new plupload.Uploader({
runtimes : 'html5',
url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
chunk_size: '<?php echo $chunk_size; ?>',
max_retries : 10,
multi_selection: false,
'file_data_name':  'async-upload' ,
multipart_params : {
"_ajax_nonce" : "<?php echo wp_create_nonce( 'articulate_upload_file' ); ?>",
"action" : "articulate_upload_file"
},
browse_button : 'pickfiles', // you can pass in id...
container: document.getElementById('container'), // ... or DOM Element itself
filters : {
max_file_size: '0',
mime_types: [
{title : "Zip files", extensions : "zip"}
]
},
// Flash settings
flash_swf_url : '<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>js/plupload/js/Moxie.swf',
// Silverlight settings
silverlight_xap_url : '<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>js/plupload/js/Moxie.xap',
init: {
PostInit: function() {
document.getElementById('filelist').innerHTML = '';
document.getElementById('uploadfiles').onclick = function() {
art_uploader.start();
return false;
};
},
FilesAdded: function(up, files) {
plupload.each(files, function(file) {
});
},
UploadProgress: function(up, file) {
						if( up.total.percent == 100 ) {
							document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>File uploaded. Unzipping content.</span>';
					
						} else {
							document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + up.total.percent + "%</span>";}
					},
Error: function(up, err) {
	var Msg = "";
	if( err.code == 200 ) {
		Msg = "\nError #" + err.code + ": " + err.message +' Upload failed. Please contact support at <a target="_blank" href="https://www.elearningfreak.com/contact-us/">https://www.elearningfreak.com/contact-us/</a>';
	} else {
		Msg = "\nError #" + err.code + ": " + err.message +' Upload failed. Please contact support at <a target="_blank" href="https://www.elearningfreak.com/contact-us/">https://www.elearningfreak.com/contact-us/</a>';
	}
	document.getElementById('console').innerHTML += Msg;
}
}
});
art_uploader.init();
articulatejq(function($) { 


if(window.navigator.userAgent.indexOf("Edge") > -1){
articulatejq('#pickfiles').removeClass('waves-effect');
articulatejq('#uploadfiles').removeClass('waves-effect');
}
if(window.navigator.userAgent.indexOf("Vivaldi") > -1){
	articulatejq('#pickfiles').removeClass('waves-effect');
}

art_uploader.bind('FilesAdded', function(up, files) {
articulatejq("#filelist").show();
articulatejq("#filelist").removeClass('uploaded_file');
if (art_uploader.files.length > 1) {
art_uploader.removeFile(art_uploader.files[0]);
}
$.each(files, function(i, file) {
articulatejq('#filelist').html(
'<div id="' + file.id + '">' +
file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
'</div>');
});
up.refresh(); // Reposition Flash/Silverlight
});
art_uploader.bind('FileUploaded', function(upldr, file, object) {
var upload_response = jQuery.parseJSON(object.response);
if(upload_response.OK === 1){
articulatejq("#filelist").addClass('uploaded_file');
articulatejq("#filelist").append(' <span>'+upload_response.info+'</span>');
dir = upload_response.path;
var uploaded_dir_neme=upload_response.folder;
var win = window.dialogArguments || opener || parent || top; 
articulatejq("#file_url_1").val(dir);
articulatejq("#dir_name_1").val(uploaded_dir_neme);
articulatejq("#file_name_1").val(upload_response.name.file_name);
var regex = new RegExp('_', 'g');
articulatejq("#title").val(uploaded_dir_neme.replace(regex," "));
show_detail(1);
}else{
articulatejq('#fileerror').show();
articulatejq('#fileerror').html(upload_response.info);	
}
});
});









</script>
	<?php _e( 'Please choose a .zip file that you published with the software', 'insert-or-embed-articulate-content-into-wordpress' ); ?><br />
	<?php
	print_detail_form( 1 );
	?>
<p class="flow-text"><?php _e( 'Need help?  See the screencast below', 'insert-or-embed-articulate-content-into-wordpress' ); ?>:</p>
<iframe src="https://www.youtube.com/embed/exojBaymRkw" width="600" height="338" title="New Feature: xAPI Support"></iframe>
<p/>
	<?php
	// echo "<h3>END print_upload</h3>";
}
// handle uploaded file here
add_action( 'wp_ajax_articulate_upload_file', 'articulate_upload_ajax_file' );
add_action( 'wp_ajax_articulate_get_dir_data', 'articulate_ajax_get_dir_data' );

/**
 * Handle file uploading via AJAX
 */

function articulate_upload_ajax_file() {
	articulate_setup_php_ini();
	check_ajax_referer( 'articulate_upload_file' );

	if ( ! is_user_logged_in() || ! current_user_can( 'upload_files' ) || ! current_user_can( 'unfiltered_html' ) ) {
		die(
			json_encode(
				array(
					'OK'   => 0,
					'info' => __( 'You do not have permissions to upload this file. Contact your admin to get the unfiltered_html capability.', 'insert-or-embed-articulate-content-into-wordpress' ),
				)
			)
		);
	}

	$count_dirs = getDirs();
	// you can use WP's wp_handle_upload() function:
	$file = $_FILES['async-upload'];
	$dir  = untrailingslashit( getUploadsPath() );

	if ( empty( $_FILES ) || $_FILES['async-upload']['error'] ) {
		die(
			json_encode(
				array(
					'OK'   => 0,
					'info' => __(
						'Failed to move uploaded file.  Please check if the folder has write permissions.',
						'insert-or-embed-articulate-content-into-wordpress'
					),
				)
			)
		);
	}
	$chunk    = isset( $_REQUEST['chunk'] ) ? intval( $_REQUEST['chunk'] ) : 0;
	$chunks   = isset( $_REQUEST['chunks'] ) ? intval( $_REQUEST['chunks'] ) : 0;
	$fileName = isset( $_REQUEST['name'] ) ? sanitize_file_name( $_REQUEST['name'] ) : sanitize_file_name( $_FILES['async-upload']['name'] );
	$filePath = '' . $dir . '/' . sanitize_file_name( $fileName ) . '';
	// Open temp file
	$out = @fopen( "{$filePath}.part", $chunk == 0 ? 'wb' : 'ab' );
	if ( $out ) {
		// Read binary input stream and append it to temp file
		$in = @fopen( $_FILES['async-upload']['tmp_name'], 'rb' );
		if ( $in ) {
			while ( $buff = fread( $in, 4096 ) ) {
				fwrite( $out, $buff );
			}
		} else {
			die(
				json_encode(
					array(
						'OK'   => 0,
						'info' => __(
							'Failed to open input stream. Please check if the folder has write permissions',
							'insert-or-embed-articulate-content-into-wordpress'
						),
					)
				)
			);
		}
		@fclose( $in );
		@fclose( $out );
		@unlink( $_FILES['async-upload']['tmp_name'] );
	} else {
		die(
			json_encode(
				array(
					'OK'   => 0,
					'info' => __(
						'Failed to open output stream.  Please check if the folder has write permissions',
						'insert-or-embed-articulate-content-into-wordpress'
					),
				)
			)
		);
	}

	// Security check.
	$mime  = wp_check_filetype( $filePath );
	$mimes = get_allowed_mime_types( get_current_user_id() );
	if ( ! isset( $mime['type'] ) || ! in_array( $mime['type'], array_values( $mimes ) ) ) {
		die(
			json_encode(
				array(
					'OK'   => 0,
					'info' => __(
						'Failed to upload this file for security reasons. Contact your admin to ensure your user can access all mime types from get_allowed_mime_types.',
						'insert-or-embed-articulate-content-into-wordpress'
					),
				)
			)
		);
	}

	// Check if file has been uploaded
	if ( ! $chunks || $chunk == $chunks - 1 ) {
		// Strip the temp .part suffix off
		articulate_custom_rename( "{$filePath}.part", $filePath );
		// start extracting
		// unzip file
		$dir    = explode( '.', $fileName );
		$dir[0] = str_replace( ' ', '_', $dir[0] );
		$target = getUploadsPath() . $dir[0];
		$file   = $filePath;
		while ( file_exists( $target ) ) {
			$r       = rand( 1, 10 );
			$target .= $r;
			$dir[0] .= $r;
		}
		$arr = extractZip( $file, $target, $dir[0] );
		unlink( $filePath );
		do_action( 'iea/uploaded_quiz', $arr, $target );
		$ok       = isset( $arr[4] ) ? $arr[4] : 0;
		$response = array(
			'OK'     => $ok,
			'info'   => $arr[0],
			'folder' => $arr[2],
			'path'   => $arr[1],
			'name'   => $arr[3],
			'target' => $target,
		);
		die( json_encode( $response ) );
	} else {
		die(
			json_encode(
				array(
					'OK'   => 1,
					'info' => __(
						'Uploading chunks!',
						'insert-or-embed-articulate-content-into-wordpress'
					),
				)
			)
		);
	}
	exit;
};

function articulate_ajax_get_dir_data() {
	$response             = array();
	$response['status']   = 'success';
	$response['message']  = __( 'Success', 'quiz' );
	$response['dir_list'] = getDirs();
	wp_send_json( $response );
}

function wp_ajax_del_dir() {
	$response = array();
	check_ajax_referer( 'articulate_del_dir' );
	if ( ! is_user_logged_in() || ! current_user_can( 'upload_files' ) ) {
		$response['status']  = 'fail';
		$response['message'] = __( 'Authentication failed', 'quiz' );
		wp_send_json( $response );
	}

	$dirname = sanitize_file_name( $_POST['dir'] );
	if ( $dirname != '' ) {
		$dir = getUploadsPath() . $dirname;
		articulate_rrmdir( $dir );
	}

	$response['status']  = 'success';
	$response['message'] = __( 'Success', 'quiz' );
	$add_dir_list        = ( isset( $_POST['return_dir_list'] ) && intval( $_POST['return_dir_list'] ) == 1 ) ? true : false;
	if ( $add_dir_list ) {
		$response['dir_list'] = getDirs();
	}
	wp_send_json( $response );
}

function wp_ajax_rename_dir() {
	 check_ajax_referer( 'articulate_rename_dir' );
	if ( ! is_user_logged_in() || ! current_user_can( 'upload_files' ) ) {
		die();
	}
	$dir_name = ( isset( $_REQUEST['dir_name'] ) ) ? $_REQUEST['dir_name'] : '';
	$dir_name = sanitize_file_name( $dir_name );

	$title = ( isset( $_REQUEST['title'] ) ) ? $_REQUEST['title'] : '';
	$title = sanitize_file_name( $title );

	$arr = array();
	if ( $dir_name != '' ) {
		$target = getUploadsPath() . $dir_name;
		if ( file_exists( $target ) ) {
			if ( $title ) {
				$title    = str_replace( ' ', '_', $title );
				$new_file = getUploadsPath() . $title;
				while ( file_exists( $new_file ) ) {
					$r         = rand( 1, 10 );
					$new_file .= $r;
					$title    .= $r;
				}
				articulate_custom_rename( $target, $new_file );
				$arr[0] = 'success';
				$arr[1] = $title;
			} else {
				$arr[0] = 'error';
				$arr[1] = __( 'Failed: New Title Was Not Given', 'insert-or-embed-articulate-content-into-wordpress' );
			}
		} else {
			$arr[0] = 'error';
			$arr[1] = __( 'Failed: Given File is Not Exits', 'insert-or-embed-articulate-content-into-wordpress' );
		}
	} else {
		$arr[0] = 'error';
		$arr[1] = __( 'Failed: Targeted Directory Name Was Not Given', 'insert-or-embed-articulate-content-into-wordpress' );
	}
	echo json_encode( $arr );
	die();
}

// check if quiz is in a folder as many times as it takes    @anthonysbrown
function wp_ajax_quiz_check_folder( $dir ) {
	$arr = preg_grep( '/_macosx/i', scandir( $dir ), PREG_GREP_INVERT );
	foreach ( $arr as $key => $folder ) {
		if ( $folder != '.' && $folder != '..' ) {
			$structure[] = $folder;
		}
	}
	// Directory can contain only a single file( not another directory ) for mp4 type uploads.
	// So must check that is directory or not to avoid infinity loop of this recursive function.
	if ( isset( $structure ) && is_array( $structure ) && count( $structure ) == 1 && is_dir( $dir . '/' . $structure[0] ) ) {
		$sub_folder = $dir . '/' . $structure[0] . '/';
		articulate_custom_rename( $dir, $dir . '_temp' );
		articulate_custom_rename( $dir . '_temp/' . $structure[0] . '/', $dir );
		rmdir( $dir . '_temp' );
		wp_ajax_quiz_check_folder( $dir );
	}
}

function articulate_has_php_file( $dir ) {
    $dir = rtrim( $dir, '/' );
    if ( is_dir( $dir ) ) {

        $dir_handle = opendir( $dir );
        if ( $dir_handle ) {
            while ( $file = readdir( $dir_handle ) ) {
                if ( $file != '.' && $file != '..' ) {
                    if ( !is_dir( $dir . '/' . $file ) &&
                        (strpos($file, '.phtml') !== false ||
                         strpos($file, '.php') !== false && $file != 'relay.php' ||
                         strpos($file, '.phar') !== false) ) { // Added condition for .phar files
                        return true;
                    } else {
                        $found = articulate_has_php_file( $dir . '/' . $file );
                        if ( $found ) {
                            return true;
                        }
                    }
                }
            }
            closedir( $dir_handle );
        }

        return false;
    }
    return false;
}

function articulate_run_admin_memory_limit_hook( $limit ) {
	$limit = apply_filters( 'articulate_admin_memory_limit', $limit );
	return $limit;
}
// use WordPress unzip function instead
function extractZip( $fileName, $target, $dir ) {
	add_filter( 'admin_memory_limit', 'articulate_run_admin_memory_limit_hook', 100, 1 );
	// admin_memory_limit hook is called in wp_raise_memory_limit function that is called in unzip_file function.
	$arr      = array();
	$unzipper = new Quiz_Unzip( true );
	$unzip    = $unzipper->unzip_file( $fileName, $target );

	if ( $unzip ) {
		wp_ajax_quiz_check_folder( $target );
		if ( articulate_has_php_file( $target ) ) {
			$arr[0] = '<span style="color:red">' . sprintf( __( 'ZIP upload successful, but we found a PHP file that is not allowed in your content directory. Contact support at %s', 'insert-or-embed-articulate-content-into-wordpress' ), '<a style="color: black" target="_blank" href="https://www.elearningfreak.com/upload-file/">www.elearningfreak.com</a>' ) . '</span>';
			articulate_rrmdir( $target );
			$arr[4] = 0;// OK = 0
			$arr[1] = '';
			$arr[2] = $dir;
			$arr[3] = '';
		} else {

			$file   = getFile( $target, true );// true to get return value as an array of detail
			$arr[0] = 'Upload Complete!';
			if ( $file['status'] == 'valid_html_file_found' || $file['status'] == 'index_html_file_found' || $file['status'] == 'other_html_file_found' ) {
				$arr[0] = __( 'Upload Complete!', 'insert-or-embed-articulate-content-into-wordpress' );
				$arr[4] = 1;// OK = 1
			} elseif ( $file['status'] == 'no_html_file_found' ) {
				$arr[0] = '<span style="color:black">' . sprintf( __( 'ZIP upload successful, but we were unable to find an HTML file. Either increase your WP_MEMORY_LIMIT, define your FS_METHOD as DIRECT, or contact support at %s', 'insert-or-embed-articulate-content-into-wordpress' ), '<a style="color: black" target="_blank" href="https://www.elearningfreak.com/upload-file/">www.elearningfreak.com</a>' ) . '</span>';
				articulate_rrmdir( $target );
				$arr[4] = 0;// OK = 0
			}

			$arr[1] = getUploadsUrl() . $dir . '/' . $file['file_name'];
			$arr[2] = $dir;
			$arr[3] = $file;
		}
	} else {
		$arr[0] = __( 'File upload failed', 'insert-or-embed-articulate-content-into-wordpress' );
		$arr[4] = 0;// OK = 0
	}
	return $arr;
}
function articulate_rrmdir( $dir ) {
	if ( is_dir( $dir ) ) {
		$objects = scandir( $dir );
		foreach ( $objects as $object ) {
			if ( $object != '.' && $object != '..' ) {
				if ( filetype( $dir . '/' . $object ) == 'dir' ) {
					articulate_rrmdir( $dir . '/' . $object );
				} else {
					unlink( $dir . '/' . $object );
				}
			}
		}
		reset( $objects );
		rmdir( $dir );
	}
}
function quiz_embeder_admin_scripts() {
	if ( isset( $_GET['page'] ) && ( $_GET['page'] == 'articulate_content' || $_GET['page'] == 'articulate-settings-button' ) ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_register_script( 'quiz_embeder_upload', WP_QUIZ_EMBEDER_PLUGIN_URL . ADMINJS );
		wp_enqueue_script( 'quiz_embeder_upload' );
		wp_enqueue_style( 'materialize-css', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZE_CSS );
		wp_enqueue_script( 'materializejs', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEJS );
		wp_enqueue_script( 'jshelpers', WP_QUIZ_EMBEDER_PLUGIN_URL . JSHELPERS );
	}
}
add_action( 'admin_enqueue_scripts', 'quiz_embeder_admin_scripts' );

function quiz_embeder_create_upload_dir() {
	$upload_path = getUploadsPath();
	$dir         = untrailingslashit( $upload_path );
	if ( ! is_dir( $dir ) ) {
		mkdir( $dir, 0777 );
	}
	return $upload_path;
}

function quiz_embeder_is_apache_2_4_or_grater() {
	$words   = explode( ' ', strtolower( $_SERVER['SERVER_SOFTWARE'] ) );
	$version = '';
	foreach ( $words as $word ) {
		if ( strpos( $word, 'apache/' ) !== false ) {
			$parts   = explode( '/', $word );
			$version = $parts[1];
			break;
		}
	}

	if ( $version != '' ) {
		return version_compare( $version, '2.4', '>=' );
	} else {
		return false;
	}
}

function quiz_embeder_get_htaccess_rules() {
	$rules = "#allow articulate uploads\n";
	if ( quiz_embeder_is_apache_2_4_or_grater() ) {
		$rules     .= "<Files ~ \"...\">\n";
			$rules .= "Require all granted\n";
		$rules     .= "</Files>\n";
		$rules     .= "<Files *.php>\n";
			$rules .= "Require all denied\n";
		$rules     .= "</Files>\n";
	} else {
		$rules     .= "<Files ~ \"...\">\n";
			$rules .= "Order Allow,Deny\n";
			$rules .= "Allow from all\n";
		$rules     .= "</Files>\n";
		$rules     .= "<Files *.php>\n";
			$rules .= "Order allow,deny\n";
			$rules .= "Deny from all\n";
		$rules     .= "</Files>\n";
	}
	return $rules;
}

function quiz_embeder_create_protection_files( $force = false ) {
	if ( false === get_transient( 'quiz_embeder_create_protection_files' ) || $force ) {
		$upload_path = quiz_embeder_create_upload_dir();
		// Top level .htaccess file
		$rules = quiz_embeder_get_htaccess_rules();
		if ( file_exists( $upload_path . '.htaccess' ) ) {
			$contents = @file_get_contents( $upload_path . '.htaccess' );
			if ( $contents !== $rules || ! $contents ) {
				// Update the .htaccess rules if they don't match
				@file_put_contents( $upload_path . '.htaccess', $rules );
			}
		} elseif ( wp_is_writable( $upload_path ) ) {
			// Create the file if it doesn't exist
			@file_put_contents( $upload_path . '.htaccess', $rules );
		}
		// Top level blank index.php
		if ( ! file_exists( $upload_path . 'index.php' ) && wp_is_writable( $upload_path ) ) {
			@file_put_contents( $upload_path . 'index.php', '<?php' . PHP_EOL . '// Silence is golden.' );
		}
		// Check for the files once per day
		set_transient( 'quiz_embeder_create_protection_files', true, 3600 * 24 );
	}
}
add_action( 'admin_init', 'quiz_embeder_create_protection_files' );

function quiz_embeder_wp_footer() {
	?>

	<!--QUIZ_EMBEDER START-->
	<?php
	if ( isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
		?>
		<script type="text/javascript">
			window.wpActiveEditor = 'main_content_content_vb_tiny_mce'; //id of textarea of DIVI builder text component tinyMCE editor
		</script>
		<?php
	}
	?>
		
	<!--QUIZ_EMBEDER END-->
	<?php
}
