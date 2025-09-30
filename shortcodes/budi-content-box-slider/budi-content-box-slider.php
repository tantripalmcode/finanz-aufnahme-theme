<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_CONTENT_BOX_SLIDER extends BUDI_SHORTCODE_BASE {

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
        return 'budi_content_box_slider';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Content Box Slider', _BUDI_TEXT_DOMAIN );
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
            "params" => array(
                array(
                    'type' => 'param_group',
                    'param_name' => 'content_box_slider_items',
                    'params' => array(
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Type', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'type',
                            'value' => array(
                                'Image' => 'image',
                                'Number' => 'number',
                            ),
                            'std' => "image"
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Number', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'number',
                            'admin_label' => true,
                            "dependency" => array(
                                "element" => "type",
                                "value" => "number",
                            )
                        ),
                        array(
                            'type' => 'dropdown',
                            'heading' => __( 'Use SVG Code', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'use_svg_code',
                            'value' => array(
                                'Yes' => 'yes',
                                'No' => 'no',
                            ),
                            'std' => "no",
                            "dependency" => array(
                                "element" => "type",
                                "value" => "image",
                            )
                        ),
                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => __( 'Image', _BUDI_TEXT_DOMAIN ),
                            "param_name" => "image",
                            "value" => '',
                            "dependency" => array(
                                "element" => "type",
                                "value" => "image",
                            )
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __( 'Title', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __( 'Sub Title', _BUDI_TEXT_DOMAIN ),
                            'param_name' => 'sub_title',
                            'admin_label' => true,
                        ),
                        array(
                            "type" => "textarea",
                            "holder" => "div",
                            "class" => "",
                            "heading" => __( 'Content', _BUDI_TEXT_DOMAIN ),
                            "param_name" => "description",
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
                
                array(
                    "type" => "checkbox",
                    "class" => "",
                    "heading" => __( 'Make link for whole box', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "make_link_whole_box",
                    "description" => __( 'If checked the whole box will be linked.', _BUDI_TEXT_DOMAIN )
                ),

                ...$this->get_slider_style_options_controls(),
                ...$this->get_image_style_options_controls(),
                ...$this->get_title_style_options_controls(),
                ...$this->get_sub_title_style_options_controls(),
                ...$this->get_description_style_options_controls(),
                ...$this->get_button_style_options_controls(),
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
            'content_box_slider_items' => '',
            'make_link_whole_box'      => '',
            'title_class'              => '',
            'title_heading_tag'        => 'h2',
            'sub_title_class'          => '',
            'sub_title_heading_tag'    => 'span',
            'sub_title_position'       => 'after_title',
            'image_size'               => 'large',
            'image_size_custom'        => '',
            'image_class'              => '',
            'description_class'        => '',
            'button_class'             => '',
            'equal_height'             => '',
            'infinite_loop'            => '',
            'autoplay'                 => '',
            'autoplay_speed'           => 2500,
            'pause_on_hover'           => 'yes',
            'space_between_desktop'    => 20,
            'space_between_tablet'     => 20,
            'space_between_mobile'     => 20,
            'slides_per_view_desktop'  => 4,
            'slides_per_view_tablet'   => 2,
            'slides_per_view_mobile'   => 1,
            'slides_to_scroll_desktop' => 1,
            'slides_to_scroll_tablet'  => 1,
            'slides_to_scroll_mobile'  => 1,
            'enable_dots_desktop'      => '',
            'enable_dots_tablet'       => '',
            'enable_dots_mobile'       => '',
            'enable_arrows_desktop'    => 'yes',
            'enable_arrows_tablet'     => '',
            'enable_arrows_mobile'     => '',
            'widget_class'             => '',
            'css'                      => '',
        ], $atts );

        $widget_class               = sc_merge_css( $atts['css'], $atts['widget_class'] );
        $make_link_whole_box        = isset( $atts['make_link_whole_box'] ) ? $atts['make_link_whole_box'] : false;
        $equal_height               = $atts['equal_height'];
        $infinite_loop              = $atts['infinite_loop'];
        $autoplay                   = $atts['autoplay'];
        $autoplay_speed             = $atts['autoplay_speed'];
        $pause_on_hover             = $atts['pause_on_hover'];
        $space_between_desktop      = $atts['space_between_desktop'];
        $space_between_tablet       = $atts['space_between_tablet'];
        $space_between_mobile       = $atts['space_between_mobile'];
        $slides_per_view_desktop    = $atts['slides_per_view_desktop'];
        $slides_per_view_tablet     = $atts['slides_per_view_tablet'];
        $slides_per_view_mobile     = $atts['slides_per_view_mobile'];
        $slides_to_scroll_desktop   = $atts['slides_to_scroll_desktop'];
        $slides_to_scroll_tablet    = $atts['slides_to_scroll_tablet'];
        $slides_to_scroll_mobile    = $atts['slides_to_scroll_mobile'];
        $enable_dots_desktop        = $atts['enable_dots_desktop'];
        $enable_dots_tablet         = $atts['enable_dots_tablet'];
        $enable_dots_mobile         = $atts['enable_dots_mobile'];
        $enable_arrows_desktop      = $atts['enable_arrows_desktop'];
        $enable_arrows_tablet       = $atts['enable_arrows_tablet'];
        $enable_arrows_mobile       = $atts['enable_arrows_mobile'];

        ob_start();

        $items            = vc_param_group_parse_atts( $atts['content_box_slider_items'] );
        $item_add_class   = "";

        if ( $items ) {
            unset( $atts['content_box_slider_items'] );
            $item_add_class .= $equal_height === "yes" ? ' h-auto' : ''; ?>

            <section id="<?php echo esc_attr( $this->widget_id ); ?>" class="budi-content-box-slider__wrapper <?php echo esc_attr( $widget_class ); ?>">
                <div class="swiper budi-content-image-slider">
                    <div class="swiper-wrapper">
                        <?php foreach( $items as $item ) { ?>

                            <?php
                            $link                   = isset( $item['link'] ) ? $item['link'] : '';
                            $budi_build_link        = $this->budi_vc_build_link( $link );
                            $link_url               = $budi_build_link['link_url'];
                            $link_target            = $budi_build_link['link_target'];
                            $link_rel               = $budi_build_link['link_rel'];
                            $link_title             = $budi_build_link['link_title'];

                            $item_args = array_merge( $item, $atts,
                                array( 
                                    'link_url' => $link_url,
                                    'link_target' => $link_target,
                                    'link_rel' => $link_rel,
                                    'link_title' => $link_title,
                                    'make_link_whole_box' => $make_link_whole_box,
                                ) 
                            );
                            ?>

                            <?php if( $link_url && $make_link_whole_box ) { ?>

                                <a class="budi-content-image-slider__item swiper-slide <?php echo esc_attr( $item_add_class ); ?>" 
                                    href="<?php echo esc_url( $link_url ); ?>" 
                                    target="<?php echo esc_attr( $link_target ); ?>" rel="<?php echo esc_attr( $link_rel ); ?>">

                                    <?php get_template_part( 'shortcodes/budi-content-box-slider/budi-content-box-slider', 'template', $item_args ); ?>
                                </a>

                            <?php } else { ?>

                                <div class="budi-content-image-slider__item swiper-slide <?php echo esc_attr( $item_add_class ); ?>">

                                    <?php get_template_part( 'shortcodes/budi-content-box-slider/budi-content-box-slider', 'template', $item_args ); ?>

                                </div>

                            <?php } ?>

                        <?php } ?>
                    </div>

                    <!-- Arrow & Pagination -->
                    <div class="budi-swiper-arrow d-flex align-items-center justify-content-md-center justify-content-between mt-5">

                        <div class="swiper-button-prev swiper-arrow position-relative m-0 <?php echo ($enable_arrows_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_arrows_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_arrows_mobile ? 'd-block ' : 'd-none '); ?>"></div>

                        <div class="swiper-pagination position-relative width-auto m-0 <?php echo ($enable_dots_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_dots_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_dots_mobile ? 'd-block ' : 'd-none '); ?>"></div>

                        <div class="swiper-button-next swiper-arrow position-relative m-0 <?php echo ($enable_arrows_desktop ? 'd-xl-block ' : 'd-xl-none ') . ($enable_arrows_tablet ? 'd-md-block ' : 'd-md-none ') . ($enable_arrows_mobile ? 'd-block ' : 'd-none '); ?>"></div>

                    </div>
                </div>
            </section>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $this->widget_id; ?>';
                        const $slider = $widget_id + ' .budi-content-image-slider';

                        let settings = {
                            slidesPerView: <?php echo $slides_per_view_mobile; ?>,
                            spaceBetween: <?php echo $space_between_mobile ?>,
                            slidesPerGroup: <?php echo $slides_to_scroll_mobile; ?>,
                            loop: <?php echo $infinite_loop === "yes" ? 1 : 0; ?>,
                            pagination: {
                                el: $widget_id + " .swiper-pagination",
                                dynamicBullets: true,
                                clickable: true,
                            },
                            navigation: {
                                nextEl: $widget_id + " .swiper-button-next",
                                prevEl: $widget_id + " .swiper-button-prev",
                            },
                            breakpoints: {
                                768: {
                                    slidesPerView: <?php echo $slides_per_view_tablet; ?>,
                                    spaceBetween: <?php echo $space_between_tablet; ?>,
                                    slidesPerGroup: <?php echo $slides_to_scroll_tablet; ?>,
                                },
                                1200: {
                                    slidesPerView: <?php echo $slides_per_view_desktop; ?>,
                                    spaceBetween: <?php echo $space_between_desktop; ?>,
                                    slidesPerGroup: <?php echo $slides_to_scroll_desktop; ?>,
                                },
                            },
                        };

                        <?php if ($autoplay === "yes") { ?>
                            settings = {
                                ...settings,
                                autoplay: {
                                    delay: <?php echo $autoplay_speed; ?>,
                                },
                            };
                        <?php } ?>

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

new BUDI_CONTENT_BOX_SLIDER();