<?php
/**
 * Setup do tema.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cdb_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'custom-logo', array( 'flex-height' => true, 'flex-width' => true ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'assets/css/main.css' );

	register_nav_menus(
		array(
			'primary' => __( 'Menu principal', 'compras-do-bebe-seo-theme' ),
			'footer'  => __( 'Menu de rodapé', 'compras-do-bebe-seo-theme' ),
		)
	);

	add_image_size( 'cdb-card', 640, 400, true );
}
add_action( 'after_setup_theme', 'cdb_theme_setup' );
