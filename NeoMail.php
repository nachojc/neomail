<?php
/**
 * Classic Editor
 *
 * Plugin Name: Neo Mail
 * Plugin URI:  https://www.ipsoft.es/plugins/neomail/
 * Description: Enables the WordPress Send NewsLetters
 * Version:     0.1
 * Author:      Nachojc
 * Author URI:  https://www.ipsoft.es/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: classic-editor
 * Domain Path: /languages
 * Network:     true
 *
  */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

use NeoMail\Utils\Menus;

if ( ! class_exists( 'NeoMail' ) ) {
    $neomail_plugin = array(
        'version' => '0.1',
        'filename' => __FILE__,
        'path' => dirname(__FILE__),
        'autoloader' => dirname(__FILE__) . '/inc/autoloader.php'
    );

    require_once( $neomail_plugin['autoloader']);
    class NeoMail {
        private function __construct() {}

        public static function init_actions() {
            register_activation_hook( __FILE__, array( __CLASS__, 'activate' ) );
            register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );

            $menu = new Menus();
        }
    
        /**
         * Activation.
         */
        public static function activate() {
        }

        /**
         * Uninstall.
         */
        public static function uninstall() {
        }
    }


    add_action( 'plugins_loaded', array( 'NeoMail', 'init_actions' ) );
}