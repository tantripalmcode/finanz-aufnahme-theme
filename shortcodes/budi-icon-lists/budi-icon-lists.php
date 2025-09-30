<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_ICON_LISTS extends BUDI_SHORTCODE_BASE {

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
        return 'budi_icon_lists';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Icon Lists', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {
        // Enqueue CSS & JS
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION );
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
            "show_settings_on_create" => false,
            "is_container" => false,
            "params" => array(
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => __( 'Global Icon', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "global_icon",
                    "value" => '',
                ),

                array(
                    'type' => 'param_group',
                    'param_name' => 'items',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Use Global Icon', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'use_global_icon',
                            'value' => array(
                                'Yes' => 'yes',
                                'No' => 'no',
                            ),
                            'std' => 'yes',
                        ),
                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => __( 'Icon', _BUDI_TEXT_DOMAIN ),
                            "param_name" => "icon",
                            "value" => '',
                            "dependency" => array(
                                "element" => "use_global_icon",
                                "value" => "no",
                            )
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Title', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            "type" => "vc_link",
                            "class" => "",
                            "heading" => __( 'Link', _BUDI_TEXT_DOMAIN ),
                            "param_name" => "link",
                            "value" => '',
                            'admin_label' => true,
                        ),
                    )
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
            'global_icon'  => '',
            'items'        => '',
            'widget_class' => '',
            'css'          => '',
        ], $atts );

        $global_icon    = $atts['global_icon'];
        $widget_class   = sc_merge_css( $atts['css'], $atts['widget_class'] );
        $items          = vc_param_group_parse_atts( $atts['items'] );
        $this_widget_id = $this->get_widget_id( uniqid() );

        if ( $items ) { ?>

            <ul id="<?php echo esc_attr( $this_widget_id ); ?>" class="budi-icon-lists__wrapper list-unstyled <?php echo esc_attr( $widget_class ); ?>">
                <?php 
                foreach ( $items as $item ) {
                    $used_icon       = $item['use_global_icon'] === 'yes' ? $global_icon: ($item['icon'] ?? '');
                    $title           = do_shortcode( $item['title'] );
                    $budi_build_link = $this->budi_vc_build_link( $item['link'] ?? '' );
                    $link_url        = $budi_build_link['link_url'];
                    $link_target     = $budi_build_link['link_target'];
                    $link_rel        = $budi_build_link['link_rel'];
                
                    $display_icon = '';
                    if ( $used_icon ) {
                        $image_path = wp_get_original_image_path( $used_icon );
                        $file_info = pathinfo( $image_path );
                
                        $display_icon = isset( $file_info['extension'] ) && $file_info['extension'] === "svg"
                            ? file_get_contents( $image_path )
                            : wp_get_attachment_image( $used_icon, 'medium_large', true );
                    }
                
                    if ( $display_icon && !empty($title) ) {

                        if( $link_url ){
                            echo "<li class=\"budi-icon-lists__item\"><a href=\"$link_url\" target=\"$link_target\" rel=\"$link_rel\"><div class=\"budi-icon-lists__item-inner d-flex\"><span class=\"budi-icon-lists__icon\">$display_icon</span><span class=\"budi-icon-lists__text\">$title</span></div></a></li>";
                        } else {
                            echo "<li class=\"budi-icon-lists__item\"><div class=\"budi-icon-lists__item-inner d-flex\"><span class=\"budi-icon-lists__icon\">$display_icon</span><span class=\"budi-icon-lists__text\">$title</span></div></li>";
                        }
                    }
                }
                ?>
            </ul>

        <?php }

        return ob_get_clean();
    }

}

new BUDI_ICON_LISTS();