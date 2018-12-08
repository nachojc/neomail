<?php

spl_autoload_register( array('Autoloader', 'loadClassLoader') );

class Autoloader {
    static function loadClassLoader(){
        spl_autoload_register(function ($class) {
            global $neomail_plugin;

            $namespace = explode("\\" , $class );
            if ($class == 'NeoMail'){
                return true;
            }
            
            
            $len = sizeof($namespace);
            if ($len ==1){
                require_once ($namespace[0] . '.php');
                // include $namespace[0] . '.php';
            } else {
                $file = 'lib/';
                for($i = 1; $i <$len-1; $i++){
                    $file .=  $namespace[$i] . '/' ;
                }
                $file .=  $namespace[$len-1] . '.php';

                require_once($neomail_plugin['path'] . '/'. $file);
            }
            


            // include $nombre_clase . '.php';


            // var_dump(get_included_files());
        });
    }

    public function library($class)
    {
        set_include_path(get_include_path().PATH_SEPARATOR.'/lib/');
        spl_autoload_extensions('.php');
        spl_autoload($class);
    }
}