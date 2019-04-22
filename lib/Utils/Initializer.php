<?php

namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;


class Initializer{

    function __construct( ) {
        global $neomail_plugin;
        Env::init( $neomail_plugin );
        
        DB::checkDB();
        Api::init();

        $menu = new Menus();
        $menu->init();
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