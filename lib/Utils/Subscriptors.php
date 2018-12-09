<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Subscriptors extends Commonview {
    
  function init($Env = 'mails'){
    parent::init($Env);
    echo '
    <div class="wrap">
      <neo-root>Loading....</neo-root>
    </div>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/runtime.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/polyfills.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/styles.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/vendor.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/suscriptors/main.js"></script>
    ';
  }



  
}
