<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Lists extends Commonview {
  const PARAM ='lists';

  function init($env = 'lists'){
    parent::init($env);
    self::loadView($env);

    $total =  self::getTotal()[0]['total'];
    $del = self::getTotal()[1]['total'];
    // die(var_dump());
    self::replaceInView('TOTAL', $total);
    self::replaceInView('DELETED', $del);

    self::parseData(self::getResults(), self::$repeat);

    echo self::getFullView();
    wp_enqueue_script('neomail_js_'.self::PARAM);
  }

  private function parseData($data, &$template){
    $tmp_Data = '';
    foreach ($data as $result) {
      $tmp = $template;
      $tmp_Data .= self::replaceObj($result, $tmp);
    }

    $template = $tmp_Data;
  }
  
  private function getTotal(){
    global $wpdb;
    return $wpdb->get_results( "SELECT COUNT(Id) as 'total' FROM ". self::$table ." GROUP BY deleted ORDER BY deleted ASC", ARRAY_A );
  }
  
  private function getResults() {
    global $wpdb;

    $trash = ( array_key_exists("trash", $_GET) && trim($_GET['trash'])=='true' ) ? '1' : '0';
    $cond = " WHERE deleted=" . $trash; 
    
    $query = "SELECT * FROM ". self::$table . $cond ." ORDER BY name ASC LIMIT " . self::$per_page;
    return $wpdb->get_results( $query );
  }

  // API
  function getLists($request){
    global $wpdb; parent::init(self::PARAM);
    $filter = '';
    $p = $request->get_param('p');
    $p = $p == null ? 1 : (int)$p;
    if ($p > -1){
      $page = DB::getOption('neomail_'.self::PARAM.'_per_page'); //self::$per_page || 20;
      $off = max( $page * ((int)$p - 1), 0);
      $filter = ' LIMIT '. $page .' OFFSET '. $off;
    }



    $query = 'SELECT id,name FROM `'. self::$table . '`' . $filter . ';';
    return wp_send_json($wpdb->get_results($query));
  }
  function addElement($request){
    global $wpdb; parent::init(self::PARAM);
    $name = $request->get_param('name');
    $description = $request->get_param('description');


    $data = array('name' => $name, 'description' => $description);
    $wpdb->insert(self::$table ,  $data);
    
    
    return wp_send_json(array( "id" => $wpdb->insert_id,"tabla"=> $table,"query"=> $wpdb->last_query), 200);
  }

  function deleteElement($request){
    global $wpdb; parent::init(self::PARAM);

    $id = $request->get_param('id');

    return $wpdb->update(self::$table, array('deleted' => 1), array('id' => $id), array('%d'), array('%d'));
  }
  function removeElement($request){
    global $wpdb; parent::init(self::PARAM);
    $id = $request->get_param('id');

    return $wpdb->update(self::$table, array('deleted' => 1), array('id' => $id), array('%d'), array('%d'));
  }
}
