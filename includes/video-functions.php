<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Get Youtube Embed Code
 */
if ( !function_exists( 'budi_get_youtube_embed_code' ) ) {
    function budi_get_youtube_embed_code( $params = [] ) {
        $defaults = [
            'video_url'            => '',
            'width'                => 800,
            'height'               => 500,
            'control_youtube'      => 'yes',
            'mute_youtube'         => 'no',
            'autoplay_youtube'     => 'no',
            'privacy_mode_youtube' => 'no',
            'start_time_youtube'   => '',
            'end_time_youtube'     => '',
        ];
    
        $params = array_merge( $defaults, $params );

        $url            = $params['video_url'];
        $width          = $params['width'];
        $height         = $params['height'];
        $control        = $params['control_youtube'];
        $mute           = $params['mute_youtube'];
        $autoplay       = $params['autoplay_youtube'];
        $privacy_mode   = $params['privacy_mode_youtube'];
        $start_time     = $params['start_time_youtube'];
        $end_time       = $params['end_time_youtube'];
        $video_id       = '';
        $url_components = parse_url($url);

        parse_str( $url_components['query'], $query_params );
    
        if ( isset( $query_params['v'] ) ) {
            $video_id = $query_params['v'];
        } elseif ( preg_match( '/\/embed\/([^"&?\/\s]{11})/', $url, $matches ) ) {
            $video_id = $matches[1];
        } elseif ( preg_match( '/\/v\/([^"&?\/\s]{11})/', $url, $matches ) ) {
            $video_id = $matches[1];
        } elseif ( preg_match( '/\/vi\/([^"&?\/\s]{11})/', $url, $matches ) ) {
            $video_id = $matches[1];
        }

        if ( empty( $video_id ) ) return;
    
        return sprintf(
            '<iframe class="budi-youtube" src="https://www.youtube%s.com/embed/%s?controls=%d&rel=0&playsinline=0&modestbranding=0&autoplay=%d&start=%d&end=%d&mute=%d&enablejsapi=1&origin=%s&dnt=" width="%d" height="%d" frameborder="0" allow="autoplay" allowfullscreen></iframe>',
            $privacy_mode === 'yes' ? '-nocookie' : '',
            $video_id,
            $control === 'yes' ? 1 : 0,
            $autoplay === 'yes' ? 1 : 0,
            is_numeric($start_time) ? $start_time : '',
            is_numeric($end_time) ? $end_time : '',
            $mute === 'yes' ? 1 : 0,
            esc_url(get_site_url()),
            $width,
            $height
        );
    }
}

/**
 * Get Vimeo Embed Code
 */
if ( !function_exists( 'budi_get_vimeo_embed_code' ) ) {
    function budi_get_vimeo_embed_code( $params = [] ) {
        $defaults = [
            'video_url'            => '',
            'width'                => 800,
            'height'               => 500,
            'autoplay_vimeo'       => 'no',
            'privacy_mode_vimeo'   => 'no',
            'mute_vimeo'           => 'no',
            'loop_vimeo'           => 'no',
            'intro_title_vimeo'    => 'yes',
            'intro_portrait_vimeo' => 'yes',
            'intro_byline_vimeo'   => 'yes',
            'start_time_vimeo'     => '',
        ];

        $params = array_merge( $defaults, $params );

        $url                  = $params['video_url'];
        $width                = $params['width'];
        $height               = $params['height'];
        $autoplay             = $params['autoplay_vimeo'];
        $privacy_mode         = $params['privacy_mode_vimeo'];
        $mute                 = $params['mute_vimeo'];
        $loop                 = $params['loop_vimeo'];
        $intro_title_vimeo    = $params['intro_title_vimeo'];
        $intro_portrait_vimeo = $params['intro_portrait_vimeo'];
        $intro_byline_vimeo   = $params['intro_byline_vimeo'];
        $start_time           = $params['start_time_vimeo'];

        // Extract video ID from Vimeo URL
        $vimeo_regex = '/(?:https?:\/\/)?(?:www\.)?(?:vimeo\.com)\/?(.+)/';
        preg_match( $vimeo_regex, $url, $matches );

        if ( !isset( $matches[1] ) || empty( $matches[1] ) ) return;

        $video_id = $matches[1];

        // Construct Vimeo embed code
        $embed_code = sprintf(
            '<iframe class="budi-vimeo" src="https://player.vimeo.com/video/%s?autoplay=%d&playsinline=1&color&autopause=0&dnt=%s&loop=%d&muted=%d&title=%d&portrait=%d&byline=%d#t=%s" width="%d" height="%d" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>',
            $video_id,
            $autoplay === 'yes' ? 1 : 0,
            $privacy_mode === 'yes' ? 'true' : 'false',
            $loop === 'yes' ? 1 : 0,
            $mute === 'yes' ? 1 : 0,
            $intro_title_vimeo === 'yes' ? 1 : 0,
            $intro_portrait_vimeo === 'yes' ? 1 : 0,
            $intro_byline_vimeo === 'yes' ? 1 : 0,
            is_numeric($start_time) ? "00h00m{$start_time}s" : '',
            $width,
            $height
        );

        return $embed_code;
    }
}

