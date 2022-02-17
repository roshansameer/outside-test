<?php
/**
 * Plugin Name: OutSide Test
 * Description: Test Project for WordPress Developer.
 * Version: 1.0.0
 * Author: Roshan Sameer
 * Author URI: https://github.com/roshansameer
 * Text Domain: outside
 * Domain Path: /languages/
 *
 * @package OutSide
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define OUTSIDE_PLUGIN_FILE.
if ( ! defined( 'OUTSIDE_PLUGIN_FILE' ) ) {
	define( 'OUTSIDE_PLUGIN_FILE', __FILE__ );
}

// Include the main OutSide class.
if ( ! class_exists( 'OutSide' ) ) {
	include_once dirname( __FILE__ ) . '/includes/outside.php';
}

/**
 * Main instance of OutSide.
 *
 * Returns the main instance of EVF to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return OutSide
 */
function outside() {
	return OutSide::instance();
}

outside();