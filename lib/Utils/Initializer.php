<?php

namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;


class Initializer{

    function __construct( ) {
        global $neomail_plugin;
        Env::init( $neomail_plugin );
        
        //  TODO no va
        // register_activation_hook( 
        //     Env::$file,
        //     array(
        //         $this,
        //         'activate'
        //         )
        //     );
        // register_uninstall_hook( 
        //     Env::$file,
        //     array(
        //         $this,
        //         'uninstall'
        //         )
        //     );

        DB::checkDB();
        Api::init();

        $menu = new Menus();
        $menu ->init();

    }
    

    /**
     * Activation.
     */
    function activate(){
        DB::checkDB();
    }

    /**
     * Uninstall.
     */
    public static function uninstall() {
    }
    
}