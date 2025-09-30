<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if (!defined('ABSPATH') || !function_exists('vc_map')) {
    return;
}

class BUDI_VIDEO_GRID extends BUDI_SHORTCODE_BASE
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
        return 'budi_video_grid';
    }

    /**
     * get_title
     */
    protected function get_title()
    {
        return __('Budi Video Grid', _BUDI_TEXT_DOMAIN);
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
                    'type' => 'param_group',
                    'heading' => __('Video Items', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'video_items',
                    'params' => array(
                        array(
                            'type' => 'textarea',
                            'heading' => __('Title', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'title',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Sub Title', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'sub_title',
                            'admin_label' => true,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Video URL', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'video_url',
                            'admin_label' => false,
                        ),
                        array(
                            'type' => 'textfield',
                            'heading' => __('Video URL for Mobile', _BUDI_TEXT_DOMAIN),
                            'param_name' => 'video_url_mobile',
                            'description' => __('Leave empty to use desktop video URL on mobile', _BUDI_TEXT_DOMAIN),
                            'admin_label' => false,
                        ),
                        array(
                            "type" => "attach_image",
                            "class" => "",
                            "heading" => __('Video Poster', _BUDI_TEXT_DOMAIN),
                            "param_name" => "video_poster",
                            'admin_label' => false,
                        ),
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Player Controls', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'control_self_hosted',
                    'value' => array(
                        __('Yes', _BUDI_TEXT_DOMAIN) => 'yes'
                    ),
                    'std' => 'yes',
                    'admin_label' => false,
                    'group' => 'Video Settings',
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Mute', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'mute_self_hosted',
                    'value' => array(
                        __('Yes', _BUDI_TEXT_DOMAIN) => 'yes'
                    ),
                    'admin_label' => false,
                    'group' => 'Video Settings',
                ),
                array(
                    'type' => 'checkbox',
                    'heading' => __('Loop', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'loop_self_hosted',
                    'value' => array(
                        __('Yes', _BUDI_TEXT_DOMAIN) => 'yes'
                    ),
                    'admin_label' => false,
                    'group' => 'Video Settings',
                ),
                array(
                    'type' => 'attach_image',
                    'heading' => __('Icon Play Button', _BUDI_TEXT_DOMAIN),
                    'param_name' => 'icon_play_button',
                    'description' => __('If you&apos;re not using a custom play button icon, leave this field blank.', _BUDI_TEXT_DOMAIN),
                    'group' => 'Video Settings',
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
            'video_items'         => '',
            'control_self_hosted' => 'yes',
            'mute_self_hosted'    => '',
            'loop_self_hosted'    => '',
            'icon_play_button'    => '',
            'widget_class'        => '',
            'css'                 => '',
        ], $atts);

        $control_self_hosted = $atts['control_self_hosted'];
        $mute_self_hosted    = $atts['mute_self_hosted'];
        $loop_self_hosted    = $atts['loop_self_hosted'];
        $icon_play_button    = $atts['icon_play_button'];
        $widget_class        = sc_merge_css($atts['css'], $atts['widget_class']);
        $widget_id           = $this->widget_id . uniqid();

        $video_items = vc_param_group_parse_atts($atts['video_items']);

        if ($video_items): ?>

            <div class="budi-video-grid__wrapper <?php echo esc_attr($widget_class); ?>" id="<?php echo esc_attr($widget_id); ?>">

                <?php foreach ($video_items as $item):
                    $video_url        = $item['video_url'];
                    $video_url_mobile = $item['video_url_mobile'] ?? '';
                    $video_poster     = $item['video_poster'] ?? '';
                    $video_title      = $item['title'] ?? '';
                    $video_sub_title  = $item['sub_title'] ?? '';

                    if ($video_poster) {
                        if (wp_http_validate_url($video_poster)) {
                            $video_poster_url = $video_poster;
                        } else {
                            $video_poster_url = wp_get_attachment_image_url($video_poster, 'full', false);
                        }
                    }

                    if (empty($video_url)) continue; ?>

                    <div class="budi-video-grid__item budi-video-wrapper">
                        <div class="budi-video-grid__item-inner position-relative overflow-hidden">

                            <div class="budi-video-overlay"></div>
                            <?php
                            $atts['video_url']    = $video_url;
                            $atts['video_poster'] = $video_poster;


                            echo sprintf(
                                '<video preload="none" %s %s %s %s playsinline="" class="budi-video" data-src="%s" data-src-mobile="%s"></video>',
                                $control_self_hosted === 'yes' ? 'controls' : '',
                                $loop_self_hosted === 'yes' ? 'loop' : '',
                                $mute_self_hosted === 'yes' ? 'muted' : '',
                                !empty($video_poster_url) ? sprintf('poster="%s"', esc_url($video_poster_url)) : '',
                                esc_url($video_url),
                                esc_url($video_url_mobile),
                            );

                            if ($icon_play_button) {
                                echo wp_get_attachment_image($icon_play_button, 'full', false, array(
                                    'class' => 'budi-video-play-button position-absolute'
                                ));
                            }
                            ?>

                            <div class="video-grid-item__content position-absolute text-center">
                                <?php if (!empty($video_sub_title)): ?>
                                    <p class="video-grid-item__subtitle mx-0 text-white"><?php echo $video_sub_title; ?></p>
                                <?php endif; ?>
                                <?php if (!empty($video_title)): ?>
                                    <h3 class="video-grid-item__title mb-0 text-white position-relative d-inline-block"><?php echo $video_title; ?></h3>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

            <script>
                (function($) {
                    $(document).ready(function() {
                        const $widget_id = $('#<?php echo $widget_id; ?>');
                        let isDesktopView = window.innerWidth > 767;
                        let activeVideo = null;
                        let activeGridItem = null;
                        let isPauseIntentional = false; // Flag to track intentional pauses
                        let videoTimeStates = {}; // Track video time states for resuming

                        // Handle window resize
                        $(window).on('resize', function() {
                            const newIsDesktopView = window.innerWidth > 767;

                            // Check if view mode has changed
                            if (isDesktopView !== newIsDesktopView) {
                                isDesktopView = newIsDesktopView;

                                // If there's an active video, pause it with force reset
                                if (activeVideo) {
                                    stopVideo(activeVideo, activeGridItem, true);
                                    activeVideo = null;
                                    activeGridItem = null;
                                }
                            }
                        });

                        // Video play button click handler
                        $($widget_id).on('click', '.budi-video-play-button', function(e) {
                            const $this = $(this);
                            const $video_grid_item = $(this).closest('.budi-video-grid__item');
                            const $first_item = $($widget_id).find('.budi-video-grid__item:first-child');
                            const $video = $this.siblings('video');

                            // Check if screen width is above 767px
                            if (window.innerWidth > 767) {
                                // If clicked video is not in the first item
                                if (!$video_grid_item.is(':first-child')) {
                                    // Store elements from first item
                                    const $first_video = $first_item.find('video').clone();
                                    const $first_title = $first_item.find('.video-grid-item__title').clone();
                                    const $first_subtitle = $first_item.find('.video-grid-item__subtitle').clone();
                                    const $first_poster = $first_item.find('.budi-video-play-button').clone();

                                    // Clone elements from clicked item
                                    const $cloned_video = $video.clone();
                                    const $cloned_title = $video_grid_item.find('.video-grid-item__title').clone();
                                    const $cloned_subtitle = $video_grid_item.find('.video-grid-item__subtitle').clone();
                                    const $cloned_poster = $video_grid_item.find('.budi-video-play-button').clone();

                                    // Update first item content
                                    $first_item.find('video').remove();
                                    $first_item.find('.video-grid-item__title').remove();
                                    $first_item.find('.video-grid-item__subtitle').remove();
                                    $first_item.find('.budi-video-play-button').remove();

                                    $first_item.find('.budi-video-grid__item-inner').prepend($cloned_video);
                                    $first_item.find('.video-grid-item__content').prepend($cloned_subtitle);
                                    $first_item.find('.video-grid-item__content').append($cloned_title);
                                    $first_item.find('.budi-video-grid__item-inner').append($cloned_poster);

                                    // Update clicked item content
                                    $video_grid_item.find('video').remove();
                                    $video_grid_item.find('.video-grid-item__title').remove();
                                    $video_grid_item.find('.video-grid-item__subtitle').remove();
                                    $video_grid_item.find('.budi-video-play-button').remove();

                                    $video_grid_item.find('.budi-video-grid__item-inner').prepend($first_video);
                                    $video_grid_item.find('.video-grid-item__content').prepend($first_subtitle);
                                    $video_grid_item.find('.video-grid-item__content').append($first_title);
                                    $video_grid_item.find('.budi-video-grid__item-inner').append($first_poster);
                                    $video_grid_item.find('.budi-video-play-button').show();

                                    // Scroll to first item
                                    $('html, body').animate({
                                        scrollTop: $first_item.offset().top - 100
                                    }, 500);

                                    // Setup and autoplay new first video
                                    const $new_first_video = $first_item.find('video');
                                    const videoSrc = isDesktopView ? $new_first_video.data("src") : ($new_first_video.data("src-mobile") || $new_first_video.data("src"));
                                    $new_first_video.attr("src", videoSrc);
                                    $first_item.find('.budi-video-play-button').fadeOut(300);
                                    $first_item.find('.budi-video-overlay, .video-grid-item__content').fadeOut(300);

                                    // Wait for scroll animation to complete before playing
                                    setTimeout(() => {
                                        $new_first_video.prop('controls', true);
                                        $new_first_video[0].play();
                                        activeVideo = $new_first_video[0];
                                        activeGridItem = $first_item;
                                    }, 600);

                                    // Setup event handlers for new video
                                    setupVideoHandlers($new_first_video, $first_item);
                                } else {
                                    // If clicked video is the first item
                                    playVideo($video, $this, $video_grid_item);
                                    activeVideo = $video[0];
                                    activeGridItem = $video_grid_item;
                                }
                            } else {
                                // For mobile view, stop any currently playing video first with force reset
                                if (activeVideo && activeVideo !== $video[0]) {
                                    stopVideo(activeVideo, activeGridItem, true);
                                }

                                // Play the clicked video
                                playVideo($video, $this, $video_grid_item);
                                activeVideo = $video[0];
                                activeGridItem = $video_grid_item;
                            }
                        });

                        // Helper function to setup video event handlers
                        function setupVideoHandlers($video, $gridItem) {
                            $video.on('play', function() {
                                $gridItem.find('.budi-video-play-button').fadeOut(300);
                                $(this).prop('controls', true);
                                isPauseIntentional = false;
                            });

                            // Add click handler for the video element
                            // $video.on('click', function() {
                            //     const video = this;
                            //     if (!video.paused) {
                            //         // If video is playing, pause it
                            //         video.pause();
                            //         // Show controls
                            //         $(this).prop('controls', true);
                            //     } else {
                            //         // If video is paused, play it
                            //         video.play();
                            //     }
                            //     // Prevent default behavior
                            //     return false;
                            // });

                            $video.on('pause', function(e) {
                                // Only stop and reset the video if it's ended
                                if (this.ended) {
                                    safeStopVideo(this, $gridItem);
                                } else {
                                    // For pause, just store the current time and show UI
                                    const videoId = $(this).data('src');
                                    if (this.currentTime > 0) {
                                        videoTimeStates[videoId] = this.currentTime;
                                    }
                                    
                                    // Show play button when paused if it exists
                                    const $playButton = $gridItem.find('.budi-video-play-button');
                                    if ($playButton.length > 0) {
                                        $playButton.fadeIn(300);
                                    }
                                    // Just show controls when paused
                                    $(this).prop('controls', true);
                                }
                            });

                            $video.on('ended', function() {
                                // When video ends, force reset to clear the time state
                                stopVideo(this, $gridItem, true);
                            });
                        }

                        // Helper function to detect Safari on iOS
                        function isSafariMobile() {
                            const ua = navigator.userAgent;
                            return /iPhone|iPad|iPod/.test(ua) &&
                                !window.MSStream &&
                                /WebKit/.test(ua) &&
                                !/Chrome/.test(ua);
                        }

                        // Safer way to stop video that works cross-browser
                        function safeStopVideo(videoElement, $gridItem, forceReset = false) {
                            if (!videoElement || !$gridItem) return;

                            try {
                                // First try to pause the video immediately
                                videoElement.pause();

                                // Wait a tiny bit before continuing with cleanup
                                setTimeout(function() {
                                    stopVideo(videoElement, $gridItem, forceReset);
                                }, 10);
                            } catch (e) {
                                console.log("Error in safeStopVideo", e);
                                // Fallback to the regular stop method
                                stopVideo(videoElement, $gridItem, forceReset);
                            }
                        }

                        // Helper function to stop video and reset UI
                        function stopVideo(videoElement, $gridItem, forceReset = false) {
                            if (!videoElement || !$gridItem) return;

                            const $video = $(videoElement);
                            const videoId = $video.attr('data-src'); // Use video source as unique identifier

                            try {
                                // Force pause first (again, to be sure)
                                videoElement.pause();

                                // Only reset source if forceReset is true (for switching videos or video ended)
                                if (forceReset) {
                                    // Use a safely created empty source instead of about:blank
                                    // which can cause issues in Chrome
                                    const emptySource = isSafariMobile() ?
                                        // Different handling for Safari mobile
                                        "" :
                                        // Other browsers
                                        "";

                                    // Set the source to empty
                                    $video.attr("src", emptySource);

                                    // Try to clear video buffer - with error handling
                                    try {
                                        videoElement.load();
                                    } catch (e) {
                                        console.log("Load error ignored:", e);
                                    }

                                    // Clear the time state when forcing reset
                                    delete videoTimeStates[videoId];
                                } else {
                                    // Store current time for resuming later
                                    if (!videoElement.ended && videoElement.currentTime > 0) {
                                        videoTimeStates[videoId] = videoElement.currentTime;
                                    }
                                }

                                // Reset controls state
                                $video.prop('controls', <?php echo $control_self_hosted === "yes" ? 'true' : 'false'; ?>);

                                // Show UI elements
                                $gridItem.find('.budi-video-play-button').fadeIn(300);
                                $gridItem.find('.budi-video-overlay, .video-grid-item__content').fadeIn(300);

                                // Reset global tracking
                                if (activeVideo === videoElement) {
                                    activeVideo = null;
                                    activeGridItem = null;
                                }
                            } catch (e) {
                                console.log("Error stopping video:", e);

                                // Emergency fallback - create a new video element
                                try {
                                    const $newVideo = $('<video></video>')
                                        .attr('class', $video.attr('class'))
                                        .attr('data-src', $video.attr('data-src'))
                                        .attr('data-src-mobile', $video.attr('data-src-mobile'))
                                        .prop('controls', <?php echo $control_self_hosted === "yes" ? 'true' : 'false'; ?>);

                                    $video.replaceWith($newVideo);

                                    $gridItem.find('.budi-video-play-button').show();
                                    $gridItem.find('.budi-video-overlay, .video-grid-item__content').show();

                                    if (activeVideo === videoElement) {
                                        activeVideo = null;
                                        activeGridItem = null;
                                    }
                                } catch (e) {
                                    console.log("Fallback error:", e);
                                }
                            }
                        }

                        // Helper function to play video
                        function playVideo($video, $playButton, $gridItem) {
                            const videoId = $video.data('src'); // Use video source as unique identifier
                            const hasStoredTime = videoTimeStates.hasOwnProperty(videoId);
                            
                            const videoSrc = isDesktopView ? $video.data("src") : ($video.data("src-mobile") || $video.data("src"));
                            
                            // Only set source if video doesn't already have it or if we need to resume
                            if (!$video.attr('src') || $video.attr('src') !== videoSrc) {
                                $video.attr("src", videoSrc);
                            }
                            
                            $playButton.fadeOut(300);
                            $gridItem.find('.budi-video-overlay, .video-grid-item__content').fadeOut(300);
                            $video.prop('controls', true);

                            // Add a delay before playing to ensure UI updates complete first
                            setTimeout(function() {
                                // If we have a stored time, seek to that position
                                if (hasStoredTime && videoTimeStates[videoId] > 0) {
                                    $video[0].currentTime = videoTimeStates[videoId];
                                }
                                
                                $video[0].play().catch(function(error) {
                                    console.log("Error playing video:", error);
                                    // Show controls even if autoplay fails
                                    $video.prop('controls', true);
                                });
                            }, 100);

                            setupVideoHandlers($video, $gridItem);
                        }
                    });
                })(jQuery);
            </script>

<?php endif;

        return ob_get_clean();
    }
}

new BUDI_VIDEO_GRID();
