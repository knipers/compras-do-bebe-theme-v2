<?php
/**
 * Helpers utilitários do tema.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function cdb_get_primary_category_name( $post_id = 0 ) {
	$post_id    = $post_id ? $post_id : get_the_ID();
	$categories = get_the_category( $post_id );
	return ! empty( $categories ) ? $categories[0]->name : '';
}

function cdb_should_show_medical_disclaimer( $post_id = 0 ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$terms   = wp_get_post_terms( $post_id, 'category', array( 'fields' => 'names' ) );
	$text    = strtolower( wp_strip_all_tags( get_the_title( $post_id ) . ' ' . implode( ' ', $terms ) ) );
	$needles = array( 'bebê', 'bebe', 'saúde', 'saude', 'desenvolvimento', 'sono', 'alimentação', 'alimentacao', 'higiene', 'segurança', 'seguranca' );

	foreach ( $needles as $needle ) {
		if ( false !== strpos( $text, $needle ) ) {
			return true;
		}
	}

	return false;
}

function cdb_trimmed_excerpt( $length = 22 ) {
	$excerpt = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() );
	return wp_trim_words( $excerpt, absint( $length ), '…' );
}
