<?php

defined('ABSPATH') || exit;

class AI_Admin_Menu {

	public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_page_scripts' ) );
	}

	public function admin_menu(){
		add_menu_page(
			'Allegro',
			'Allegro',
			'manage_options',
			'allegro',
			array( $this, 'admin_page' ),
			'dashicons-store'
		);
	}

	public function admin_page(){
		include_once("templates/admin-page-layout.php");
	}

	public function admin_page_scripts()
	{
		wp_enqueue_registered_block_scripts_and_styles();
		wp_register_style( 
			'bulmacss', 
			'https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css' 
		);

		wp_enqueue_style('bulmacss');

		wp_register_script(
			'ai-main-js',
			plugins_url('assets/js/main.js', ALLEGRO_IMPORTER_FILE),
			array('jquery', 'jquery-blockui'),
			false,
			true
		);
		wp_register_script(
			'ai-fontawesome-js',
			'https://use.fontawesome.com/releases/v5.3.1/js/all.js',
			array(),
			'5.3.1',
			true
		);

		wp_localize_script( 
			'ai-main-js', 
			'ai_params', 
			array(
				'name' => get_option('allegro_business_name'),
				'client_id' => get_option( 'allegro_client_id' ),
				'client_secret' => get_option( 'allegro_client_secret' ),
			)
		);
		
		wp_enqueue_script( 'ai-fontawesome-js');
		wp_enqueue_script( 'ai-main-js');
	}

}

new AI_Admin_Menu();