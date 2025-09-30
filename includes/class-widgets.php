<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class BUDI_CHILD_WIDGETS {

    public function __construct() {
        $this->init_hooks();
    }

    /**
     * init_hooks
     *
     * @return void
     */
    private function init_hooks() {
        add_action( 'init', array( $this, 'scan_widgets_folder' ) );
    }

    /**
     * Scan widgets folder
     */
    public function scan_widgets_folder() {
        $indir = scandir(_BUDI_CHILD_WIDGETS_DIR);

        foreach($indir as $file){
            $fileinfo = pathinfo( _BUDI_CHILD_WIDGETS_DIR . '/' . $file );

            if( is_dir( _BUDI_CHILD_WIDGETS_DIR . '/' . $file ) ){
                if( $file == "." || $file == ".." ){ continue; }
                if( file_exists(_BUDI_CHILD_WIDGETS_DIR . "/$file/$file.php") ){
                    require _BUDI_CHILD_WIDGETS_DIR . "/$file/$file.php" ;
                }
            }else{
                if( isset($fileinfo["extension"]) && $fileinfo["extension"] == 'php'){
                    require _BUDI_CHILD_WIDGETS_DIR . '/' . $file;
                }
            }
        }
    }
}

new BUDI_CHILD_WIDGETS();