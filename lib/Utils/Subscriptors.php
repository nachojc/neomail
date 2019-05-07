<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Subscriptors extends Commonview {
  const PARAM ='mails';
    
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



  /**
   *  params
   * p default=0 , (-1 return all registers) // page
   * t = 0:unconfirmed ,1:confirmed, 2:deleted, 9:other // def not 2
   * opt = name //order by
   * o = a | d //order
   * del = true | false
   */
  const SORT_CONST = array(
    'n' => 'A.name', 
    'd' => 'A.description',
    'c' => 'A.created_at',
    'u' => 'A.updated_at'
  ); 
  static function getMails($request){
    global $wpdb; parent::init(self::PARAM);

    $p = $request->get_param('p'); 
    $t = $request->get_param('t'); 
    $opt = $request->get_param('opt'); 
    $ord = $request->get_param('o'); 
    $del = $request->get_param('del');

    $opt = $opt == null || is_null(self::SORT_CONST[$opt]) ? null : self::SORT_CONST[$opt];

    $p = $p == null ? 1 : (int)$p;
    // $t = ($del == 'true' || $t == null) ? "2" : $t;
    $filter = ''; $order = ' ORDER BY';
    if ($p > -1){
      $page = (int)DB::getOption('neomail_'.self::PARAM.'_per_page'); 
      if ($page == 0){ DB::setOption('neomail_'.self::PARAM.'_per_page','20'); $page = 20; }
      $off = max( $page * ((int)$p - 1), 0);
      $filter = ' LIMIT '. $page .' OFFSET '. $off;
    }
    // $where=' WHERE '.($del == 'true'?'':'NOT').' A.status='.$t;
    $where=' WHERE '.($t == null?'NOT A.status=2':'A.status='.$t);
    
    $tbl2 =  Env::$db_prefix . DB::TABLES['relMailList'];

    // $query = 'SELECT A.id, A.name, A.description, GROUP_CONCAT( B.list_id) as lists, COUNT( B.list_id) as total, A.created_at as date, A.updated_at as last FROM '
    //     .self::$table.' A LEFT JOIN `'.$tbl2.'` B on A.id = B.mail_id'.$where.' GROUP BY A.id';
    $query = 'SELECT A.id, A.name, A.company, A.email, A.status, COALESCE(GROUP_CONCAT( B.list_id),"") as lists, A.created_at as date, A.updated_at as last FROM '
        .self::$table.' A LEFT JOIN `'.$tbl2.'` B on A.id = B.mail_id'.$where.' GROUP BY A.id';
    
    $order .= $opt != null ? ' '.$opt : ' name';
    $order .= ($ord == null || $ord == 'a')? ' ASC': ' DESC';
    $query .= $order . $filter;

    // return $query;
    return wp_send_json( array('step' => $page, 'active'=> self::getTotal(),'deleted'=> self::getTotal(false),'attributes' => $wpdb->get_results($query)));



  }

  static function getTotal($active = true){
    global $wpdb; 
    $where = ' WHERE '.($active ? '':'NOT ').'status = 2';
    return $wpdb->get_var('SELECT COUNT(id) FROM '.self::$table . $where);
  }
}
