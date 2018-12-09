<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class DB {

    const BASE = 'neomail';
    const TABLES = array(
        'mails' => 'subcriptors',
        'sends' => 'newsleters',
        'lists' => 'lists',
        'statistics' => 'statistics',
        'settings' => 'options',
        'relMailList' => 'maillist'
    );
    
    // mail: status ( 0:unconfirmed ,1:confirmed, 2:deleted, 9:other)
    // sends: status ( 0:saved , 1:pending, 2:cancelled, 9:sended)
    
    const QUERYS = array(
        'mails' => "(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `company` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
            `status` int(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0, 
            `subscribed_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `confirmed_ip` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
            `confirmed_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `deleted_at` timestamp NULL DEFAULT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        )",
        'sends' => "(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
            `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `sender_address` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `sender_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `status` int(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0, 
            `sends` int(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0, 
            `errors` int(11) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0, 
            `sent_at` timestamp NULL DEFAULT current_timestamp(),
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            INDEX `hash` (`slug`)
        )",
        'lists' => "(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `description` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            `deleted` int(1) NOT NULL DEFAULT 0,            
            PRIMARY KEY (`id`),
            UNIQUE KEY`name` (`name`)
        )",
        'statistics' => "(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            PRIMARY KEY (`id`)
        )",
        'settings' => "(
            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `value` longtext COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            PRIMARY KEY (`id`),
            UNIQUE KEY`name` (`name`),
            INDEX `opt` (`name`)
        )",
        'relMailList' => "(
            `list_id` int(11) UNSIGNED NOT NULL,
            `mail_id` int(11) UNSIGNED NOT NULL,
            `status` int(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 0, 
            `created_at` timestamp NULL DEFAULT current_timestamp(),
            `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
            UNIQUE KEY `mail_list` (`list_id`, `mail_id`),
            INDEX `mail_status` (`list_id`, `status`)
        )"                  
    );

    function getOption($opt){
        global $wpdb;

        return $wpdb->get_var('SELECT `value` FROM `'. self::getTableName() .'` WHERE name="' . $opt .'"' ) ;
    }
    function setOption($opt, $value){
        global $wpdb;
        if (self::getOption($opt)!= null){
            $result = $wpdb->update(self::getTableName(), array('value' => $value), array('name' => $opt), array('%s'), array('%s'));
        }else{
            $result = $wpdb->insert(self::getTableName() , array('value' => $value, 'name' => $opt));
        }
        return $result;
    }

    function getTableName(){
        return Env::$db_prefix . DB::TABLES['settings'];
    }


    

    static function checkDB(){
        foreach (self::TABLES as $key => $value){
            self::create_table ( $key );
        }
        
    }
    
    static function create_table($table) {
        global $wpdb;

        $table_name = $wpdb->prefix . self::BASE . '_' . self::TABLES[$table];

     
        if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name . "'" ) == $table_name ) {
            return true;
        }
     
        $query =  'CREATE TABLE ' . $table_name . ' ' . self::QUERYS[$table];
        $wpdb->query($query);
     
        return $wpdb->get_var( "SHOW TABLES LIKE '" . $table_name . "'"  ) == $table_name ;
    }


}