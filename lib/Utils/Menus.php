<?php

namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Menus{
    const SLUG = 'neomail_';
    const MAIN_PAGE_SLUG = 'NeoMail';
    const ACCESS_CONTROL = 'manage_options';

    public function __construct(){
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts',  array($this,'register_js'));
    }

    function add_admin_menu(){
        add_menu_page('Neo Mail News', 'Neo Mail', self::ACCESS_CONTROL, self::MAIN_PAGE_SLUG,  null, Env::$assets_url . "/img/menuico.png" );
        
        add_submenu_page(self::MAIN_PAGE_SLUG, 'Dashboard', 'Dashboard', self::ACCESS_CONTROL, self::MAIN_PAGE_SLUG, array(&$this,'Dashboard') );

        $this->$lists_page_opt = add_submenu_page(self::MAIN_PAGE_SLUG, 'Lists', 'Lists', self::ACCESS_CONTROL, self::SLUG.'lists', array(&$this,'lists') );
        add_action('load-' . $this->$lists_page_opt, function () {
            add_screen_option( 'per_page', array(
                'label' => __('Lists in page', 'neomail'),
                'default' => 20,
                'option' => 'neomail_lists_per_page'
            ) );
        });
        
        $this->$mails_page_opt = add_submenu_page(self::MAIN_PAGE_SLUG, 'Users', 'Users', self::ACCESS_CONTROL, self::SLUG.'users', array(&$this,'users') );
        add_action('load-' . $this->$mails_page_opt, function () {
            add_screen_option( 'per_page', array(
                'label' => __('Lists in page', 'neomail'),
                'default' => 20,
                'option' => 'neomail_mails_per_page'
            ) );
        });

        add_submenu_page(self::MAIN_PAGE_SLUG, 'Newsletter', 'New newsleter', self::ACCESS_CONTROL, self::SLUG.'mail', array(&$this,'newNewsleter') );

    }

    function init(){
        $this->load_Css();
        $this->add_filters();
    }

    function Dashboard(){
        Dashboard::init();
    }
    function lists($opt){
        Lists::init();
    }
    function users(){
        Subscriptors::init();
    }
    function newNewsleter(){
        Newsletter::init();
    }


    function load_Css(){
        $css_loader = new CSS_Loader();
        $css_loader->init();
    }

    function register_js(){
        if (!is_admin() ) return;

        wp_register_script('neomail_js_lists', ENV::$plugin_url . '/assets/js/lists.js', array('jquery'), '1', true );
        
    }

    function add_filters(){
        add_filter(
            'set-screen-option',
            array($this, 'setScreenOption'),
            10, 3
          );
    }

    function setScreenOption($status, $option, $value) {
        if(preg_match('/^neomail_(.*)_per_page$/', $option)) {
            var_dump($status,':' ,$option,':' ,$value);

            DB::setOption($option, $value);
            return $value;
          } else {
            return $status;
          }
    }

    
}
