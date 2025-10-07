<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_MULTISTEP_FORM extends BUDI_SHORTCODE_BASE {

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
        return 'budi_multistep_form';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Multistep Form', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {
        // Enqueue CSS & JS
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION );
        wp_enqueue_script( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.js", array('jquery'), _BUDI_VERSION, true );
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
            'params' => array(
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Contact Form 7', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'contact_form_id',
                    'value' => $this->get_contact_forms(),
                    'description' => __( 'Select a Contact Form 7 form', _BUDI_TEXT_DOMAIN ),
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Form Title', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'form_title',
                    'description' => __( 'Enter form title (optional)', _BUDI_TEXT_DOMAIN ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Form Description', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'form_description',
                    'description' => __( 'Enter form description (optional)', _BUDI_TEXT_DOMAIN ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'CSS Class', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'widget_class',
                    'description' => __( 'Add custom CSS class', _BUDI_TEXT_DOMAIN ),
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __( 'CSS', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'css',
                    'group' => __( 'Design Options', _BUDI_TEXT_DOMAIN ),
                ),
            )
        );

        vc_map( $args );
    }

    /**
     * get_contact_forms
     * Get all Contact Form 7 forms
     */
    private function get_contact_forms() {
        $forms = array();
        
        if ( function_exists( 'wpcf7_contact_form' ) ) {
            $cf7_forms = get_posts( array(
                'post_type' => 'wpcf7_contact_form',
                'numberposts' => -1,
                'post_status' => 'publish'
            ) );
            
            foreach ( $cf7_forms as $form ) {
                $forms[ $form->post_title ] = $form->ID;
            }
        }
        
        if ( empty( $forms ) ) {
            $forms[ __( 'No forms found', _BUDI_TEXT_DOMAIN ) ] = '';
        }
        
        return $forms;
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {

        $atts = shortcode_atts( [
            'contact_form_id'         => '',
            'form_title'              => '',
            'form_description'        => '',
            'widget_class'            => '',
            'css'                     => '',
        ], $atts );

        ob_start();

        $this->widget_open_tag( $atts );

        $this->widget_body( $atts );

        $this->widget_close_tag( $atts );
        
        return ob_get_clean();
    }
    
    /**
     * widget_open_tag
     */
    private function widget_open_tag( $atts ) {
        $widget_id      = esc_attr( $this->widget_id );
        $widget_class   = "budi-multistep-form-widget budi-linear-background ";
        $widget_class   .= sc_merge_css( $atts['css'], $atts['widget_class'] );

        $html = sprintf( '<div id="%s" class="%s">', 
            esc_attr( $widget_id ), 
            esc_attr( $widget_class )
        );

        echo $html;
    }
    
    /**
     * widget_close_tag
     */
    private function widget_close_tag( $atts ) {
        $html = '</div>';
        echo $html;
    }
    
    /**
     * widget_body
     */
    private function widget_body( $atts ) {
        $contact_form_id = isset( $atts['contact_form_id'] ) ? $atts['contact_form_id'] : '';
        $form_title = isset( $atts['form_title'] ) ? $atts['form_title'] : '';
        $form_description = isset( $atts['form_description'] ) ? $atts['form_description'] : '';

        if ( empty( $contact_form_id ) ) {
            echo '<p>' . __( 'Please select a Contact Form 7 form.', _BUDI_TEXT_DOMAIN ) . '</p>';
            return;
        }

        ?>
        <div class="budi-multistep-form-container">
            <?php if ( !empty( $form_title ) ) : ?>
                <div class="budi-multistep-form-title">
                    <h3><?php echo esc_html( $form_title ); ?></h3>
                </div>
            <?php endif; ?>

            <?php if ( !empty( $form_description ) ) : ?>
                <div class="budi-multistep-form-description">
                    <p><?php echo esc_html( $form_description ); ?></p>
                </div>
            <?php endif; ?>

            <div class="budi-multistep-form-wrapper">
                <?php
                // Display the Contact Form 7 form
                if ( function_exists( 'wpcf7_contact_form' ) ) {
                    echo do_shortcode( '[contact-form-7 id="' . esc_attr( $contact_form_id ) . '"]' );
                } else {
                    echo '<p>' . __( 'Contact Form 7 plugin is not active.', _BUDI_TEXT_DOMAIN ) . '</p>';
                }
                ?>
            </div>
        </div>
        <?php
    }
}

new BUDI_MULTISTEP_FORM();
