<?php 
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function sc_budi_horizontal_accordion_item( $atts, $content = null ) {
    ob_start();

    $atts = shortcode_atts( [
        'title'     => '',
        'sub_title' => '',
        'link'      => '',
        'image'     => '',
    ], $atts );

    $title     = $atts['title'];
    $sub_title = $atts['sub_title'];
    $link      = $atts['link'];
    $image     = $atts['image'];

    $content_item = [
        'id'        => sanitize_title( $title ) . uniqid(),
        'title'     => $title,
        'sub_title' => $sub_title,
        'link'      => $link,
        'image'     => $image,
        'content'   => $content,
    ];

    global $sc_horizontal_accordion;

    array_push($sc_horizontal_accordion, $content_item);

    return ob_get_clean();
}
add_shortcode( 'budi_horizontal_accordion_item', 'sc_budi_horizontal_accordion_item' );

add_action( 'vc_before_init', function(){
	if( !function_exists('vc_map') ) return;

    vc_map( array(
        'name' => __( 'Budi Horizontal Accordion Item', _BUDI_TEXT_DOMAIN ),
        'base' => 'budi_horizontal_accordion_item',
        'category' => _BUDI_CATEGORY_WIDGET_NAME,
        'content_element' => true,
        'as_child' => array('only' => 'budi_horizontal_accordion'),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Title', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'title',
                'holder' => 'div',
                'description' => ''
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Sub Title', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'sub_title',
                'holder' => 'div',
                'description' => ''
            ),
            array(
                'type' => 'textarea_html',
                'heading' => __( 'Content', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'content',
                'description' => ''
            ),
            array(
                'type' => 'vc_link',
                'heading' => __( 'Link', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'link',
                'description' => ''
            ),
            array(
                'type' => 'attach_image',
                'class' => '',
                'heading' => __( 'Image', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image',
                'value' => '',
            ),
        )
    ) );

    if ( class_exists( 'WPBakeryShortCode' ) ) {
        class WPBakeryShortCode_Budi_Horizontal_Accordion_Item extends WPBakeryShortCode {
        }
    }
});