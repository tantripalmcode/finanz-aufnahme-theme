<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

abstract class BUDI_SHORTCODE_BASE {

    protected $uniqid, $widget_name, $widget_title, $widget_id;

    public function __construct() {
        $this->uniqid = $this->get_uniqid();
        $this->widget_name = $this->get_name();
        $this->widget_title = $this->get_title();
        $this->widget_id = $this->get_widget_id();

        // Render assets
        add_action( 'wp_enqueue_scripts', array( $this, 'render_assets' ), 21 );

        // Register Controls
        add_action( 'vc_before_init', array( $this, 'register_controls' ) );

        // Add Shortcode
        add_shortcode( $this->widget_name, array( $this, 'render_view' ) );
    }
    
    /**
     * get_uniqid
     */
    protected function get_uniqid() {
        return uniqid();
    }
    
    /**
     * get_name
     */
    protected function get_name() { 
        return 'base_name';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return 'base_title';
    }
    
    /**
     * get_widget_id
     */
    protected function get_widget_id( $thisuniqid = '' ) {
        if($thisuniqid){
            return "{$this->widget_name}_{$thisuniqid}";
        }
        return "{$this->widget_name}_{$this->uniqid}";
    }

    /**
     * render_assets
     */
    public function render_assets() {
        global $post;

        if ( $post && has_shortcode( $post->post_content, $this->widget_name) ) {
            $this->render_asset_lists();
        }
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {}
    
    /**
     * register_controls
     */
    public function register_controls() {}
    
    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {}
    
    /**
     * generate_asset_id
     */
    protected function generate_asset_id() {
        return str_replace( "_", "-", $this->widget_name );
    }
    
    /**
     * budi_vc_build_link
     */
    protected function budi_vc_build_link( $link ) {
        $link           = vc_build_link( $link );
        $link_url       = isset( $link['url'] ) && !empty( $link['url'] ) ? $link['url'] : '';
        $link_target    = isset( $link['target'] ) ? $link['target'] : '';
        $link_rel       = isset( $link['rel'] ) ? $link['rel'] : '';
        $link_title     = isset( $link['title'] ) ? $link['title'] : '';

        return array(
            'link_url' => $link_url,
            'link_target' => $link_target,
            'link_rel' => $link_rel,
            'link_title' => $link_title,
        );
    }
    
    /**
     * Get design options controls array
     */
    protected function get_design_options_controls() {
        $widget_group = "Design Options";

        return array(
            array(
                'type' => 'textfield',
                'heading' => __(' Custom Widget Class', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'widget_class',
                'group' => $widget_group,
                'admin_label' => true,
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'CSS', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'css',
                'group' => $widget_group,
            ),
        );
    }

    /**
     * Get image style controls array
     */
    protected function get_image_style_options_controls() {
        $widget_group = 'Image Style';

        $wp_image_size = get_intermediate_image_sizes();
        $custom_option = array( 'custom' );
        $image_sizes   = array_merge( $wp_image_size, $custom_option );

        return array(
            array(
                'type' => 'dropdown',
                'heading' => __( 'Image Size', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image_size',
                'value' => $image_sizes,
                'std' => 'large',
                'group' => $widget_group,
            ),array(
                'type' => 'textfield',
                'heading' => __( 'Image Size Custom', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image_size_custom',
                'group' => $widget_group,
                'description' => __( 'Alternatively enter size in pixels (Example: 200x100 (Width x Height)).', _BUDI_TEXT_DOMAIN ),
                'dependency' => array(
                    'element' => 'image_size',
                    'value' => 'custom',
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Image Class', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'image_class',
                'group' => $widget_group,
                
            ),
        );
    }
    
    /**
     * get_heading_option_controls
     */
    protected function get_heading_option_controls( $heading, $param_name, $std, $group ) {
        return array(
            'type' => 'dropdown',
            'heading' => $heading,
            'param_name' => $param_name,
            'value' => array(
                'h1' => 'h1',
                'h2' => 'h2',
                'h3' => 'h3',
                'h4' => 'h4',
                'h5' => 'h5',
                'h6' => 'h6',
                'p' => 'p',
                'span' => 'span',
                'div' => 'div',
            ),
            'std' => $std,
            'group' => $group,
        );
    }

    /**
     * Get title style controls array
     */
    protected function get_title_style_options_controls( $name = 'Title', $std = 'h2' ) {
        $sanitize_name = sanitize_title( $name );
        $widget_group  = "{$name} Style";

        return array(
            $this->get_heading_option_controls(
                __( "{$name} Heading Tag", _BUDI_TEXT_DOMAIN ),
                "{$sanitize_name}_heading_tag",
                $std,
                $widget_group
            ),
            array(
                'type' => 'textfield',
                'heading' => __( "{$name} Class", _BUDI_TEXT_DOMAIN ),
                'param_name' => "{$sanitize_name}_class",
                'group' => $widget_group,
            ),
        );
    }

    /**
     * Get sub title style controls array
     */
    protected function get_sub_title_style_options_controls() {
        $widget_group = "Sub Title Style";

        return array(
            $this->get_heading_option_controls(
                __( 'Sub Title Heading Tag', _BUDI_TEXT_DOMAIN ),
                'sub_title_heading_tag',
                'span',
                $widget_group
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Sub Title Class', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'sub_title_class',
                'group' => $widget_group,
            ),
            array(
                'type' => 'dropdown',
                'heading' => __( 'Sub Title Position', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'sub_title_position',
                'value' => array(
                    __( 'After Title', _BUDI_TEXT_DOMAIN ) => 'after_title',
                    __( 'Before Title', _BUDI_TEXT_DOMAIN ) => 'before_title',
                ),
                'std' => 'after_title',
                'group' => $widget_group,
            ),
        );
    }

    /**
     * Get description controls array
     */
    protected function get_description_style_options_controls( $name = 'Description' ) {
        $sanitize_name = sanitize_title( $name );
        $widget_group = "{$name} Style";

        return array(
            array(
                'type' => 'textfield',
                'heading' => __( "{$name} Class", _BUDI_TEXT_DOMAIN ),
                'param_name' => "{$sanitize_name}_class",
                'group' => $widget_group,
            ),
        );
    }

    /**
     * Get button controls array
     */
    protected function get_button_style_options_controls() {
        $widget_group = "Button Style";

        return array(
            array(
                'type' => 'textfield',
                'heading' => __( 'Button Class', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'button_class',
                'group' => $widget_group,
            ),
        );
    }


    /**
     * Get video controls array
     */
    protected function get_video_style_options_controls() {
        $widget_group = "Video Style";

        return array(

            // Self Hosted
            array(
                'type' => 'textfield',
                'heading' => __( "Video Max Width", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'video_max_width',
                'group' => $widget_group,
                "dependency" => array(
                    "element" => "video_source",
                    "value" => "self_hosted",
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( "Start Time", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'start_time_self_hosted',
                'group' => $widget_group,
                "description" => __( "Please enter numbers only. Specify a start time (in seconds)", _BUDI_TEXT_DOMAIN ),
                "dependency" => array(
                    "element" => "video_source",
                    "value" => "self_hosted",
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( "End Time", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'end_time_self_hosted',
                'group' => $widget_group,
                "description" => __( "Please enter numbers only. Specify a end time (in seconds)", _BUDI_TEXT_DOMAIN ),
                "dependency" => array(
                    "element" => "video_source",
                    "value" => "self_hosted",
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Player Controls', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'control_self_hosted',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes' 
                ),
                'std' => 'yes',
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'self_hosted',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Autoplay', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'autoplay_self_hosted',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'self_hosted',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Mute', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'mute_self_hosted',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'self_hosted',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Loop', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'loop_self_hosted',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'self_hosted',
                ),
            ),

            // Youtube
            array(
                'type' => 'textfield',
                'heading' => __( "Start Time", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'start_time_youtube',
                'group' => $widget_group,
                "description" => __( "Please enter numbers only. Specify a start time (in seconds)", _BUDI_TEXT_DOMAIN ),
                "dependency" => array(
                    "element" => "video_source",
                    "value" => "youtube",
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( "End Time", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'end_time_youtube',
                'group' => $widget_group,
                "description" => __( "Please enter numbers only. Specify a end time (in seconds)", _BUDI_TEXT_DOMAIN ),
                "dependency" => array(
                    "element" => "video_source",
                    "value" => "youtube",
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Player Controls', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'control_youtube',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes' 
                ),
                'std' => 'yes',
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'youtube',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Autoplay', _BUDI_TEXT_DOMAIN ),
                'description' => __( 'In YouTube, when you enable autoplay, it is necessary to also enable mute for autoplay to function properly.', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'autoplay_youtube',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'youtube',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Mute', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'mute_youtube',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'youtube',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Privacy Mode', _BUDI_TEXT_DOMAIN ),
                'description' => __( 'When you turn on privacy mode, YouTube won&apos;t store information about visitors on your website unless they play the video.', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'privacy_mode_youtube',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'youtube',
                ),
            ),


            // Vimeo
            array(
                'type' => 'textfield',
                'heading' => __( "Start Time", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'start_time_vimeo',
                'group' => $widget_group,
                "description" => __( "Please enter numbers only. Specify a start time (in seconds)", _BUDI_TEXT_DOMAIN ),
                "dependency" => array(
                    "element" => "video_source",
                    "value" => "vimeo",
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Autoplay', _BUDI_TEXT_DOMAIN ),
                'description' => __( 'In Vimeo, when you enable autoplay, it is necessary to also enable mute for autoplay to function properly.', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'autoplay_vimeo',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Mute', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'mute_vimeo',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Loop', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'loop_vimeo',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Privacy Mode', _BUDI_TEXT_DOMAIN ),
                'description' => __( 'When you turn on privacy mode, Vimeo won&apos;t store information about visitors on your website unless they play the video.', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'privacy_mode_vimeo',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Intro Title', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'intro_title_vimeo',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
                'std' => 'yes',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Intro Portrait', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'intro_portrait_vimeo',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
                'std' => 'yes',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Intro Byline', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'intro_byline_vimeo',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => false,
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'vimeo',
                ),
                'std' => 'yes',
            ),


            array(
                'type' => 'attach_image',
                'heading' => __( 'Icon Play Button', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'icon_play_button',
                'description' => __( 'If you&apos;re not using a custom play button icon, leave this field blank.', _BUDI_TEXT_DOMAIN ),
                'group' => $widget_group,
                'dependency' => array(
                    'element' => 'video_source',
                    'value' => 'self_hosted',
                ),
            ),
        );
    }

    protected function get_slider_style_options_controls() {
        $widget_group = "Slider Settings";

        return array(
            // Carousel Settings
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Full Width?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_fullwidth',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
            ),

            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Infinite Loop?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'infinite_loop',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
            ),

            array(
                'type' => 'checkbox',
                'heading' => __( 'Equal Height?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'equal_height',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
            ),

            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable RTL?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_rtl',
                'value' => array( 
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
            ),


            // Autoplay
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Auto Play?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'autoplay',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
            ),
            array(
                'type' => 'textfield',
                'heading' => __( "Autoplay Speed", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'autoplay_speed',
                'group' => $widget_group,
                'std' => 2500,
                "description" => __( "Please enter numbers only.", _BUDI_TEXT_DOMAIN ),
                "dependency" => array(
                    "element" => "autoplay",
                    "value" => "yes",
                ),
                'edit_field_class' => 'vc_col-sm-3',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Pause on Hover?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'pause_on_hover',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'std' => 'yes',
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-6',
                "dependency" => array(
                    "element" => "autoplay",
                    "value" => "yes",
                ),
            ),

            // Space Between
            array(
                'type' => 'textfield',
                'heading' => __( 'Space Between (Desktop)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'space_between_desktop',
                'admin_label' => true,
                'std' => 20,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Space Between (Tablet)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'space_between_tablet',
                'admin_label' => true,
                'std' => 20,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Space Between (Mobile)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'space_between_mobile',
                'admin_label' => true,
                'std' => 20,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),

            // Slides To Show
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __( "Slides To Show (Desktop)", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'slides_per_view_desktop',
                'value' => array( 'auto', 1, 2, 3, 4, 5, 6 ),
                'std' => 4,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __( "Slides To Show (Tablet)", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'slides_per_view_tablet',
                'value' => array( 'auto', 1, 2, 3, 4, 5, 6 ),
                'std' => 2,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __( "Slides To Show (Mobile)", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'slides_per_view_mobile',
                'value' => array( 'auto', 1, 2, 3, 4, 5, 6 ),
                'std' => 1,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),

            // Max Width Image
            array(
                'type' => 'textfield',
                'heading' => __( 'Max Width Image (Desktop)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'max_width_image_desktop',
                'admin_label' => true,
                'std' => '400px',
                'group' => $widget_group,
                "dependency" => array(
                    "element" => "slides_per_view_desktop",
                    "value" => "auto",
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Max Width Image (Tablet)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'max_width_image_tablet',
                'admin_label' => true,
                'std' => '400px',
                'group' => $widget_group,
                "dependency" => array(
                    "element" => "slides_per_view_tablet",
                    "value" => "auto",
                ),
            ),
            array(
                'type' => 'textfield',
                'heading' => __( 'Max Width Image (Mobile)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'max_width_image_mobile',
                'admin_label' => true,
                'std' => '300px',
                'group' => $widget_group,
                "dependency" => array(
                    "element" => "slides_per_view_mobile",
                    "value" => "auto",
                ),
            ),

            // Slides To Scroll
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __( "Slides To Scroll (Desktop)", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'slides_to_scroll_desktop',
                'value' => array( 1, 2, 3, 4, 5, 6 ),
                'std' => 1,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __( "Slides To Scroll (Tablet)", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'slides_to_scroll_tablet',
                'value' => array( 1, 2, 3, 4, 5, 6 ),
                'std' => 1,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'admin_label' => true,
                'type' => 'dropdown',
                'heading' => __( "Slides To Scroll (Mobile)", _BUDI_TEXT_DOMAIN ),
                'param_name' => 'slides_to_scroll_mobile',
                'value' => array( 1, 2, 3, 4, 5, 6 ),
                'std' => 1,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),

            // Dots
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Dots? (Desktop)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_dots_desktop',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Dots? (Tablet)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_dots_tablet',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Dots? (Mobile)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_dots_mobile',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Arrows? (Desktop)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_arrows_desktop',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
                'std' => 'yes',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Arrows? (Tablet)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_arrows_tablet',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),
            array(
                'type' => 'checkbox',
                'heading' => __( 'Enable Arrows? (Mobile)', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'enable_arrows_mobile',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
                'edit_field_class' => 'vc_col-sm-4',
            ),

            array(
                'type' => 'checkbox',
                'heading' => __( 'Show only 1 arrow?', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'show_only_1_arrow',
                'value' => array(
                    __( 'Yes', _BUDI_TEXT_DOMAIN ) => 'yes'
                ),
                'admin_label' => true,
                'group' => $widget_group,
            ),
        );
    }

    protected function get_aos_animation_options_controls() {
        $widget_group = "AOS Animation";

        return array(
            array(
                'type' => 'textarea',
                'heading' => __('AOS Animation Attributes', _BUDI_TEXT_DOMAIN ),
                'param_name' => 'aos_animation_attributes',
                'group' => $widget_group,
                'admin_label' => true,
                'std' => '',
                'description' => __( 'Set custom attributes for the wrapper element. Each attribute in a separate line. Separate attribute key from the value using | character.', _BUDI_TEXT_DOMAIN )
            ),
        );
    }

    /**
     * Maps title positions to alignment classes based on device breakpoints.
     *
     * @param string $position Title position from shortcode attribute.
     * @param string $device Device type ('desktop', 'tablet', 'mobile').
     *
     * @return string Alignment class based on the provided position and device.
     */
    protected function map_title_alignment($position, $device) {
        $alignment_classes = array(
            'desktop' => array(
                'title-left-desktop'   => 'align-items-md-start',
                'title-center-desktop' => 'align-items-md-center',
                'title-right-desktop'  => 'align-items-md-end',
            ),
            'tablet' => array(
                'title-left-tablet'   => 'align-items-sm-start',
                'title-center-tablet' => 'align-items-sm-center',
                'title-right-tablet'  => 'align-items-sm-end',
            ),
            'mobile' => array(
                'title-left-mobile'   => 'align-items-start',
                'title-center-mobile' => 'align-items-center',
                'title-right-mobile'  => 'align-items-end',
            ),
        );

        return isset($alignment_classes[$device][$position]) ? $alignment_classes[$device][$position] : '';
    }
}