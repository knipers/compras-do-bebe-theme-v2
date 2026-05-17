<?php
/**
 * Regras de SEO técnico.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cdb_has_problematic_query_params() {
	$keys = array( 'query-page', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'gclid', 'fbclid' );
	foreach ( $keys as $key ) {
		if ( isset( $_GET[ $key ] ) && '' !== sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) ) {
			return true;
		}
	}
	return false;
}

function cdb_should_force_noindex_follow() {
	$paged = max( 1, absint( get_query_var( 'paged' ) ) );
	if ( is_search() || cdb_has_problematic_query_params() ) {
		return true;
	}
	if ( $paged > 1 && ( is_home() || is_archive() || is_category() ) ) {
		return true;
	}
	return false;
}

function cdb_output_meta_robots() {
	if ( cdb_should_force_noindex_follow() ) {
		echo '<meta name="robots" content="noindex,follow" />' . "\n";
	}
}
add_action( 'wp_head', 'cdb_output_meta_robots', 1 );
