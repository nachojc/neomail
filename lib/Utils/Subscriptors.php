<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Subscriptors extends Commonview {
    
  static function init($Env = 'mails'){
    parent::init($Env);
    echo '
    <div class="wrap">
      <neo-root>Loading....</neo-root>
    </div>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/runtime.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/polyfills.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/vendor.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/styles.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/main.js"></script>
    ';
  }



  
}
