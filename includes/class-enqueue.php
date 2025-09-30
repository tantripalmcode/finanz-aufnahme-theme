<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BUDI_CHILD_ENQUEUE {
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

        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_child_css' ), 20 );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_child_js' ), 20 );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'dequeue_child_scripts' ), 100 );
    }

    /**
     * enqueue_child_css
     *
     * @return void
     */
    public static function enqueue_child_css() {
        // Swiper CSS
        wp_register_style( 'swiper', _BUDI_ASSETS_URL . 'lib/swiper/swiper-bundle.min.css', array(), '10.3.1' );
        wp_register_style('effect-panorama', _BUDI_ASSETS_URL . 'lib/effect-panorama/effect-panorama.min.css', array(), '1.0.0', 'all');

        // AOS Animation
        // wp_enqueue_style( 'aos-animation', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], '2.3.1', 'all' );
        wp_enqueue_style( 'aos-animation', _BUDI_ASSETS_URL . 'lib/aos/aos.css', [], '2.3.1', 'all' );

        // Header & Footer CSS
        $style_modified = date( 'YmdHis', filemtime( _BUDI_PATH . '/css/header-footer.css' ) );
        wp_register_style( 'budi-header-footer-style', _BUDI_URL . '/css/header-footer.css', array(), $style_modified, 'all' );
        wp_enqueue_style( 'budi-header-footer-style' );

        $style_modified = date( 'YmdHis', filemtime( _BUDI_PATH . '/css/style.css' ) );
        wp_register_style( 'budi-child-style', _BUDI_URL . '/css/style.css', array(), $style_modified, 'all' );
        wp_enqueue_style( 'budi-child-style' );
    }

    /**
     * enqueue_child_js
     *
     * @return void
     */
    public static function enqueue_child_js() {
        // Swiper JS
        wp_register_script( 'swiper', _BUDI_ASSETS_URL . 'lib/swiper/swiper-bundle.min.js', array(), '10.3.1', true );
        wp_register_script('effect-panorama', _BUDI_ASSETS_URL . 'lib/effect-panorama/effect-panorama.min.js', array(), '1.0.0', true);

        // Rellax js
        wp_enqueue_script( 'rellax', 'https://cdn.jsdelivr.net/gh/dixonandmoe/rellax@master/rellax.min.js', array(), '1.0.0', true );

        // AOS Animation
        // wp_enqueue_script( 'aos-animation', 'https://unpkg.com/aos@2.3.1/dist/aos.js', [], '2.3.1', true );
        wp_enqueue_script( 'aos-animation', _BUDI_ASSETS_URL . 'lib/aos/aos.js', [], '2.3.1', true );

        // Get scripts modification time.
        $modified = date( 'YmdHis', filemtime( _BUDI_PATH . '/js/scripts.js' ) );
        wp_enqueue_script( 'budi-child-scripts', _BUDI_URL . '/js/scripts.js', array(), $modified, true );

        // Add ajax url
        $vars['ajaxurl'] = admin_url( 'admin-ajax.php' );
        $vars['strings'] = array(
            'error_400' => __( 'Unauthorized access', _BUDI_TEXT_DOMAIN ),
            'error_403' => __( 'Authorization error, please contact your webmaster', _BUDI_TEXT_DOMAIN ),
            'error_500' => __( 'Server error, please contact your server administrator.', _BUDI_TEXT_DOMAIN ),
        );

        wp_localize_script( 'budi-child-scripts', '_budigital', $vars );
    }

    /**
     * dequeue_child_scripts
     *
     * @return void
     */
    public static function dequeue_child_scripts() {
        // dequeue CSS
        wp_dequeue_style( 'slick' );
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' );
        wp_dequeue_style( 'mmenu' );
        wp_dequeue_style( 'font-awesome' );

        if( is_front_page() ){
            wp_dequeue_style( 'wp-job-manager-job-listings' );
        }

        // dequeue JS
        wp_dequeue_script( 'slick' );
        wp_dequeue_script( 'mmenu' );
    }
}

// Call the class
BUDI_CHILD_ENQUEUE::init();
