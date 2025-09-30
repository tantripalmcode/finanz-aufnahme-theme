<?php
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) return; // Exit if accessed directly

class BUDI_SPACING extends BUDI_SHORTCODE_BASE {
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
        return 'BUDI_SPACING';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Spacing', _BUDI_TEXT_DOMAIN );
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
            "params" => array(
                array(
                    "type" => "dropdown",
                    "heading" => __( 'Spacing', _BUDI_TEXT_DOMAIN ),
                    "param_name" => 'spacing',
                    "value" => array(
                        __( 'Large', _BUDI_TEXT_DOMAIN ) => 'large',
                        __( 'Medium', _BUDI_TEXT_DOMAIN ) => 'medium',
                        __( 'Small', _BUDI_TEXT_DOMAIN ) => 'small',
                    ),
                    "description" => __( 'Select spacing size. You can configure spacing values in the Seitenoptionen Customizer.', _BUDI_TEXT_DOMAIN ),
                    "admin_label" => true
                )
            ),
        );

        vc_map( $args );
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {
        $atts = shortcode_atts([
            'spacing' => 'large'
        ], $atts);

        $class_name = 'section-spacing-' . esc_attr($atts['spacing']);

        ob_start();
        echo "<div class='{$class_name}'></div>";
        return ob_get_clean();
    }
}

new BUDI_SPACING();
