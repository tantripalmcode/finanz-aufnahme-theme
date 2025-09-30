<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_ICON_BOX_GRID extends BUDI_SHORTCODE_BASE
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
        return 'budi_icon_box_grid';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Icon Box Grid', _BUDI_TEXT_DOMAIN);
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
                    'type' => 'dropdown',
                    'heading' => __('Layout', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'structure_layout',
                    'value' => array(
                        1 => 1,
                        2 => 2,
                    ),
                    'std' => 1,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Gap', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'gap',
                    'admin_label' => true,
                    'std' => '30px',
                ),
                array(
                    'type' => 'param_group',
                    'param_name' => 'items',
                    'admin_label' => false,
                    'params' => array(
                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => __('Icon', _BUDI_TEXT_DOMAIN),
                            "param_name" => "icon",
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

                // Columns
                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __("Columns (Desktop)", _BUDI_TEXT_DOMAIN),
                    'param_name' => 'columns_desktop',
                    'value' => array(1, 2, 3, 4, 5, 6),
                    'std' => 3,
                    'group' => 'Grid Layout',
                ),

                array(
                    'admin_label' => true,
                    'type' => 'dropdown',
                    'heading' => __("Items per Slide", _BUDI_TEXT_DOMAIN),
                    'param_name' => 'items_per_slide',
                    'value' => array(1, 2, 3, 4, 5, 6),
                    'std' => 3,
                    'group' => 'Mobile Slider',
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
            'structure_layout'  => 1,
            'gap'               => '30px',
            'items'             => '',
            'image_size'        => 'large',
            'image_class'       => '',
            'title_class'       => '',
            'title_heading_tag' => 'h2',
            'description_class' => '',
            'columns_desktop'   => 3,
            'items_per_slide'   => 3,
            'widget_class'      => '',
            'css'               => '',
        ], $atts);

        $items             = vc_param_group_parse_atts($atts['items']);
        $widget_class      = sc_merge_css($atts['css'], $atts['widget_class']);
        $image_size        = $atts['image_size'];
        $image_class       = $atts['image_class'];
        $title_class       = $atts['title_class'];
        $title_heading_tag = $atts['title_heading_tag'];
        $description_class = $atts['description_class'];
        $columns_desktop   = $atts['columns_desktop'];
        $items_per_slide   = $atts['items_per_slide'];
        $structure_layout  = $atts['structure_layout'];
        $gap               = $atts['gap'];
        $widget_id         = $this->widget_id . uniqid();

        if ($items) { ?>
            <div id="<?php echo esc_attr($widget_id); ?>" class="budi-icon-box-grid__wrapper <?php echo esc_attr($widget_class); ?>">
                <!-- Desktop -->
                <?php
                $row_class   = "budi-icon-box-grid__row row row-cols-md-{$columns_desktop} d-none d-md-flex";
                $count_items = count($items);
                ?>
                <div class="<?php echo esc_attr($row_class); ?>">
                    <?php foreach ($items as $item) { ?>

                        <?php
                        $item['image_size']        = $image_size;
                        $item['image_class']       = $image_class;
                        $item['title_heading_tag'] = $title_heading_tag;
                        $item['title_class']       = $title_class;
                        $item['description_class'] = $description_class;
                        $item['for_mobile']        = false;
                        ?>

                        <div class="col">
                            <?php get_template_part('shortcodes/budi-icon-box-grid/layout/layout', $structure_layout, $item); ?>
                        </div>

                    <?php } ?>
                </div>

                <!-- Mobile -->
                <div class="d-md-none">
                    <div class="swiper budi-icon-box__slider">
                        <div class="swiper-wrapper">
                            <?php
                            $slide_counter = 0;
                            foreach ($items as $item) {

                                if ($slide_counter % $items_per_slide === 0) echo '<div class="swiper-slide">';
                                $item['image_size']        = $image_size;
                                $item['image_class']       = $image_class;
                                $item['title_heading_tag'] = $title_heading_tag;
                                $item['title_class']       = $title_class;
                                $item['description_class'] = $description_class;
                                $item['for_mobile']        = true;

                                get_template_part('shortcodes/budi-icon-box-grid/layout/layout', $structure_layout, $item);

                                $slide_counter++;

                                if ($slide_counter % $items_per_slide === 0 || $slide_counter === count($items)) echo '</div>';
                            }
                            ?>
                        </div>

                        <!-- Navigation & Pagination -->
                        <?php get_template_part('template-parts/swiper/navigation'); ?>
                    </div>
                </div>
            </div>

            <?php if ($gap) { ?>
                <style>
                    #<?php echo $widget_id; ?> .budi-icon-box-grid__row {
                        row-gap: <?php echo $gap; ?>;
                    }
                </style>
            <?php } ?>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $widget_id; ?>';
                        const $slider = $($widget_id + ' .budi-icon-box__slider');

                        let settings = {
                            slidesPerView: 1,
                            loop: false,
                            autoHeight: true,
                            centeredSlides: false,
                            spaceBetween: 10,
                            pagination: {
                                el: $widget_id + " .swiper-pagination",
                                clickable: true,
                                dynamicBullets: true,
                                dynamicMainBullets: 5,
                            },
                            autoplay: {
                                delay: 2500,
                                disableOnInteraction: false,
                            },
                        };

                        // Initialize Swiper
                        const swiper = new Swiper($slider[0], settings);

                        // Pause autoplay initially
                        swiper.autoplay.stop();

                        // Intersection Observer to detect when the slider is in the viewport
                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    // Start autoplay when the slider is in the viewport
                                    swiper.autoplay.start();
                                } else {
                                    // Stop autoplay when the slider is out of the viewport
                                    swiper.autoplay.stop();
                                }
                            });
                        }, {
                            threshold: 0.5, // Adjust this value to control when the autoplay starts/stops
                            rootMargin: '0px', // Adjust this to add a margin around the viewport
                        });

                        // Observe the Swiper element
                        observer.observe($slider[0]);
                    });
                })(jQuery);
            </script>

<?php }

        return ob_get_clean();
    }
}

new BUDI_ICON_BOX_GRID();
