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


include_once "includes/ai-admin-menu.php";
include_once "includes/ai-admin-wc-settings.php";