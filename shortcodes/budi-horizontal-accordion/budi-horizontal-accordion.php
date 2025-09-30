<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

require "budi-horizontal-accordion-item.php";

function sc_budi_horizontal_accordion( $atts, $content = null ) {
    ob_start();

    $atts = shortcode_atts([
        'widget_class' => '',
        'css'          => '',
    ], $atts);

    $widget_class  = sc_merge_css( $atts['css'], $atts['widget_class'] );

    budi_add_style( 'budi-horizontal-accordion', this_dir_url(__FILE__) . 'budi-horizontal-accordion.css', [], _BUDI_VERSION );

    global $sc_horizontal_accordion;
    $sc_horizontal_accordion = array();

    do_shortcode( $content );

    $widget_id = 'budi-horizontal-accordion-' . uniqid();
    $count_item = count($sc_horizontal_accordion);

    if ( $sc_horizontal_accordion ) { ?>

        <div id="<?php echo esc_attr($widget_id); ?>" class="budi-horizontal-accordion__wrapper d-md-flex <?php echo esc_attr($widget_class); ?>">

            <?php foreach( $sc_horizontal_accordion as $index => $item ) {
                $id           = $item['id'];
                $title        = $item['title'];
                $sub_title    = $item['sub_title'];
                $link         = vc_build_link( $item['link'] );
                $image        = $item['image'];
                $link_url     = $link['url'] ?: '';
                $link_target  = $link['target'] ?: '';
                $link_rel     = $link['rel'] ?: '';
                $link_title   = $link['title'] ?: '';
                $description  = $item['content']; ?>

                <div class="budi-horizontal-accordion__item mb-0 overflow-hidden d-md-flex flex-md-column position-relative justify-content-end transition-all-03s <?php echo $index === 0 ? 'budi-opened' : ''; ?>">
                    <!-- Arrow Bottom Expand -->
                    <span class="budi-horizontal-accordion__item-arrow-bottom position-absolute transition-all-03s d-md-none">
                        <svg width="26" height="14" viewBox="0 0 26 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.524498 1.79466C0.534697 1.55897 0.592445 1.32586 0.694426 1.10872C0.796406 0.891572 0.940615 0.69466 1.11877 0.52928C1.2967 0.36346 1.5051 0.232308 1.73208 0.143317C1.95905 0.0543269 2.20015 0.00924237 2.44159 0.0106401C2.68303 0.0120378 2.92008 0.0598904 3.1392 0.151463C3.35832 0.243036 3.55521 0.376535 3.71863 0.544331L7.37448 3.74539L12.3558 8.09596L22.143 0.650992C22.5022 0.316832 22.9775 0.130722 23.4646 0.133541C23.9516 0.136361 24.4105 0.327879 24.7404 0.666029C25.0699 1.00459 25.2434 1.46195 25.2227 1.93764C25.202 2.41333 24.9888 2.86845 24.63 3.20303L13.6712 13.4099C13.4933 13.5757 13.2848 13.7068 13.0579 13.7958C12.8309 13.8848 12.5898 13.9299 12.3484 13.9285C12.1069 13.9271 11.8699 13.8793 11.6508 13.7877C11.4316 13.6961 11.2347 13.5626 11.0713 13.3948L1.00839 3.06629C0.84471 2.89893 0.717704 2.70044 0.634666 2.48223C0.551627 2.26401 0.514188 2.03035 0.524498 1.79466Z" fill="#E0163C"/>
                        </svg>
                    </span>

                    <?php echo $image ? sprintf( '<div class="budi-horizontal-accordion__item-image w-100 h-100 position-absolute">%s</div>', wp_get_attachment_image( $image, 'large', false, ['class' => 'w-100 h-100'] ) ) : ''; ?>

                    <div class="budi-horizontal-accordion__item-content position-relative transition-all-03s">

                        <div class="budi-horizontal-accordion__item-quote-icon">
                            <svg width="67" height="48" viewBox="0 0 67 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0 17.8394C0 7.98315 8.03103 0 17.9464 0H19.1429C21.79 0 23.9286 2.12587 23.9286 4.75719C23.9286 7.3885 21.79 9.51437 19.1429 9.51437H17.9464C13.3252 9.51437 9.57143 13.2458 9.57143 17.8394V19.0287H19.1429C24.4221 19.0287 28.7143 23.2953 28.7143 28.5431V38.0575C28.7143 43.3053 24.4221 47.5719 19.1429 47.5719H9.57143C4.29219 47.5719 0 43.3053 0 38.0575V17.8394ZM38.2857 17.8394C38.2857 7.98315 46.3167 0 56.2321 0H57.4286C60.0757 0 62.2143 2.12587 62.2143 4.75719C62.2143 7.3885 60.0757 9.51437 57.4286 9.51437H56.2321C51.6109 9.51437 47.8571 13.2458 47.8571 17.8394V19.0287H57.4286C62.7078 19.0287 67 23.2953 67 28.5431V38.0575C67 43.3053 62.7078 47.5719 57.4286 47.5719H47.8571C42.5779 47.5719 38.2857 43.3053 38.2857 38.0575V17.8394Z" fill="white" fill-opacity="0.2"/>
                            </svg>
                        </div>

                        <div class="budi-horizontal-accordion__item-content-inner">
                            <h4 class="budi-horizontal-accordion__item-title mb-2 transition-all-03s text-white">
                                <?php echo do_shortcode( $title ); ?>
                            </h4>
                            
                            <?php echo $sub_title ? sprintf( '<p class="budi-horizontal-accordion__item-subtitle budi-small-text text-white mb-4">%s</p>', $sub_title ) : ''; ?>

                            <div class="budi-horizontal-accordion__item-desc position-relative text-white">
                                <?php echo wpautop(do_shortcode($description)); ?>
                                <span class="budi-horizontal-accordion__item-icon d-none d-md-inline-block position-absolute"></span>
                            </div>

                        </div>
                    </div>

                    <span class="budi-horizontal-accordion__item-icon d-inline-block d-md-none position-absolute"></span>
                </div>

            <?php } ?>

        </div>

        <style>
            @media only screen and (min-width: 768px) {
                #<?php echo esc_attr($widget_id); ?> .budi-horizontal-accordion__item {
                    width: calc(100vw / <?php echo $count_item; ?>);
                }
            }

            @media only screen and (min-width: 768px) {
                #<?php echo esc_attr($widget_id); ?> .budi-horizontal-accordion__item.budi-opened {
                    width: calc(100vw - (50px*(<?php echo $count_item; ?> - 1)));
                }
            }

            @media only screen and (min-width: 1200px) {
                #<?php echo esc_attr($widget_id); ?> .budi-horizontal-accordion__item.budi-opened {
                    width: calc(100vw - (120px*(<?php echo $count_item; ?> - 1)));
                }
            }

            @media only screen and (min-width: 1720px) {
                #<?php echo esc_attr($widget_id); ?> .budi-horizontal-accordion__item.budi-opened {
                    width: calc(100vw - (165px*(<?php echo $count_item; ?> - 1)));
                }
            }
        </style>

        <script>
            (function($) {
                $(document).ready(function() {
                    const $widget_id = '#<?php echo $widget_id; ?>';

                    $(document).on("mouseover", ".budi-horizontal-accordion__item:not(.budi-opened)", function(e) {
                        e.preventDefault();
                        const $this = $(this);
                        $($widget_id).find('.budi-horizontal-accordion__item.budi-opened').not($this).removeClass("budi-opened");

                        $this.toggleClass("budi-opened");
                    });

                    $(document).on("click", ".budi-horizontal-accordion__item-arrow-bottom", function(e) {
                        const $this = $(this);
                        $this.parents('.budi-horizontal-accordion__item').removeClass("budi-opened");
                    });
                });
            })(jQuery);
        </script>

    <?php }

    return ob_get_clean();
}
add_shortcode( 'budi_horizontal_accordion', 'sc_budi_horizontal_accordion' );

add_action( 'vc_before_init', function(){
	if( !function_exists('vc_map') ) return;

    $widget_group = "Design Options";

    vc_map( array(
        'name' => __( 'Budi horizontal Accordion', _BUDI_TEXT_DOMAIN ),
        'base' => 'budi_horizontal_accordion',
        'category' => _BUDI_CATEGORY_WIDGET_NAME,
        'as_parent' => array('only' => 'budi_horizontal_accordion_item'),
        'content_element' => true,
        'show_settings_on_create' => false,
        'is_container' => true,
        'params' => array(
            array(
                'type' => 'textfield',
                'heading' => __('Custom Widget Class', _BUDI_TEXT_DOMAIN ),
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
        ),
        'js_view' => 'VcColumnView'
    ) );

    //Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
    if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
        class WPBakeryShortCode_Budi_Horizontal_Accordion extends WPBakeryShortCodesContainer {
        }
    }

});
