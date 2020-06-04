<?php

defined('ABSPATH') || exit;

use AsocialMedia\AllegroApi\AllegroRestApi; 

class AI_Admin_Ajax {

	private $api = null;

	public function __construct(){

		$ajax_methods = array(
			'get_allegro_offers',
			'get_allegro_categories'
		);

		foreach($ajax_methods as $ajax_method){

			add_action( "wp_ajax_$ajax_method", array( $this, $ajax_method ) );
		}


		$tokens       = get_option('allegro_tokens');
		$access_token = (!empty($tokens) && !empty($tokens->access_token)) ? $tokens->access_token : "";
		$this->api    = new AllegroRestApi($access_token);

	}

	public function get_allegro_offers(){
		
		$endpoint = !empty($_POST['filters']) ? "/offers/listing?".$_POST['filters'] : "/offers/listing";
		error_log($endpoint);

		wp_send_json( $this->api->get( $endpoint ) );
	}


	public function get_allegro_categories(){

		$endpoint  = !empty($_POST['category']) ? '/sale/categories?parent.id='.$_POST['category'] : '/sale/categories'; 

		wp_send_json( $this->api->get($endpoint) );
	}

}

new AI_Admin_Ajax();