<?php
/*
Plugin Name: WSU Embeds
Version: 1.3.0
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-Embeds
Description: Provides various embed codes supported on the WSUWP Platform.
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-wsuwp-embeds.php';

add_action( 'after_setup_theme', 'WSUWP_Embeds' );
/**
 * Start things up.
 *
 * @since 0.9.0
 *
 * @return \WSUWP_Embeds
 */
function WSUWP_Embeds() {
	return WSUWP_Embeds::get_instance();
}
