<?php

function ai_add_media_from_url($url = ""){

	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$url_array  = explode('/',$url);
	$image_name = $url_array[count($url_array)-1];
	$ch         = curl_init();

	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

	$image_data = curl_exec($ch);

	curl_close($ch);

	$upload_dir       = wp_upload_dir();
	$ext              = exif_imagetype($url);
	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name . image_type_to_extension($ext) );
	$filename         = basename( $unique_file_name );

	if( wp_mkdir_p( $upload_dir['path'] ) ) {
		$file = $upload_dir['path'] . '/' . $filename;
	} else {
		$file = $upload_dir['basedir'] . '/' . $filename;
	}
	file_put_contents( $file, $image_data );
	
	$wp_filetype = wp_check_filetype( $filename, null );
	$attachment  = array(
		'post_mime_type' => $wp_filetype['type'] ? $wp_filetype['type'] : $ext,
		'post_title' 	 => sanitize_file_name( $filename ),
		'post_content' 	 => '',
		'post_status' 	 => 'inherit'
	);
	$attach_id   = wp_insert_attachment( $attachment, $file, 0 );
	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
	
	wp_update_attachment_metadata( $attach_id, $attach_data );

	return $attach_id;
}

function ai_create_product($data){

	$props = array(
		'name',
		'description',
		'short_description',
		'sku',
		'regular_price',
		'sale_price',
		'manage_stock',
		'stock_quantity',
		'stock_status',
		'parent_id',
		'image_id',
		'gallery_image_ids',
		'category_ids'
	);

	$product    = !empty( $data['id'] ) ? new \WC_Product( $data['id'] ) : new \WC_Product();

	foreach($props as $prop){
		if( !empty( $data[$prop] ) ){
			$props[$prop] = $data[$prop]; 
		}
	}
	$product->set_props($props);

	return $product->save();
}

function ai_get_currency_rate(){

	$rate = floatval(get_option( 'allegro_currency_rate', 0 ));
	if( empty($empty) ){
		allegro_update_currency_rate_callback();
		$rate = floatval(get_option( 'allegro_currency_rate', 0 ));
	}

	return $rate ? $rate : 1;
}

function ai_get_site_price($price){

	$rate  = ai_get_currency_rate();
	$rated_price = floatval($rate) * floatval($price);

	return number_format( $rated_price, 2 );
}