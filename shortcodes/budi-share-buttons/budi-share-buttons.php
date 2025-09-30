<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_SHARE_BUTTONS extends BUDI_SHORTCODE_BASE {
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
        return 'budi_share_buttons';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Share Buttons', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {
        // Enqueue CSS & JS
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array( ), _BUDI_VERSION );
        wp_enqueue_script( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.js", array(), _BUDI_VERSION );
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
            'show_settings_on_create' => false,
            'is_container' => false,
            'params' => array(
                array(
                    'type' => 'param_group',
                    'param_name' => 'share_button_lists',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Network', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'network',
                            'value' => array(
                                'Facebook'  => 'facebook',
                                'Twitter'   => 'twitter',
                                'Linkedin'  => 'linkedin',
                                'Pinterest' => 'pinterest',
                                'Telegram'  => 'telegram',
                                'WhatsApp'  => 'whatsapp',
                                'Email'     => 'email',
                                'Xing'      => 'xing',
                                'Tumblr'    => 'tumblr',
                                'Skype'     => 'skype',
                            ),
                            'std' => 'facebook',
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Custom label', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'custom_label',
                        ),
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'View', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'view',
                    'value' => array(
                        __( 'Icon & Text', _BUDI_TEXT_DOMAIN ) => 'icon_text',
                        __( 'Icon', _BUDI_TEXT_DOMAIN ) => 'icon',
                        __( 'Text', _BUDI_TEXT_DOMAIN ) => 'text',
                    ),
                    'std' => 'icon_text',
                ),

                ...$this->get_design_options_controls(),
            )
        );

        vc_map( $args );
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {

        ob_start();

        $atts = shortcode_atts( [
            'share_button_lists' => '',
            'view'               => 'icon_text',
            'widget_class'       => '',
            'css'                => '',
        ], $atts );

        $widget_class = sc_merge_css( $atts['css'], $atts['widget_class'] );
        $button_lists = vc_param_group_parse_atts( $atts['share_button_lists'] );
        $view         = $atts['view'];

        if ( $button_lists ) { ?>

            <ul id="<?php echo esc_attr( $this->widget_id ); ?>" class="budi-share-buttons__wrapper <?php echo esc_attr( $widget_class ); ?>">

                <?php foreach( $button_lists as $button_list ) { ?>
                    
                    <?php
                    $network = $button_list['network'];
                    $icon    = $this->get_icon_svg_code( $network );
                    $label   = isset( $button_list['custom_label'] ) ? $button_list['custom_label']: ucwords( $network );
                    ?>

                    <li class="budi-share-buttons__item budi-share-buttons__<?php echo esc_attr( $network ); ?>" data-network="<?php echo esc_attr( $network ); ?>">
                        <?php
                        switch ( $view ) {

                            case "icon_text":
                                if ( $icon ) echo sprintf( '<span class="budi-share-buttons__icon d-inline-block">%s</span>', $icon );
                                echo sprintf( '<span class="budi-share-buttons__text">%s</span>', $label );
                                break;

                            case "icon":
                                if ( $icon ) echo sprintf('<span class="budi-share-buttons__icon d-inline-block">%s</span>', $icon );
                                break;

                            default:
                                echo sprintf( '<span class="budi-share-buttons__text">%s</span>', $label );
                                break;
                                
                        }
                        ?>
                    </li>

                <?php } ?>

            </ul>

        <?php }

        return ob_get_clean();
    }

    
    /**
     * get_icon_svg_code
     */
    private function get_icon_svg_code( $network ) {
        $icon_path_folder  = dirname( __FILE__ ) . '/icon/';
        $current_icon_path = $icon_path_folder . $network . '.svg';
        $svg_code          = file_exists( $current_icon_path ) ? file_get_contents( $current_icon_path ) : false;

        return $svg_code;
    }
}

new BUDI_SHARE_BUTTONS();