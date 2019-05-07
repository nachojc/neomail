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

  

// APIs
static function getSimpleLists($request){
  global $wpdb; parent::init(self::PARAM);
  $query = 'SELECT id, name FROM '.self::$table.' WHERE deleted=0 ORDER BY name';
  // return $query;
  return wp_send_json( array('status' => '200','attributes' => $wpdb->get_results($query)));
}

/**
 *  params
 * p default=0 , (-1 return all registers) // page
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
  static function getLists($request){
    global $wpdb; parent::init(self::PARAM);
    
    $p = $request->get_param('p'); 
    $opt = $request->get_param('opt'); 
    $ord = $request->get_param('o'); 
    $del = $request->get_param('del');

    $opt = $opt == null || is_null(self::SORT_CONST[$opt]) ? null : self::SORT_CONST[$opt];

    $p = $p == null ? 1 : (int)$p;
    $filter = ''; $order = ' ORDER BY';
    if ($p > -1){
      $page = (int)DB::getOption('neomail_'.self::PARAM.'_per_page'); 
      if ($page == 0){ DB::setOption('neomail_'.self::PARAM.'_per_page','20'); $page = 20; }
      $off = max( $page * ((int)$p - 1), 0);
      $filter = ' LIMIT '. $page .' OFFSET '. $off;
    }
    $where=' WHERE A.deleted='.($del == 'true'?'1 ':'0');
    
    $tbl2 =  Env::$db_prefix . DB::TABLES['relMailList'];

    $query = 'SELECT id, name, description, COALESCE(SUM(B.status ), 0) as status, COUNT( B.list_id) as total, A.created_at as date, A.updated_at as last FROM '
        .self::$table.' A LEFT JOIN `'.$tbl2.'` B on A.id = B.list_id'.$where.' GROUP BY A.id';
    
    $order .= $opt != null ? ' '.$opt : ' name';
    $order .= ($ord == null || $ord == 'a')? ' ASC': ' DESC';
    $query .= $order . $filter;

    return wp_send_json( array('step' => $page, 'active'=> self::getTotal(),'deleted'=> self::getTotal(false),'attributes' => $wpdb->get_results($query)));
  }
  static function addElement($request){
    global $wpdb; parent::init(self::PARAM);
    $name = strip_tags($request->get_param('name'));
    $description = rip_tags($request->get_param('description'));

    $wpdb->insert(self::$table ,  array('name' => $name, 'description' => $description));
    
    return wp_send_json(array( "id" => $wpdb->insert_id), 200);
  }

  /**
 *  params
 * p default=0 , (-1 return all registers) // page
 * opt = name //order by
 * o = a | d //order
 * del = true | false
 */
  static function deleteElement($request){
    global $wpdb; parent::init(self::PARAM);
    
    $id = $request->get_param('id');
    $value = $request->get_param('r');
    $token = $request->get_param('v');

    if(is_null( $token )) {
      $value = is_null($value) ? '1' : $value;
      return self::removeElement($id, $value);
    }

    return $wpdb->delete(self::$table, array('id' => $id, 'deleted' => 1, 'updated_at'=> $token), array('%d','%d', '%s'));
  }

  static function removeElement($id, $val = 1){
    global $wpdb; parent::init(self::PARAM);

    return $wpdb->update(self::$table, array('deleted' => $val), array('id' => $id), array('%d'), array('%d'));
  }

  static function updateElement($request){
    global $wpdb; parent::init(self::PARAM);

    $id = filter_var( $request->get_param('id'), FILTER_VALIDATE_INT) ;
    $name = filter_var( $request->get_param('name'), FILTER_SANITIZE_STRING);
    $description = filter_var( $request->get_param('description'), FILTER_SANITIZE_STRING);
    $token = filter_var( $request->get_param('v'), FILTER_SANITIZE_STRING);

    $wpdb->update(self::$table, array("name" => $name, "description"=> $description), array("id" => $id, 'updated_at'=> $token), array('%s', '%s'), array('%d', '%s'));
    return wp_send_json($wpdb->get_results('SELECT * FROM '.self::$table.' WHERE id='.$id));
    // return $wpdb->last_query;
  }


  static function getTotal($active = true){
    global $wpdb; 
    $where = ' WHERE '.($active ? '':'NOT ').'deleted = 0';
    return $wpdb->get_var('SELECT COUNT(id) FROM '.self::$table . $where);
  }
}
