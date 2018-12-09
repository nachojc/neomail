<?php

spl_autoload_register( array('Autoloader', 'loadClassLoader') );

class Autoloader {
    const MAIN_NAMESPACE = 'NeoMail';

    static function loadClassLoader(){
        spl_autoload_register(function ($class) {
            global $neomail_plugin;

            if ( false === strpos( $class, self::MAIN_NAMESPACE ) ) {
                return;
            }
            $namespace = explode("\\" , $class );
            
            $len = sizeof($namespace);
            if ($len > 1){
                $file = 'lib/';
                for($i = 1; $i < $len-1 ; $i++){
                    $file .=  $namespace[$i] . '/' ;
                }
                $file .= $namespace[$len-1] . '.php';
                $filepath = $neomail_plugin['path'] . '/'. $file;
                if ( file_exists( $filepath ) ) {
                    include_once( $filepath );
                } else {
                    wp_die(
                        esc_html( "The file attempting to be loaded at $filepath does not exist." )
                    );
                }
                // require_once( $neomail_plugin['path'] . '/'. $file );
            }
        });
    }
}