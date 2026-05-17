<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="site-header">
	<div class="cdb-container site-header__inner">
		<div class="site-branding">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_kses_post( get_custom_logo() ); ?>
			<?php else : ?>
				<a class="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>">Compras do Bebê</a>
			<?php endif; ?>
		</div>
		<button class="menu-toggle" aria-expanded="false" aria-controls="primary-menu" aria-label="Abrir menu">☰</button>
		<nav class="main-navigation" id="site-navigation" aria-label="Menu principal">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => 'cdb_primary_menu_fallback',
				)
			);
			?>
		</nav>
		<div class="header-search"><?php get_search_form(); ?></div>
	</div>
</header>
<main class="site-main" id="primary">
