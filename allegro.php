<?php
/**
 * Plugin Name:       Allegro Importer
 * Plugin URI:        #
 * Description:       Import products from Allegro.
 * Version:           0.0.1
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            John P
 * Author URI:        #
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       allegro-import
 * Domain Path:       /languages
 */

defined('ABSPATH') || EXIT ;

// check woocommerce is active
if( !in_array( 'woocommerce/woocommerce.php', apply_filters('active_plugins', get_option( 'active_plugins' ) ) ) ){ 
	add_filter( 'admin_notices', function(){
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'Woocommerce is required for the plugin', 'allegro-import' ); ?></p>
		</div>
		<?php
	});
	return;
}

define('ALLEGRO_IMPORTER_FILE', __FILE__);


register_activation_hook( __FILE__, 'allegro_init' );
function allegro_init(){

	if( !wp_next_scheduled('allegro_update_currency_rate') ){
		wp_schedule_event( 
			time(),
			'twicedaily',
			'allegro_update_currency_rate_event'
		);
	}
}

register_deactivation_hook( __FILE__, 'allegro_destroy' );
function allegro_destroy(){
	wp_clear_scheduled_hook( 'allegro_update_currency_rate_event' );
}

add_action( 'allegro_update_currency_rate_event', 'allegro_update_currency_rate_callback', 10, 2 );
function allegro_update_currency_rate_callback(){

	$response = wp_remote_get('http://data.fixer.io/api/convert?access_key=b63d769e9b7bd6b13b50c19091d964a4&from=PLN&to=UAH&amount=1');
	if( !is_wp_error( $response ) ){
		$body = json_decode(wp_remote_retrieve_body( $response ));
		if( $body->result ){
			update_option( 'allegro_currency_rate', $body->result );
		}
	}
}

include_once "includes/ai-functions.php";
include_once "vendor/allegro-api/AllegroRestApi.php";
include_once "includes/ai-admin-ajax.php";
include_once "includes/ai-admin-menu.php";
include_once "includes/ai-admin-wc-settings.php";