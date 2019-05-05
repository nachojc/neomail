<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Api{
    const VERSION = '/v1';
    const NAMESPACE = 'neomail' . self::VERSION;

    static function init(){
        add_action('rest_api_init', function(){ self::registrar_endpoints(); });
    }

    static function registrar_endpoints(){
        register_rest_route( self::NAMESPACE, '/list', self::getArrayList());
        register_rest_route( self::NAMESPACE, '/list/add/', self::getArrayList_Add());
        register_rest_route( self::NAMESPACE, '/list/upt/(?P<id>[\d]+)', self::getArrayList_Update());
        register_rest_route( self::NAMESPACE, '/list/del/(?P<id>[\d]+)', self::getArrayList_Del());
        register_rest_route( self::NAMESPACE, '/lists', self::getSimpleLists());
        

        register_rest_route( self::NAMESPACE, '/token/', self::getArrayToken());
    }


    

    static function getArrayList(){
        return array(
            'methods'  => 'GET',
            'callback' => function( $request){
                return Lists::getLists($request);
            },
            'args'     => array(
                'p' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
                'opt' => array(
                    'sanitize_callback' => function($param, $request, $key) {
                        return wp_strip_all_tags( $param, true );
                    }
                ),
                'o' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return $param == 'a' || $param== 'd';
                    }
                ),
                'del' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return $param == 'true' || $param == 'false' || $param == null ;
                    }
                )
            )
        );
    }

    static function getArrayList_Add(){
        return array(
            'methods'  => 'POST',
            'callback' => function( $request){
                return Lists::addElement($request);
            },
            'args'     => array(
                'name' => array(
                    'required'          => true,
                    'sanitize_callback' => function($param, $request, $key) {
                        return strip_tags( $param );
                    }
                ),
                'description' => array(
                    'required'          => true,
                    'sanitize_callback' => function($param, $request, $key) {
                        return strip_tags( $param );
                    }
                )
            )
        );
    }

    static function getArrayList_Del(){
        return array(
            'methods'  => 'PUT',
            'callback' => function( $request){
                return Lists::deleteElement($request);
            },
            'args'     => array(
                'id' => array(
                    'required'          => true,
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
                'r' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return $param == '1' || $param == '0';
                    }
                ),
                'v' => array(
                    'validate_callback' => function($param, $request, $key) {
                        return Helper::isDateFormat($param);
                    }
                )
            )
        );
    }

    static function getArrayList_Update(){
        return array(
            'methods'  => 'PUT',
            'callback' => function( $request){
                return Lists::updateElement($request);
            },
            'args'     => array(
                'id' => array(
                    'required'          => true,
                    'validate_callback' => function($param, $request, $key) {
                        return is_numeric($param);
                    }
                ),
                'v' => array(
                    'required'          => true,
                    'validate_callback' => function($param, $request, $key) {
                        return Helper::isDateFormat($param);
                    }
                )
            )
        );
    }
    
    static function getArrayToken(){
        return array(
            'methods'  => 'GET',
            'callback' => function( $request){
                
                return wp_create_nonce( 'wp_rest' );
            }
        );
    }

    static function getSimpleLists(){
        return array(
            'methods'  => 'GET',
            'callback' => function( $request){
                return Lists::getSimpleLists($request);
            }
        );
    }
}