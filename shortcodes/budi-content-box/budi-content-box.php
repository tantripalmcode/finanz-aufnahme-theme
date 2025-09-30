<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_CONTENT_BOX extends BUDI_SHORTCODE_BASE {

    /**
     * __construct
     */
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * get_name
     */
    protected function get_name() {
        return 'budi_content_box';
    }
    
    /**
     * get_title
     */
    protected function get_title() {
        return __( 'Budi Content Box', _BUDI_TEXT_DOMAIN );
    }

    /**
     * render_asset_lists
     */
    protected function render_asset_lists() {
        // Enqueue CSS & JS
        wp_enqueue_style( $this->generate_asset_id(), this_dir_url(__FILE__) . "{$this->generate_asset_id()}.css", array(), _BUDI_VERSION );
    }

    /**
     * register_controls
     */
    public function register_controls() {
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
                    'heading' => __( 'Layout', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'layout_type',
                    'value' => array(
                        'Layout 1' => 'layout_1',
                        'Layout 2' => 'layout_2',
                    ),
                    'std' => "layout_1"
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Type', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'type',
                    'value' => array(
                        'Image' => 'image',
                        'Number'  => 'number',
                    ),
                    'std' => "image"
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Number', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'number',
                    'admin_label' => true,
                    "dependency" => array(
                        "element" => "type",
                        "value" => "number",
                    )
                ),
                array(
                    'type' => 'dropdown',
                    'heading' => __( 'Use SVG Code', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'use_svg_code',
                    'value' => array(
                        'Yes' => 'yes',
                        'No' => 'no',
                    ),
                    'std' => "no",
                    "dependency" => array(
                        "element" => "type",
                        "value" => "image",
                    )
                ),
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => __( 'Image', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "image",
                    "value" => '',
                    "dependency" => array(
                        "element" => "type",
                        "value" => "image",
                    )
                ),
                array(
                    'type' => 'textarea',
                    'heading' => __( 'Title', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'title',
                    'admin_label' => true,
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __( 'Sub Title', _BUDI_TEXT_DOMAIN ),
                    'param_name' => 'sub_title',
                    'admin_label' => true,
                ),
                array(
                    "type" => "textarea_html",
                    "holder" => "div",
                    "class" => "",
                    "heading" => __( 'Content', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "content",
                ),
                array(
                    "type" => "vc_link",
                    "class" => "",
                    "heading" => __( 'Link', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "link",
                    "value" => '',
                    'admin_label' => true,
                ),
                array(
                    "type" => "attach_image",
                    "class" => "",
                    "heading" => __( 'Button Icon', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "btn_icon"
                ),
                array(
                    "type" => "checkbox",
                    "class" => "",
                    "heading" => __( 'Make button outside Description', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "btn_outside",
                    "description" => __( 'If checked the button will be outside budi-content-box__content.', _BUDI_TEXT_DOMAIN )
                ),
                array(
                    "type" => "checkbox",
                    "class" => "",
                    "heading" => __( 'Make link for the whole box', _BUDI_TEXT_DOMAIN ),
                    "param_name" => "make_link_whole_box",
                    "description" => __( 'If checked the whole box will be linked.', _BUDI_TEXT_DOMAIN )
                ),

                ...$this->get_image_style_options_controls(),
                ...$this->get_title_style_options_controls(),
                ...$this->get_sub_title_style_options_controls(),
                ...$this->get_description_style_options_controls(),
                ...$this->get_button_style_options_controls(),
                ...$this->get_design_options_controls(),
            )
        );

        vc_map( $args );
    }

    /**
     * render_view
     */
    public function render_view( $atts, $content = null ) {

        $atts = shortcode_atts( [
            'layout_type'           => 'layout_1',
            'type'                  => 'image',
            'number'                => '',
            'title'                 => '',
            'title_class'           => '',
            'title_heading_tag'     => 'h2',
            'sub_title'             => '',
            'sub_title_class'       => '',
            'sub_title_heading_tag' => 'span',
            'sub_title_position'    => 'after_title',
            'link'                  => '',
            'use_svg_code'          => 'no',
            'image'                 => '',
            'image_size'            => 'large',
            'image_size_custom'     => '',
            'image_class'           => '',
            'btn_icon'              => '',
            'btn_outside'           => '',
            'make_link_whole_box'   => '',
            'description_class'     => '',
            'button_class'          => '',
            'widget_class'          => '',
            'css'                   => '',
        ], $atts );

        $link                   = isset( $atts['link'] ) ? $atts['link'] : '';
        $budi_build_link        = $this->budi_vc_build_link( $link );
        $link_url               = $budi_build_link['link_url'];
        $link_target            = $budi_build_link['link_target'];
        $link_rel               = $budi_build_link['link_rel'];
        $link_title             = $budi_build_link['link_title'];
        $make_link_whole_box    = isset( $atts['make_link_whole_box'] ) ? $atts['make_link_whole_box'] : false;

        ob_start();

        $atts = array_merge( 
            $atts,
            array( 
                'content'             => $content,
                'link_url'            => $link_url,
                'link_target'         => $link_target,
                'link_rel'            => $link_rel,
                'link_title'          => $link_title,
                'make_link_whole_box' => $make_link_whole_box,
            ) 
        );

        $this->widget_open_tag( $atts );

        $this->widget_body( $atts );

        $this->widget_close_tag( $atts );
        
        return ob_get_clean();
    }
    
    /**
     * widget_open_tag
     */
    private function widget_open_tag( $atts ) {
        $widget_id      = esc_attr( $this->widget_id );
        $widget_class   = "budi-content-box__wrapper ";
        $widget_class   .= sc_merge_css( $atts['css'], $atts['widget_class'] );
        $link_url       = $atts['link_url'];
        $link_target    = $atts['link_target'];
        $link_rel       = $atts['link_rel'];

        if ( $atts['link'] && $atts['make_link_whole_box'] ){
            $html = sprintf( '<a id="%s" class="%s" href="%s" target="%s" rel="%s">', esc_attr( $widget_id ), esc_attr( $widget_class ), esc_url( $link_url ), esc_attr( $link_target ), esc_attr( $link_rel ) );
        } else {
            $html = sprintf( '<div id="%s" class="%s">', esc_attr( $widget_id ), esc_attr( $widget_class ) );
        }

        echo $html;
    }
    
    /**
     * widget_close_tag
     */
    private function widget_close_tag( $atts ) {
        if ( $atts['link'] && $atts['make_link_whole_box'] ){
            $html = '</a>';
        } else {
            $html = '</div>';
        }
        
        echo $html;
    }
    
    /**
     * widget_body
     */
    private function widget_body( $atts ) {
        $layout_type              = isset( $atts['layout_type'] ) ? $atts['layout_type'] : '';
        $type                     = isset( $atts['type'] ) ? $atts['type'] : '';
        $number                   = isset( $atts['number'] ) ? $atts['number'] : '';
        $image                    = isset( $atts['image'] ) ? $atts['image'] : '';
        $image_size               = isset( $atts['image_size'] ) ? $atts['image_size'] : '';
        $image_size_custom        = isset( $atts['image_size_custom'] ) ? $atts['image_size_custom'] : '';
        $image_class              = isset( $atts['image_class'] ) ? $atts['image_class'] : '';
        $use_svg_code             = isset( $atts['use_svg_code'] ) ? $atts['use_svg_code'] : '';
        $title                    = isset( $atts['title'] ) ? $atts['title'] : '';
        $title_class              = isset( $atts['title_class'] ) ? $atts['title_class'] : '';
        $title_heading_tag        = isset( $atts['title_heading_tag'] ) ? $atts['title_heading_tag'] : '';
        $sub_title                = isset( $atts['sub_title'] ) ? $atts['sub_title'] : '';
        $sub_title_class          = isset( $atts['sub_title_class'] ) ? $atts['sub_title_class'] : '';
        $sub_title_heading_tag    = isset( $atts['sub_title_heading_tag'] ) ? $atts['sub_title_heading_tag'] : '';
        $sub_title_position       = isset( $atts['sub_title_position'] ) ? $atts['sub_title_position'] : '';
        $content                  = isset( $atts['content'] ) ? $atts['content'] : '';
        $description_class        = isset( $atts['description_class'] ) ? $atts['description_class'] : '';
        $button_class             = isset( $atts['button_class'] ) ? $atts['button_class'] : '';
        $link_url                 = isset( $atts['link_url'] ) ? $atts['link_url'] : '';
        $link_target              = isset( $atts['link_target'] ) ? $atts['link_target'] : '';
        $link_rel                 = isset( $atts['link_rel'] ) ? $atts['link_rel'] : '';
        $link_title               = isset( $atts['link_title'] ) ? $atts['link_title'] : '';
        $btn_icon                 = isset( $atts['btn_icon'] ) ? $atts['btn_icon'] : '';
        $btn_outside              = isset( $atts['btn_outside'] ) ? $atts['btn_outside'] : '';
        $make_link_whole_box      = isset( $atts['make_link_whole_box'] ) ? $atts['make_link_whole_box'] : '';

        $image_size = budi_get_image_size( $image_size, $image_size_custom );
        ?>

        <?php if( $layout_type === "layout_2" ) echo '<div class="budi-content-box__top-wrapper">'; ?>

            <?php if( $type === "number" ){ ?>
                <div class="budi-content-box__number"><?php echo $number; ?></div>
            <?php } ?>

            <?php if ( $type === "image" && $image ) { ?>

                <figure class="budi-content-box__image <?php echo esc_attr( $image_class ); ?>">
                    <?php if ( $use_svg_code === "yes" ) {

                        $image_path         = wp_get_original_image_path( $image );
                        $image_file_type    = wp_check_filetype( $image_path );

                        if ( isset($image_file_type['ext']) && $image_file_type['ext'] === "svg" ) {
                            $image_svg_code = file_get_contents( $image_path );
                            echo $image_svg_code;
                        } else{
                            echo wp_get_attachment_image( $image, $image_size, false );
                        }
                    } else {
                        echo wp_get_attachment_image( $image, $image_size, false );
                    }
                    ?>
                </figure>

            <?php } ?>

            <div class="budi-content-box__content">

                <!-- If subtitle available and position is before title -->
                <?php if( $sub_title_position === "before_title" && $sub_title ){ ?>
                    <<?php echo $sub_title_heading_tag; ?> class="budi-content-box__sub-title <?php echo esc_attr( $sub_title_class ); ?>">
                        <?php echo $sub_title; ?>
                    </<?php echo $sub_title_heading_tag; ?>>
                <?php } ?>

                <?php if ( $title ) { ?>
                    <<?php echo $title_heading_tag; ?> class="budi-content-box__title <?php echo esc_attr( $title_class ); ?>">
                        <?php echo budi_fix_special_characters( $title ); ?>
                    </<?php echo $title_heading_tag; ?>>
                <?php } ?>

                <!-- If subtitle available and position is after title -->
                <?php if( $sub_title_position === "after_title" && $sub_title ){ ?>
                    <<?php echo $sub_title_heading_tag; ?> class="budi-content-box__sub-title <?php echo esc_attr( $sub_title_class ); ?>">
                        <?php echo $sub_title; ?>
                    </<?php echo $sub_title_heading_tag; ?>>
                <?php } ?>

                <?php if ( $content ) { ?>
                    <div class="budi-content-box__description <?php echo esc_attr( $description_class ); ?>">
                        <?php echo wpautop( $content ); ?>
                    </div>
                <?php } ?>

                <?php if ( $link_url ) { ?>
                                    
                    <?php if ( !$btn_outside ) { ?>
                        <?php if ( $make_link_whole_box ) { ?>

                            <span class="budi-content-box__button <?php echo esc_attr( $button_class ); ?>">
                                <?php echo esc_html( $link_title ); ?>
                                <?php if ( !empty( $btn_icon ) ) {
                                    $icon_path = wp_get_original_image_path( $btn_icon );
                                    $icon_type = wp_check_filetype( $icon_path );
                                    
                                    if ( isset($icon_type['ext']) && $icon_type['ext'] === "svg" ) {
                                        $icon_svg_code = file_get_contents( $icon_path );
                                        echo '<span class="budi-content-box__button-icon">' . $icon_svg_code . '</span>';
                                    } else {
                                        $icon_img = wp_get_attachment_image( $btn_icon, 'full', false, array( 'class' => 'budi-content-box__button-icon' ) );
                                        echo $icon_img;
                                    }
                                } ?>
                            </span>

                        <?php } else { ?>

                            <a href="<?php echo esc_url( $link_url ); ?>" class="budi-content-box__button <?php echo esc_attr( $button_class ); ?>" target="<?php echo esc_attr( $link_target ); ?>" rel="<?php echo esc_attr( $link_rel ); ?>">
                                <?php echo esc_html( $link_title ); ?>
                                <?php if ( !empty( $btn_icon ) ) {
                                    $icon_path = wp_get_original_image_path( $btn_icon );
                                    $icon_type = wp_check_filetype( $icon_path );
                                    
                                    if ( isset($icon_type['ext']) && $icon_type['ext'] === "svg" ) {
                                        $icon_svg_code = file_get_contents( $icon_path );
                                        echo '<span class="budi-content-box__button-icon">' . $icon_svg_code . '</span>';
                                    } else {
                                        $icon_img = wp_get_attachment_image( $btn_icon, 'full', false, array( 'class' => 'budi-content-box__button-icon' ) );
                                        echo $icon_img;
                                    }
                                } ?>
                            </a>

                        <?php } ?>
                    <?php } ?>

                <?php } ?>

            </div>

        <?php if( $layout_type === "layout_2" ) echo '</div>'; ?>

        <?php if ( $btn_outside && $link_url ) : ?>

            <?php if ( $make_link_whole_box ) { ?>

                <div class="budi-content-box__button-wrapper">
                    <span class="budi-content-box__button <?php echo esc_attr( $button_class ); ?>">
                        <?php echo $link_title; ?>
                        <?php if ( !empty( $btn_icon ) ) {
                            $icon_path = wp_get_original_image_path( $btn_icon );
                            $icon_type = wp_check_filetype( $icon_path );
                                    
                            if ( isset($icon_type['ext']) && $icon_type['ext'] === "svg" ) {
                                $icon_svg_code = file_get_contents( $icon_path );
                                echo '<span class="budi-content-box__button-icon">' . $icon_svg_code . '</span>';
                            } else {
                                $icon_img = wp_get_attachment_image( $btn_icon, 'full', false, array( 'class' => 'budi-content-box__button-icon' ) );
                                echo $icon_img;
                            }
                        } ?>
                    </span>
                </div>

                <?php } else { ?>

                    <a href="<?php echo esc_url( $link_url ); ?>" class="budi-content-box__button <?php echo esc_attr( $button_class ); ?>" target="<?php echo esc_attr( $link_target ); ?>" rel="<?php echo esc_attr( $link_rel ); ?>">
                        <?php echo $link_title; ?>
                        <?php if ( !empty( $btn_icon ) ) {
                            $icon_path = wp_get_original_image_path( $btn_icon );
                            $icon_type = wp_check_filetype( $icon_path );
                                
                            if ( isset($icon_type['ext']) && $icon_type['ext'] === "svg" ) {
                                $icon_svg_code = file_get_contents( $icon_path );
                                echo '<span class="budi-content-box__button-icon">' . $icon_svg_code . '</span>';
                            } else {
                                $icon_img = wp_get_attachment_image( $btn_icon, 'full', false, array( 'class' => 'budi-content-box__button-icon' ) );
                                echo $icon_img;
                            }
                        } ?>
                    </a>

            <?php } ?>

        <?php endif; ?>

    <?php }
}

new BUDI_CONTENT_BOX();