<?php
if (! defined('ABSPATH')) {
    die('-1');
}

require "budi-benefit-tabs-item.php";

function sc_budi_benefit_tabs($atts, $content = null)
{
    ob_start();

    $atts = shortcode_atts([
        'widget_class' => '',
        'css'          => '',
        'image_max_width' => '250px',
    ], $atts);

    $widget_class  = sc_merge_css($atts['css'], $atts['widget_class']);

    budi_add_style('budi-benefit-tabs', this_dir_url(__FILE__) . 'budi-benefit-tabs.css', [], _BUDI_VERSION);
    budi_add_script('budi-benefit-tabs', this_dir_url(__FILE__) . 'budi-benefit-tabs.js', ['jquery'], _BUDI_VERSION, true);

    global $sc_benefit_tabs;
    $sc_benefit_tabs = array();

    do_shortcode($content);

    $widget_id = 'budi-benefit-tabs-' . uniqid();

    if ($sc_benefit_tabs) { ?>

        <div id="<?php echo esc_attr($widget_id); ?>" class="budi-benefit-tabs__wrapper budi-linear-background <?php echo esc_attr($widget_class); ?>">

            <div class="budi-benefit-tabs__container">

                <!-- Tab Navigation -->
                <div class="budi-benefit-tabs__nav-wrapper text-center">
                    <div class="budi-benefit-tabs__nav d-inline-flex justify-content-center mx-auto">
                        <?php foreach ($sc_benefit_tabs as $index => $item) {
                            $tab_text = $item['tab_text'];
                            $active_class = $index === 0 ? 'active' : '';
                        ?>
                            <button class="budi-benefit-tabs__nav-item btn <?php echo $active_class; ?>" data-tab="<?php echo $index; ?>">
                                <?php echo esc_html($tab_text); ?>
                            </button>
                        <?php } ?>
                    </div>
                </div>

                <!-- Floating Icon Container (only one, will be updated by JavaScript) -->
                <div class="budi-benefit-tabs__floating-icon-container">
                    <?php
                    // Show floating icon only for the first active tab
                    $first_item = $sc_benefit_tabs[0] ?? null;
                    if ($first_item && isset($first_item['floating_icon']) && $first_item['floating_icon']) {
                        $icon_position = isset($first_item['icon_position']) ? $first_item['icon_position'] : 'right';
                        $floating_icon_width = isset($first_item['floating_icon_width']) ? $first_item['floating_icon_width'] : '80px';
                        $floating_icon_width_mobile = isset($first_item['floating_icon_width_mobile']) ? $first_item['floating_icon_width_mobile'] : '60px';
                    ?>
                        <div class="budi-benefit-tabs__floating-icon budi-benefit-tabs__floating-icon--<?php echo esc_attr($icon_position); ?>" 
                             style="--icon-width: <?php echo esc_attr($floating_icon_width); ?>; --icon-width-mobile: <?php echo esc_attr($floating_icon_width_mobile); ?>;">
                            <?php echo wp_get_attachment_image($first_item['floating_icon'], 'medium', false, ['class' => 'budi-benefit-tabs__floating-icon-img', 'style' => 'max-width: ' . esc_attr($floating_icon_width) . ';']); ?>
                        </div>
                    <?php } ?>
                </div>

                <!-- Tab Content -->
                <div class="budi-benefit-tabs__content">
                    <?php foreach ($sc_benefit_tabs as $index => $item) {
                        $title                  = $item['title'];
                        $content_text           = $item['content'];
                        $button                 = vc_build_link($item['button']);
                        $button_position        = isset($item['button_position']) ? $item['button_position'] : 'left';
                        $image                  = $item['image'];
                        $image_position         = isset($item['image_position']) ? $item['image_position'] : 'right';
                        $image_max_width        = isset($item['image_max_width']) ? $item['image_max_width'] : '250px';
                        $floating_icon          = isset($item['floating_icon']) ? $item['floating_icon'] : '';
                        $icon_position          = isset($item['icon_position']) ? $item['icon_position'] : 'right';
                        $floating_icon_width    = isset($item['floating_icon_width']) ? $item['floating_icon_width'] : '80px';
                        $floating_icon_width_mobile = isset($item['floating_icon_width_mobile']) ? $item['floating_icon_width_mobile'] : '60px';
                        $button_alignment       = isset($item['button_alignment']) ? $item['button_alignment'] : 'left';
                        $active_class           = $index === 0 ? 'active' : '';

                        $button_url             = $button['url'] ?: '';
                        $button_target          = $button['target'] ?: '';
                        $button_text            = $button['title'] ?: 'Jetzt App downloaden';

                        // Determine column order based on image position
                        $content_col_class      = $image_position === 'left' ? 'col-lg-7 order-lg-2' : 'col-lg-7 order-lg-1';
                        $image_col_class        = $image_position === 'left' ? 'col-lg-5 order-lg-1' : 'col-lg-5 order-lg-2';
                    ?>

                        <div class="budi-benefit-tabs__content-item <?php echo $active_class; ?>"
                            data-tab="<?php echo $index; ?>"
                            data-floating-icon="<?php echo $floating_icon ? wp_get_attachment_url($floating_icon) : ''; ?>"
                            data-icon-position="<?php echo esc_attr($icon_position); ?>"
                            data-icon-width="<?php echo esc_attr($floating_icon_width); ?>"
                            data-icon-width-mobile="<?php echo esc_attr($floating_icon_width_mobile); ?>"
                            data-image-position="<?php echo esc_attr($image_position); ?>">
                            <div class="row flex-column-reverse flex-lg-row">
                                <!-- Content Column -->
                                <div class="<?php echo $content_col_class; ?>">
                                    <div class="budi-benefit-tabs__content-text h-100 d-flex flex-column position-relative">

                                        <h2 class="budi-benefit-tabs__title h3">
                                            <?php echo do_shortcode($title); ?>
                                        </h2>

                                        <div class="budi-benefit-tabs__description <?php echo $button_url && $button_position === 'left' ? 'mb-4' : ''; ?> flex-grow-1">
                                            <?php echo wpautop(do_shortcode($content_text)); ?>
                                        </div>

                                        <?php if ($button_url && $button_position === 'left') : ?>
                                            <div class="budi-benefit-tabs__button-wrapper text-center text-lg-<?php echo esc_attr($button_alignment); ?>">
                                                <a href="<?php echo esc_url($button_url); ?>"
                                                    class="budi-benefit-tabs__button btn btn-outline-primary"
                                                    <?php if ($button_target) echo 'target="' . esc_attr($button_target) . '"'; ?>>
                                                    <?php echo esc_html($button_text); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Image Column -->
                                <div class="<?php echo $image_col_class; ?>">
                                    <div class="d-flex flex-column h-100">
                                        <div class="budi-benefit-tabs__image-wrapper flex-grow-1 <?php echo $button_url && $button_position === 'right' ? 'mb-5' : ''; ?>">
                                            <?php if ($image) : ?>
                                                <?php echo wp_get_attachment_image($image, 'large', false, ['class' => 'budi-benefit-tabs__image img-fluid', 'style' => 'max-width: ' . esc_attr($image_max_width) . ';']); ?>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($button_url && $button_position === 'right') : ?>
                                            <div class="budi-benefit-tabs__button-wrapper mt-4 text-center text-lg-<?php echo esc_attr($button_alignment); ?>">
                                                <a href="<?php echo esc_url($button_url); ?>"
                                                    class="budi-benefit-tabs__button btn btn-outline-primary"
                                                    <?php if ($button_target) echo 'target="' . esc_attr($button_target) . '"'; ?>>
                                                    <?php echo esc_html($button_text); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>


        </div>

<?php }

    return ob_get_clean();
}
add_shortcode('budi_benefit_tabs', 'sc_budi_benefit_tabs');

add_action('vc_before_init', function () {
    if (!function_exists('vc_map')) return;

    $widget_group = "Design Options";

    vc_map(array(
        'name' => __('Budi Benefit Tabs', _BUDI_TEXT_DOMAIN),
        'base' => 'budi_benefit_tabs',
        'category' => _BUDI_CATEGORY_WIDGET_NAME,
        'as_parent' => array('only' => 'budi_benefit_tabs_item'),
        'content_element' => true,
        'show_settings_on_create' => false,
        'is_container' => true,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Custom Widget Class', _BUDI_TEXT_DOMAIN),
                'param_name' => 'widget_class',
                'group' => $widget_group,
                'admin_label' => true,
            ),
            array(
                'type' => 'textfield',
                'heading' => __('Image Max Width', _BUDI_TEXT_DOMAIN),
                'param_name' => 'image_max_width',
                'value' => '250px',
                'description' => __('Set the maximum width for the images (e.g., 250px, 300px, 50%)', _BUDI_TEXT_DOMAIN),
                'group' => $widget_group,
                'admin_label' => true,
            ),
            array(
                'type' => 'css_editor',
                'heading' => __('CSS', _BUDI_TEXT_DOMAIN),
                'param_name' => 'css',
                'group' => $widget_group,
            ),
        ),
        'js_view' => 'VcColumnView'
    ));

    //Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
    if (class_exists('WPBakeryShortCodesContainer')) {
        class WPBakeryShortCode_Budi_Benefit_Tabs extends WPBakeryShortCodesContainer {}
    }
});
