<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;


class Env {
    static $version;
    static $file;
    static $path;
    static $plugin_name;
    static $plugin_url;
    static $assets_path;
    static $assets_url;
    static $util_path;
    static $views_path;

    static $db_prefix;

    private function __construct() {}

    static function init($config) {
        global $wpdb;
        
        self::$version = $config['version'];
        self::$file = $config['file'];
        self::$path = dirname(self::$file);
        self::$plugin_name = 'neomail';
        self::$plugin_url = plugins_url('', self::$file);
        self::$assets_path = self::$path . '/assets';
        self::$assets_url = plugins_url('/assets', self::$file);
        self::$util_path = self::$path . '/lib/Util';
        self::$views_path = self::$path . '/inc/views';

        self::$db_prefix = $wpdb->prefix . self::$plugin_name . '_';

    }

}