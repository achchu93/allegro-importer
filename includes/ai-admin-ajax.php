<?php

defined('ABSPATH') || exit;

require dirname(ALLEGRO_IMPORTER_FILE) . '/vendor/autoload.php';

use AsocialMedia\AllegroApi\AllegroRestApi;
use Goutte\Client; 

class AI_Admin_Ajax {

	private $api = null;

	public function __construct(){

		$ajax_methods = array(
			'get_allegro_offers',
			'get_allegro_categories',
			'import_allegro_products'
		);

		foreach($ajax_methods as $ajax_method){

			add_action( "wp_ajax_$ajax_method", array( $this, $ajax_method ) );
		}


		$tokens       = get_option('allegro_tokens');
		$access_token = (!empty($tokens) && !empty($tokens->access_token)) ? $tokens->access_token : "";
		$this->api    = new AllegroRestApi($access_token);

	}

	public function get_allegro_offers(){
		
		$endpoint = !empty($_POST['filters']) ? "/offers/listing?limit=10&".$_POST['filters'] : "/offers/listing";
		$offers   = $this->api->get( $endpoint );

		if( property_exists( $offers, 'items' ) ){

			foreach( $offers->items->promoted as $key => $item ){

				$product_id                               = wc_get_product_id_by_sku( $item->id );
				$offers->items->promoted[$key]->permalink = get_permalink( $product_id );
			}

		}

		wp_send_json( $offers );
	}


	public function get_allegro_categories(){

		$endpoint  = !empty($_POST['category']) ? '/sale/categories?parent.id='.$_POST['category'] : '/sale/categories'; 

		wp_send_json( $this->api->get($endpoint) );
	}

	public function import_allegro_products(){

		$ids      = $_POST['product_ids'];
		$price    = $_POST['price'];
		$products = array();

		foreach($ids as $id){

			
			$client = new Client();
			$crawler = $client->request("GET", "https://allegro.pl/oferta/$id");

			try{

				$summary = json_decode($crawler->filter('script[data-serialize-box-name=summary]')->text());
				$description = json_decode($crawler->filter('script[data-serialize-box-name=Description]')->text());
				$parameters = json_decode($crawler->filter('script[data-serialize-box-id="LUo64bt1RQOuwIphdVaomw==kQ32FRhOQcqYmKhfpeU6lQ=="]')->text());
				$images = json_decode($crawler->filter('script[data-serialize-box-id="LUo64bt1RQOuwIphdVaomw==gS_dkQFZTXmAdVgz1vLfLA=="]')->text());


				$original_price = floatval( str_replace( ",", ".", $summary->price->originalPrice ) );
				$regular_price = floatval( $summary->schema->price );

				if( !empty($price) ){

					$add = floatval($price);
					$original_price += $add ? ( $add/100 * ( $original_price ) ) : 0;
					$regular_price += $add ? ( $add/100 * ( $regular_price ) ) : 0;
				}

				ob_start();
				include("templates/product-parameters-content.php");
				include("templates/product-description-content.php");
				$description = ob_get_contents();
				ob_end_clean();
				

				$product_id = ai_create_product(
					array(
						'name' 		  		=> $summary->schema->name,
						'description' 		=> $description,
						'short_description' => $summary->schema->description,
						'sku'				=> $summary->schema->sku,
						'regular_price'     => ai_get_site_price( !empty( $original_price ) ? $original_price : $regular_price ),
						'sale_price'		=> !empty( $original_price ) ? ai_get_site_price($regular_price) : '' ,
						'manage_stock'		=> true,
						'stock_quantity'	=> $summary->notifyAndWatch->quantity,
						'stock_status'      => $summary->notifyAndWatch->quantity > 1 ? "instock" : "outofstock",
						'parent_id'			=> 0,
						'image_id'			=> ai_add_media_from_url($summary->schema->image),
						'gallery_image_ids'	=> []
					)
				);

			}catch(Exception $e){
				error_log($e->get_message());
				$product_id = 0;
			}

			$products[$id] = $product_id > 0 ? get_permalink($product_id) : false;
		}

		wp_send_json( $products );
	}

}

new AI_Admin_Ajax();