<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BUDI_ACF {
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
    }
    
}

// Call the class
BUDI_ACF::init();