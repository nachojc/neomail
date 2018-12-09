<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Lists extends Commonview {
  const PARAM ='lists';

  function init($env = 'lists'){
    parent::init($env);
    

    echo 'Lists';
   
  }

  

  // API
  function getLists($request){
    global $wpdb; parent::init(self::PARAM);
    
    $filter = '';
    $p = $request->get_param('p');
    $p = $p == null ? 1 : (int)$p;
    if ($p > -1){
      $page = (int)DB::getOption('neomail_'.self::PARAM.'_per_page'); 
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
