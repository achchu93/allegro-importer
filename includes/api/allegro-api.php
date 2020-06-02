<?php

defined('ABSPATH') || exit;

class Allegro_API {

    private $url = "https://api.allegro.pl";
    private $client_id;
    private $client_secret;
    private $access_token;

    public static $instance = null;

    public function __construct(){

        $this->client_id = get_option('allegro_client_id');
        $this->client_secret = get_option('allegro_client_secret');
        $this->access_token = $this->get_access_token()->access_token;
    }

    public static function instance(){
        
        if(self::$instance == null) {
            self::$instance = new Allegro_API();
        }
        return self::$instance;
    } 

    public function get_access_token(){

        $request = wp_remote_post( 
            'https://allegro.pl/auth/oauth/token?grant_type=client_credentials',
            array(
                "headers" => array(
                    "Authorization" => 'Basic '.base64_encode($this->client_id.":".$this->client_secret)
                )
            )
        );
        return json_decode(wp_remote_retrieve_body( $request ));
    }

    public function get_categories(){
        $request = wp_remote_get( 
            $this->url.'/sale/categories',
            array(
                'headers' => array(
                    'Accept' => 'application/vnd.allegro.public.v1+json',
                    'Authorization' => 'Bearer '.$this->access_token
                )
            ) 
        );
        return json_decode(wp_remote_retrieve_body( $request ));
    }
}