<?php
// Check if the constant 'ABSPATH' is not defined (WordPress base path constant) 
// OR the function 'vc_map' does not exist (commonly used by Visual Composer).
if ( !defined( 'ABSPATH' ) || !function_exists( 'vc_map' ) ) {
    return;
}

class BUDI_CHILD_SHORTCODES {

    public function __construct() {
        $this->init_hooks();
    }

    /**
     * init_hooks
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'scan_shortcodes_folder' ) );
    }
    
    /**
     * scan_shortcodes_folder
     *
     * @return void
     */
    public function scan_shortcodes_folder() {
        $indir = scandir(_BUDI_CHILD_SHORTCODES_DIR);

        if( $indir ){
            foreach ( $indir as $file ) {
                $fileinfo = pathinfo( _BUDI_CHILD_SHORTCODES_DIR . '/' . $file );
            
                if ( is_dir( _BUDI_CHILD_SHORTCODES_DIR . '/' . $file ) ) {
                    if ( $file == "." || $file == ".." ) {
                        continue;
                    }
                    if ( file_exists( _BUDI_CHILD_SHORTCODES_DIR . "/$file/$file.php" ) ) {
                        require _BUDI_CHILD_SHORTCODES_DIR . "/$file/$file.php";
                    }
                } else {
                    if ( isset( $fileinfo["extension"] ) && $fileinfo["extension"] == 'php' ) {
                        require _BUDI_CHILD_SHORTCODES_DIR . '/' . $file;
                    }
                }
            }
        }
    }
}

new BUDI_CHILD_SHORTCODES();