<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BUDI_CHILD_CUSTOMIZER {
    private static $initiated = false;
    
    /**
     * init
     *
     * @return void
     */
    public static function init() {
        if( !self::$initiated ) {
            self::init_hooks();
        }
    }

    /**
     * init_hooks
     *
     * @return void
     */
    private static function init_hooks() {
        self::$initiated = true;

        add_action( 'add_customizer_options_in_section', array( __CLASS__, 'custom_unternehmen_section' ), 10, 2 );
        add_action( 'add_customizer_options_in_section', array( __CLASS__, 'custom_seitenoptionen_section' ), 10, 2 );
    }

    public static function custom_unternehmen_section( $title_section, $section ) {
        if ( $title_section === "Unternehmen" ) {
            bundesweit_theme_customize::add_customizer_image_option( "Unternehmens-Logo White", "company_logo_white", $section );
        }
    }

    public static function custom_seitenoptionen_section( $title_section, $section ) {
        if ( $title_section === "Seitenoptionen" ) {
            bundesweit_theme_customize::add_customizer_seperator("Section Spacing", $section);
            bundesweit_theme_customize::add_customizer_text_option("Section Spacing Large", "section_spacing_large", $section, $default = "120px");
            bundesweit_theme_customize::add_customizer_text_option("Section Spacing Medium", "section_spacing_medium", $section, $default = "80px");
            bundesweit_theme_customize::add_customizer_text_option("Section Spacing Small", "section_spacing_small", $section, $default = "60px");

            bundesweit_theme_customize::add_customizer_seperator("Heading Spacing", $section);
            bundesweit_theme_customize::add_customizer_text_option("H1", "h1_spacing", $section, $default = "80px");
            bundesweit_theme_customize::add_customizer_text_option("H2", "h2_spacing", $section, $default = "60px");
            bundesweit_theme_customize::add_customizer_text_option("H3", "h3_spacing", $section, $default = "30px");
            bundesweit_theme_customize::add_customizer_text_option("H4", "h4_spacing", $section, $default = "20px");
            bundesweit_theme_customize::add_customizer_text_option("H5", "h5_spacing", $section, $default = "20px");
            bundesweit_theme_customize::add_customizer_text_option("H6", "h6_spacing", $section, $default = "20px");
            bundesweit_theme_customize::add_customizer_text_option("Sub-Headline", "sub_headline_spacing", $section, $default = "18px");
        }
    }
    
}

// Call the class
BUDI_CHILD_CUSTOMIZER::init();