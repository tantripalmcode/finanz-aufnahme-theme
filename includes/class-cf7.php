<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BUDI_CF7 {
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

        /**
         * Remove auto tag p from contact 7 plugin
         */
        add_filter( 'wpcf7_autop_or_not', '__return_false' );
    }
}

// Call the class
BUDI_CF7::init();
