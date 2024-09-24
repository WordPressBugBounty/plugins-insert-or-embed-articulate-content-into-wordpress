<?php
/*
Plugin Name: Insert or Embed Articulate Content into WordPress Trial
Plugin URI: https://www.elearningfreak.com
Description: Quickly embed or insert e-Learning content into a post or page no matter if you use Articulate Storyline, Rise, Captivate, Lectora, Camtasia, iSpring, Elucidat, Gomo, Obisidian Black, MindManager, or any other tool.  Learn more about the premium plugin at https://www.elearningfreak.com
Version: 4.3000000024
Text Domain: insert-or-embed-articulate-content-into-wordpress
Domain Path: /languages
Author: Brian Batt
Author URI: https://www.elearningfreak.com
*/
		define( 'WP_QUIZ_EMBEDER_PLUGIN_DIR_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WP_QUIZ_EMBEDER_PLUGIN_DIR', dirname( __FILE__ ) ); // Plugin Directory
		define( 'WP_QUIZ_EMBEDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) ); // Plugin URL (for http requests)
global $wpdb;

require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/settings-file.php';
require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/include/class-custom-fs-functions.php';
require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/class-quiz-unzip.php';
require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/functions.php';
const PLUGINVERSION = '43000000024';
const MATERIALIZE_CSS = 'css/materialize.css';
const MATERIALIZEJS   = 'js/materialize.js';
const ADMINJS         = 'js/admin.js';
function quiz_embeder_gutenberg_load() {
	if ( function_exists( 'register_block_type' ) ) {
		include_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/gutenberg/gutenberg.php';
	}
};

add_action( 'plugins_loaded', 'quiz_embeder_gutenberg_load', 999 );
require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/include/shortcode.php';
register_activation_hook( __FILE__, 'quiz_embeder_install' );
/*add_action( 'admin_notices', 'quiz_embeder_banner');*/
register_deactivation_hook( __FILE__, 'quiz_embeder_remove' );
if ( ! function_exists( 'articulate_fs' ) ) {
	// Create a helper function for easy SDK access.
	function articulate_fs() {
		global $articulate_fs;

		if ( ! isset( $articulate_fs ) ) {
			// Include Freemius SDK.
			require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/freemius/start.php';

			$articulate_fs = fs_dynamic_init(
				array(
					'id'             => '1159',
					'slug'           => 'insert-or-embed-articulate-content-into-wordpress',
					'type'           => 'plugin',
					'public_key'     => 'pk_33392c26e487a56795b740ebd9594',
					'is_premium'     => false,
					'has_addons'     => false,
					'has_paid_plans' => false,
					'menu'           => array(
						'first-path' => 'index.php?page=articulate-welcome-screen-about',
						'account'    => false,
						'contact'    => false,
						'support'    => false,
					),
				)
			);
		}

		return $articulate_fs;
	}

	// Init Freemius.
	articulate_fs();
	// Signal that SDK was initiated.
	do_action( 'articulate_fs_loaded' );
}

function articulate_fs_custom_connect_message_on_update(
		$message,
		$user_first_name,
		$plugin_title,
		$user_login,
		$site_link,
		$freemius_link
	) {
	return sprintf(
		__( 'Hey %1$s', 'insert-or-embed-articulate-content-into-wordpress' ) . ',<br>' .
		__( 'By opting in you will receive an email with instructions on how to use the plugin. If you skip this, that\'s okay! The plugin will still work just fine. <p> Don\'t miss out on the premium plugin features like <a href="https://www.elearningfreak.com/xapi-now-available-for-storyline-and-rise/" target="_blank">tracking and reporting with xAPI support</a>, <a href="https://www.youtube.com/watch?v=2a8mMQShugk" target="_blank">launch content in full screen</a>, responsive iframes, <a href="https://www.youtube.com/watch?v=OxD_U81a3rQ" target="_blank">lightboxes with 13 beautiful themes</a>, custom launch buttons & more! <a href="https://bit.ly/elearningfreak2024">Purchase the premium plugin now.</a>', 'insert-or-embed-articulate-content-into-wordpress' ),
		$user_first_name,
		'<b>' . $plugin_title . '</b>',
		'<b>' . $user_login . '</b>',
		$site_link,
		$freemius_link
	);
}

	articulate_fs()->add_filter( 'connect_message_on_update', 'articulate_fs_custom_connect_message_on_update', 10, 6 );

function articulate_fs_custom_connect_message(
		$message,
		$user_first_name,
		$plugin_title,
		$user_login,
		$site_link,
		$freemius_link
	) {
	return sprintf(
		__( 'Hey %1$s', 'insert-or-embed-articulate-content-into-wordpress' ) . ',<br>' .
		__( 'By opting in you will receive an email with instructions on how to use the plugin. If you skip this, that\'s okay! The plugin will still work just fine. <p> Don\'t miss out on the premium plugin features like <a href="https://www.elearningfreak.com/xapi-now-available-for-storyline-and-rise/" target="_blank">tracking and reporting with xAPI support</a>, <a href="https://www.youtube.com/watch?v=2a8mMQShugk" target="_blank">launch content in full screen</a>, responsive iframes, <a href="https://www.youtube.com/watch?v=OxD_U81a3rQ" target="_blank">lightboxes with 13 beautiful themes</a>, custom launch buttons & more! <a href="https://bit.ly/elearningfreak2024">Purchase the premium plugin now.</a>', 'insert-or-embed-articulate-content-into-wordpress' ),
		$user_first_name,
		'<b>' . $plugin_title . '</b>',
		'<b>' . $user_login . '</b>',
		$site_link,
		$freemius_link
	);
}

	articulate_fs()->add_filter( 'connect_message', 'articulate_fs_custom_connect_message', 10, 6 );

function quiz_embeder_count() {
	$count = 2;
	return apply_filters( 'quiz_embeder_count', $count );
}
function quiz_embeder_install() {
	quiz_embeder_create_protection_files( true );// this function will create the upload directory also.
}
function quiz_embeder_remove() {
	// $qz_upload_path=getUploadsPath();
	// if(file_exists($qz_upload_path.".htaccess")){unlink($qz_upload_path.".htaccess");}
}
add_action( 'wp_ajax_quiz_upload', 'wp_ajax_quiz_upload' );
add_action( 'wp_ajax_del_dir', 'wp_ajax_del_dir' );
add_action( 'wp_ajax_rename_dir', 'wp_ajax_rename_dir' );
function wp_myplugin_media_button() {
	$wp_myplugin_media_button_image = getPluginUrl() . 'quiz.png';
	$siteurl                        = get_admin_url();
	echo '<a href="' . $siteurl . 'media-upload.php?type=articulate-upload&TB_iframe=true&tab=articulate-upload" class="thickbox">
<img src="' . $wp_myplugin_media_button_image . '" width=15 height=15 /></a>';
}
function media_upload_quiz_form() {
	wp_enqueue_style( 'materialize-css', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZE_CSS );
	wp_enqueue_script( 'materializejs', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEJS );
	wp_enqueue_style( 'material-icons', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEICONS, array(), PLUGINVERSION, 'all' );
	print_tabs();
	echo '<div class="wrap" style="margin-left:20px;  margin-bottom:50px;">';
	echo '<div id="icon-upload" class="icon32"><br></div><h2 class="header">' . __( 'Upload File', 'insert-or-embed-articulate-content-into-wordpress' ) . '</h2>';
	print_upload();
	echo '</div>';
}
function media_upload_quiz_content() {
	wp_enqueue_style( 'materialize-css', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZE_CSS );
	wp_enqueue_script( 'materializejs', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEJS );
	wp_enqueue_style( 'material-icons', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEICONS, array(), PLUGINVERSION, 'all' );
	print_tabs();
	echo '<div class="wrap" style="margin-left:20px;  margin-bottom:50px;">';
	echo '<div id="icon-upload" class="icon32"><br></div><h2 class="header">' . __( 'Content Library', 'insert-or-embed-articulate-content-into-wordpress' ) . '</h2>';
	printInsertForm();
	echo '</div>';
}
function media_upload_quiz() {
	wp_enqueue_style( 'materialize-css', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZE_CSS );
	wp_enqueue_script( 'materializejs', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEJS );
	wp_iframe( 'media_upload_quiz_content' );
}
function media_upload_upload() {
	if ( isset( $_REQUEST['tab'] ) && strstr( $_REQUEST['tab'], 'articulate-quiz' ) ) {
		wp_iframe( 'media_upload_quiz_content' );
	} else {
		wp_iframe( 'media_upload_quiz_form' );
	}
}
function print_tabs() {
	function quiz_tabs( $tabs ) {
		$newtab1 = array( 'articulate-upload' => __( 'Upload File', 'insert-or-embed-articulate-content-into-wordpress' ) );
		$newtab2 = array( 'articulate-quiz' => __( 'Content Library', 'insert-or-embed-articulate-content-into-wordpress' ) );
		return array_merge( $newtab1, $newtab2 );
	}
	add_filter( 'media_upload_tabs', 'quiz_tabs' );
	media_upload_header();
}
if ( ! function_exists( 'quiz_embeder_register_plugin_links' ) ) {
	function quiz_embeder_register_plugin_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() ) {
				$links[] = '<a href="index.php?page=articulate-welcome-screen-about">' . __( 'How to Use', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			}
			$links[] = '<a href="https://www.elearningfreak.com/checkout/?edd_action=add_to_cart&download_id=1032&edd_options%5Bprice_id%5D=10">' . __( 'Upgrade to Premium', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://help.elearningfreak.com" target="_blank">' . __( 'Support', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.elearningfreak.com/xapi-now-available-for-storyline-and-rise/" target="_blank">' . __( 'Tracking and Reporting', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.elearningfreak.com/upload-articulate-storyline-wordpress/" target="_blank">' . __( 'How to upload with: Storyline', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.elearningfreak.com/upload-articulate-rise-wordpress/" target="_blank">' . __( 'Rise', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.elearningfreak.com/upload-adobe-captivate-wordpress/" target="_blank">' . __( 'Captivate', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.elearningfreak.com/upload-ispring-wordpress/" target="_blank">' . __( 'iSpring', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.youtube.com/watch?v=NAngYz5QbC8" target="_blank">' . __( 'Lectora', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
			$links[] = '<a href="https://www.youtube.com/watch?v=zIUCmVDP_4w" target="_blank">' . __( 'gomo', 'insert-or-embed-articulate-content-into-wordpress' ) . '</a>';
		}
		return $links;
	}
}
add_action( 'media_upload_articulate-upload', 'media_upload_upload' );
add_action( 'media_upload_articulate-quiz', 'media_upload_quiz' );
add_action( 'media_buttons', 'wp_myplugin_media_button', 100 );
add_action( 'wp_footer', 'quiz_embeder_wp_footer' );
add_filter( 'plugin_row_meta', 'quiz_embeder_register_plugin_links', 10, 2 );
function quiz_embeder_enqueue_script() {
	wp_enqueue_script( 'jquery' );
	if ( isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
		wp_enqueue_script( 'media-upload' );
		wp_enqueue_script( 'thickbox' );
		wp_enqueue_style( 'thickbox' );
	}
}
add_action( 'wp_enqueue_scripts', 'quiz_embeder_enqueue_script' );

