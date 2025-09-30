<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_IMAGE_BOX_SLIDER extends BUDI_SHORTCODE_BASE
{
    /**
     * __construct
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * get_name
     */
    protected function get_name()
    {
        return 'budi_image_box_slider';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Image Box Slider', _BUDI_TEXT_DOMAIN);
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists()
    {
        // Enqueue CSS & JS
        wp_enqueue_style('swiper');
        wp_enqueue_style($this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION);
        wp_enqueue_script('swiper');
    }

    /**
     * register_controls
     */
    public function register_controls()
    {
        $args = array(
            'name' => $this->widget_title,
            'base' => $this->widget_name,
            'category' => _BUDI_CATEGORY_WIDGET_NAME,
            'content_element' => true,
            "show_settings_on_create" => false,
            "is_container" => false,
            'params' => array(
                array(
                    'type' => 'param_group',
                    'param_name' => 'items',
                    'admin_label' => false,
                    'params' => array(
                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => __('Image', _BUDI_TEXT_DOMAIN),
                            "param_name" => "image",
                            "value" => '',
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Title', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            "type" => "textarea",
                            "holder" => "div",
                            "class" => "",
                            "heading" => __('Content', _BUDI_TEXT_DOMAIN),
                            "param_name" => "description",
                        ),
                    )
                ),

                ...$this->get_image_style_options_controls(),
                ...$this->get_title_style_options_controls(),
                ...$this->get_description_style_options_controls(),
                ...$this->get_design_options_controls(),
            ),
        );

        vc_map($args);
    }

    /**
     * render_view
     */
    public function render_view($atts, $content = null)
    {
        ob_start();

        $atts = shortcode_atts([
            'items'             => '',
            'title_class'       => '',
            'title_heading_tag' => 'h2',
            'image_size'        => 'large',
            'image_size_custom' => '',
            'image_class'       => '',
            'description_class' => '',
            'widget_class'      => '',
            'css'               => '',
        ], $atts);

        $widget_class      = sc_merge_css($atts['css'], $atts['widget_class']);
        $this_widget_id    = $this->get_widget_id(uniqid());
        $title_class       = $atts['title_class'];
        $title_heading_tag = $atts['title_heading_tag'];
        $title_heading_tag = $atts['title_heading_tag'];
        $image_size        = $atts['image_size'];
        $image_size_custom = $atts['image_size_custom'];
        $image_class       = $atts['image_class'];
        $description_class = $atts['description_class'];

        $items = vc_param_group_parse_atts($atts['items']);

        if ($items) { ?>

            <div id="<?php echo esc_attr($this_widget_id); ?>" class="budi-image-box-slider__wrapper  <?php echo esc_attr($widget_class); ?>">
                <div class="budi-image-box-slider transition-all-03s swiper overflow-visible">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($items as $index => $item) {
                            $number      = str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                            $title       = $item['title'];
                            $description = $item['description'];
                            $image       = $item['image']; ?>

                            <div class="swiper-slide budi-image-box-slider__item transition-all-03s d-flex align-items-end">
                                <div class="budi-image-box-slider__item-inner">
                                    <?php if ($image) echo wp_get_attachment_image($image, 'large', false, ['class' => 'budi-image-box-slider__image transition-all-03s']); ?>

                                    <div class="budi-image-box-slider__content">
                                        <div class="budi-image-box-slider__content-top mb-3 d-flex align-items-end">
                                            <p class="budi-image-box-slider__number mb-0 font-weight-semi-bold d-inline-block transition-all-03s"><?php echo $number; ?></p>
                                            <<?php echo $title_heading_tag; ?> class="budi-image-box-slider__title text-color-main transition-all-03s <?php echo esc_attr( $title_class ); ?>">
                                                <?php echo do_shortcode( nl2br( $title ) ); ?>
                                            </<?php echo $title_heading_tag; ?>>
                                        </div>
                                        <div class="budi-image-box-slider__desc transition-all-03s <?php echo esc_attr( $description_class ); ?>">
                                            <?php echo do_shortcode( $description ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Navigation & Pagination -->
                <?php get_template_part( 'template-parts/swiper/navigation' ); ?>
            </div>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $this_widget_id; ?>';
                        const $slider = $($widget_id + ' .budi-image-box-slider');

                        let settings = {
                            slidesPerView: 1,
                            loop: false,
                            centeredSlides: true,
                            autoHeight: false,
                            spaceBetween: 24,
                            navigation: {
                                nextEl: $widget_id + " .swiper-button-next",
                                prevEl: $widget_id + " .swiper-button-prev",
                            },
                            pagination: {
                                el: $widget_id + " .swiper-pagination",
                                clickable: true,
                            },
                            breakpoints: {
                                768: {
                                    slidesPerView: 2,
                                },
                                1024: {
                                    slidesPerView: "auto",
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

new BUDI_IMAGE_BOX_SLIDER();
