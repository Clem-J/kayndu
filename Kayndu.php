<?php

/*
Plugin Name: Kayndu
Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
Description: Plugin development test for Kayndu
Version: 1.0
Author: Clément Esso
Author URI: http://clementesso.com
License: A "Slug" license name e.g. GPL2
*/

//Block direct access
defined( 'ABSPATH' ) or die( 'Forbidden direct access' );


// Include Kayndu_functions.php
require_once plugin_dir_path(__FILE__) . 'includes/Kayndu_functions.php';

class Kayndu_plugin {

	public function __construct()
	{
		// Add Javascript and CSS for admin screens
		add_action('admin_enqueue_scripts', array($this,'kayndu_enqueue_admin'));
	}

	//enqueue scripts and styles for admin only
	function kayndu_enqueue_admin()
	{
		wp_enqueue_script( 'script', plugins_url( 'includes/js/script.js', __FILE__ ), array( 'jquery' ), '1.0', true );
		wp_enqueue_style( 'style', plugins_url( 'includes/css/style.css', __FILE__ ), '', '1.1' );
	}

}

new Kayndu_plugin();