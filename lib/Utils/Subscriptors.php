<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Subscriptors extends Commonview {
    
  function init($env = 'mails'){
    parent::init($env);
    echo '
    <div class="wrap">
      <neo-root>Loading....</neo-root>
    </div>
    <script type="text/javascript" src="'.ENV::$plugin_url.'/assets/ang/suscriptors/runtime.js"></script>
    <script type="text/javascript" src="'.ENV::$plugin_url.'/assets/ang/suscriptors/polyfills.js"></script>
    <script type="text/javascript" src="'.ENV::$plugin_url.'/assets/ang/suscriptors/styles.js"></script>
    <script type="text/javascript" src="'.ENV::$plugin_url.'/assets/ang/suscriptors/vendor.js"></script>
    <script type="text/javascript" src="'.ENV::$plugin_url.'/assets/ang/suscriptors/main.js"></script>
    ';
  }



  
}
