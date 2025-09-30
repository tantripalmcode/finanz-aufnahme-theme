<?php

/**
 * CTA_Box_Widget.
 */
class CTA_Box_Widget extends WP_Widget
{

    /**
     * Register widget with WordPress.
     */
    public function __construct()
    {
        parent::__construct(
            'cta_box_widget', // Base ID
            WIDGETS_TITLE_PREPEND . 'CTA Box', // Name
            array('description' => 'Call-to-Action Box mit Social Media Icons und Button.',) // Args
        );

        // Enqueue scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    // Enqueue admin scripts
    public function admin_enqueue_scripts()
    {
        wp_enqueue_media();
        wp_enqueue_script('cta-box-admin-script', this_dir_url(__FILE__) . 'CTA_Box.js', array('jquery'), '1.0', true);
    }

    // Enqueue frontend scripts
    public function enqueue_scripts()
    {
        wp_enqueue_style('cta-box-widget', this_dir_url(__FILE__) . 'CTA_Box.css');
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance)
    {
        extract($args);

        // Get instance values
        $show_social_media = isset($instance['show_social_media']) ? $instance['show_social_media'] : true;
        $top_text = isset($instance['top_text']) ? $instance['top_text'] : 'Folge unseren Social Media-Kanälen für mehr Insights:';
        $main_headline = isset($instance['main_headline']) ? $instance['main_headline'] : 'TECHNOLOGIE TRIFFT STRATEGIE.';
        $supporting_text = isset($instance['supporting_text']) ? $instance['supporting_text'] : 'Wir machen dein Business zukunftssicher.';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : 'DIREKT KONTAKT AUFNEHMEN';
        $button_link = isset($instance['button_link']) ? $instance['button_link'] : '#';
        $background_image = isset($instance['background_image']) ? $instance['background_image'] : '';
        $min_height = isset($instance['min_height']) ? $instance['min_height'] : '';

        $element_id = 'cta_box_' . uniqid();
?>

        <style>
            #<?php echo $element_id ?> {
                background-image: <?php echo $background_image ? 'url(' . esc_url($background_image) . ')' : 'none' ?>;
                <?php if (!empty($min_height)): ?>min-height: <?php echo esc_attr($min_height) ?>;
                <?php endif; ?>
            }
        </style>

        <div id="<?php echo $element_id ?>" class="cta-box-widget">

            <div class="cta-box-content__top">


                <p class="cta-top-text"><?php echo esc_html($top_text) ?></p>
                <?php if ($show_social_media): ?>
                    <!-- Social Media Section -->
                    <div class="cta-social-section">
                        <div class="social-media-icons">
                            <?php echo apply_shortcodes("[social-media-icons type='embedded' align='flex-start' class='cta-social-icons']") ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="cta-box-content__bottom w-100">
                <!-- Main Content Section -->
                <?php if ($main_headline || $supporting_text): ?>
                    <div class="cta-main-content">
                        <?php if ($main_headline): ?>
                            <p class="cta-headline h3"><?php echo esc_html($main_headline) ?></p>
                        <?php endif; ?>
                        <?php if ($supporting_text): ?>
                            <p class="cta-supporting-text"><?php echo esc_html($supporting_text) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- CTA Button Section -->
                <?php if ($button_text): ?>
                    <div class="cta-button-section budi-button-container w-100">
                        <a href="<?php echo esc_url($button_link) ?>" class="btn btn-primary justify-content-center">
                            <?php echo esc_html($button_text) ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    <?php
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance)
    {
        $show_social_media = isset($instance['show_social_media']) ? $instance['show_social_media'] : true;
        $top_text = isset($instance['top_text']) ? $instance['top_text'] : 'Folge unseren Social Media-Kanälen für mehr Insights:';
        $main_headline = isset($instance['main_headline']) ? $instance['main_headline'] : 'TECHNOLOGIE TRIFFT STRATEGIE.';
        $supporting_text = isset($instance['supporting_text']) ? $instance['supporting_text'] : 'Wir machen dein Business zukunftssicher.';
        $button_text = isset($instance['button_text']) ? $instance['button_text'] : 'DIREKT KONTAKT AUFNEHMEN';
        $button_link = isset($instance['button_link']) ? $instance['button_link'] : '#';
        $background_image = isset($instance['background_image']) ? $instance['background_image'] : '';
        $min_height = isset($instance['min_height']) ? $instance['min_height'] : '';
    ?>

        <p>Call-to-Action Box with Social Media Icons and Button.</p>

        <!-- Top Text -->
        <p>
            <label for="<?php echo $this->get_field_id('top_text'); ?>">Top Text</label>
            <input class="widefat" id="<?php echo $this->get_field_id('top_text'); ?>" name="<?php echo $this->get_field_name('top_text'); ?>" type="text" value="<?php echo esc_attr($top_text); ?>" />
        </p>

        <!-- Show Social Media -->
        <p>
            <input class="checkbox" type="checkbox" id="<?php echo $this->get_field_id('show_social_media'); ?>" name="<?php echo $this->get_field_name('show_social_media'); ?>" <?php checked($show_social_media); ?> />
            <label for="<?php echo $this->get_field_id('show_social_media'); ?>">Show Social Media Section</label>
        </p>

        <!-- Background Image -->
        <p>
            <label for="<?php echo $this->get_field_id('background_image'); ?>">Background Image (optional)</label>
            <input class="widefat background_image" id="<?php echo $this->get_field_id('background_image'); ?>" name="<?php echo $this->get_field_name('background_image'); ?>" type="text" value="<?php echo esc_url($background_image); ?>" />
            <button class="button background-picker">Select Image</button>
        </p>

        <!-- Minimum Height -->
        <p>
            <label for="<?php echo $this->get_field_id('min_height'); ?>">Minimum Height (e.g., 300px, 50vh)</label>
            <input class="widefat" id="<?php echo $this->get_field_id('min_height'); ?>" name="<?php echo $this->get_field_name('min_height'); ?>" type="text" value="<?php echo esc_attr($min_height); ?>" />
        </p>

        <!-- Main Headline -->
        <p>
            <label for="<?php echo $this->get_field_id('main_headline'); ?>">Main Headline</label>
            <input class="widefat" id="<?php echo $this->get_field_id('main_headline'); ?>" name="<?php echo $this->get_field_name('main_headline'); ?>" type="text" value="<?php echo esc_attr($main_headline); ?>" />
        </p>

        <!-- Supporting Text -->
        <p>
            <label for="<?php echo $this->get_field_id('supporting_text'); ?>">Supporting Text</label>
            <input class="widefat" id="<?php echo $this->get_field_id('supporting_text'); ?>" name="<?php echo $this->get_field_name('supporting_text'); ?>" type="text" value="<?php echo esc_attr($supporting_text); ?>" />
        </p>

        <!-- Button Text -->
        <p>
            <label for="<?php echo $this->get_field_id('button_text'); ?>">Button Text</label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_text'); ?>" name="<?php echo $this->get_field_name('button_text'); ?>" type="text" value="<?php echo esc_attr($button_text); ?>" />
        </p>

        <!-- Button Link -->
        <p>
            <label for="<?php echo $this->get_field_id('button_link'); ?>">Button Link</label>
            <input class="widefat" id="<?php echo $this->get_field_id('button_link'); ?>" name="<?php echo $this->get_field_name('button_link'); ?>" type="text" value="<?php echo esc_url($button_link); ?>" />
        </p>

<?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['show_social_media'] = isset($new_instance['show_social_media']) ? (bool) $new_instance['show_social_media'] : false;
        $instance['top_text'] = (!empty($new_instance['top_text'])) ? strip_tags($new_instance['top_text']) : '';
        $instance['main_headline'] = (!empty($new_instance['main_headline'])) ? strip_tags($new_instance['main_headline']) : '';
        $instance['supporting_text'] = (!empty($new_instance['supporting_text'])) ? strip_tags($new_instance['supporting_text']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? strip_tags($new_instance['button_text']) : '';
        $instance['button_link'] = (!empty($new_instance['button_link'])) ? esc_url_raw($new_instance['button_link']) : '';
        $instance['background_image'] = (!empty($new_instance['background_image'])) ? esc_url_raw($new_instance['background_image']) : '';
        $instance['min_height'] = (!empty($new_instance['min_height'])) ? strip_tags($new_instance['min_height']) : '';

        return $instance;
    }
} // class CTA_Box_Widget

// Register CTA_Box_Widget widget
add_action('widgets_init', 'register_CTA_Box_Widget');

function register_CTA_Box_Widget()
{
    register_widget('CTA_Box_Widget');
}