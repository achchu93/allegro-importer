<?php

defined('ABSPATH') || exit;

class AI_Admin_WC_Settings {

	public function __construct(){

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
		add_action( 'woocommerce_settings_tabs_allegro', array( $this, 'add_settings_tab_content' ) );
		add_action( 'woocommerce_update_options_allegro', array( $this, 'update_settings_tab_content' ) );

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
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'allegro_section_end'
            )
		);


		return $settings;
	}


}

new AI_Admin_WC_Settings();