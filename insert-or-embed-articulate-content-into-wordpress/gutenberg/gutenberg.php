<?php
const BLOCKJS  = 'gutenberg/build/block.js';
const BLOCKCSS = 'gutenberg/build/block.css';
function articulate_enqueue_gutenberg_scripts() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'materializejs', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEJS, array( 'jquery', 'jquery-ui-core', 'jquery-ui-tooltip' ) );
	wp_enqueue_script( 'articulate-gutenberg-block', WP_QUIZ_EMBEDER_PLUGIN_URL . BLOCKJS, array( 'wp-api', 'wp-i18n', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-editor', 'wp-element' ), filemtime( WP_QUIZ_EMBEDER_PLUGIN_DIR . '/gutenberg/build/block.js' ), true );

	wp_localize_script(
		'articulate-gutenberg-block',
		'articulateOptions',
		array(
			'options'            => get_quiz_embeder_options(),
			'ajax_url'           => admin_url( 'admin-ajax.php' ),
			'_nonce_upload_file' => wp_create_nonce( 'articulate_upload_file' ),
			'_nonce_del_dir'     => wp_create_nonce( 'articulate_del_dir' ),
			'_nonce_rename_dir'  => wp_create_nonce( 'articulate_rename_dir' ),
			'dir'                => count( getDirs() ),
			'count'              => quiz_embeder_count(),
			'plupload'           => array(
				'chunk_size'  => articulate_get_upload_chunk_size(),
				'max_retries' => 10,
			),
		)
	);

	wp_enqueue_style( 'articulate-gutenberg-block', WP_QUIZ_EMBEDER_PLUGIN_URL . BLOCKCSS );
	wp_enqueue_style( 'material-icons', WP_QUIZ_EMBEDER_PLUGIN_URL . MATERIALIZEICONS, array(), PLUGINVERSION, 'all' );
}

add_action( 'enqueue_block_editor_assets', 'articulate_enqueue_gutenberg_scripts' );

function articulate_register_block() {
	register_block_type(
		'e-learning/block',
		array(
			'render_callback' => 'articulate_gutenberg_block_callback',
			'attributes'      => array(
				'src'            => array(
					'type' => 'string',
				),
				'href'           => array(
					'type' => 'string',
				),
				'type'           => array(
					'type'    => 'string',
					'default' => 'iframe',
				),
				'width'          => array(
					'type'    => 'string',
					'default' => '100%',
				),
				'height'         => array(
					'type'    => 'string',
					'default' => '600px',
				),
				'ratio'          => array(
					'type'    => 'string',
					'default' => '4:3',
				),
				'frameborder'    => array(
					'type'    => 'string',
					'default' => '0',
				),
				'scrolling'      => array(
					'type'    => 'string',
					'default' => 'no',
				),
				'title'          => array(
					'type' => 'string',
				),
				'link_text'      => array(
					'type' => 'string',
				),
				'button'         => array(
					'type' => 'string',
				),
				'scrollbar'      => array(
					'type' => 'string',
				),
				'colorbox_theme' => array(
					'type' => 'string',
				),
				'size_opt'       => array(
					'type' => 'string',
				),
			),
		)
	);
}

add_action( 'init', 'articulate_register_block' );

function articulate_gutenberg_block_callback( $attr ) {
	if ( $attr['type'] === 'iframe' || $attr['type'] === 'iframe_responsive' ) {
			unset( $attr['href'] );
	} else {
		unset( $attr['src'] );
	}

		$params = wp_parse_args( array_filter( $attr ) );

	if ( ! empty( $attr['src'] ) || ! empty( $attr['href'] ) ) {
		return iframe_handler( $params );
	}
}
