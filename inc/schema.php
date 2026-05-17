<?php
/**
 * Schema fallback quando Rank Math não estiver ativo.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cdb_output_fallback_schema() {
	if ( cdb_is_rank_math_active() ) {
		return;
	}

	$schemas = array();

	if ( is_front_page() || is_home() ) {
		$schemas[] = array(
			'@context' => 'https://schema.org',
			'@type'    => 'WebSite',
			'name'     => get_bloginfo( 'name' ),
			'url'      => home_url( '/' ),
		);
	}

	if ( is_single() && 'post' === get_post_type() ) {
		$schemas[] = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'Article',
			'headline'        => get_the_title(),
			'datePublished'   => get_the_date( DATE_W3C ),
			'dateModified'    => get_the_modified_date( DATE_W3C ),
			'author'          => array( '@type' => 'Person', 'name' => get_the_author() ),
			'mainEntityOfPage'=> get_permalink(),
		);
	}

	if ( ! is_front_page() ) {
		$current_url = home_url( add_query_arg( null, null ) );
		$schemas[] = array(
			'@context' => 'https://schema.org',
			'@type'    => 'BreadcrumbList',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Início', 'item' => home_url( '/' ) ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => wp_strip_all_tags( wp_get_document_title() ), 'item' => $current_url ),
			),
		);
	}

	if ( ! empty( $schemas ) ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $schemas, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}
}
add_action( 'wp_head', 'cdb_output_fallback_schema', 30 );