/**
 * Get Self Hosted HTML
 */
if ( !function_exists( 'budi_get_self_hosted_html' ) ) {
    function budi_get_self_hosted_html( $params = [] ) {
        $defaults = [
            'video_url'              => '',
            'video_poster'           => '',
            'control_self_hosted'    => 'yes',
            'autoplay_self_hosted'   => 'no',
            'loop_self_hosted'       => 'no',
            'mute_self_hosted'       => 'no',
            'start_time_self_hosted' => '',
            'end_time_self_hosted'   => '',
            'icon_play_button'       => '',
        ];
    
        $params = array_merge( $defaults, $params );
    
        $video_url        = $params['video_url'];
        $video_poster     = $params['video_poster'];
        $control          = $params['control_self_hosted'];
        $autoplay         = $params['autoplay_self_hosted'];
        $loop             = $params['loop_self_hosted'];
        $muted            = $params['mute_self_hosted'];
        $start_time       = $params['start_time_self_hosted'];
        $end_time         = $params['end_time_self_hosted'];
        $icon_play_button = $params['icon_play_button'];
    
        if ( empty( $video_url ) ) return;
    
        $video_html = sprintf(
            // '<video preload="metadata" %s %s %s %s playsinline="" src="%s#t=%s,%s"></video>',
            '<video %s %s %s %s %s playsinline="" src="%s#t=%s,%s"></video>',
            $control === 'yes' ? 'controls' : '',
            $autoplay === 'yes' ? 'autoplay' : '',
            $loop === 'yes' ? 'loop' : '',
            $muted === 'yes' ? 'muted' : '',
            !empty($video_poster) ? sprintf('poster="%s"', esc_url($video_poster)) : '',
            esc_url($video_url),
            is_numeric($start_time) ? $start_time : '', 
            is_numeric($end_time) ? $end_time : ''
        ); 
        
        if ( $icon_play_button ) {
            $video_html .= wp_get_attachment_image( $icon_play_button, 'full', false, array( 
                'class' => 'budi-video-play-button position-absolute' 
            ) );
        }

        return $video_html;
    }
}

if ( !function_exists( 'budi_get_self_hosted_html_lazy' ) ) {
    function budi_get_self_hosted_html_lazy( $params = [] ) {
        $defaults = [
            'video_url'              => '',
            'video_poster'           => '',
            'control_self_hosted'    => 'yes',
            'autoplay_self_hosted'   => 'no',
            'loop_self_hosted'       => 'no',
            'mute_self_hosted'       => 'no',
            'start_time_self_hosted' => '',
            'end_time_self_hosted'   => '',
            'icon_play_button'       => '',
        ];
    
        $params = array_merge( $defaults, $params );
        $uniqid = uniqid();
    
        $video_url        = $params['video_url'];
        $video_poster     = $params['video_poster'];
        $control          = $params['control_self_hosted'];
        $autoplay         = $params['autoplay_self_hosted'];
        $loop             = $params['loop_self_hosted'];
        $muted            = $params['mute_self_hosted'];
        $start_time       = $params['start_time_self_hosted'];
        $end_time         = $params['end_time_self_hosted'];
        $icon_play_button = $params['icon_play_button'];

        $video_poster_url = "";
        if($video_poster){
            if(wp_http_validate_url($video_poster)){
                $video_poster_url = $video_poster;
            }else{
                $video_poster_url = wp_get_attachment_image_url($video_poster, 'full', false);
            }
        }
    
        if ( empty( $video_url ) ) return;
    
        $video_html = sprintf(
            '<video preload="none" %s %s %s %s %s playsinline="" class="budi-video" data-src="%s#t=%s,%s"></video>',
            $control === 'yes' ? 'controls' : '',
            $autoplay === 'yes' ? 'autoplay' : '',
            $loop === 'yes' ? 'loop' : '',
            $muted === 'yes' ? 'muted' : '',
            !empty($video_poster_url) ? sprintf('poster="%s"', esc_url($video_poster_url)) : '',
            esc_url($video_url),
            is_numeric($start_time) ? $start_time : '', 
            is_numeric($end_time) ? $end_time : ''
        ); 
        
        if ( $icon_play_button ) {
            $video_html .= wp_get_attachment_image( $icon_play_button, 'full', false, array( 
                'class' => 'budi-video-play-button position-absolute budi-video-play-button-' . $uniqid 
            ) );
        }
        
        // Video-Wrapper Div hinzufügen
        $html = '<div class="budi-video-wrapper position-relative">';
        $html .= $video_html;
        $html .= '</div>';
        
        $html .= '
        <script>
            jQuery(document).ready(function($) {
                $(".budi-video-play-button-'. $uniqid.'").on("click", function() {
                    var playButton = $(this);
                    var videoWrapper = playButton.closest(".budi-video-wrapper");
                    var video = playButton.siblings(".budi-video");

                    video.attr("src", video.data("src"));

                    video.on("loadeddata", function() {
                        video.get(0).play().catch(function(error) {
                            console.log("Error attempting to play the video:", error);
                            playButton.show();
                        });
                    });

                    playButton.hide();
                });
            });
        </script>';
    
        return $html;
    }
}


