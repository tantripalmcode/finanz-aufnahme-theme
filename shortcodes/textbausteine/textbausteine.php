<?php
include get_stylesheet_directory() . '/shortcodes/textbausteine/cpt.php';

function sc_textbaustein($atts, $content){
    $atts = shortcode_atts( [
        'textbaustein_title' => '',
    ], $atts );

    if( $atts['textbaustein_title'] == "" ){ return ""; }


    $posts = get_posts( array(
        'post_type' => 'textbausteine',
        'numberposts' => -1
    ) );

    foreach ($posts as $post) {
        // echo "Postname: " . $post->post_name . "<br/>";
        // print_r($post);

        if(strtolower($post->post_title) == strtolower($atts['textbaustein_title'])){
            return apply_shortcodes(nl2br($post->post_content));
        }
    }
}
add_shortcode('textbaustein', 'sc_textbaustein');


function vc_get_textbausteine(){


    $posts = get_posts( array(
        'post_type' => 'textbausteine',
        'numberposts' => -1
    ) );

    $return = array();

    foreach($posts as $post){
        $return[$post->post_title] = strtolower($post->post_title);
    }

    return $return;
}

/*
	WPBakery den Shortcode beibringen und Datenfelder beschreiben für das Konfig-Feld

    REFERENZ: https://kb.wpbakery.com/docs/inner-api/vc_map/
*/



add_action( 'vc_before_init', function(){

    if( !function_exists('vc_map') ){ return;}

	vc_map( array(
		"name" => "Textbausteine",
		"description" => "Zeigt einen globalen Textbaustein an",
		"base" => "textbaustein",
		"class" => "",
		//"icon" => this_dir_url(__FILE__) . 'bundesweit.digital.png', // URL zum Element-Icon
		"category" => "Textbausteine",
		"content_element" => true,
		"params" => array(
			array(
				"type" => "dropdown",
				"class" => "",
				"heading" => "Textbaustein",
				"param_name" => "textbaustein_title",
				"value" => vc_get_textbausteine(),
                "std" => "",
				"description" => "",
				"admin_label" => true
			),
		),
    ) );
 } );
