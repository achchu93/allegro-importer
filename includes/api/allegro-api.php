<?php

defined('ABSPATH') || exit;

class Allegro_API {

    private $url = "https://api.allegro.pl";
    private $client_id;
    private $client_secret;

    public static $instance = null;

    public function __construct(){

        $this->client_id = get_option('allegro_client_id');
        $this->client_secret = get_option('allegro_client_secret');
    }

    public static function instance(){
        
        if(self::$instance == null) {
            self::$instance = new Allegro_API();
        }
        return self::$instance;
    } 

    public function get_categories(){
        $request = wp_remote_get( 
            'https://api.allegro.pl/sale/categories',
            array(
                'headers' => array(
                    'Accept' => 'application/vnd.allegro.public.v1+json',
                    'Authorization' => 'Bearer ' + base64_encode($this->client_id.":".$this->client_secret)
                )
            ) 
        );

        return wp_remote_retrieve_body( $request );
    }
}