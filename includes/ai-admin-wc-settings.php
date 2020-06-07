<?php

defined('ABSPATH') || exit;

use AsocialMedia\AllegroApi\AllegroRestApi;

class AI_Admin_WC_Settings {

	public function __construct(){

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_allegro', array( $this, 'add_settings_tab_content' ) );
		add_action( 'woocommerce_update_options_allegro', array( $this, 'update_settings_tab_content' ) );
		add_action( 'woocommerce_admin_field_allegro_activate', array( $this, 'add_allegro_activate_field' ) );
		add_action( 'init', array( $this, 'wc_settings_to_allegro' ) );

		add_filter( 'cron_schedules', array( $this, 'allegro_token_duration') );
		add_action( 'allegro_token_cron_hook', array( $this, 'allegro_token_cron_func' ) );

	}

	public function add_settings_tab($tabs){
		$tabs["allegro"] = __( "Allegro", 'allegro-import' );
		return $tabs;
	}

	public function add_settings_tab_content(){
		woocommerce_admin_fields($this->get_settings());
	}

	public function update_settings_tab_content(){
		woocommerce_update_options($this->get_settings());
	}

	public function get_settings(){
		$settings = array(
			'section_title' => array(
				'name'     => __( 'Allegro Credentials', 'allegro-import' ),
				'type'     => 'title',
				'desc'     => '',
				'id'       => 'allegro_section_title'
			),
			'name' => array(
				'name' => __( 'Business Name', 'allegro-import' ),
				'type' => 'text',
				'desc' => __( 'Name of the Business', 'allegro-import' ),
				'id'   => 'allegro_business_name'
			),
			'client_id' => array(
				'name' => __( 'Client ID', 'allegro-import' ),
				'type' => 'text',
				'desc' => __( 'Use your Allegro Client ID', 'allegro-import' ),
				'id'   => 'allegro_client_id'
			),
			'client_secret' => array(
				'name' => __( 'Client Secret', 'allegro-import' ),
				'type' => 'text',
				'desc' => __( 'Use your Allegro Client Secret', 'allegro-import' ),
				'id'   => 'allegro_client_secret'
			),
			'allegro_activate' => array(
				'name' => __( 'Verify Auth', 'allegro-import' ),
				'type' => 'allegro_activate',
				'desc' => __( 'Verify your credintials for the site', 'allegro-import' ),
				'id'   => 'allegro_activate'
			),
			'section_end' => array(
				 'type' => 'sectionend',
				 'id' => 'allegro_section_end'
			)
		);


		return $settings;
	}
	
	public function add_allegro_activate_field($field){

		?>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field['id'] ); ?>"><?php echo esc_html( $field['title'] ); ?></label>
				<?php echo  WC_Admin_Settings::get_field_description( $field )['tooltip_html']; ?>
			</th>
			
			<td class="forminp forminp-<?php echo sanitize_title( $field['type'] ) ?>">

				<?php 
				global $wp;
				$redirect_url = home_url() . add_query_arg( $wp->query_vars);
				$client_id = get_option('allegro_client_id');
				$client_secret = get_option('allegro_client_secret');
				$tokens = get_option('allegro_tokens');

				if ( empty($client_id) || empty($client_secret) ) : ?>
					<P class="description" style="color:red;"><?php _e( "Client ID and Secret are must.", "allegro-import" ); ?></P>
				<?php elseif(!empty($tokens) && $tokens->access_token): ?>
					<button type="button" class="button" style="background-color:#4CAF50;color:#fff;border:none;vertical-align: middle;"><?php _e('Verified successfully!', 'allegro-import') ?></button>
					<a href="<?php echo add_query_arg( "action", "revoke" ); ?>"><?php _e('Revoke', 'allegro-import'); ?></a>
				<?php else: ?>  
					<a class="button-secondary" href="<?php echo add_query_arg( "state", "app-allegro", AllegroRestApi::getAuthLink($client_id, admin_url("admin.php?page=wc-settings") ) ); ?>"><?php echo $field['name']; ?></a>
					<p class="description"><?php echo $field['desc']; ?></p>
				<?php endif; ?>
			</td>
		</tr>

		<?php

	}

	public function wc_settings_to_allegro(){ 

		if( !empty($_REQUEST['page']) && $_REQUEST['page'] === "wc-settings" ){

			if( !empty( $_REQUEST['code'] ) && !empty($_REQUEST['state']) && $_REQUEST['state'] === "app-allegro" ){


				$tokens = AllegroRestApi::generateToken(
					(isset($_REQUEST['code']) ? $_GET['code'] : null), 
					get_option('allegro_client_id'), 
					get_option('allegro_client_secret'), 
					admin_url("admin.php?page=wc-settings")
				);

				if(!empty($tokens->access_token)){
					update_option( "allegro_tokens", $tokens );

					if ( ! wp_next_scheduled( 'allegro_token_cron_hook' ) ) {
    					wp_schedule_event( time(), 'allegro_token_duration', 'allegro_token_cron_hook' );
					}
				}

				wp_redirect( 
					add_query_arg( 
						array(
							'code' => $_REQUEST['code'],
							'page' => 'wc-settings',
							'tab'  => 'allegro'
						), 
						admin_url( 'admin.php' )
					) 
				);
				die();
			}

			if( !empty($_GET['tab']) && $_GET['tab'] === 'allegro' ){

				if( !empty($_GET['action']) && $_GET['action'] === 'revoke' ){

					delete_option('allegro_tokens');
					wp_redirect( 
						add_query_arg( 
							array(
								'page' => 'wc-settings',
								'tab'  => 'allegro'
							), 
							admin_url( 'admin.php' )
						) 
					);
					die();
				}

			}

		}
	}


	public function allegro_token_duration(){

		$schedules['allegro_token_duration'] = array(
			'interval' => 39600,
			'display' => __('Every 11 hours', 'allegro-importer')
		);
		return $schedules;
	}


	public function allegro_token_cron_func(){
		$tokens = get_option('allegro_tokens');

		if( is_object($tokens) && property_exists($tokens, 'refresh_token') ){
			$new_tokens = AllegroRestApi::refreshToken(
				$tokens->refresh_token,
				get_option('allegro_client_id'), 
				get_option('allegro_client_secret'), 
				admin_url("admin.php?page=wc-settings")
			);

			if( !empty($new_tokens->access_token) ){
				update_option( "allegro_tokens", $new_tokens );
			}
		}
	}


}

new AI_Admin_WC_Settings();