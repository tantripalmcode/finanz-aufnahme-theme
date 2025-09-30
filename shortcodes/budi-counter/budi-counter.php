<?php
// Sicherheit, das A und O
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function sc_budi_counter($atts, $content = null){
    $atts = shortcode_atts([
         'border_radius' => '0px',
         'bg_color' => '#f2f2f2',
         'image' => '',
         'image_max_width' => '75%',
         'text' => '',
         'prefix' => '',
         'suffix' => '',
         'color' => '#000',
         'value' => '',
         'use_decimal' => '',
         'speed' => '1000',      //ms
         'max_width' => '100%',

         'extra_css' => '',
         'css' => ''
    ], $atts);

    $css_class = sc_merge_css( $atts["css"], $atts["extra_css"] );

    $element_id = "counter-element-" . uniqid();
    $js_var = "counter_" . uniqid();

    budi_add_style('budi-counter-element', this_dir_url(__FILE__) . "budi-counter.css");
    budi_add_script('budi-counter-element2-js', this_dir_url(__FILE__) . "jQuery.countTo.js");

    $image = wp_get_attachment_image_url($atts['image'], 'full');

    ob_start();

    if( $atts['border_radius'] == 'Button-Border-Radius' ){
        $atts['border_radius'] = 'var(--button-corners)';
    }

    ?>

    <div id="<?php echo $element_id; ?>" class="counter-element <?php echo $css_class; ?>">
        <style>
        #<?php echo $element_id; ?>{
            background-color: <?php echo $atts['bg_color']; ?>;
            max-width: <?php echo $atts['max_width']; ?>;
            border-radius: <?php echo $atts['border_radius']; ?>;
        }

        #<?php echo $element_id; ?> p{
            color: <?php echo $atts["color"]; ?>;
        }
        #<?php echo $element_id; ?> img{
            max-width: <?php echo $atts["image_max_width"]; ?>
        }

        #<?php echo $element_id; ?> p.counter-element-timer::before {
            content: '<?php echo htmlspecialchars_decode($atts["prefix"]); ?>';
        }

        #<?php echo $element_id; ?> p.counter-element-timer::after {
            content: '<?php echo htmlspecialchars_decode($atts["suffix"]); ?>';
        }
        </style>

        <img src="<?php echo $image; ?>" class="img-responsive" />

        <p class="counter-element-timer" data-from="0" data-to="<?php echo $atts['value']; ?>" data-speed="<?php echo $atts["speed"]; ?>" data-refresh-interval="50" data-decimals="<?php echo $atts['use_decimal'] === "yes" ? "1" : "0"; ?>">0</p>
        <p class="counter-element-description"><?php echo $atts["text"]; ?></p>

        <script>
            var <?php echo $js_var; ?> = 1;

            jQuery(document).ready(function(){
                jQuery(window).scroll(function() {
                    var top_of_element = jQuery("#<?php echo $element_id; ?> .counter-element-timer").offset().top;
                    var bottom_of_element = jQuery("#<?php echo $element_id; ?> .counter-element-timer").offset().top + jQuery("#<?php echo $element_id; ?> .counter-element-timer").outerHeight();
                    var bottom_of_screen = jQuery(window).scrollTop() + jQuery(window).innerHeight();
                    var top_of_screen = jQuery(window).scrollTop();

                    if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)){
                        if( <?php echo $js_var; ?> == 1) {
                            <?php echo $js_var; ?> = 0;
                            jQuery('#<?php echo $element_id; ?> .counter-element-timer').countTo();
                        }
                    } else {
                        // the element is not visible, do something else
                    }
                });

				var top_of_element = jQuery("#<?php echo $element_id; ?> .counter-element-timer").offset().top;
				var bottom_of_element = jQuery("#<?php echo $element_id; ?> .counter-element-timer").offset().top + jQuery("#<?php echo $element_id; ?> .counter-element-timer").outerHeight();
				var bottom_of_screen = jQuery(window).scrollTop() + jQuery(window).innerHeight();
				var top_of_screen = jQuery(window).scrollTop();

				if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)){
					if( <?php echo $js_var; ?> == 1) {
						<?php echo $js_var; ?> = 0;
						jQuery('#<?php echo $element_id; ?> .counter-element-timer').countTo();
					}
				} else {
					// the element is not visible, do something else
				}
            });
        </script>
    </div>

    <?php
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}
add_shortcode('budi-counter-element', 'sc_budi_counter');


add_action( 'vc_before_init', function(){
    if( !function_exists('vc_map') ){ return; }

    vc_map( array(
        "name" => "Budi Counter Element",
        "base" => "budi-counter-element",
        "description" => "Zählt eine Zahl auf einen voreingestellten Wert hoch.",
        "class" => "",
        "icon" => this_dir_url(__FILE__) . "counter-element.svg",
        "category" => "bundesweit.digital",
        // 'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
        // 'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
        "params" => array(
            array(
                "type" => "textfield",
                // "holder" => "div",
                "class" => "",
                "heading" => "Maximale Breite des Elements (mit Einheit)",
                "param_name" => "max_width",
                "value" => "100%",
                "description" => ""
            ),
            array(
                "type" => "colorpicker",
                // "holder" => "div",
                "class" => "",
                "heading" => "Hintergrundfarbe",
                "param_name" => "bg_color",
                "value" => "#f2f2f2",
                "description" => ""
            ),
            array(
              "type" => "dropdown",
              "class" => "",
              "heading" => 'Border-Radius',
              "param_name" => "border_radius",
              "value" => array("0px" => "0px", "5px" => "5px", "10px" => "10px", "15px" => "15px", "20px" => "20px", "25px" => "25px", "30px" => "30px", "Button-Border-Radius" => "var(--button-corners)"),
              "description" => '',
              'admin_label' => false
            ),
            array(
                "type" => "attach_image",
                // "holder" => "div",
                "class" => "",
                "heading" => "Bild",
                "param_name" => "image",
                "value" => "",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => "Maximale Breite des Bildes (mit Einheit)",
                "param_name" => "image_max_width",
                "value" => "75%",
                "description" => ""
            ),
            array(
                "type" => "colorpicker",
                // "holder" => "div",
                "class" => "",
                "heading" => "Textfarbe",
                "param_name" => "color",
                "value" => "#000",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "holder" => "div",
                "class" => "",
                "heading" => "Text",
                "param_name" => "text",
                "value" => "",
                "description" => ""
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => "Prefix",
                "param_name" => "prefix",
                "value" => "",
                "description" => "Zeigt ein beliebigen Text vor der Zahl an."
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => "Suffix",
                "param_name" => "suffix",
                "value" => "",
                "description" => "Zeigt ein beliebigen Text nach der Zahl an."
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => "Zählen bis",
                "param_name" => "value",
                "value" => "",
                "description" => ""
            ),
            array(
                'type' => 'checkbox',
                'heading' => 'Dezimalwert verwenden',
                'param_name' => 'use_decimal',
                'value' => array(
                    'Ja' => 'yes'
                ),
                'admin_label' => false,
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => "Speed (in ms)",
                "param_name" => "speed",
                "value" => "1000",
                "description" => ""
            ),

            array(
                "type" => "textfield",
                "class" => "",
                "heading" => "CSS Klassenname",
                "param_name" => 'extra_css',
                "description" => "",
                "admin_label" => true
            ),
            array(
              'type' => 'css_editor',
              'heading' => 'CSS',
              'param_name' => 'css',
              'group' => 'Design Options',
            ),
        )
    ) );

});


?>
