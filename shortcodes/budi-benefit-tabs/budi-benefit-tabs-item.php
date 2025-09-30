<?php 
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function sc_budi_benefit_tabs_item( $atts, $content = null ) {
    ob_start();

    $atts = shortcode_atts( [
        'tab_text' => '',
        'title'    => '',
        'button'   => '',
        'button_position' => 'left',
        'button_alignment' => 'left',
        'image'    => '',
        'image_position' => 'right',
        'image_max_width' => '100%',
        'floating_icon' => '',
        'icon_position' => 'right',
        'floating_icon_width' => '80px',
    ], $atts );

    $tab_text = $atts['tab_text'];
    $title    = $atts['title'];
    $button   = $atts['button'];
    $button_position = $atts['button_position'];
    $button_alignment = $atts['button_alignment'];
    $image    = $atts['image'];
    $image_position = $atts['image_position'];
    $image_max_width = $atts['image_max_width'];
    $floating_icon = $atts['floating_icon'];
    $icon_position = $atts['icon_position'];
    $floating_icon_width = $atts['floating_icon_width'];

    $content_item = [
        'tab_text' => $tab_text,
        'title'    => $title,
        'content'  => $content,
        'button'   => $button,
        'button_position' => $button_position,
        'button_alignment' => $button_alignment,
        'image'    => $image,
        'image_position' => $image_position,
        'image_max_width' => $image_max_width,
        'floating_icon' => $floating_icon,
        'icon_position' => $icon_position,
        'floating_icon_width' => $floating_icon_width,
    ];

    global $sc_benefit_tabs;

    array_push($sc_benefit_tabs, $content_item);

    return ob_get_clean();
}
add_shortcode( 'budi_benefit_tabs_item', 'sc_budi_benefit_tabs_item' );

add_action( 'vc_before_init', function(){
	if( !function_exists('vc_map') ) return;

    vc_map( array(
        'name' => __( 'Budi Benefit Tabs Item', _BUDI_TEXT_DOMAIN ),
        'base' => 'budi_benefit_tabs_item',
        'category' => _BUDI_CATEGORY_WIDGET_NAME,
        'content_element' => true,
        'as_child' => array('only' => 'budi_benefit_tabs'),
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Tab Text', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'tab_text',
                'holder' => 'div',
                'description' => 'Text displayed on the tab button'
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Title', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'title',
                'holder' => 'div',
                'description' => 'Main title for this tab content'
            ),
            array(
                'type' => 'textarea_html',
                'heading' => __( 'Content', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'content',
                'description' => 'Description text for this tab'
            ),
            array(
                'type' => 'attach_image',
                'class' => '',
                'heading' => __( 'Image', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image',
                'value' => '',
                'description' => 'Image to display with this tab content',
                'group' => 'Image',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image Position', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image_position',
                'value' => array(
                    __( 'Right Side', _BUDI_TEXT_DOMAIN ) => 'right',
                    __( 'Left Side', _BUDI_TEXT_DOMAIN ) => 'left',
                ),
                'std' => 'right',
                'description' => 'Choose the position of the image relative to the content',
                'group' => 'Image',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image Max Width', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image_max_width',
                'value' => '100%',
                'description' => 'Set the maximum width for the image (e.g., 250px, 300px, 50%)',
                'group' => 'Image',
            ),
            array(
                'type' => 'attach_image',
                'class' => '',
                'heading' => __( 'Floating Icon', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'floating_icon',
                'value' => '',
                'description' => 'Floating icon to display with this tab content',
                'group' => 'Floating Icon',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Floating Icon Position', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'icon_position',
                'value' => array(
                    __( 'Right Side', _BUDI_TEXT_DOMAIN ) => 'right',
                    __( 'Left Side', _BUDI_TEXT_DOMAIN ) => 'left',
                ),
                'std' => 'right',
                'description' => 'Choose the position of the floating icon relative to the content',
                'group' => 'Floating Icon',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Floating Icon Max Width', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'floating_icon_width',
                'value' => '80px',
                'description' => 'Maximum width of the floating icon (e.g., 80px, 5rem, 10%, 50vw). Default: 80px',
                'group' => 'Floating Icon',
            ),
            array(
                'type' => 'vc_link',
                'heading' => __( 'Button', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'button',
                'description' => 'Call-to-action button link',
                'group' => 'Button',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Button Position', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'button_position',
                'value' => array(
                    __( 'Left Column', _BUDI_TEXT_DOMAIN ) => 'left',
                    __( 'Right Column', _BUDI_TEXT_DOMAIN ) => 'right',
                ),
                'std' => 'left',
                'description' => 'Choose which column the button should appear in',
                'group' => 'Button',
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Button Alignment', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'button_alignment',
                'value' => array(
                    __( 'Left', _BUDI_TEXT_DOMAIN ) => 'left',
                    __( 'Center', _BUDI_TEXT_DOMAIN ) => 'center',
                    __( 'Right', _BUDI_TEXT_DOMAIN ) => 'right',
                ),
                'std' => 'left',
                'description' => 'Choose the alignment for the button within its column',
                'group' => 'Button',
            ),
        )
    ) );

    if ( class_exists( 'WPBakeryShortCode' ) ) {
        class WPBakeryShortCode_Budi_Benefit_Tabs_Item extends WPBakeryShortCode {
        }
    }
});
