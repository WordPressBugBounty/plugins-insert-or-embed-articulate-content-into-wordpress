<?php
/** Shortcode handler */

function iframe_handler($atts) {
    // Define default values and retrieve attributes
    $default_atts = array(
        'src' => '', // Default source is empty
    );
    $atts = shortcode_atts($default_atts, $atts, 'iframe_loader');

    // Sanitize the URL
    $src = esc_url($atts['src']);

    // Check if the src is empty
    if (empty($src)) {
        return '<p>Error: No source URL provided for the iframe.</p>';
    }

    // Return the sanitized and escaped iframe HTML with hardcoded width and height
    return "<p><iframe src='" . esc_attr($src) . "' width='100%' height='600px' frameborder='0' scrolling='no' title='e-learning content'></iframe></p>";
}

add_shortcode('iframe_loader', 'iframe_handler');
