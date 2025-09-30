<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if the 'vc_map' function does not exist (used by Visual Composer).
if ( !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_HEADING extends BUDI_SHORTCODE_BASE {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * get_name
     */
    protected function get_name() {
        return 'budi_heading';
    }

    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Heading', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {

        $defaults = array(
            'show_subtitle' => 'false',
            'subtitle' => '',
            'title_heading_tag' => 'h2',
            'title_class' => '',
            'title-position-desktop' => 'title-left-desktop',
            'title-position-tablet' => 'title-left-tablet',
            'title-position-mobile' => 'title-left-mobile',
            'max_width' => '',
            'font-weight' => '',
            'line-height' => '',
            'sub_title_heading_tag' => 'span',
            'sub_title_class' => '',
            'sub_title_position' => '',
            'aos_animation_attributes' => '',
        );
        $atts = shortcode_atts( $defaults, $atts );

        $title_tag = esc_html( $atts['title_heading_tag'] );
        $title_class = esc_attr( $atts['title_class'] );

        $sub_title_tag = esc_html( $atts['sub_title_heading_tag'] );
        $sub_title_class = esc_attr( $atts['sub_title_class'] );

        $show_subtitle = ( $atts['show_subtitle'] === 'true' ) ? true : false;
        $subtitle_text = esc_html( $atts['subtitle'] );

        $sub_title_position = $atts['sub_title_position'];
        $title_position_desktop = $atts['title-position-desktop'];
        $title_position_tablet = $atts['title-position-tablet'];
        $title_position_mobile = $atts['title-position-mobile'];

        $max_width = $atts['max_width'] ?? '';

        // Font weight & line height
        $font_weight = $atts['font-weight'];
        $line_height = $atts['line-height'];
        $style_attribute = '';

        if ( ! empty( $font_weight ) || ! empty( $line_height ) ) {
            $style_attribute .= ! empty( $font_weight ) ? 'font-weight: ' . $font_weight . '; ' : '';
            $style_attribute .= ! empty( $line_height ) ? 'line-height: ' . $line_height . '; ' : '';
        }

        $align_class_desktop = $this->map_title_alignment($title_position_desktop, 'desktop');
        $align_class_tablet = $this->map_title_alignment($title_position_tablet, 'tablet');
        $align_class_mobile = $this->map_title_alignment($title_position_mobile, 'mobile');

        ob_start();
        $uniqid = uniqid();
        ?>

        <div id="<?php echo esc_attr( $this->widget_id . $uniqid ); ?>" class="budi-headline d-flex flex-column <?php echo ( $sub_title_position !== 'before_title' ) ? 'flex-column-reverse' : ''; ?> <?php echo $align_class_desktop; ?> <?php echo $align_class_tablet; ?> <?php echo $align_class_mobile; ?> " <?php echo aos_animation_attributes( $atts ); ?>>
            <?php if ( $show_subtitle && ! empty( $subtitle_text ) ) : ?>
                <<?php echo $sub_title_tag; ?> class="budi-headline__subtitle <?php echo $sub_title_class; ?>">
                    <?php echo $subtitle_text; ?>
                </<?php echo $sub_title_tag; ?>>
            <?php endif; ?>
            <<?php echo $title_tag; ?> class="budi-headline__title <?php echo $title_class; ?>" style="<?php echo esc_attr( $style_attribute ); ?>">
                <?php echo $content; ?>
            </<?php echo $title_tag; ?>>
        </div>

        <?php if ( $max_width ) { ?>

            <style>
                #<?php echo esc_attr( $this->widget_id . $uniqid ); ?> .budi-headline__title {
                    max-width: <?php echo esc_attr( $max_width ); ?>
                }
            </style>

        <?php } ?>

        <?php
        return ob_get_clean();
    }

    /**
     * register_controls
     */
    public function register_controls() {
        $args = array(
            'name' => $this->widget_title,
            'base' => $this->widget_name,
            'category' => _BUDI_CATEGORY_WIDGET_NAME,
            'content_element' => true,
            "show_settings_on_create" => true,
            "is_container" => false,
            'params' => array(

                // General Tab
                array(
                    'type' => 'textarea_html',
                    "holder" => "div",
                    "class" => "",
                    'heading' => __( 'Heading Text', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'content',
                ),
                // array(
                //     'type' => 'textfield',
                //     'heading' => __( 'Extra Class', _BUDI_TEXT_DOMAIN ),
                //     'param_name' => 'title_class',
                //     'value' => '',
                // ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Show Subtitle', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'show_subtitle',
                    'value' => array( __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'true' ),
                    'std' => 'false',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Subtitle Text', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'subtitle',
                    'value' => '',
                    'dependency' => array(
                        'element' => 'show_subtitle',
                        'value' => 'true',
                    ),
                    'admin_label' => true,
                ),

                // Title Style Tab
                ...$this->get_title_style_options_controls(),

                array(
                    'type' => 'textfield',
                    'heading' => __( 'Max Width', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'max_width',
                    'value' => '',
                    'description' => __( 'Example: 100%, 900px, 500px, etc', _BUDI_TEXT_DOMAIN ),
                    'group' => 'Title Style',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Font Weight', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'font-weight',
                    'value' => '',
                    'description' => __( 'Example: 100, 200, 800, lighter, bold', _BUDI_TEXT_DOMAIN ),
                    'group' => 'Title Style',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Line Height', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'line-height',
                    'value' => '',
                    'description' => __( 'Example: 2em, 30px, 1.5rem', _BUDI_TEXT_DOMAIN ),
                    'group' => 'Title Style',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Title Position | Desktop', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'title-position-desktop',
                    'value' => array(
                        'Left' => 'title-left-desktop',
                        'Center' => 'title-center-desktop',
                        'Right' => 'title-right-desktop',
                    ),
                    'std' => "title-left",
                    'group' => 'Title Style',
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Title Position | Tablet', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'title-position-tablet',
                    'value' => array(
                        'Left' => 'title-left-tablet',
                        'Center' => 'title-center-tablet',
                        'Right' => 'title-right-tablet',
                    ),
                    'std' => "title-left",
                    'group' => 'Title Style',
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Title Position | Mobile', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'title-position-mobile',
                    'value' => array(
                        'Left' => 'title-left-mobile',
                        'Center' => 'title-center-mobile',
                        'Right' => 'title-right-mobile',
                    ),
                    'std' => "title-left",
                    'group' => 'Title Style',
                    'edit_field_class' => 'vc_col-sm-4',
                ),

                // Other Tab
                ...$this->get_sub_title_style_options_controls(),
                ...$this->get_aos_animation_options_controls(),
            )
        );

        vc_map( $args );
    }

}

new BUDI_HEADING();
