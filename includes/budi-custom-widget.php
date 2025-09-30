<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) return;

/**
 * require once class functions
 */
require_once _BUDI_INCLUDE_PATH . 'wpbakery/class-shortcode-base.php';
require_once _BUDI_INCLUDE_PATH . 'wpbakery/class-shortcodes.php';

/**
 * Aos animation attribute generator
 */
function aos_animation_attributes( $atts ) {
    $aos_animation_attributes = $atts['aos_animation_attributes'];
    $lines_aos_animation = explode("\n", $aos_animation_attributes);

    // Initialize an empty array to store attributes
    $attributes = [];

    // Process each line
    foreach ($lines_aos_animation as $line) {
        // Explode each line by |
        $parts = explode("|", $line);
        if (count($parts) == 2) {
            $attributes[$parts[0]] = $parts[1];
        }
    }

    $attributes_html = '';
    foreach ($attributes as $key => $value) {
        $attributes_html .= ' ' . $key . '="' . strip_tags($value) . '"';
    }

    return $attributes_html;
}