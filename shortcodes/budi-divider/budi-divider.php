<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_DIVIDER extends BUDI_SHORTCODE_BASE {

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
        return 'budi_divider';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Divider', _BUDI_TEXT_DOMAIN );
    }

    /**
     * register_controls
     */
    public function register_controls() {
        $args = array(
            'name' => $this->widget_title,
            'base' => $this->widget_name,
            "category" => _BUDI_CATEGORY_WIDGET_NAME,
            "content_element" => true,
            "show_settings_on_create" => false,
            "is_container" => false,
            "params" => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __( "Style", _BUDI_TEXT_DOMAIN) ,
                    'param_name' => 'divider_style',
                    'value' => array(
                        'Solid' => 'solid',
                        'Dotted' => 'dotted',
                        'Dashed' => 'dashed',
                        'Double' => 'double',
                        'Groove' => 'groove',
                        'Ridge' => 'ridge',
                    ),
                    'std' => 'solid',
                    'group' => 'Divider',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Width", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'divider_width',
                    'group' => 'Divider',
                    "description" => __( "Ex: 100%, 50%, 100px, 200px.", _BUDI_TEXT_DOMAIN ),
                    'std' => '100%',
                    'edit_field_class' => 'vc_col-sm-5',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( "Alignment", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'alignment',
                    'value' => array(
                        'Left' => 'text-left',
                        'Center' => 'text-center',
                        'Right' => 'text-right',
                    ),
                    'std' => 'text-start',
                    'group' => 'Divider',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( "Add Element", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'add_element',
                    'value' => array(
                        'None' => 'none',
                        'Text' => 'text',
                        'Icon' => 'icon',
                    ),
                    'std' => 'none',
                    'group' => 'Divider',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Text", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'text',
                    'group' => 'Divider',
                    'edit_field_class' => 'vc_col-sm-5',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "text",
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( "HTML Tag", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'html_tag',
                    'value' => array(
                        'h1' => 'h1',
                        'h2' => 'h2',
                        'h3' => 'h3',
                        'h4' => 'h4',
                        'h5' => 'h5',
                        'h6' => 'h6',
                        'div' => 'div',
                        'p' => 'p',
                        'span' => 'span',
                    ),
                    'std' => 'span',
                    'group' => 'Divider',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "text",
                    ),
                ),
                array(
                    "type" => "attach_image",
                    "heading" => __( 'Icon', 'palmcode' ),
                    "param_name" => "icon",
                    "description" => __( "Please only choose icon with svg format", _BUDI_TEXT_DOMAIN ),
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "icon",
                    ),
                    'group' => 'Divider',
                ),

                // Divider Style
                array(
                    "type" => "colorpicker",
                    "class" => "",
                    "heading" => __( "Color", _BUDI_TEXT_DOMAIN ),
                    "param_name" => "divider_color_custom",
                    "value" => '',
                    'group' => 'Divider Style',
                    'std' => '#000000',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Weight", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'divider_weight',
                    'description' => __( "Please enter only numbers.", _BUDI_TEXT_DOMAIN ),
                    'std' => 10,
                    'group' => 'Divider Style',
                ),

                // Text Style
                array(
                    'type' => 'dropdown',
                    'heading' => __( "Position", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'text_position',
                    'value' => array(
                        'Left' => 'left',
                        'Center' => 'center',
                        'Right' => 'right',
                    ),
                    'std' => 'center',
                    'group' => 'Text Style',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "text",
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Spacing (px)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'text_spacing',
                    'group' => 'Text Style',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "text",
                    ),
                    'std' => 20,
                    'description' => __( "Please enter only numbers.", _BUDI_TEXT_DOMAIN ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Custom Text Class", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'text_custom_class',
                    'group' => 'Text Style',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "text",
                    ),
                ),

                // Icon Style
                array(
                    'type' => 'dropdown',
                    'heading' => __( "Position", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'icon_position',
                    'value' => array(
                        'Left' => 'left',
                        'Center' => 'center',
                        'Right' => 'right',
                    ),
                    'std' => 'center',
                    'group' => 'Icon Style',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "icon",
                    ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Spacing (px)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'icon_spacing',
                    'group' => 'Icon Style',
                    "dependency" => array(
                        "element" => "add_element",
                        "value" => "icon",
                    ),
                    'std' => 20,
                    'description' => __( "Please enter only numbers.", _BUDI_TEXT_DOMAIN ),
                ),
                
                ...$this->get_design_options_controls(),
            ),
        );

        vc_map( $args );
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {
        $atts = shortcode_atts([
            'divider_style'        => 'solid',
            'divider_width'        => '100%',
            'alignment'            => 'text-start',
            'add_element'          => 'none',
            'text'                 => '',
            'html_tag'             => 'span',
            'icon'                 => '',
            'divider_color_custom' => '#000000',
            'divider_weight'       => 10,
            'text_custom_class'    => '',
            'text_position'        => 'center',
            'text_spacing'         => 20,
            'icon_position'        => 'center',
            'icon_spacing'         => 20,
            'widget_class'         => '',
            'css'                  => '',
        ], $atts);

        ob_start();

        $divider_style          = $atts['divider_style'];
        $divider_width          = $atts['divider_width'];
        $alignment              = $atts['alignment'];
        $add_element            = $atts['add_element'];
        $text                   = $atts['text'];
        $html_tag               = $atts['html_tag'];
        $icon                   = $atts['icon'];
        $divider_color_custom   = $atts['divider_color_custom'];
        $divider_weight         = $atts['divider_weight'];
        $text_custom_class      = $atts['text_custom_class'];
        $text_position          = $atts['text_position'];
        $text_spacing           = $atts['text_spacing'];
        $icon_position          = $atts['icon_position'];
        $icon_spacing           = $atts['icon_spacing'];

        $widget_class = sc_merge_css( $atts['css'], $atts['widget_class'] );
        $widget_class .= $alignment ? " " . $alignment : '';

        // Separator Class
        $separator_class = '';
        if ( $alignment === "text-left" ) {
            $separator_class .= 'ml-0 mr-auto';
        } elseif ( $alignment === "text-right" ) {
            $separator_class .= 'mr-0 ml-auto';
        } else {
            $separator_class .= 'mx-auto';
        }
        ?>

        <div id="<?php echo esc_attr( $this->widget_id ); ?>" class="budi-divider <?php echo esc_attr( $widget_class ); ?>">
            <span class="budi-divider-separator d-flex align-items-center <?php echo $separator_class; ?>">
                <?php
                if ( $add_element === "text" ) {
                    echo sprintf( '<%1$s class="budi-divider__text budi-divider__element %3$s">%2$s</%1$s>', $html_tag, $text, $text_custom_class );
                } elseif ( $add_element === "icon" && $icon ) {
                    $icon_path        = wp_get_original_image_path( $icon );
                    $icon_file_type   = wp_check_filetype( $icon_path );

                    if ( isset( $icon_file_type['ext'] ) && $icon_file_type['ext'] === "svg" ) {
                        $icon_svg_code = file_get_contents( $icon_path );
                        echo sprintf( '<span class="budi-divider__icon budi-divider__element">%s</span>', $icon_svg_code );
                    }
                }
                ?>
            </span>
        </div>

        <style>
            #<?php echo $this->widget_id; ?>.budi-divider .budi-divider-separator {
                width: <?php echo $divider_width; ?>;
            }
        </style>

        <?php if ($add_element === "none") { ?>
            <style>
                #<?php echo $this->widget_id; ?>.budi-divider .budi-divider-separator {
                    border-top: <?php echo $divider_weight . 'px ' . $divider_style . ' ' . $divider_color_custom; ?>;
                }
            </style>
        <?php } else {

            $element_spacing = $add_element === "text" ? $text_spacing : $icon_spacing; ?>

            <style>
                #<?php echo $this->widget_id; ?>.budi-divider .budi-divider-separator::before,
                #<?php echo $this->widget_id; ?>.budi-divider .budi-divider-separator::after {
                    display: block;
                    content: "";
                    flex-grow: 1;
                    border-top: <?php echo $divider_weight . 'px ' . $divider_style . ' ' . $divider_color_custom; ?>;
                }

                #<?php echo $this->widget_id; ?>.budi-divider .budi-divider__element {
                    margin: 0 <?php echo $element_spacing . "px"; ?>
                }
            </style>

            <?php if ($text_position === "left" || $icon_position === "left") { ?>
                <style>
                    #<?php echo $this->widget_id; ?>.budi-divider .budi-divider-separator::before {
                        display: none;
                    }

                    #<?php echo $this->widget_id; ?>.budi-divider .budi-divider__element {
                        margin-left: 0;
                    }
                </style>
            <?php } elseif ($text_position === "right" || $icon_position === "right") { ?>
                <style>
                    #<?php echo $this->widget_id; ?>.budi-divider .budi-divider-separator::after {
                        display: none;
                    }

                    #<?php echo $this->widget_id; ?>.budi-divider .budi-divider__element {
                        margin-right: 0;
                    }
                </style>
            <?php } ?>

        <?php } ?>

        <?php return ob_get_clean();
    }
}

new BUDI_DIVIDER();