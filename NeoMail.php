<?php
/**
 * Classic Editor
 *
 * Plugin Name: Neo Mail
 * Plugin URI:  https://www.ipsoft.es/plugins/neomail/
 * Description: Enables WordPress NewsLetters
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

if ( ! class_exists( 'NeoMail' ) ) {
    $neomail_plugin = array(
        'version' => '0.1',
        'file' => __FILE__,
        'templates' => 'templates',
        'path' => dirname(__FILE__),
        'autoloader' => dirname(__FILE__) . '/inc/autoloader.php'
    );

    require_once( $neomail_plugin['autoloader']);
    class NeoMail {
        public static function init_actions() {
            $app = new NeoMail\Utils\Initializer();
        }
    }

    add_action( 'plugins_loaded', array( 'NeoMail', 'init_actions' ) );
}