<?php
/**
 * Enqueue de assets.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cdb_enqueue_assets() {
	$version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'cdb-style', get_stylesheet_uri(), array(), $version );
	wp_enqueue_style( 'cdb-main', get_template_directory_uri() . '/assets/css/main.css', array( 'cdb-style' ), $version );

	wp_enqueue_script(
		'cdb-main',
		get_template_directory_uri() . '/assets/js/main.js',
		array(),
		$version,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'cdb_enqueue_assets' );
