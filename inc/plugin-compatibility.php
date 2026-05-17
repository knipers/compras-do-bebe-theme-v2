<?php
/**
 * Compatibilidade com plugins.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cdb_is_rank_math_active() {
	return defined( 'RANK_MATH_VERSION' ) || function_exists( 'rank_math' );
}

function cdb_has_rank_math_breadcrumbs() {
	return cdb_is_rank_math_active() && function_exists( 'rank_math_the_breadcrumbs' );
}

function cdb_is_yarpp_active() {
	return defined( 'YARPP_VERSION' ) || function_exists( 'yarpp_related' ) || function_exists( 'yarpp_related_exists' );
}

function cdb_is_wpp_active() {
	return function_exists( 'wpp_get_mostpopular' ) || shortcode_exists( 'wpp' );
}

function cdb_output_breadcrumbs() {
	if ( cdb_has_rank_math_breadcrumbs() ) {
		echo '<nav class="cdb-breadcrumbs" aria-label="Breadcrumb">';
		rank_math_the_breadcrumbs();
		echo '</nav>';
		return;
	}

	get_template_part( 'template-parts/breadcrumbs' );
}
