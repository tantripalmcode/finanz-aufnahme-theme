<?php
if ( !function_exists( 'vc_map' ) ) {
    return;
}


/**
 * Text Column Widget
 */
vc_add_param('vc_column_text', [
    'type' => 'textfield',
    'heading' => "Max Width",
    'param_name' => 'budi_max_width',
    'description' => 'Ex. 600px, 1000px, 50%, 100%'
]);

/**
 * Single Image Widget - Custom Attributes
 */
vc_add_param('vc_single_image', [
    'type' => 'textarea',
    'heading' => 'Custom Attributes',
    'param_name' => 'budi_custom_attributes',
    'description' => 'Add custom attributes in format: data-speed="2" data-delay="100" or any other custom attributes you need. One attribute per line.',
    'group' => 'Custom Attributes'
]);
