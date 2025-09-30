<?php
if (!defined('ABSPATH') || !function_exists('vc_map')) return; // Exit if accessed directly

class BUDI_VIDEO extends BUDI_SHORTCODE_BASE
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
        return 'budi_video';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Video', _BUDI_TEXT_DOMAIN);
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
            "params" => array(
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __('Video Sub Title', _BUDI_TEXT_DOMAIN),
                    "param_name" => 'video_sub_title',
                    "description" => "",
                    "admin_label" => true,
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __('Video Title', _BUDI_TEXT_DOMAIN),
                    "param_name" => 'video_title',
                    "description" => "",
                    "admin_label" => true,
                ),
                array(
                    "type" => "dropdown",
                    "class" => "",
                    "heading" => __("Video Source", _BUDI_TEXT_DOMAIN),
                    "param_name" => "video_source",
                    "value" => array(
                        __("Self Hosted", _BUDI_TEXT_DOMAIN) => "self_hosted",
                        __("Youtube", _BUDI_TEXT_DOMAIN) => "youtube",
                        __("Vimeo", _BUDI_TEXT_DOMAIN) => "vimeo",
                    ),
                    "description" => "",
                    "admin_label" => true,
                    "std" => 'self_hosted',
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Lazy Video?', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'lazy_video',
                    'value' => array(
                        __('Yes', _BUDI_TEXT_DOMAIN) => 'yes'
                    ),
                    'std' => '',
                    'admin_label' => false,
                    'dependency' => array(
                        'element' => 'video_source',
                        'value' => 'self_hosted',
                    ),
                    'description' => __('If checked, plese ensure to upload the video poster image.', _BUDI_TEXT_DOMAIN),
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => __('Video Poster', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'video_poster',
                    'dependency' => array(
                        'element' => 'lazy_video',
                        'value' => 'yes',
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Set Video Poster as Background for Clip Title?', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'set_video_poster_as_clip_title',
                    'value' => array(
                        __('Yes', _BUDI_TEXT_DOMAIN) => 'yes'
                    ),
                    'std' => '',
                    'admin_label' => false,
                    'dependency' => array(
                        'element' => 'lazy_video',
                        'value' => 'yes',
                    ),
                    'description' => __('If checked, plese ensure to upload the video poster image.', _BUDI_TEXT_DOMAIN),
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __('Video URL', _BUDI_TEXT_DOMAIN),
                    "param_name" => 'video_url',
                    "description" => "",
                    "admin_label" => true,
                ),
                array(
                    "type" => "textfield",
                    "class" => "",
                    "heading" => __('Video URL Mobile', _BUDI_TEXT_DOMAIN),
                    "param_name" => 'video_url_mobile',
                    "description" => "",
                    "admin_label" => true,
                ),

                ...$this->get_title_style_options_controls('Video Title'),
                ...$this->get_video_style_options_controls(),
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

        $atts = shortcode_atts([
            'video_sub_title'                => '',
            'video_title'                    => '',
            'video-title_class'              => '',
            'video-title_heading_tag'        => 'h2',
            'video_source'                   => 'self_hosted',
            'video_url'                      => '',
            'video_max_width'                => '',
            'video_poster'                   => '',
            'set_video_poster_as_clip_title' => '',
            'lazy_video'                     => '',
            'start_time_self_hosted'         => '',
            'end_time_self_hosted'           => '',
            'control_self_hosted'            => 'yes',
            'autoplay_self_hosted'           => '',
            'mute_self_hosted'               => '',
            'loop_self_hosted'               => '',
            'start_time_youtube'             => '',
            'end_time_youtube'               => '',
            'control_youtube'                => 'yes',
            'autoplay_youtube'               => '',
            'mute_youtube'                   => '',
            'privacy_mode_youtube'           => '',
            'start_time_vimeo'               => '',
            'autoplay_vimeo'                 => '',
            'mute_vimeo'                     => '',
            'loop_vimeo'                     => '',
            'privacy_mode_vimeo'             => '',
            'intro_title_vimeo'              => 'yes',
            'intro_portrait_vimeo'           => 'yes',
            'intro_byline_vimeo'             => 'yes',
            'icon_play_button'               => '',
            'widget_class'                   => '',
            'css'                            => '',
        ], $atts);

        $widget_class = sc_merge_css($atts['css'], $atts['widget_class']);

        ob_start();

        $video_sub_title                = $atts['video_sub_title'];
        $video_title                    = $atts['video_title'];
        $video_title_class              = $atts['video-title_class'];
        $video_title_heading_tag        = $atts['video-title_heading_tag'];
        $video_max_width                = $atts['video_max_width'];
        $video_source                   = $atts['video_source'];
        $video_url                      = $atts['video_url'];
        $video_poster                   = $atts['video_poster'];
        $lazy_video                     = $atts['lazy_video'];
        $control_self_hosted            = $atts['control_self_hosted'];
        $icon_play_button               = $atts['icon_play_button'];
        $set_video_poster_as_clip_title = $atts['set_video_poster_as_clip_title'];

        $this_widget_id = $this->get_widget_id(uniqid());

        if ($video_url) { ?>

            <div class="budi-video__wrapper position-relative <?php echo esc_attr($widget_class); ?>" id="<?php echo esc_attr($this_widget_id); ?>" <?php echo $video_max_width ? 'style="max-width: ' . esc_attr($video_max_width) . ';"' : ''; ?>>

                <div class="budi-video-content__container">
                    <?php

                    if ($video_sub_title) {
                        echo sprintf('<div class="budi-video__sub-title">%s</div>', esc_html($video_sub_title));
                    }

                    $title_add_style = "";
                    if ($video_title) {
                        if ($set_video_poster_as_clip_title && $video_poster) {
                            $title_add_style .= ' background-image: url(' . esc_url(wp_get_attachment_image_url($video_poster)) . ');';
                            $video_title_class .= ' budi-video-poster-clip position-relative';
                        }

                        echo sprintf('<%1$s class="budi-video__title %2$s" style="%4$s">%3$s</%1$s>', esc_attr($video_title_heading_tag), esc_attr($video_title_class), $video_title, esc_attr($title_add_style));
                    }
                    ?>
                </div>
                <?php

                switch ($video_source) {
                    case 'youtube':
                        echo budi_get_youtube_embed_code($atts);
                        break;
                    case 'vimeo':
                        echo budi_get_vimeo_embed_code($atts);
                        break;
                    default:
                        if ($lazy_video === 'yes') {
                            echo budi_get_self_hosted_html_lazy($atts);
                        } else {
                            echo budi_get_self_hosted_html($atts);
                        }

                        echo '<canvas id="' . $this_widget_id . '-canvas" width="640" height="360" style="display:none;"></canvas>';
                        break;
                }
                ?>

            </div>

            <?php if ($icon_play_button && $video_source === "self_hosted") { ?>
                <script>
                    (function($) {
                        $(document).ready(function() {
                            const $widget_id = $('#<?php echo $this_widget_id; ?>');
                            const $video_wrapper = $widget_id.find('.budi-video-wrapper');
                            const $video = $widget_id.find('video');
                            const $play_button = $video.siblings('.budi-video-play-button');

                            // Video play button on click
                            $($widget_id).on('click', '.budi-video-play-button', function(e) {
                                $play_button.fadeOut(300);
                                $video[0].play();
                                $video.prop('controls', true);
                            });

                            // Video On Play
                            $video.on('play', function() {
                                $widget_id.addClass('budi-video-playing');
                                if ($play_button.length > 0) {
                                    $play_button.fadeOut(300);
                                }
                                $video.prop('controls', true);
                            });

                            // Video On Pause
                            $video.on('pause', function() {
                                $widget_id.removeClass('budi-video-playing');
                                $play_button.fadeIn(300);
                                $video.prop('controls', <?php echo $control_self_hosted === "yes" ? 'true' : 'false'; ?>);
                            });
                        });
                    })(jQuery);
                </script>
            <?php } ?>

<?php }

        return ob_get_clean();
    }
}

new BUDI_VIDEO();
