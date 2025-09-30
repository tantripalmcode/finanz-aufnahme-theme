<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if the 'vc_map' function does not exist (used by Visual Composer).
if ( !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_CUSTOM_BUTTON extends BUDI_SHORTCODE_BASE {

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
        return 'budi_custom_button';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Custom Button', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {
        // Enqueue CSS & JS
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION );
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {

        $defaults = array(
            'prim_link' => '',
            'prim_image' => '',
            'prim-icon-size' => '',
            'prim-icon-position' => '',
            'prim-btn-style' => '',
            'prim_class' => 'btn btn-primary',
            'sec_link' => '',
            'sec_image' => '',
            'sec-icon-position' => '',
            'sec-btn-style' => '',
            'sec_class' => 'btn btn-secondary',
            'show_secondary_button' => 'false',
            'btn-position' => 'btn-left',
            'btn-position-desktop' => '',
            'btn-position-tablet' => '',
            'btn-position-mobile' => '',
            'btn-gap' => '14px',
            'prim-icon-gap' => '4px',
            'sec-icon-gap' => '4px',
            'hide_on_desktop' => '',
            'hide_on_mobile' => '',
            'widget_class'  => '',
            'css'           => '',
        );
        $atts = shortcode_atts( $defaults, $atts );

        // Defined Variable
        $prim_link = $atts['prim_link'];
        $sec_link = $atts['sec_link'];

        // For title
        $prim_title = empty(vc_build_link($prim_link)['title']) ? 'Primary Button' : vc_build_link($prim_link)['title'];
        $sec_title = empty(vc_build_link($sec_link)['title']) ? 'Secondary Button' : vc_build_link($sec_link)['title'];          

        // Button URL
        $url_data = vc_build_link($prim_link);
        $prim_url = $url_data['url'];
        $prim_urlTarget = $url_data['target'];
        $prim_urlRel = $url_data['rel'];

        $url_data = vc_build_link($sec_link);
        $sec_url = $url_data['url'];
        $sec_urlTarget = $url_data['target'];
        $sec_urlRel = $url_data['rel'];

        // Button Image 
        if (strpos(strtolower($atts['prim-icon-size']), 'x') !== false) {
            $btn_image_size = explode("x", str_replace(" ", "", strtolower($atts['prim-icon-size'])));
        } else {
            $btn_image_size = array(25, 25);
        }
        $prim_image_url = wp_get_attachment_image($atts['prim_image'], $btn_image_size, false, array("class" => "img-responsive budi-button-container__image"));
        $sec_image_url = wp_get_attachment_image($atts['sec_image'], $btn_image_size, false, array("class" => "img-responsive budi-button-container__image"));

        // Button Icon Position
        $prim_icon_position_class = ($atts['prim-icon-position'] === 'prim-icon-right') ? 'flex-row-reverse' : 'flex-row';
        $sec_icon_position_class = ($atts['sec-icon-position'] === 'sec-icon-right') ? 'flex-row-reverse' : 'flex-row';

        // Button Position Responsive | Desktop
        $btn_position_desktop_mapping = array(
            'btn-left-desktop' => 'justify-content-md-start',
            'btn-center-desktop' => 'justify-content-md-center',
            'btn-right-desktop' => 'justify-content-md-end',
        );
        $btn_position_desktop = $btn_position_desktop_mapping[$atts['btn-position-desktop']] ?? 'justify-content-md-start';

         // Button Position Responsive | Tablet
        $btn_position_tablet_mapping = array(
            'btn-left-tablet' => 'justify-content-sm-start',
            'btn-center-tablet' => 'justify-content-sm-center',
            'btn-right-tablet' => 'justify-content-sm-end',
        );
        $btn_position_tablet = $btn_position_tablet_mapping[$atts['btn-position-tablet']] ?? 'justify-content-sm-start';

        // Button Position Responsive | Mobile
        $btn_position_mobile_mapping = array(
            'btn-left-mobile' => 'justify-content-start',
            'btn-center-mobile' => 'justify-content-center',
            'btn-right-mobile' => 'justify-content-end',
        );
        $btn_position_mobile = $btn_position_mobile_mapping[$atts['btn-position-mobile']] ?? 'justify-content-start';

        // Button Style
        $prim_btn_style = ($atts['prim-btn-style']) ? 'disabled' : '' ;
        $sec_btn_style = ($atts['sec-btn-style']) ? 'disabled' : '' ;

        $widget_class  = sc_merge_css($atts['css'], $atts['widget_class']);
        
        // Combine hide_on_mobile and hide_on_desktop logic into a single class
        $hide_class = 'd-flex';
        $is_hide_on_mobile = isset($atts['hide_on_mobile']) && $atts['hide_on_mobile'] === 'true';
        $is_hide_on_desktop = isset($atts['hide_on_desktop']) && $atts['hide_on_desktop'] === 'true';

        if ($is_hide_on_mobile && $is_hide_on_desktop) {
            // Hide everywhere
            $hide_class = 'd-none';
        } elseif ($is_hide_on_mobile) {
            // Hide on mobile, show on md+
            $hide_class = 'd-none d-md-flex';
        } elseif ($is_hide_on_desktop) {
            // Hide on desktop, show on sm-
            $hide_class = 'd-flex d-md-none';
        }

        ob_start();
        ?>
    
        <!-- View -->
        <div class="budi-button-container <?php echo esc_attr($hide_class); ?> flex-wrap <?php echo esc_attr($btn_position_desktop); ?> <?php echo esc_attr($btn_position_tablet); ?> <?php echo esc_attr($btn_position_mobile); ?> <?php echo esc_attr($widget_class); ?>" style="gap :<?php echo esc_attr($atts['btn-gap']) ?>;">
            <a href="<?php echo esc_url( $prim_url ); ?>" class="<?php echo esc_attr($atts['prim_class']); ?> d-inline-flex <?php echo esc_attr($prim_icon_position_class); ?> <?php echo esc_attr($prim_btn_style)?>" role="button" target="<?php echo esc_attr($prim_urlTarget); ?>" rel="<?php echo esc_attr($prim_urlRel); ?>" style="gap :<?php echo esc_attr($atts['prim-icon-gap']) ?>;">
                <?php if ($atts['prim_image']) : 
                    $prim_icon_path = wp_get_original_image_path($atts['prim_image']);
                    $file_info      = pathinfo( $prim_icon_path );

                    if ( isset( $file_info['extension'] ) && $file_info['extension'] === "svg" ) {
                        $prim_icon_svg_code = file_get_contents($prim_icon_path);
                        $prim_icon_svg_code_with_class = str_replace('<svg', '<svg class="budi-button-image__item"', $prim_icon_svg_code);
                        echo '<span class="budi-button-image__wrapper">' . $prim_icon_svg_code_with_class . '</span>';
                    } else {
                        echo '<span class="budi-button-image__wrapper">' . wp_get_attachment_image($atts['prim_image'], $btn_image_size, false, array("class" => "img-responsive budi-button-image__item")) . '</span>';
                    }
                endif; ?>
                <span class="budi-button-text"><?php echo $prim_title; ?></span>
            </a>

            <?php if ($atts['show_secondary_button'] === 'true') : ?>
                <a href="<?php echo esc_url( $sec_url ); ?>" class="<?php echo esc_attr($atts['sec_class']); ?> d-inline-flex <?php echo esc_attr($sec_icon_position_class); ?> <?php echo esc_attr($sec_btn_style)?>" role="button" target="<?php echo esc_attr($sec_urlTarget); ?>" rel="<?php echo esc_attr($sec_urlRel); ?>" style="gap :<?php echo esc_attr($atts['sec-icon-gap']) ?>;">
                    <?php if ($atts['sec_image']) : 
                        $sec_icon_path = wp_get_original_image_path($atts['sec_image']);
                        $sec_icon_type = wp_check_filetype($sec_icon_path);

                        if (isset($sec_icon_type['ext']) && $sec_icon_type['ext'] === "svg") {
                            $sec_icon_svg_code = file_get_contents($sec_icon_path);
                            $sec_icon_svg_code_with_class = str_replace('<svg', '<svg class="budi-button-image__item"', $sec_icon_svg_code);
                            echo '<span class="budi-button-image__wrapper">' . $sec_icon_svg_code_with_class . '</span>';
                        } else {
                            echo '<span class="budi-button-image__wrapper">' . wp_get_attachment_image($atts['sec_image'], $btn_image_size, false, array("class" => "img-responsive budi-button-image__item")) . '</span>';
                        }
                    endif; ?>
                    <span class="budi-button-text"><?php echo $sec_title; ?></span>
                </a>
            <?php endif; ?>
        </div>

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

                // Tab Primary Button
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Button Primary', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'prim_link',
                    'value' => '#',
                    'group' => 'Primary Button',
                    "admin_label" => true
                ),
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => __( 'Icon', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "prim_image",
                    "value" => '',
                    "dependency" => array(
                        "element" => "type",
                        "value" => "image",
                    ),
                    'group' => 'Primary Button',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Icon Size', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'prim-icon-size',
                    'description' => __( 'Enter a value: 10x10 / 25x25 / others.', _BUDI_TEXT_DOMAIN ),
                    'value' => '',
                    'group' => 'Primary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Icon Gap', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'prim-icon-gap',
                    'description' => __( 'Enter a value: 10px, 15px, 20px, or more.', _BUDI_TEXT_DOMAIN ),
                    'value' => '4px',
                    'group' => 'Primary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Icon Position', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'prim-icon-position',
                    'value' => array(
                        'Left' => 'prim-icon-left',
                        'Right' => 'prim-icon-right',
                    ),
                    'std' => "prim-icon-left",
                    'group' => 'Primary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Style', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'prim-btn-style',
                    'value' => array(
                        'Filled' => 'prim-btn-style-filled',
                        'Disabled' => 'prim-btn-style-disabled',
                    ),
                    'std' => "prim-btn-style-filled",
                    'group' => 'Primary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Button Primary | Extra Class', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'prim_class',
                    'value' => 'btn btn-primary',
                    'group' => 'Primary Button',
                ),

                // Tab Secondary Button
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Show Secondary Button', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'show_secondary_button',
                    'value' => array( __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'true' ),
                    'std' => 'false',
                    'group' => 'Secondary Button',
                ),
                array(
                    'type' => 'vc_link',
                    'heading' => __( 'Button Secondary', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sec_link',
                    'value' => '#',
                    'dependency' => array(
                        'element' => 'show_secondary_button',
                        'value' => 'true',
                    ),
                    'group' => 'Secondary Button',
                ),
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => __( 'Icon', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "sec_image",
                    "value" => '',
                    "dependency" => array(
                        "element" => "show_secondary_button",
                        "value" => "true",
                    ),
                    'group' => 'Secondary Button',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Icon Size', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sec-icon-size',
                    'description' => __( 'Enter a value: 10x10 / 25x25 / others.', _BUDI_TEXT_DOMAIN ),
                    'value' => '',
                    "dependency" => array(
                        "element" => "show_secondary_button",
                        "value" => "true",
                    ),
                    'group' => 'Secondary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Icon Gap', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sec-icon-gap',
                    'description' => __( 'Enter a value: 10px, 15px, 20px, or more.', _BUDI_TEXT_DOMAIN ),
                    'value' => '4px',
                    "dependency" => array(
                        "element" => "show_secondary_button",
                        "value" => "true",
                    ),
                    'group' => 'Secondary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Icon Position', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sec-icon-position',
                    'value' => array(
                        'Left' => 'sec-icon-left',
                        'Right' => 'sec-icon-right',
                    ),
                    'dependency' => array(
                        'element' => 'show_secondary_button',
                        'value' => 'true',
                    ),
                    'std' => "sec-icon-left",
                    'group' => 'Secondary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Style', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sec-btn-style',
                    'value' => array(
                        'Filled' => 'sec-btn-style-filled',
                        'Disabled' => 'sec-btn-style-disabled',
                    ),
                    'dependency' => array(
                        'element' => 'show_secondary_button',
                        'value' => 'true',
                    ),
                    'std' => "sec-btn-style-filled",
                    'group' => 'Secondary Button',
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Button Secondary | Extra Class', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sec_class',
                    'value' => 'btn btn-secondary',
                    'dependency' => array(
                        'element' => 'show_secondary_button',
                        'value' => 'true',
                    ),
                    'group' => 'Secondary Button',
                ),

                // Tab Responsive
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Button Gap', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'btn-gap',
                    'value' => '14px',
                    'description' => __( 'Enter a value: 10px, 15px, 20px, or more.', _BUDI_TEXT_DOMAIN ),
                    'dependency' => array(
                        'element' => 'show_secondary_button',
                        'value' => 'true',
                    ),
                    'group' => 'Responsive',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Position | Desktop', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'btn-position-desktop',
                    'value' => array(
                        'Left' => 'btn-left-desktop',
                        'Center' => 'btn-center-desktop',
                        'Right' => 'btn-right-desktop',
                    ),
                    'std' => "btn-left-desktop",
                    'group' => 'Responsive',
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Position | Tablet', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'btn-position-tablet',
                    'value' => array(
                        'Left' => 'btn-left-tablet',
                        'Center' => 'btn-center-tablet',
                        'Right' => 'btn-right-tablet',
                    ),
                    'std' => "btn-left-tablet",
                    'group' => 'Responsive',
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Button Position | Mobile', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'btn-position-mobile',
                    'value' => array(
                        'Left' => 'btn-left-mobile',
                        'Center' => 'btn-center-mobile',
                        'Right' => 'btn-right-mobile',
                    ),
                    'std' => "btn-left-mobile",
                    'group' => 'Responsive',
                    'edit_field_class' => 'vc_col-sm-4',
                ),

                //checkbox hide on mobile & desktop
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Hide on Desktop', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'hide_on_desktop',
                    'value' => array( __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'true' ),
                    'group' => 'Responsive',
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Hide on Mobile', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'hide_on_mobile',
                    'value' => array( __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'true' ),
                    'group' => 'Responsive',
                    'edit_field_class' => 'vc_col-sm-4',
                ),


                ...$this->get_design_options_controls(),
            )
        );

        vc_map( $args );
    }

}

new BUDI_CUSTOM_BUTTON();