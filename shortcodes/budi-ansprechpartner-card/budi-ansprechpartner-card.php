<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_ANSPRECHPARTNER_CARD extends BUDI_SHORTCODE_BASE
{

    const post_type = 'ansprechpartner';

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
        return 'budi_ansprechpartner_card';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Ansprechpartner Card', _BUDI_TEXT_DOMAIN);
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
            'show_settings_on_create' => false,
            'is_container' => false,
            'params' => array(
                array(
                    'type' => 'autocomplete',
                    'heading' => __('Select Ansprechpartner', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'ansprechpartner_id',
                    'admin_label' => true,
                    'description' => __('Search and select an ansprechpartner', _BUDI_TEXT_DOMAIN),
                    'settings' => array(
                        'multiple' => false,
                        'sortable' => false,
                        'unique_values' => true,
                        'values' => get_posts_for_autocomplete('ansprechpartner'),
                    ),
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __('Image Size', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'image_size',
                    'value' => array(
                        __('Thumbnail', _BUDI_TEXT_DOMAIN) => 'thumbnail',
                        __('Medium', _BUDI_TEXT_DOMAIN) => 'medium',
                        __('Large', _BUDI_TEXT_DOMAIN) => 'large',
                        __('Full', _BUDI_TEXT_DOMAIN) => 'full',
                    ),
                    'std' => 'medium',
                    'description' => __('Select the size of the featured image', _BUDI_TEXT_DOMAIN),
                ),

                ...$this->get_design_options_controls(),
            )
        );

        vc_map($args);
    }

    /**
     * render_view
     */
    public function render_view($atts, $content = null)
    {
        ob_start();
        // Enqueue CSS
        budi_add_style($this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION);

        $atts = shortcode_atts([
            'ansprechpartner_id'   => '',
            'image_size'           => 'medium',
            'widget_class'         => '',
            'css'                  => '',
        ], $atts);

        $ansprechpartner_id = $atts['ansprechpartner_id'];
        $image_size         = $atts['image_size'];
        $widget_class       = sc_merge_css($atts['css'], $atts['widget_class']);

        // If no ansprechpartner selected, return empty
        if (empty($ansprechpartner_id)) {
            return '';
        }

        // Get the ansprechpartner post
        $ansprechpartner = get_post($ansprechpartner_id);
        
        if (!$ansprechpartner || $ansprechpartner->post_type !== self::post_type) {
            return '';
        }

        // Get the data
        $name = get_the_title($ansprechpartner);
        $phone = get_field('phone', $ansprechpartner_id);
        $featured_image = get_post_thumbnail_id($ansprechpartner_id);

        ?>

        <div class="budi-ansprechpartner-card <?php echo esc_attr($widget_class); ?>">
            <div class="budi-ansprechpartner-card__inner position-relative overflow-hidden">
                <?php if ($featured_image): ?>
                    <div class="budi-ansprechpartner-card__image">
                        <?php echo wp_get_attachment_image($featured_image, $image_size, false, array('alt' => esc_attr($name))); ?>
                    </div>
                <?php endif; ?>
                
                <div class="budi-ansprechpartner-card__content">
                    <h3 class="budi-ansprechpartner-card__name"><?php echo esc_html($name); ?></h3>
                    
                    <?php if ($phone) { ?>
                        <div class="budi-ansprechpartner-card__phone">
                            <span class="budi-ansprechpartner-card__phone-label">Tel.:</span>
                            <a class="budi-ansprechpartner-card__phone-number" href="tel:<?php echo sanitize_tel($phone); ?>"><?php echo esc_html($phone); ?></a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <?php

        return ob_get_clean();
    }
}

new BUDI_ANSPRECHPARTNER_CARD();
