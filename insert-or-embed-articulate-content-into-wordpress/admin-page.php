<?php
const CSSHELPERS       = 'css/css-helpers.css';
const MATERIALIZEICONS = 'css/materializeicons.css';
const CSSJQUERYUI      = 'css/jquery-ui.css';
const ADMINCSS         = 'css/adminfreak.css';

function get_quiz_embeder_options() {
	$default = array(
		'lightbox_script'      => 'colorbox',
		'colorbox_transition'  => 'elastic',
		'colorbox_theme'       => 'default',

		'nivo_lightbox_effect' => 'fade',
		'nivo_lightbox_theme'  => 'default',

		'size_opt'             => 'default',
		'height'               => 500,
		'width'                => 750,
		'height_type'          => 'px',
		'width_type'           => 'px',
		'buttons'              => array(),

	);
	$opt = get_option( 'quiz_embeder_option', $default );
	if ( '' === $opt['lightbox_script'] ) {
		$opt['lightbox_script'] = 'colorbox';}
	if ( '' === $opt['colorbox_theme'] ) {
		$opt['colorbox_theme'] = 'default';}
	if ( '' === $opt['size_opt'] ) {
		$opt['size_opt'] = 'default';}
	return $opt;
}

function quiz_admin_head() {

	echo '<style type="text/css">

#toplevel_page_articulate_content .wp-first-item{display:none;}
</style>';
}
add_action( 'admin_head', 'quiz_admin_head' );
function quiz_embeder_menu_pages() {
	global $iea_admin_lightbox;
	add_menu_page( 'Articulate', 'Articulate', 'manage_options', 'articulate-settings', 'articulate_settings_page');
	remove_submenu_page( 'articulate-settings', 'Articulate' );
	add_submenu_page(
    'Lightbox Settings',
    'Lightbox Settings',
    'manage_options',
    'lightbox-settings',
    'lightbox_settings_page'
);

add_submenu_page(
    'Custom Buttons',
    'Custom Buttons',
    'manage_options',
    'custom-buttons',
    'custom_buttons_page'
);

add_submenu_page(
    'Reports',
    'Reports',
    'manage_options',
    'reports',
    'reports_page'
);

add_submenu_page(
    'Statement Viewer',
    'Statement Viewer',
    'manage_options',
    'statement-viewer',
    'statement_viewer_page'
);
function lightbox_settings_page() {
    ?>
    <div class="wrap">
        <h1>Lightbox Settings</h1>
        <p>Here you can configure your plugin's settings.</p>
    </div>
    <?php
}

function custom_buttons_page() {
    ?>
    <div class="wrap">
        <h1>Custom Buttons</h1>
        <p>Here you can configure your plugin's settings.</p>
    </div>
    <?php
}

function reports_page() {
    ?>
    <div class="wrap">
        <h1>My Settings</h1>
        <p>Here you can configure your plugin's settings.</p>
    </div>
    <?php
}

function statement_viewer_page() {
    ?>
    <div class="wrap">
        <h1>Reports</h1>
        <p>Here you can configure your plugin's settings.</p>
    </div>
    <?php
}
	do_action( 'iea_admin_menu' );
}

//add_action( 'admin_menu', 'quiz_embeder_menu_pages' );

function quiz_admin_pw_load_scripts( $hook ) {

	if ( 'post.php' === $hook || 'post-edit.php' === $hook || 'post-new.php' === $hook || 'media-upload-popup' === $hook || false !== strpos( $hook, 'articulate' ) ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-tooltip' );
		wp_enqueue_style( 'deprecated-media' );
	}
}
add_action( 'admin_enqueue_scripts', 'quiz_admin_pw_load_scripts' );

function quiz_admin_enqueue_scripts() {
	if ( strpos( $_SERVER['REQUEST_URI'], 'articulate_content' ) ) {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'jquery-ui-standard-css', WP_QUIZ_EMBEDER_PLUGIN_URL . CSSJQUERYUI, array(), PLUGINVERSION, 'all' );
	}

		// Some plugins may remove all actions from 'media_buttons' hook!
		// Add our function to 'media_buttons' hook again if that is not exist!
	if ( ! has_action( 'media_buttons', 'wp_myplugin_media_button' ) ) {
		add_action( 'media_buttons', 'wp_myplugin_media_button', 100 );
	}

}
add_action( 'admin_enqueue_scripts', 'quiz_admin_enqueue_scripts' );

add_action( 'admin_menu', 'quiz_admin_welcome_screen_pages' );

function quiz_admin_welcome_screen_pages() {
	add_dashboard_page(
		'How to Use elearningfreak.com',
		'How to Use elearningfreak.com',
		'read',
		'articulate-welcome-screen-about',
		'quiz_admin_welcome_screen_content'
	);
}