function rename_quiz_modify_coursepress_element_editor_args( $args, $editor_name, $editor_id ) {
	$args['media_buttons'] = true;
	return $args;
}
add_filter( 'coursepress_element_editor_args', 'rename_quiz_modify_coursepress_element_editor_args', 100, 3 );

function quiz_admin_footer() {
	?>
	<style type="text/css">
		/* additional CSS for coursepress plugin. Fix the style 'Short Overview' label */
		#course-setup-steps .step-content label.drop-line{
			margin-bottom: 40px;
		}
	</style>
<script type="text/javascript"> jQuery(document).on('contextmenu','.quiz_btn', function(e) { return false; }); </script>
	<?php
}
add_action( 'admin_footer', 'quiz_admin_footer' );

function quiz_admin_footer_fix_with_fusion_builder() {
	if ( defined( 'FUSION_BUILDER_PLUGIN_DIR' ) ) {
		?>
		<div id="quiz_embeder_button_copy" style="display:none;"><?php wp_myplugin_media_button(); ?></div>
		<script type="text/javascript">
			(function( $ ){
				//See /fusion-builder/assets/js/wpeditor/wp-editor.js
				$(document).on('fusionButtons', function( event , current_id ){
					if( $("#wp-"+current_id+"-media-buttons" ).find(".quiz_btn").length == 0 )
					{
						console.log("adding quiz button");
						$("#wp-"+current_id+"-media-buttons" ).append( $("#quiz_embeder_button_copy").html());
					}
				});
			})( jQuery )
		</script>
		<?php
	}
}
add_action( 'admin_footer', 'quiz_admin_footer_fix_with_fusion_builder' );

require_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/admin-page.php';

if ( is_admin() ) {
	include_once WP_QUIZ_EMBEDER_PLUGIN_DIR . '/include/five-star-wp-rate-notice.php';
}

function quiz_embeder_plugins_loaded() {
	load_plugin_textdomain( 'insert-or-embed-articulate-content-into-wordpress', false, dirname( __FILE__ ) . '/' . 'languages/' );
}
add_action( 'plugins_loaded', 'quiz_embeder_plugins_loaded' );

