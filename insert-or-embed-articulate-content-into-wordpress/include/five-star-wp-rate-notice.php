<?php
$quiz_five_star_wp_rate_notice_clicked2022 = intval( get_option( 'quiz_five_star_wp_rate_notice_clicked2022' ) );
if ( 1 !== $quiz_five_star_wp_rate_notice_clicked2022 ) {
	$dirs = getDirs();
	if ( count( $dirs ) >= 5 ) {
		add_action( 'admin_notices', 'quiz_five_star_wp_rate_notice' );
		add_action( 'admin_enqueue_scripts', 'quiz_rate_notice_admin_enqueue_scripts' );
		add_action( 'wp_ajax_quiz_five_star_wp_rate', 'quiz_five_star_wp_rate_action' );
	}
}


function quiz_five_star_wp_rate_notice() {
	?>
	<div class="quiz-five-star-wp-rate-action notice notice-success">
		<span class="quiz-slug"><strong>Insert or Embed e-Learning Content into WordPress</strong> <em>Plugin</em></span> 

		<div>
			<?php esc_html_e( "Hey, I noticed you have uploaded at least 5 content items - that's awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation", 'insert-or-embed-articulate-content-into-wordpress' ); ?>
			<br/><br/>
			<strong><em>~ Brian Batt at elearningfreak.com</em></strong>
		</div>
		<ul data-nonce="<?php echo wp_create_nonce( 'quiz_five_star_wp_rate_action_nonce2022' ); ?>">
			<li><a data-rate-action="do-rate" target="_blank" rel="noopener noreferrer" href="https://wordpress.org/support/plugin/insert-or-embed-articulate-content-into-wordpress/reviews/?rate=5#new-post"><?php esc_html_e( 'Ok, you deserve it', 'insert-or-embed-articulate-content-into-wordpress' ); ?></a></li>
			<li><a data-rate-action="done-rating" href="#"><?php esc_html_e( 'I already did', 'insert-or-embed-articulate-content-into-wordpress' ); ?></a></li>
			<li><a data-rate-action="not-enough" href="#"><?php esc_html_e( 'No, not good enough', 'insert-or-embed-articulate-content-into-wordpress' ); ?></a></li>
		</ul>
	</div>
	<?php
}


function quiz_rate_notice_admin_enqueue_scripts() {
	 wp_enqueue_script( 'quiz_rate_notice', WP_QUIZ_EMBEDER_PLUGIN_URL . 'js/five_star_wp_rate_notice.js', array( 'jquery' ), PLUGINVERSION, false );
}


function quiz_five_star_wp_rate_action() {
	// Continue only if the nonce is correct!
	check_admin_referer( 'quiz_five_star_wp_rate_action_nonce2022', '_n' );
	update_option( 'quiz_five_star_wp_rate_notice_clicked2022', 1 );
	echo 1;
	exit;
}