function quiz_admin_welcome_screen_content() {
	wp_enqueue_style( 'css-helpers', WP_QUIZ_EMBEDER_PLUGIN_URL . ADMINCSS, array(), PLUGINVERSION, 'all' );
	?>
  
  <div id="wpforms-welcome" class="lite">

<div class="container">

	<div class="intro">

		<div class="sullie">
			<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>images/elearningfreak.png" alt="logo for elearningfreak.com" width="78" height="69">
		</div>

		<div class="block">
			<h1>Thank you for installing this plugin by <a rel="noopener noreferrer" target=_"blank" href="https://www.elearningfreak.com/?utm_source=freeplugin&utm_medium=adminpage&utm_campaign=thankyouforinstalling">elearningfreak.com</a></h1>
			<h6>Helping instructional designers, universities, and businesses get their e-learning content into WordPress since 2011.</h6>
		</div>

		<a target=_"blank" href="https://www.youtube.com/watch?v=lQQaYiCI2Hw" class="play-video" title="Here's How To Get Started" rel="noopener noreferrer">
			<img width="716" height="403" src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>images/welcome-video.png" alt="Watch how to get started" class="video-thumbnail">
		</a>

		<div class="block">

			<h6>Upload any tool's content & even custom content including Articulate Storyline, Rise, Studio, Adobe Captivate, iSpring, Gomo, Elucidat & more! You can watch the video above or select an authoring tool below for a step-by-step guide:</h6>

			<div class="button-wrap wpforms-clear">
				<div class="left">
					<a target=
_blank" href="https://www.elearningfreak.com/upload-articulate-storyline-wordpress/?utm_source=freeplugin&utm_medium=adminpage&utm_campaign=uploadstorylinewordpress" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-orange" rel="noopener noreferrer">
						Storyline								</a>
				</div>
				<div class="right">
					<a href="https://www.elearningfreak.com/upload-adobe-captivate-wordpress/?utm_source=freeplugin&utm_medium=adminpage&utm_campaign=uploadobecaptivatewordpress" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-grey" target="_blank" rel="noopener noreferrer">
						Captivate								</a>
				</div>

			</div>
			<div class="button-wrap wpforms-clear">
				<div class="left">
					<a target=
_blank" href="https://www.elearningfreak.com/upload-ispring-wordpress/?utm_source=freeplugin&utm_medium=adminpage&utm_campaign=uploadispringwordpress" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-orange" rel="noopener noreferrer">
						iSpring								</a>
				</div>
				<div class="right">
					<a rel="noopener noreferrer" href="https://www.youtube.com/channel/UCVFSb0lNtWaHEVvBaGyWkZg/videos" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-grey" target="_blank">
						And More!								</a>
				</div>
			</div>

		</div>

	</div><!-- /.intro -->

	<div class="challenge">
<div class="block">
<h1>Works With Any Page Builder</h1>
<h6>Use with your favorite editors including Elementor, Divi, Beaver Builder, Gutenberg, the Classic Editor, and more! Check out our Youtube Channel for step-by-step videos!</h6>
<div class="button-wrap">
<a rel="noopener noreferrer" target="_blank" href="https://www.youtube.com/channel/UCVFSb0lNtWaHEVvBaGyWkZg/videos" class="wpforms-btn wpforms-btn-lg wpforms-btn-orange">
	See the Youtube Channel			</a>
</div>
</div>
</div>


	<div class="features">

		<div class="block">

			<h1>Coming Soon to Premium!</h1>
			<h6>These features will be added as free updates to the Premium plugin.</h6>

			<div class="feature-list wpforms-clear">

				<div class="feature-block first">
					<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>images/welcome-feature-icon-2.png" width="46" height="46" alt="icon drawing of a folder">
					<h5>My Content</h5>
					<p>With this new feature, you’ll be able see every page or post that contains your e-learning content.</p>
				</div>
				
				<div class="feature-block last">
					<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>images/welcome-feature-icon-1.png" width="46" height="46" alt="icon drawing for xapi">
					<h5>Tell Us!</h5>
					<p>We're working on updates to the Content Library, additional report types, and more!  What else would you like to see?  Contact Us!</p>
				</div>

			</div>

			<div class="button-wrap">
				<a href="https://www.elearningfreak.com/changelog-release-notes-roadmap/?utm_source=freeplugin&utm_medium=adminpage&utm_campaign=changelogreleasenotesroadmap" class="wpforms-btn wpforms-btn-lg wpforms-btn-grey" rel="noopener noreferrer" target="_blank">
					See the Release Notes &#38; Roadmap						</a>
			</div>

		</div>

	</div><!-- /.features -->

	<div class="upgrade-cta upgrade">

		<div class="block wpforms-clear">

			<div class="left">
				<h2>Upgrade to Premium</h2>
				<ul>
					<li><span class="dashicons dashicons-yes"></span> xAPI Support</li>
					<li><span class="dashicons dashicons-yes"></span> Reports &#38; Tracking</li>
					<li><span class="dashicons dashicons-yes"></span> Responsive iFrames</li>
					<li><span class="dashicons dashicons-yes"></span> Custom iFrame Sizing</li>
					<li><span class="dashicons dashicons-yes"></span> 13 Lightbox Themes</li>
					<li><span class="dashicons dashicons-yes"></span> Open In a New Window</li>
					<li><span class="dashicons dashicons-yes"></span> Launch Full Screen</li>
					<li><span class="dashicons dashicons-yes"></span> Open In Same Window</li>
					<li><span class="dashicons dashicons-yes"></span> Custom Buttons</li>
					<li><span class="dashicons dashicons-yes"></span> Upload MP4 Files</li>
					<li><span class="dashicons dashicons-yes"></span> Support by Brian Batt</li>
					<li><span class="dashicons dashicons-yes"></span> And More!</li>
				</ul>
			</div>

			<div class="right">
				<h2><span>Premium</span></h2>
				<div class="price">
						  <span class="amount">149</span><br>
					<span class="term">per year</span>
				</div>
				<a href="https://www.elearningfreak.com/checkout/?edd_action=add_to_cart&download_id=1032&edd_options%5Bprice_id%5D=10&utm_source=freeplugin&utm_medium=adminpage&utm_campaign=buynow" rel="noopener noreferrer" target="_blank" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-orange wpforms-upgrade-modal">
					Buy Now							</a>
			</div>

		</div>

	</div>

	<div class="testimonials upgrade">

		<div class="block">

			<h1>Testimonials</h1>

			<div class="testimonial-block wpforms-clear">
				<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>images/welcome-testimonial-emiley.jpg" width="100" height="100" alt="small image of Emiley">
				<p>Thank you, thank you, thank you! Once again I am able to do the most amazing things with your plug-in! 1000% worth the money, thank you!							</p><p>
				</p><p><strong>Emiley Chorley</strong>, Creator & Founder of https://www.pickandmixcreator.com</p>
			</div>

			<div class="testimonial-block wpforms-clear">
				<img src="<?php echo WP_QUIZ_EMBEDER_PLUGIN_URL; ?>images/welcome-testimonial-julie.jpg" width="100" height="100" alt="small image of">
				<p>Absolutely love this plugin. It is so so simple to use and works like a dream in LearnDash. I was tossing up using SCORM plugins BUT found out that I didn’t need that level or complexity for my course structure. The support is also AMAZING – had a question and got a response very quickly. Very happy with my purchase of the paid version.							</p><p>
				</p><p><strong>Julie Franke</strong>, e-Learning Developer</p>
			</div>

		</div>

	</div><!-- /.testimonials -->

	<div class="footer">

		<div class="block wpforms-clear">

			<div class="button-wrap wpforms-clear">
				<div class="left">
					<a href="https://help.elearningfreak.com/contact" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-orange" rel="noopener noreferrer" target="_blank">
						Need Help? Contact Us!								</a>
				</div>
				<div class="right">
					<a href="https://www.elearningfreak.com/checkout/?edd_action=add_to_cart&download_id=1032&edd_options%5Bprice_id%5D=10" target="_blank" rel="noopener noreferrer" class="wpforms-btn wpforms-btn-block wpforms-btn-lg wpforms-btn-trans-green wpforms-upgrade-modal">
						<span class="underline">
							Upgrade to Premium <span class="dashicons dashicons-arrow-right"></span>
						</span>
					</a>
				</div>
			</div>

		</div>

	</div><!-- /.footer -->
<script type="text/javascript" src="https://load.fomo.com/api/v1/WpNY3yRpcx3b7EpnDobGTA/load.js" async></script>
	<script SameSite="None; Secure" src="https://static.landbot.io/landbot-3/landbot-3.0.0.js"></script>
<script>
  var myLandbot = new Landbot.Livechat({
	configUrl: 'https://chats.landbot.io/v3/H-907005-AZFORA3Z2TMZ7C89/index.json',
  });
</script>
</div><!-- /.container -->

</div>
<script type="text/javascript">
	adroll_adv_id = "SIYHOE435ZFOBMO4GD7YCY";
	adroll_pix_id = "PCEPYERE5BG2LHFA6RKX3Q";
	adroll_version = "2.0";

	(function(w, d, e, o, a) {
		w.__adroll_loaded = true;
		w.adroll = w.adroll || [];
		w.adroll.f = [ 'setProperties', 'identify', 'track' ];
		var roundtripUrl = "https://s.adroll.com/j/" + adroll_adv_id
				+ "/roundtrip.js";
		for (a = 0; a < w.adroll.f.length; a++) {
			w.adroll[w.adroll.f[a]] = w.adroll[w.adroll.f[a]] || (function(n) {
				return function() {
					w.adroll.push([ n, arguments ])
				}
			})(w.adroll.f[a])
		}

		e = d.createElement('script');
		o = d.getElementsByTagName('script')[0];
		e.async = 1;
		e.src = roundtripUrl;
		o.parentNode.insertBefore(e, o);
	})(window, document);
	adroll.track("pageView");
</script>

	<?php
}

add_action( 'admin_head', 'quiz_admin_welcome_screen_remove_menus' );

function quiz_admin_welcome_screen_remove_menus() {
	remove_submenu_page( 'index.php', 'articulate-welcome-screen-about' );
}

