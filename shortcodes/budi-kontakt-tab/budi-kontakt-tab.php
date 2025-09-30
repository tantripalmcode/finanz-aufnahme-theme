<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_KONTAKT_TAB extends BUDI_SHORTCODE_BASE
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
        return 'budi_kontakt_tab';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Kontakt Tab', _BUDI_TEXT_DOMAIN);
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists()
    {
        // Enqueue CSS & JS
        wp_enqueue_style($this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION);
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
                    "heading" => __('Image', _BUDI_TEXT_DOMAIN),
                    "param_name" => "kontakt_image",
                    "value" => '',
                ),
                array(
                    'type' => 'param_group',
                    'param_name' => 'kontakt_tabs',
                    'params' => array(
                        array(
                            'type' => 'textfield',
                            'heading' => __('Tab Text', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'tab_text',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textarea',
                            'heading' => __('Content / Shortcode', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'content_shortcode',
                            'admin_label' => true,
                        ),
                    ),
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

        // Shortcode Attribute definieren
        $atts = shortcode_atts([
            'kontakt_image' => '',
            'kontakt_tabs'  => '',
            'widget_class'  => '',
            'css'           => '',
        ], $atts);

        $widget_class  = sc_merge_css($atts['css'], $atts['widget_class']);
        $kontakt_image = $atts['kontakt_image'];
        $kontakt_tabs  = vc_param_group_parse_atts($atts['kontakt_tabs']);
        $uniqid        = uniqid();

        if ($kontakt_tabs) { ?>

            <div id="<?php echo esc_attr($this->widget_id . $uniqid); ?>" class="budi-kontakt-tab__wrapper <?php echo esc_attr($widget_class); ?>">
                <div class="row mx-0 flex-column-reverse flex-lg-row">
                    <div class="col-lg-7 px-0 <?php echo empty($kontakt_image) ? 'd-none' : ''; ?>">
                        <div class="budi-kontakt-tab__image"
                            <?php if (get_the_ID() !== 2318 && get_the_ID() !== 4754) : ?>
                            data-bg-url="<?php echo wp_get_attachment_url($kontakt_image); ?>"
                            <?php else : ?>
                            style="background-image: url('<?php echo esc_url(wp_get_attachment_url($kontakt_image)); ?>');"
                            <?php endif; ?>></div>
                    </div>

                    <div class="<?php echo empty($kontakt_image) ? 'col' : 'col-lg-5'; ?> px-0">
                        <div class="budi-kontakt-tab__right h-100">
                            <?php if (count($kontakt_tabs) > 1) : ?>
                                <ul class="budi-kontakt-tab__buttons list-unstyled nav nav-tabs border-0 d-inline-flex" role="tablist">
                                    <div class="tab-slider"></div>
                                    <?php foreach ($kontakt_tabs as $index => $kontakt_tab) { ?>

                                        <?php
                                        $tab_text           = $kontakt_tab['tab_text'];
                                        $tab_text_sanitize  = sanitize_title($tab_text);
                                        $tab_id             = "kontakt-tab-{$tab_text_sanitize}";
                                        $tab_arial_controls = "kontakt-pane-{$tab_text_sanitize}";
                                        ?>

                                        <li>
                                            <a id="<?php echo esc_attr($tab_id); ?>" href="#kontakt-pane-<?php echo esc_attr($tab_text_sanitize); ?>" class="w-100 text-center budi-kontakt-tab__button d-inline-block font-weight-bold <?php echo $index === 0 ? 'active' : ''; ?>" data-toggle="tab" role="tab" aria-controls="<?php echo esc_attr($tab_arial_controls); ?>" aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                                                <?php echo "<span>{$tab_text}</span>"; ?>
                                            </a>
                                        </li>

                                    <?php } ?>
                                </ul>
                            <?php endif; ?>

                            <div class="budi-kontakt-tab__right-inner">
                                <div class="tab-content" id="budi-kontakt-tab-content">
                                    <?php foreach ($kontakt_tabs as $index => $kontakt_tab) { ?>

                                        <?php
                                        $tab_text          = $kontakt_tab['tab_text'] ?? '';
                                        $tab_text_sanitize = sanitize_title($tab_text);
                                        $tab_content       = $kontakt_tab['content_shortcode'] ?? '';
                                        ?>

                                        <div class="budi-kontakt-tab__content tab-pane <?php echo $index === 0 ? 'active' : ''; ?>" id="kontakt-pane-<?php echo esc_attr($tab_text_sanitize); ?>" role="tabpanel" aria-labelledby="kontakt-pane-<?php echo esc_attr($tab_text_sanitize); ?>-tab">
                                            <?php if (get_the_ID() !== 2318 && get_the_ID() !== 4754) : ?>
                                                <!-- <div class="lazy-load-content" data-content="<?php echo esc_attr(do_shortcode($tab_content)); ?>"></div> -->
                                                <?php echo do_shortcode($tab_content); ?>
                                            <?php else : ?>
                                                <div><?php echo do_shortcode($tab_content); ?></div>
                                            <?php endif; ?>

                                            <?php if ($tab_text_sanitize === 'termin-buchen') {
                                                printf('<div class="budi-kontakt-tab__info-text text-white">Unsere Beratungstermine sind selbstverständlich unverbindlich und kostenlos.</div>');
                                            } ?>
                                        </div>

                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = '#<?php echo $this->widget_id . $uniqid; ?>';
                        const $widget_element = $($widget_id);
                        const $tab_button = $widget_element.find('.budi-kontakt-tab__button');
                        const $tab_content = $widget_element.find('.budi-kontakt-tab__content');
                        const url = window.location.href;
                        const parts = url.split('#');
                        const value = parts[1];

                        scroll_to_kontakt_section(value);

                        function scroll_to_kontakt_section(value) {
                            if ($widget_element.find('#kontakt-tab-' + value).length > 0) {
                                $tab_button.removeClass('active');
                                $tab_content.removeClass('active');
                                $widget_element.find('#kontakt-tab-' + value).addClass('active');
                                $widget_element.find('#kontakt-pane-' + value).addClass('active');

                                $("html, body").animate({
                                    scrollTop: $widget_element.parents('section').offset().top - $('#simplistic-header').outerHeight(),
                                }, 300);
                            }
                        }

                        const $buttons = $('.budi-kontakt-tab__button');
                        const $slider = $('.tab-slider');
                        
                        function moveSlider($targetButton) {
                            const buttonOffset = $targetButton.offset().left;
                            const buttonWidth = $targetButton.outerWidth();
                            const containerOffset = $('.budi-kontakt-tab__buttons').offset().left;
                            const newPosition = buttonOffset - containerOffset;
                            
                            $slider.css({
                                'transform': `translateX(${newPosition}px)`,
                                'width': buttonWidth - 4 + 'px' // Subtract padding
                            });
                        }

                        // On hover
                        $buttons.on('mouseenter', function() {
                            moveSlider($(this));
                        });

                        // Return to active button when mouse leaves the container
                        $('.budi-kontakt-tab__buttons').on('mouseleave', function() {
                            const $activeButton = $buttons.filter('.active');
                            moveSlider($activeButton);
                        });

                        // Initial position
                        moveSlider($buttons.filter('.active'));

                        // Handle click
                        $buttons.on('click', function() {
                            const $this = $(this);
                            const href = $this.attr('href');
                            $buttons.removeClass('active');
                            $this.addClass('active');
                            $widget_element.find('.budi-kontakt-tab__content').removeClass('active');
                            $widget_element.find(href).addClass('active');
                            moveSlider($this);
                        });

                        // Handle window resize
                        $(window).on('resize', function() {
                            moveSlider($buttons.filter('.active'));
                        });

                        // Function to check if element is in viewport
                        function isElementInViewport(el) {
                            const rect = el.getBoundingClientRect();
                            return (
                                rect.top >= 0 &&
                                rect.left >= 0 &&
                                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                            );
                        }

                        // Modify the scroll event listener
                        let isVisible = false;
                        let animationAdded = false;

                        $(window).on('scroll', function() {
                            const elementVisible = isElementInViewport($slider[0]);

                            // Element comes into view
                            if (elementVisible && !isVisible) {
                                isVisible = true;
                                
                                // Run animation if it hasn't run yet or if we're allowing it to run again
                                if (!animationAdded) {
                                    runSliderAnimation();
                                }
                            }
                            
                            // Element goes out of view
                            if (!elementVisible && isVisible) {
                                isVisible = false;
                                animationAdded = false; // Reset so animation can run again
                                $slider.removeClass('animate-initial');
                                $('.budi-kontakt-tab__button').css('color', ''); // Reset colors
                            }
                        });

                        function runSliderAnimation() {
                            animationAdded = true;
                            $slider.addClass('animate-initial');
                            $('.budi-kontakt-tab__button.active').css('color', 'var(--color-main)');
                            $('.budi-kontakt-tab__button:not(.active)').css('color', '#ffffff');
                            
                            // After initial animation completes
                            setTimeout(() => {
                                $slider.removeClass('animate-initial');
                                $('.budi-kontakt-tab__button').css('color', '');
                                
                            }, 1000); // Match this to initial animation duration
                        }

                        // Initial check on page load
                        $(window).trigger('scroll');
                    });
                })(jQuery);
            </script>


<?php }

        return ob_get_clean();
    }
}

new BUDI_KONTAKT_TAB();
