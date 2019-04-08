<?php
namespace NeoMail\Utils;
if(!defined('ABSPATH')) exit;

class Api{
    static function init(){

        add_action('rest_api_init', function(){
            self::registrar_endpoints();
        });
    }
    static function registrar_endpoints(){
        register_rest_route(
            'neomail/v1', // namespace
            '/list', // ruta
            array(
                'methods'  => 'GET',
                'callback' => function( $request){
                    return Lists::getLists($request);
                },
                'args'     => array(
                    'p' => array(
                        'required'          => false,
                        'validate_callback' => function($param, $request, $key) {
							return is_numeric($param);
						},
                        'sanitize_callback' => function($param, $request, $key) {
                            return strip_tags( $param );
                        }
                    ),
                    'opt' => array(
                        'sanitize_callback' => function($param, $request, $key) {
                            return strip_tags( $param );
                        }
                    ),
                    'del' => array(
                        'sanitize_callback' => function($param, $request, $key) {
                            return strip_tags( $param );
                        }
                    )
                )
            )
        );
        register_rest_route(
            'neomail/v1', // namespace
            '/list/add/', // ruta
            array(
                'methods'  => 'POST',
                'callback' => function( $request){
                    return Lists::addElement($request);
                },
                'args'     => array(
                    'name' => array(
                        'required'          => true,
                        // 'validate_callback' => function($param, $request, $key) {
						// 	return is_numeric($param) and !is_null( get_post($param) );
						// },
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
            )
        );

        register_rest_route(
            'neomail/v1', // namespace
            '/list/del/', // ruta
            array(
                'methods'  => 'PUT',
                'callback' => function( $request){
                    return Lists::deleteElement($request);
                },
                'args'     => array(
                    'id' => array(
                        'required'          => true,
                        'validate_callback' => function($param, $request, $key) {
							return is_numeric($param);
						},
                        'sanitize_callback' => function($param, $request, $key) {
                            return strip_tags( $param );
                        }
                    )
                )
            )
        );
        register_rest_route(
            'neomail/v1', // namespace
            '/list/remove/', // ruta
            array(
                'methods'  => 'GET',
                'callback' => function( $request){
                    return Lists::removeElement($request);
                },
                'args'     => array(
                    'id' => array(
                        'required'          => true,
                        'validate_callback' => function($param, $request, $key) {
							return is_numeric($param);
						},
                        'sanitize_callback' => function($param, $request, $key) {
                            return strip_tags( $param );
                        }
                    )
                ),
                'permission_callback' => function() {
                    return is_user_logged_in();
                } 

            )
        );

        register_rest_route(
            'neomail/v1', // namespace
            '/list/token/', // ruta
            array(
                'methods'  => 'GET',
                'callback' => function( $request){
                    // return wp_get_current_user();
                    return wp_create_nonce( 'wp_rest' );
                }

            )
        );
        
    
    }
}