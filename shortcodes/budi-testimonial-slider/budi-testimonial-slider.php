<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_TESTIMONIAL_SLIDER extends BUDI_SHORTCODE_BASE
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
        return 'budi_testimonial_slider';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Testimonial Slider', _BUDI_TEXT_DOMAIN);
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
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => __('Icon', _BUDI_TEXT_DOMAIN),
                    "param_name" => "icon",
                    "value" => '',
                    "description" => __('Quote icon image (used for all testimonials)', _BUDI_TEXT_DOMAIN),
                ),
                array(
                    'type' => 'param_group',
                    'param_name' => 'testimonials',
                    'admin_label' => false,
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __('Title', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'title',
                            'admin_label' => true,
                            'description' => __('Professional role or title', _BUDI_TEXT_DOMAIN),
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Subtitle', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'subtitle',
                            'admin_label' => true,
                            'description' => __('Organization or company', _BUDI_TEXT_DOMAIN),
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Description', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'description',
                            'description' => __('Testimonial content', _BUDI_TEXT_DOMAIN),
                        ),
                    )
                ),

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
            'testimonials'        => '',
            'icon'               => '',
            'widget_class'       => '',
            'css'                => '',
        ], $atts);

        $widget_class      = sc_merge_css($atts['css'], $atts['widget_class']);
        $this_widget_id    = $this->get_widget_id(uniqid());

        $testimonials = vc_param_group_parse_atts($atts['testimonials']);
        if (is_array($testimonials) && count($testimonials) > 0 && count($testimonials) < 9) {
            $testimonials = array_merge($testimonials, $testimonials);
        }
        $icon = $atts['icon'];

        if ($testimonials) { ?>

            <div id="<?php echo esc_attr($this_widget_id); ?>" class="budi-testimonial-slider__wrapper <?php echo esc_attr($widget_class); ?>">
                <div class="budi-testimonial-slider transition-all-03s swiper overflow-visible">

                    <div class="swiper-wrapper">
                        <?php
                        foreach ($testimonials as $index => $testimonial) {
                            $title       = $testimonial['title'];
                            $subtitle    = $testimonial['subtitle'];
                            $description = $testimonial['description']; ?>

                            <div class="swiper-slide budi-testimonial-slider__item transition-all-03s">
                                <div class="budi-testimonial-slider__item-inner">

                                    <div class="d-flex budi-testimonial-slider__header mb-4">
                                        <?php if ($icon): ?>
                                            <div class="budi-testimonial-slider__quote-icon d-flex align-items-center justify-content-center">
                                                <?php echo wp_get_attachment_image($icon, 'thumbnail', false, ['class' => 'budi-testimonial-slider__icon transition-all-03s']); ?>
                                            </div>
                                        <?php endif; ?>

                                        <div class="budi-testimonial-slider__heading">
                                            <?php if ($title): ?>
                                                <h3 class="budi-testimonial-slider__title transition-all-03s">
                                                    <?php echo do_shortcode(nl2br($title)); ?>
                                                </h3>
                                            <?php endif; ?>

                                            <?php if ($subtitle): ?>
                                                <p class="budi-testimonial-slider__subtitle transition-all-03s">
                                                    <?php echo do_shortcode(nl2br($subtitle)); ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>

                                    </div>

                                    <div class="budi-testimonial-slider__content">

                                        <?php if ($description): ?>
                                            <div id="<?php echo esc_attr($this_widget_id . '-desc-' . $index); ?>" class="budi-testimonial-slider__description budi-testimonial-slider__description--clamped transition-all-03s">
                                                <?php echo do_shortcode($description); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- Navigation arrows -->
                    <div class="budi-testimonial-slider__navigation d-flex align-items-center justify-content-md-end justify-content-between">
                        <div class="swiper-button-prev position-relative m-0">
                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 7H17M17 7L11 1M17 7L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                        <div class="swiper-button-next position-relative m-0">
                            <svg width="18" height="14" viewBox="0 0 18 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 7H17M17 7L11 1M17 7L11 13" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>

                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $this_widget_id; ?>';
                        const $slider = $($widget_id + ' .budi-testimonial-slider');

                        let settings = {
                            slidesPerView: 'auto',
                            spaceBetween: 24,
                            loop: true,
                            speed: 1500,
                            effect: 'slide',
                            autoplay: {
                                delay: 5000,
                                pauseOnMouseEnter: true,
                                enabled: true // Start with autoplay disabled
                            },
                            navigation: {
                                nextEl: '.swiper-button-next',
                                prevEl: '.swiper-button-prev',
                            },
                            breakpoints: {
                                768: {
                                    spaceBetween: 15
                                },
                                1024: {
                                    spaceBetween: 20
                                },
                            },
                            on: {
                                init: function() {
                                    $slider.removeClass('loading');
                                    $slider.addClass('fade-in');
                                },
                                slideChange: function() {
                                    $slider.find('.swiper-slide').removeClass('slide-active');
                                    $slider.find('.swiper-slide-active').addClass('slide-active');
                                }
                            }
                        };

                        const swiper = new Swiper($slider[0], settings);

                        // Read more/less toggle for overflowing descriptions
                        const $descriptions = $($widget_id + ' .budi-testimonial-slider__description');
                        $descriptions.each(function() {
                            const $desc = $(this);
                            const descEl = this;
                            const $contentWrapper = $desc.closest('.budi-testimonial-slider__content');

                            // Helper to determine if clamped content overflows
                            const hasOverflow = () => descEl.scrollHeight > descEl.clientHeight + 1;

                            // Ensure clamped style is applied before measuring
                            $desc.addClass('budi-testimonial-slider__description--clamped');

                            // Defer measurement to ensure styles/layout are settled
                            setTimeout(() => {
                                if (hasOverflow()) {
                                    const controlId = $desc.attr('id') || '';
                                    const $toggle = $('<button type="button" class="budi-testimonial-slider__read-more" aria-expanded="false" aria-controls="' + controlId + '">Weiterlesen</button>');

                                    $toggle.on('click', function() {
                                        const isClamped = $desc.hasClass('budi-testimonial-slider__description--clamped');
                                        if (isClamped) {
                                            $desc.removeClass('budi-testimonial-slider__description--clamped');
                                            $toggle.attr('aria-expanded', 'true').text('Weniger');
                                        } else {
                                            $desc.addClass('budi-testimonial-slider__description--clamped');
                                            $toggle.attr('aria-expanded', 'false').text('Weiterlesen');
                                        }
                                    });

                                    $contentWrapper.append($toggle);
                                }
                            }, 0);
                        });
                    });
                })(jQuery);
            </script>

<?php }

        return ob_get_clean();
    }
}

new BUDI_TESTIMONIAL_SLIDER();
