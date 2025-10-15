<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant)
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BRAND_LOGO_SLIDER extends BUDI_SHORTCODE_BASE {

    /**
     * get_name
     */
    protected function get_name() {
        return 'brand_logo_slider';
    }

    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Brand Logo Slider', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {
        // Enqueue CSS & JS
        wp_enqueue_style( 'swiper' );
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION );
        wp_enqueue_script( 'swiper' );
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
                    "type" => "attach_images",
                    "class" => "",
                    "heading" => __( "Brand Logos", _BUDI_TEXT_DOMAIN ),
                    "param_name" => "images",
                    "value" => '',
                    "description" => __( "Select brand logos to display in the slider.", _BUDI_TEXT_DOMAIN )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Max Height", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'max_height',
                    'std' => '80px',
                    "description" => __( "Maximum height for logo images (e.g., 80px, 50vh)", _BUDI_TEXT_DOMAIN ),
                    'edit_field_class' => 'vc_col-sm-6',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Speed", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'speed',
                    'std' => '5000',
                    "description" => __( "Speed of continuous sliding in milliseconds. Please enter numbers only.", _BUDI_TEXT_DOMAIN ),
                    'edit_field_class' => 'vc_col-sm-6',
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

        $atts = shortcode_atts( [
            'images'                   => '',
            'max_height'               => '80px',
            'speed'                    => '5000',
            'widget_class'             => '',
            'css'                      => '',
        ], $atts );

        $widget_class   = sc_merge_css( $atts['css'], $atts['widget_class'] );

        $images         = $atts['images'];
        $max_height     = $atts['max_height'];
        $speed          = $atts['speed'];

        ob_start();
        $uniqid = uniqid();

        if ( $images ) { ?>
            
            <style>
                #<?php echo $this->widget_id . $uniqid; ?> .brand-logo-slider__item img {
                    max-height: <?php echo esc_attr( $max_height ); ?>;
                    height: auto;
                    width: auto;
                    object-fit: contain;
                    transition: filter 0.3s ease;
                }
            </style>

            <div class="brand-logo-slider__wrapper position-relative w-100 <?php echo esc_attr( $widget_class ); ?>" id="<?php echo esc_attr( $this->widget_id . $uniqid ); ?>">
                <div class="brand-logo-slider swiper">
                    <div class="swiper-wrapper align-items-center">
                        <?php
                        $images = explode( ",", $images );
                        
                        // Duplicate images for seamless loop
                        $images = array_merge( $images, $images );

                        foreach( $images as $index => $image ) {
                            echo sprintf( '<div class="brand-logo-slider__item swiper-slide text-center">%s</div>', wp_get_attachment_image( $image, 'medium_large', false, array( 'class' => 'brand-logo-slider__image' ) ) );
                        }
                        ?>
                    </div>
                </div>
            </div>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $this->widget_id . $uniqid; ?>';
                        const $slider = $widget_id + ' .brand-logo-slider';

                        let settings = {
                            slidesPerView: "auto",
                            spaceBetween: 20,
                            loop: true,
                            speed: <?php echo intval( $speed ); ?>,
                            autoplay: {
                                delay: 0,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: false,
                            },
                            freeMode: {
                                enabled: true,
                                momentum: false,
                            },
                            allowTouchMove: false,
                            breakpoints: {
                                1200: {
                                    spaceBetween: 28,
                                },
                            },
                        };

                        const swiper = new Swiper($slider, settings);

                        // Custom continuous sliding with proper timing
                        let scrollSpeed = <?php echo intval( $speed ); ?>;
                        
                        function startContinuousScroll() {
                            swiper.slideNext();
                            setTimeout(startContinuousScroll, scrollSpeed);
                        }
                        
                        // Start continuous scrolling
                        startContinuousScroll();
                    });
                })(jQuery);
            </script>

        <?php }

        return ob_get_clean();
    }

}

new BRAND_LOGO_SLIDER();
