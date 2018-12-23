<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Lists extends Commonview {
  const PARAM ='lists';

  static function init($env = 'lists'){
    parent::init($env);

    echo '
    <div class="wrap">
      <neo-root>Loading....</neo-root>
    </div>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/runtime.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/polyfills.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/vendor.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/lists/styles.js"></script>
    <script type="text/javascript" src="'.Env::$assets_url.'/ang/lists/main.js"></script>
    ';
   
  }

  

  // API
  static function getLists($request){
    global $wpdb; parent::init(self::PARAM);
    
    $filter = '';
    $p = $request->get_param('p');
    $p = $p == null ? 1 : (int)$p;
    if ($p > -1){
      $page = (int)DB::getOption('neomail_'.self::PARAM.'_per_page'); 
      if ($page == 0){
        DB::setOption('neomail_'.self::PARAM.'_per_page','20');
        $page = 20;
      }
      $off = max( $page * ((int)$p - 1), 0);
      $filter = ' LIMIT '. $page .' OFFSET '. $off;
    }

    $tbl2=  Env::$db_prefix . DB::TABLES['relMailList'];
    $query ='SELECT id, name, description, COALESCE(SUM(B.status ), 0) as status, COUNT( B.list_id) as total, A.created_at as date FROM '.self::$table.' A LEFT JOIN `'.$tbl2.'` B on A.id = B.list_id GROUP BY A.id '. $filter;

    return wp_send_json( array('total'=> self::getTotal(),'attributes' => $wpdb->get_results($query)));
  }
  static function addElement($request){
    global $wpdb; parent::init(self::PARAM);
    $name = $request->get_param('name');
    $description = $request->get_param('description');


    $data = array('name' => $name, 'description' => $description);
    $wpdb->insert(self::$table ,  $data);
    
    
    return wp_send_json(array( "id" => $wpdb->insert_id), 200);
  }

  static function deleteElement($request){
    global $wpdb; parent::init(self::PARAM);

    $id = $request->get_param('id');

    return $wpdb->update(self::$table, array('deleted' => 1), array('id' => $id), array('%d'), array('%d'));
  }
  static function removeElement($request){
    global $wpdb; parent::init(self::PARAM);
    $id = $request->get_param('id');

    return $wpdb->update(self::$table, array('deleted' => 1), array('id' => $id), array('%d'), array('%d'));
  }

  static function getTotal(){
    global $wpdb; 

    return $wpdb->get_var('SELECT COUNT(id) FROM '.self::$table);
  }
}
