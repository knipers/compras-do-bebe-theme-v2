<?php
/**
 * Bootstrap do tema.
 *
 * @package Compras_do_Bebe_SEO_Theme
 */

$theme_includes = array(
	'/inc/setup.php',
	'/inc/enqueue.php',
	'/inc/helpers.php',
	'/inc/plugin-compatibility.php',
	'/inc/seo.php',
	'/inc/schema.php',
);

foreach ( $theme_includes as $file ) {
	require_once get_template_directory() . $file;
}
