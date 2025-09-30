<?php
// Load widgets from child theme widgets directory
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