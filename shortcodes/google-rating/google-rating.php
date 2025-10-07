<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class GOOGLE_RATING extends BUDI_SHORTCODE_BASE {

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
        return 'google_rating';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Google Rating', _BUDI_TEXT_DOMAIN );
    }

    /**
     * register_controls
     */
    public function register_controls() {
        // Enqueue CSS & JS
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION );
        $args = array(
            'name' => $this->widget_title,
            'base' => $this->widget_name,
            'category' => _BUDI_CATEGORY_WIDGET_NAME,
            'content_element' => true,
            "show_settings_on_create" => false,
            "is_container" => false,
            'params' => array(
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Rating Score', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'rating_score',
                    'value' => '5.0',
                    'description' => __( 'Enter the rating score (e.g., 5.0, 4.8)', _BUDI_TEXT_DOMAIN ),
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Review Count', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'review_count',
                    'value' => '300+',
                    'description' => __( 'Enter the review count (e.g., 300+, 150)', _BUDI_TEXT_DOMAIN ),
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Review Text', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'review_text',
                    'value' => 'Bewertungen',
                    'description' => __( 'Enter the review text (e.g., Bewertungen, Reviews)', _BUDI_TEXT_DOMAIN ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Star Count', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'star_count',
                    'value' => array(
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ),
                    'std' => '5',
                    'description' => __( 'Select number of stars to display', _BUDI_TEXT_DOMAIN ),
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
     * render_view
     */
    public function render_view( $atts, $content = null ) {

        $atts = shortcode_atts( [
            'rating_score'           => '5.0',
            'review_count'           => '300+',
            'review_text'            => 'Bewertungen',
            'star_count'             => '5',
            'widget_class'           => '',
            'css'                    => '',
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
        $widget_class   = "google-rating-widget ";
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
        $rating_score   = isset( $atts['rating_score'] ) ? $atts['rating_score'] : '5.0';
        $review_count   = isset( $atts['review_count'] ) ? $atts['review_count'] : '300+';
        $review_text    = isset( $atts['review_text'] ) ? $atts['review_text'] : 'Bewertungen';
        $star_count     = isset( $atts['star_count'] ) ? intval( $atts['star_count'] ) : 5;

        // Generate stars HTML
        $stars_html = '';
        for ($i = 1; $i <= $star_count; $i++) {
            $stars_html .= '<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3.57658 1.70419C4.20991 0.568063 4.52657 0 5 0C5.47343 0 5.79009 0.568062 6.42342 1.70419L6.58727 1.99812C6.76724 2.32097 6.85722 2.48239 6.99753 2.5889C7.13783 2.69541 7.31257 2.73495 7.66206 2.81402L7.98023 2.88601C9.21007 3.16428 9.82499 3.30341 9.97129 3.77387C10.1176 4.24432 9.69838 4.73454 8.85995 5.71496L8.64304 5.96861C8.40479 6.24722 8.28566 6.38652 8.23207 6.55886C8.17848 6.73119 8.19649 6.91706 8.23251 7.28878L8.2653 7.6272C8.39206 8.9353 8.45544 9.58935 8.07243 9.88011C7.68941 10.1709 7.11366 9.90577 5.96216 9.37559L5.66426 9.23842C5.33704 9.08776 5.17343 9.01242 5 9.01242C4.82657 9.01242 4.66296 9.08776 4.33574 9.23842L4.03784 9.37558C2.88634 9.90577 2.31059 10.1709 1.92757 9.88011C1.54456 9.58935 1.60794 8.9353 1.7347 7.6272L1.76749 7.28878C1.80351 6.91706 1.82152 6.73119 1.76793 6.55886C1.71434 6.38652 1.59521 6.24722 1.35696 5.96861L1.14005 5.71496C0.301624 4.73454 -0.117588 4.24432 0.0287105 3.77387C0.175009 3.30341 0.789928 3.16428 2.01977 2.88601L2.33794 2.81402C2.68743 2.73495 2.86217 2.69541 3.00247 2.5889C3.14278 2.48239 3.23276 2.32097 3.41273 1.99812L3.57658 1.70419Z" fill="#EFA725"/></svg>';
        }
        ?>

        <div class="google-rating-container">
            <div class="google-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
            </div>
            <div class="rating-content">
                <div class="rating-score-stars">
                    <div class="rating-score"><?php echo esc_html( $rating_score ); ?></div>
                    <div class="rating-stars"><?php echo $stars_html; ?></div>
                </div>
                <div class="review-count"><?php echo esc_html( $review_count ); ?> <?php echo esc_html( $review_text ); ?></div>
            </div>
        </div>

    <?php }
}

new GOOGLE_RATING();