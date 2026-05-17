<?php
/**
 * Box institucional do autor/editorial.
 *
 * @package Compras_do_Bebe_SEO_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$about_page_url        = '';
$editorial_page_url    = '';
$about_page_candidates = array( 'sobre', 'quem-somos' );
$policy_candidates     = array( 'transparencia', 'politica-editorial', 'politica-de-transparencia' );

foreach ( $about_page_candidates as $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page instanceof WP_Post ) {
		$about_page_url = get_permalink( $page->ID );
		break;
	}
}

foreach ( $policy_candidates as $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page instanceof WP_Post ) {
		$editorial_page_url = get_permalink( $page->ID );
		break;
	}
}
?>
<section class="author-box author-box--institutional" aria-label="Informações editoriais do Compras do Bebê">
	<div class="author-box__media" aria-hidden="true">
		<?php if ( has_custom_logo() ) : ?>
			<?php echo wp_kses_post( get_custom_logo() ); ?>
		<?php else : ?>
			<?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
		<?php endif; ?>
	</div>

	<div class="author-box__content">
		<h2>Sobre o Compras do Bebê</h2>
		<p class="author-box__name"><strong>Compras do Bebê</strong></p>
		<p>
			O Compras do Bebê produz guias, comparativos e listas práticas para ajudar famílias a escolherem produtos infantis com mais segurança, organização e custo-benefício. Nossas recomendações consideram critérios como uso no dia a dia, praticidade, conforto, segurança e disponibilidade no Brasil.
		</p>

		<div class="author-box__links">
			<?php if ( ! empty( $about_page_url ) ) : ?>
				<a href="<?php echo esc_url( $about_page_url ); ?>">Conheça o projeto</a>
			<?php endif; ?>

			<?php if ( ! empty( $editorial_page_url ) ) : ?>
				<a href="<?php echo esc_url( $editorial_page_url ); ?>">Política editorial e transparência</a>
			<?php endif; ?>
		</div>
	</div>
</section>
