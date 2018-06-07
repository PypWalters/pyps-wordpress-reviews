<?php
/*
Plugin Name: Pyp Simple WordPress Reviews
Description: Adds the option for 5 star reviews on native WordPress comments
Version: 1.1
Author: Stephanie Walters
Author URI: https://pypwalters.com
Text Domain: pyp_reviews
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Currently plugin version.
 */
define( 'PLUGIN_NAME_VERSION', '1.1.0' );

/**
 * This file just pulls in all of the other files
 */

/**
 * Includes the admin menu settings
 */
if ( file_exists( __DIR__ . '/includes/pyp-reviews-options.php' ) ) {
	include_once __DIR__ . '/includes/pyp-reviews-options.php';
	add_action( 'plugins_loaded', array( 'Pyp_Reviews_Options', 'get_instance' ), 15 );
}

/**
 * Includes the user interface to add a rating to a comment
 */
if ( file_exists( __DIR__ . '/includes/pyp-reviews-user-input.php' ) ) {
	include_once __DIR__ . '/includes/pyp-reviews-user-input.php';
	add_action( 'plugins_loaded', array( 'Pyp_Reviews_Create_Rating', 'get_instance' ), 15 );
}

/**
 * Includes the display of saved ratings on comment output
 */
if ( file_exists( __DIR__ . '/includes/pyp-reviews-comment-output.php' ) ) {
	include_once __DIR__ . '/includes/pyp-reviews-comment-output.php';
	add_action( 'plugins_loaded', array( 'Pyp_Reviews_Output', 'get_instance' ), 15 );
}


/**
 * Enqueue Front-end styles
 */
function pyp_reviews_styles() {
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'pyp_reviews_styles', plugins_url( 'css/pyp-reviews-styles.css', __FILE__ ), 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'pyp_reviews_styles' );
