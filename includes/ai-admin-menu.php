<?php

defined('ABSPATH') || exit;

class AI_Admin_Menu {

	public function __construct(){

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

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
		echo "Hello World";
	}

}

new AI_Admin_Menu();