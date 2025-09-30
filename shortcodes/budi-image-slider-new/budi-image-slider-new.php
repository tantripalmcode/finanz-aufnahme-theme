<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant)
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_IMAGE_SLIDER_NEW extends BUDI_SHORTCODE_BASE {

    /**
     * get_name
     */
    protected function get_name() {
        return 'budi_image_slider_new';
    }

    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Image Slider New', _BUDI_TEXT_DOMAIN );
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
        $widget_group = "Slider Settings";

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
                    "heading" => __( "Images", _BUDI_TEXT_DOMAIN ),
                    "param_name" => "images",
                    "value" => '',
                ),
                array(
                    "type" => "textarea",
                    "heading" => 'Links',
                    "param_name" => "links",
                    "value" => "",
                    "description" => __( "Ex. https://google.com, https://amazon.com", "budigital" ),
                ),

                // Carousel Settings
                array(
                    'type' => 'checkbox',
                    'heading' => __( 'Enable Infinite Loop?', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'infinite_loop',
                    'value' => array(
                        __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                    ),
                    'admin_label' => true,
                    'group' => $widget_group,
                ),

                // Space Between
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Space Between (Desktop)', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'space_between_desktop',
                    'admin_label' => true,
                    'std' => 20,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Space Between (Tablet)', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'space_between_tablet',
                    'admin_label' => true,
                    'std' => 20,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Space Between (Mobile)', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'space_between_mobile',
                    'admin_label' => true,
                    'std' => 20,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),

                // Slides To Show
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __( "Slides To Show (Desktop)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'slides_per_view_desktop',
                    'value' => array( 'auto', 1, 2, 3, 4, 5, 6 ),
                    'std' => 4,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __( "Slides To Show (Tablet)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'slides_per_view_tablet',
                    'value' => array( 'auto', 1, 2, 3, 4, 5, 6 ),
                    'std' => 2,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __( "Slides To Show (Mobile)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'slides_per_view_mobile',
                    'value' => array( 'auto', 1, 2, 3, 4, 5, 6 ),
                    'std' => 1,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),

                // Slides To Scroll
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __( "Slides To Scroll (Desktop)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'slides_to_scroll_desktop',
                    'value' => array( 1, 2, 3, 4, 5, 6 ),
                    'std' => 1,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __( "Slides To Scroll (Tablet)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'slides_to_scroll_tablet',
                    'value' => array( 1, 2, 3, 4, 5, 6 ),
                    'std' => 1,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
                ),
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __( "Slides To Scroll (Mobile)", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'slides_to_scroll_mobile',
                    'value' => array( 1, 2, 3, 4, 5, 6 ),
                    'std' => 1,
                    'group' => $widget_group,
                    'edit_field_class' => 'vc_col-sm-4',
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
            'links'                    => '',
            'enable_fullwidth'         => '',
            'infinite_loop'            => '',
            'space_between_desktop'    => 20,
            'space_between_tablet'     => 20,
            'space_between_mobile'     => 20,
            'slides_per_view_desktop'  => 4,
            'slides_per_view_tablet'   => 2,
            'slides_per_view_mobile'   => 1,
            'max_width_image_desktop'  => '400px',
            'max_width_image_tablet'   => '400px',
            'max_width_image_mobile'   => '300px',
            'slides_to_scroll_desktop' => 1,
            'slides_to_scroll_tablet'  => 1,
            'slides_to_scroll_mobile'  => 1,
            'widget_class'             => '',
            'css'                      => '',
        ], $atts );

        $widget_class   = sc_merge_css( $atts['css'], $atts['widget_class'] );

        $images                   = $atts['images'];
        $links                    = $atts['links'];
        $enable_fullwidth         = $atts['enable_fullwidth'];
        $infinite_loop            = $atts['infinite_loop'];
        $space_between_desktop    = $atts['space_between_desktop'];
        $space_between_tablet     = $atts['space_between_tablet'];
        $space_between_mobile     = $atts['space_between_mobile'];
        $slides_per_view_desktop  = $atts['slides_per_view_desktop'];
        $slides_per_view_tablet   = $atts['slides_per_view_tablet'];
        $slides_per_view_mobile   = $atts['slides_per_view_mobile'];
        $max_width_image_desktop  = $atts['max_width_image_desktop'];
        $max_width_image_tablet   = $atts['max_width_image_tablet'];
        $max_width_image_mobile   = $atts['max_width_image_mobile'];
        $slides_to_scroll_desktop = $atts['slides_to_scroll_desktop'];
        $slides_to_scroll_tablet  = $atts['slides_to_scroll_tablet'];
        $slides_to_scroll_mobile  = $atts['slides_to_scroll_mobile'];

        ob_start();
        $uniqid = uniqid();

        if ( $images ) { ?>

            <div class="budi-image-slider-new__wrapper position-relative <?php echo $enable_fullwidth !== "yes" ? 'w-100' : ''; ?> <?php echo esc_attr( $widget_class ); ?>" id="<?php echo esc_attr( $this->widget_id . $uniqid ); ?>">
                <div class="budi-image-slider-new swiper <?php echo $enable_fullwidth === 'yes' ? 'overflow-visible' : ''; ?>">
                    <div class="swiper-wrapper align-items-center">
                        <?php
                        $links  = explode( ",", $links );
                        $images = explode( ",", $images );

                        foreach( $images as $index => $image ) {
                            if( isset( $links[$index] ) && !empty( $links[$index] ) ){
                                echo sprintf( '<a href="%s" target="_blank" class="budi-image-slider-new__item swiper-slide text-center">%s</a>', esc_url( trim( $links[$index] ) ), wp_get_attachment_image( $image, 'medium_large', false, array( 'class' => 'budi-image-slider-new__image' ) ) );
                            } else {
                                echo sprintf( '<div class="budi-image-slider-new__item swiper-slide text-center">%s</div>', wp_get_attachment_image( $image, 'medium_large', false, array( 'class' => 'budi-image-slider-new__image' ) ) );
                            }
                        }
                        ?>
                    </div>
                </div>

                <!-- Navigation & Pagination -->
                <?php get_template_part( 'template-parts/swiper/navigation' ); ?>

            </div>

            <?php if( $slides_per_view_desktop === "auto" && $max_width_image_desktop ){ ?>
                <style>
                    #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider-new__item{
                        max-width: <?php echo esc_attr( $max_width_image_desktop ); ?>;
                    }
                </style>
            <?php } ?>

            <?php if( $slides_per_view_tablet === "auto" && $max_width_image_tablet ){ ?>
                <style>
                    @media only screen and (max-width: 1200px) {
                        #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider-new__item{
                            max-width: <?php echo esc_attr( $max_width_image_tablet ); ?>;
                        }
                    }
                </style>
            <?php } ?>

            <?php if( $slides_per_view_mobile === "auto" && $max_width_image_mobile ){ ?>
                <style>
                    @media only screen and (max-width: 767px) {
                        #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider-new__item{
                            max-width: <?php echo esc_attr( $max_width_image_mobile ); ?>;
                        }
                    }
                </style>
            <?php } ?>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $this->widget_id . $uniqid; ?>';
                        const $slider = $($widget_id + ' .budi-image-slider-new');

                        let settings = {
                            slidesPerView: "<?php echo $slides_per_view_mobile; ?>",
                            spaceBetween: <?php echo $space_between_mobile ?>,
                            slidesPerGroup: <?php echo $slides_to_scroll_mobile; ?>,
                            loop: <?php echo $infinite_loop === "yes" ? 1 : 0; ?>,
                            navigation: {
                                nextEl: $widget_id + " .swiper-button-next",
                                prevEl: $widget_id + " .swiper-button-prev",
                            },
                            pagination: {
                                el: $widget_id + " .swiper-pagination",
                                clickable: true,
                            },
                            centeredSlides: false,
                            allowTouchMove: true,
                            breakpoints: {
                                768: {
                                    slidesPerView: "<?php echo $slides_per_view_tablet; ?>",
                                    spaceBetween: <?php echo $space_between_tablet; ?>,
                                    slidesPerGroup: <?php echo $slides_to_scroll_tablet; ?>,
                                },
                                1200: {
                                    slidesPerView: "<?php echo $slides_per_view_desktop; ?>",
                                    spaceBetween: <?php echo $space_between_desktop; ?>,
                                    slidesPerGroup: <?php echo $slides_to_scroll_desktop; ?>,
                                },
                            },
                        };

                        <?php include _BUDI_PATH . '/template-parts/swiper/progress-bar-script.php'; ?>

                        const swiper = new Swiper($slider[0], settings);

                        <?php include _BUDI_PATH . '/template-parts/swiper/autoplay-on-view-script.php'; ?>
                    });
                })(jQuery);
            </script>

        <?php }

        return ob_get_clean();
    }

}

new BUDI_IMAGE_SLIDER_NEW();
