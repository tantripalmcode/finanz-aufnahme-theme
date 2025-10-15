<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant)
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_IMAGE_SLIDER extends BUDI_SHORTCODE_BASE {

    /**
     * get_name
     */
    protected function get_name() {
        return 'budi_image_slider';
    }

    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Image Slider', _BUDI_TEXT_DOMAIN );
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
                    "heading" => __( "Images", _BUDI_TEXT_DOMAIN ),
                    "param_name" => "images",
                    "value" => '',
                    "description" => __( "Enter description.", _BUDI_TEXT_DOMAIN )
                ),
                array(
                    "type" => "textarea",
                    "heading" => 'Links',
                    "param_name" => "links",
                    "value" => "",
                    "description" => __( "Ex. https://google.com, https://amazon.com", "budigital" ),
                ),
                array(
                    "type" => "textarea_raw_html",
                    "heading" => __( "Last Slide HTML", _BUDI_TEXT_DOMAIN ),
                    "param_name" => "last_slide_html",
                    "value" => "",
                    "description" => __( "HTML code to display on the last slide (e.g., badges, seals, etc.)", _BUDI_TEXT_DOMAIN ),
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Max Height", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'max_height',
                    'group' => 'Slider Settings',
                    'std' => '',
                    "description" => __( "Maximum height for slider images (e.g., 200px, 50vh)", _BUDI_TEXT_DOMAIN ),
                    'edit_field_class' => 'vc_col-sm-3',
                ),
                array(
                    "type" => "checkbox",
                    "class" => "",
                    "heading" => __( "Double Images", _BUDI_TEXT_DOMAIN ),
                    "param_name" => "double_images",
                    "value" => array(
                        __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                    ),
                    "description" => __( "If checked, the images will be duplicated (doubled) in the slider.", _BUDI_TEXT_DOMAIN )
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( "Speed Transition", _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'speed_transition',
                    'group' => 'Slider Settings',
                    'std' => 500,
                    "description" => __( "Transition speed in milliseconds. Please enter numbers only.", _BUDI_TEXT_DOMAIN ),
                    'edit_field_class' => 'vc_col-sm-3',
                ),
                ...$this->get_slider_style_options_controls(),
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
            'last_slide_html'          => '',
            'enable_fullwidth'         => '',
            'equal_height'             => '',
            'enable_rtl'               => '',
            'infinite_loop'            => '',
            'autoplay'                 => '',
            'autoplay_speed'           => 2500,
            'speed_transition'         => 500,
            'pause_on_hover'           => 'yes',
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
            'enable_dots_desktop'      => '',
            'enable_dots_tablet'       => '',
            'enable_dots_mobile'       => '',
            'enable_arrows_desktop'    => 'yes',
            'enable_arrows_tablet'     => '',
            'enable_arrows_mobile'     => '',
            'show_only_1_arrow'        => '',
            'widget_class'             => '',
            'css'                      => '',
            'double_images'            => '',
            'max_height'               => '',
        ], $atts );

        $widget_class   = sc_merge_css( $atts['css'], $atts['widget_class'] );

        $images                   = $atts['images'];
        $links                    = $atts['links'];
        $last_slide_html          = $atts['last_slide_html'];
        $enable_fullwidth         = $atts['enable_fullwidth'];
        $equal_height             = $atts['equal_height'];
        $enable_rtl               = $atts['enable_rtl'] ?? '';
        $infinite_loop            = $atts['infinite_loop'];
        $autoplay                 = $atts['autoplay'];
        $autoplay_speed           = $atts['autoplay_speed'];
        $speed_transition         = $atts['speed_transition'];
        $pause_on_hover           = $atts['pause_on_hover'];
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
        $enable_dots_desktop      = $atts['enable_dots_desktop'];
        $enable_dots_tablet       = $atts['enable_dots_tablet'];
        $enable_dots_mobile       = $atts['enable_dots_mobile'];
        $enable_arrows_desktop    = $atts['enable_arrows_desktop'];
        $enable_arrows_tablet     = $atts['enable_arrows_tablet'];
        $enable_arrows_mobile     = $atts['enable_arrows_mobile'];
        $show_only_1_arrow        = $atts['show_only_1_arrow'];
        $double_images            = $atts['double_images'];
        $max_height               = $atts['max_height'];

        ob_start();
        $uniqid = uniqid();

        if ( $images ) { ?>
            
            <?php if ( !empty( $max_height ) ) { ?>
                <style>
                    #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider__item img {
                        max-height: <?php echo esc_attr( $max_height ); ?>;
                        height: auto;
                        width: auto;
                        object-fit: contain;
                    }
                </style>
            <?php } ?>

            <div class="budi-image-slider__wrapper position-relative <?php echo $enable_fullwidth !== "yes" ? 'w-100' : ''; ?> <?php echo esc_attr( $widget_class ); ?>" id="<?php echo esc_attr( $this->widget_id . $uniqid ); ?>" <?php echo $enable_rtl === "yes" ? 'dir="rtl"' : ''; ?>>
                <div class="budi-image-slider swiper <?php echo $enable_fullwidth === 'yes' ? 'overflow-visible' : ''; ?>">
                    <div class="swiper-wrapper align-items-center">
                        <?php
                        $links  = explode( ",", $links );
                        $images = explode( ",", $images );
                        if ( $double_images === 'yes' ) {
                            $images = array_merge( $images, $images );
                            if (!empty($links)) {
                                $links = array_merge( $links, $links );
                            }
                        }

                        foreach( $images as $index => $image ) {
                            if( isset( $links[$index] ) && !empty( $links[$index] ) ){
                                echo sprintf( '<a href="%s" target="_blank" class="budi-image-slider__item swiper-slide text-center">%s</a>', esc_url( trim( $links[$index] ) ), wp_get_attachment_image( $image, 'medium_large', false, array( 'class' => 'budi-image-slider__image' ) ) );
                            } else {
                                echo sprintf( '<div class="budi-image-slider__item swiper-slide text-center">%s</div>', wp_get_attachment_image( $image, 'medium_large', false, array( 'class' => 'budi-image-slider__image' ) ) );
                            }
                        }

                        // Add last slide with HTML content if provided
                        if ( !empty( $last_slide_html ) ) {
                            // Decode URL-encoded HTML content
                            $decoded_html = base64_decode( $last_slide_html );
                            $decoded_html = rawurldecode( $decoded_html );
                            echo sprintf( '<div class="budi-image-slider__item swiper-slide text-center budi-image-slider__last-slide">%s</div>', $decoded_html );
                        }
                        ?>
                    </div>
                </div>

                <!-- Arrow & Pagination -->
                <div class="budi-swiper-arrow-new d-flex align-items-center justify-content-md-center justify-content-between mt-5">

                    <?php if( $enable_rtl ) { ?>
                        <div class="swiper-button-next swiper-arrow position-relative m-0 <?php echo ($enable_arrows_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_arrows_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_arrows_mobile ? 'd-block ' : 'd-none '); ?>">
                            <svg width="67" height="25" viewBox="0 0 67 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M58.654 11.8789L51.563 6.69701C51.0935 6.36566 50.3454 6.37517 49.8919 6.71827C49.4496 7.05294 49.4496 7.58356 49.8919 7.91823L54.9655 11.6259H34.1818C33.5291 11.6258 33 12.0125 33 12.4894C33 12.9664 33.5291 13.3531 34.1818 13.3531H54.9655L49.8919 17.0607C49.4224 17.3921 49.4094 17.9388 49.8628 18.2819C50.3163 18.625 51.0645 18.6345 51.5339 18.3031C51.5438 18.2961 51.5535 18.2891 51.563 18.2819L58.6539 13.1C59.1153 12.7628 59.1153 12.2161 58.654 11.8789Z" fill="#E0163C"/>
                                <line x1="8" y1="12.8" x2="56" y2="12.8" stroke="#E0163C" stroke-width="2.4"/>
                                <rect class="progress-border" x="1" y="1" width="65" height="23" rx="11.5" stroke="url(#paint0_linear_2004_147)" stroke-width="2"/>
                                <defs>
                                    <linearGradient id="paint0_linear_2004_147" x1="65" y1="8" x2="8" y2="4" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E0163C"/>
                                        <stop offset="1" stop-color="#630718"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    <?php } else { ?>
                        <div class="swiper-button-prev swiper-arrow position-relative m-0 <?php echo ($enable_arrows_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_arrows_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_arrows_mobile ? 'd-block ' : 'd-none '); ?>">
                            <svg width="52" height="20" viewBox="0 0 52 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2_1398)">
                                <path d="M0.47711 11.0357L7.63463 16.1709C8.10838 16.4991 8.85635 16.4797 9.3053 16.1277C9.74328 15.7842 9.73641 15.2489 9.28975 14.917L4.16855 11.2428L24.9505 10.9711C25.6032 10.9626 26.1272 10.5656 26.121 10.0844C26.1148 9.60315 25.5808 9.21998 24.9281 9.22851L4.14622 9.50022L9.17135 5.69332C9.63652 5.35288 9.6424 4.80108 9.18455 4.46091C8.72671 4.12069 7.97844 4.12091 7.51333 4.46135C7.50356 4.46851 7.49396 4.47574 7.48458 4.48313L0.461343 9.80375C0.00433959 10.15 0.0114226 10.7015 0.47711 11.0357Z" fill="#E0163C"/>
                                </g>
                                <line y1="-1.2" x2="48" y2="-1.2" transform="matrix(-0.999915 0.0130731 -0.0128417 -0.999918 51.0994 8.2334)" stroke="#E0163C" stroke-width="2.4"/>
                                <defs>
                                <clipPath id="clip0_2_1398">
                                <rect width="26" height="19.1704" fill="white" transform="matrix(-0.999915 0.0130731 -0.0128417 -0.999918 26.244 19.658)"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </div>
                    <?php } ?>

                    <div class="swiper-pagination position-relative width-auto m-0 <?php echo ($enable_dots_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_dots_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_dots_mobile ? 'd-block ' : 'd-none '); ?>"></div>

                    <?php if( $enable_rtl ) { ?>
                        <div class="swiper-button-prev swiper-arrow position-relative m-0 <?php echo ($enable_arrows_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_arrows_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_arrows_mobile ? 'd-block ' : 'd-none '); ?>">
                            <svg width="52" height="20" viewBox="0 0 52 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_2_1398)">
                                <path d="M0.47711 11.0357L7.63463 16.1709C8.10838 16.4991 8.85635 16.4797 9.3053 16.1277C9.74328 15.7842 9.73641 15.2489 9.28975 14.917L4.16855 11.2428L24.9505 10.9711C25.6032 10.9626 26.1272 10.5656 26.121 10.0844C26.1148 9.60315 25.5808 9.21998 24.9281 9.22851L4.14622 9.50022L9.17135 5.69332C9.63652 5.35288 9.6424 4.80108 9.18455 4.46091C8.72671 4.12069 7.97844 4.12091 7.51333 4.46135C7.50356 4.46851 7.49396 4.47574 7.48458 4.48313L0.461343 9.80375C0.00433959 10.15 0.0114226 10.7015 0.47711 11.0357Z" fill="#E0163C"/>
                                </g>
                                <line y1="-1.2" x2="48" y2="-1.2" transform="matrix(-0.999915 0.0130731 -0.0128417 -0.999918 51.0994 8.2334)" stroke="#E0163C" stroke-width="2.4"/>
                                <defs>
                                <clipPath id="clip0_2_1398">
                                <rect width="26" height="19.1704" fill="white" transform="matrix(-0.999915 0.0130731 -0.0128417 -0.999918 26.244 19.658)"/>
                                </clipPath>
                                </defs>
                            </svg>
                        </div>
                    <?php } else { ?>
                        <div class="swiper-button-next swiper-arrow position-relative m-0 <?php echo ($enable_arrows_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_arrows_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_arrows_mobile ? 'd-block ' : 'd-none '); ?>">
                            <svg width="67" height="25" viewBox="0 0 67 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M58.654 11.8789L51.563 6.69701C51.0935 6.36566 50.3454 6.37517 49.8919 6.71827C49.4496 7.05294 49.4496 7.58356 49.8919 7.91823L54.9655 11.6259H34.1818C33.5291 11.6258 33 12.0125 33 12.4894C33 12.9664 33.5291 13.3531 34.1818 13.3531H54.9655L49.8919 17.0607C49.4224 17.3921 49.4094 17.9388 49.8628 18.2819C50.3163 18.625 51.0645 18.6345 51.5339 18.3031C51.5438 18.2961 51.5535 18.2891 51.563 18.2819L58.6539 13.1C59.1153 12.7628 59.1153 12.2161 58.654 11.8789Z" fill="#E0163C"/>
                                <line x1="8" y1="12.8" x2="56" y2="12.8" stroke="#E0163C" stroke-width="2.4"/>
                                <rect class="progress-border" x="1" y="1" width="65" height="23" rx="11.5" stroke="url(#paint0_linear_2004_147)" stroke-width="2"/>
                                <defs>
                                    <linearGradient id="paint0_linear_2004_147" x1="65" y1="8" x2="8" y2="4" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#E0163C"/>
                                        <stop offset="1" stop-color="#630718"/>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>
                    <?php } ?>
                </div>

            </div>

            <?php if( $slides_per_view_desktop === "auto" && $max_width_image_desktop ){ ?>
                <style>
                    #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider__item{
                        max-width: <?php echo esc_attr( $max_width_image_desktop ); ?>;
                    }
                </style>
            <?php } ?>

            <?php if( $slides_per_view_tablet === "auto" && $max_width_image_tablet ){ ?>
                <style>
                    @media only screen and (max-width: 1200px) {
                        #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider__item{
                            max-width: <?php echo esc_attr( $max_width_image_tablet ); ?>;
                        }
                    }
                </style>
            <?php } ?>

            <?php if( $slides_per_view_mobile === "auto" && $max_width_image_mobile ){ ?>
                <style>
                    @media only screen and (max-width: 767px) {
                        #<?php echo $this->widget_id . $uniqid; ?> .budi-image-slider__item{
                            max-width: <?php echo esc_attr( $max_width_image_mobile ); ?>;
                        }
                    }
                </style>
            <?php } ?>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const width_container = $('.page-content').outerWidth();
                        const $widget_id = '#<?php echo $this->widget_id . $uniqid; ?>';
                        const $slider = $widget_id + ' .budi-image-slider';
                        const $section_wrapper = $($slider).parents('.vc_section');
                        let bg_section_color = $section_wrapper.css("background-color");
                        if(bg_section_color === 'rgba(0, 0, 0, 0)'){
                            bg_section_color = '#FFFFFF';
                        }
                        const $column_content_slider = $section_wrapper.find('.budi-slider-content__column');

                        if ( $column_content_slider.length > 0 ) {
                            $column_content_slider.addClass('position-relative').css('z-index', 9);

                            let overlay_css = '';
                            if($column_content_slider.index() === 1){
                                $column_content_slider.append('<div class="budi-slider-content__column-overlay position-absolute" style="left: 0; background-color:'+bg_section_color+';"></div>');
                            }else{
                                $column_content_slider.append('<div class="budi-slider-content__column-overlay position-absolute" style="right: 0; background-color:'+bg_section_color+';"></div>');
                            }
                        }

                        <?php //if( $enable_fullwidth === "yes" ){ ?>
                            // $(window).on("resize load scroll", function() {
                            //     const $slider_element = $($slider);

                            //     $slider_element.css('overflow', 'visible');
                            //     const $parent_element = $slider_element.parents('.vc_column-inner');
                            //     const $parent_element_width = $parent_element.outerWidth();

                            //     if($(window).width() > 767){
                            //         $slider_element.css("width", "calc((100vw - " + width_container + "px) / 2 + " + $parent_element_width + "px)");
                            //     }else{
                            //         $slider_element.css("width", "100%");
                            //     }
                            // });
                        <?php //} ?>

                        let settings = {
                            slidesPerView: "<?php echo $slides_per_view_mobile; ?>",
                            spaceBetween: <?php echo $space_between_mobile ?>,
                            slidesPerGroup: <?php echo $slides_to_scroll_mobile; ?>,
                            loop: <?php echo $infinite_loop === "yes" ? 1 : 0; ?>,
                            pagination: {
                                el: $widget_id + " .swiper-pagination",
                                clickable: true,
                            },
                            speed: <?php echo $speed_transition; ?>,
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

                        <?php if($enable_rtl === "yes"){ ?>
                            settings = {
                                ...settings,
                                navigation: {
                                    nextEl: $widget_id + " .swiper-button-prev",
                                    prevEl: $widget_id + " .swiper-button-next",
                                },
                            };
                        <?php } else { ?>
                            settings = {
                                ...settings,
                                navigation: {
                                    nextEl: $widget_id + " .swiper-button-next",
                                    prevEl: $widget_id + " .swiper-button-prev",
                                },
                            };
                        <?php } ?>

                        <?php if ($autoplay === "yes") { ?>
                            <?php if ($autoplay_speed == 0) { ?>
                                // Continuous sliding when delay is 0
                                settings = {
                                    ...settings,
                                    autoplay: {
                                        delay: 1,
                                        disableOnInteraction: false,
                                    },
                                    freeMode: {
                                        enabled: true,
                                        momentum: false,
                                    },
                                };
                            <?php } else { ?>
                                settings = {
                                    ...settings,
                                    autoplay: {
                                        delay: <?php echo $autoplay_speed; ?>,
                                    },
                                };
                            <?php } ?>
                        <?php } ?>

                        <?php // get_template_part( 'template-parts/swiper/progress-bar-script' ); ?>

                        const swiper = new Swiper($slider, settings);

                        <?php if ($autoplay === "yes" && $pause_on_hover === "yes") { ?>
                            $($slider).mouseenter(function() {
                                swiper.autoplay.stop();
                            });

                            $($slider).mouseleave(function() {
                                swiper.autoplay.start();
                            });
                        <?php } ?>
                    });
                })(jQuery);
            </script>

        <?php }

        return ob_get_clean();
    }

}

new BUDI_IMAGE_SLIDER();
