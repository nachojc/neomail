<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Commonview{
    static $table;
    static $view;
    static $per_page;
    static $repeat;


    static public function init($env = ''){
        self::$repeat = '';
        self::$table = Env::$db_prefix . DB::TABLES[$env];
        self::$per_page = get_user_meta(
          get_current_user_id(), 'neomail_'.$env.'_per_page', true
        );
      }

    protected function addHeader(){ echo self::getHeader();}
    protected function getHeader(){
        return '<div class="wrap">
            <div class="alert-top error" style="display:none;"></div>
            <div class="alert-top info" style="display:none;"></div>
        ';
    }

    public function addFooter(){ echo self::getFooter();}
    protected function getFooter(){ return '</div>'; }
    
    function getFullView($view = ''){
        $contend = str_replace("{{REPEAT_CONTEND}}", self::$repeat, self::$view);
        return self::getHeader() . $contend . self::getFooter();
    }
    function getView($view = ''){
        if ($view != ''){
            self::loadView($view);
        }
        return isset(self::$view) ? self::$view : '';
    }
    protected function loadView($view){
        $file = Env::$plugin_view_path . '/' . $view . '.html';

        self::$view = file_exists($file) ? file_get_contents($file) : '';
        self::setRepeat();
    }
    protected function replaceInView($search, $replace){
        self::$view = str_replace("{{".$search."}}", $replace, self::$view);
    }

    protected function replace($search, $replace, $template){
        return str_replace("{{".$search."}}", $replace, $template);
    }
    protected function replaceObj($obj, $template){
        foreach ($obj as $key => $val) {
            $template = str_replace("{{".$key."}}", $val, $template);
        }
        return $template;
    }

    private function setRepeat(){
        $result = explode('{{REPEAT}}',self::getView() );
        self::$view = $result[0] . '{{REPEAT_CONTEND}}' . $result[2];
        self::$repeat =  $result[1];
      }
}