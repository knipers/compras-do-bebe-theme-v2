<?php
get_header();

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		$entry_content_classes = 'entry-content';
		$prepared              = cdb_prepare_content_with_toc( get_the_ID() );
		$toc_items             = isset( $prepared['toc'] ) && is_array( $prepared['toc'] ) ? $prepared['toc'] : array();
		$content_html          = isset( $prepared['content'] ) ? (string) $prepared['content'] : '';
		$continue_reading_link = cdb_get_continue_reading_link( get_the_ID() );

		if ( cdb_has_redundant_content_h1( get_the_ID() ) ) {
			$entry_content_classes .= ' has-redundant-h1';
		}
		?>
		<div class="cdb-container">
			<?php cdb_output_breadcrumbs(); ?>

			<article <?php post_class( 'single-article' ); ?>>
				<header class="single-article__header">
					<h1><?php the_title(); ?></h1>
					<?php get_template_part( 'template-parts/post-meta' ); ?>
				</header>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="single-article__thumb">
						<?php the_post_thumbnail( 'large', array( 'class' => 'featured-image', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! cdb_content_has_affiliate_note( get_the_ID() ) ) : ?>
					<?php get_template_part( 'template-parts/affiliate-disclaimer' ); ?>
				<?php endif; ?>

				<?php if ( cdb_should_show_medical_disclaimer() ) : ?>
					<?php get_template_part( 'template-parts/medical-disclaimer' ); ?>
				<?php endif; ?>

				<?php if ( count( $toc_items ) >= 3 ) : ?>
					<nav class="cdb-toc" aria-label="Sumário do artigo">
						<h2>Neste guia</h2>
						<ol>
							<?php foreach ( $toc_items as $item ) : ?>
								<li><a href="#<?php echo esc_attr( $item['id'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a></li>
							<?php endforeach; ?>
						</ol>
					</nav>
				<?php endif; ?>

				<div class="<?php echo esc_attr( $entry_content_classes ); ?>">
					<?php echo apply_filters( 'the_content', $content_html ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<footer class="single-end">
					<section class="single-end__conclusion" aria-label="Conclusão do artigo">
						<h2>Conclusão</h2>
						<p>Esperamos que este guia tenha ajudado você a comparar opções e escolher com mais segurança o melhor produto para sua rotina.</p>
					</section>

					<?php if ( ! empty( $continue_reading_link ) ) : ?>
						<section class="single-end__continue" aria-label="Continue lendo">
							<h2>Continue lendo</h2>
							<p>Aprofunde a leitura com o próximo conteúdo recomendado.</p>
							<a class="single-end__continue-link" href="<?php echo esc_url( $continue_reading_link['url'] ); ?>"><?php echo esc_html( $continue_reading_link['label'] ); ?></a>
						</section>
					<?php endif; ?>

					<?php get_template_part( 'template-parts/related-posts' ); ?>

					<nav class="post-nav" aria-label="Navegação entre posts">
						<?php previous_post_link( '<span class="post-nav__prev">%link</span>' ); ?>
						<?php next_post_link( '<span class="post-nav__next">%link</span>' ); ?>
					</nav>

					<?php get_template_part( 'template-parts/author-box' ); ?>
				</footer>
			</article>
		</div>
		<?php
	endwhile;
endif;

get_footer();
