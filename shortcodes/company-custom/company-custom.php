<?php
/**
 * Company Phone Custom
 */
if ( !function_exists( 'sc_company_phone_custom' ) ) {
	function sc_company_phone_custom($atts, $content = null){
		$atts = shortcode_atts([
			'prefix' => '',
			'sanitize_output' => 'yes',
		], $atts);

		$company_phone   = get_theme_mod('company_phone');
		$prefix          = $atts['prefix'];
		$sanitize_output = $atts['sanitize_output'];

		if( empty( $company_phone ) ) return;

		$tel_clean = ltrim( sanitize_tel( $company_phone ), '0' );

		$output = $sanitize_output === 'yes' ? $tel_clean : $company_phone;

		if( $prefix ){
			$output = $prefix . $tel_clean;
		}

		return $output;
	}

	add_shortcode('company-phone-custom', 'sc_company_phone_custom');
}
/**
 * Company Phone Link Custom
 */
if ( !function_exists( 'sc_company_phone_link_custom' ) ) {
	function sc_company_phone_link_custom($atts, $content = null){
		$atts = shortcode_atts([
			'prefix'    => '',
			'class'     => 'company-phone-class',
		], $atts);

		$company_phone = get_theme_mod('company_phone');
		$prefix        = $atts['prefix'];
	
		if( empty( $company_phone ) ) return;
	
		$tel_clean = ltrim( sanitize_tel( $company_phone ), '0' );
	
		$link_classes = apply_filters('company_phone_link_classes', $atts["class"]);

		$company_phone = $prefix ? $prefix . $company_phone : $company_phone;
	
		$output = "<a class='" . $link_classes . "' href='tel:" . $tel_clean . "'>" . $company_phone . "</a>";
	
		return $output;
	}
	add_shortcode('company-phone-link-custom', 'sc_company_phone_link_custom');
}

/**
 * Company Fax Link Custom
 */
if ( !function_exists( 'sc_company_fax_link_custom' ) ) {
	function sc_company_fax_link_custom($atts, $content = null){
		$atts = shortcode_atts([
			'prefix'    => '',
			'class'     => 'company-fax-class',
		], $atts);

		$tel_clean = ltrim( sanitize_tel( get_theme_mod('company_fax') ), '0' );		// Funktion sanitize_tel in inc/functions.php

		$link_classes = apply_filters('company_fax_link_classes', $atts["class"]);

		$output = "<a class='" . $link_classes . "' href='tel:" . $tel_clean . "'>" . get_theme_mod('company_fax') . "</a>";

		if( $atts["prefix"] && get_theme_mod('company_fax') ){
			$output = $atts["prefix"] . $output;
		}

		return $output;
	}
	add_shortcode('company-fax-link-custom', 'sc_company_fax_link_custom');
}