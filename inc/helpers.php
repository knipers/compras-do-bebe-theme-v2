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

/**
 * Verifica se o primeiro H1 do conteúdo é redundante com o título oficial do post.
 *
 * @param int $post_id ID do post.
 * @return bool
 */
function cdb_has_redundant_content_h1( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	if ( ! $post_id ) {
		return false;
	}

	$content = get_post_field( 'post_content', $post_id );
	$content = is_string( $content ) ? trim( $content ) : '';

	if ( '' === $content || ! preg_match( '/<h1\\b[^>]*>(.*?)<\\/h1>/is', $content, $matches ) ) {
		return false;
	}

	$first_h1_text = isset( $matches[1] ) ? wp_strip_all_tags( $matches[1] ) : '';
	$title_text    = wp_strip_all_tags( get_the_title( $post_id ) );

	$normalize = static function( $text ) {
		$text = remove_accents( strtolower( trim( (string) $text ) ) );
		$text = preg_replace( '/[^a-z0-9\\s]/', ' ', $text );
		$text = preg_replace( '/\\s+/', ' ', $text );
		return trim( (string) $text );
	};

	$normalized_h1    = $normalize( $first_h1_text );
	$normalized_title = $normalize( $title_text );

	if ( '' === $normalized_h1 || '' === $normalized_title ) {
		return false;
	}

	if ( $normalized_h1 === $normalized_title ) {
		return true;
	}

	similar_text( $normalized_h1, $normalized_title, $percent );
	return $percent >= 88;
}

/**
 * Melhora a marcação de tabelas e botões de afiliado no conteúdo dos posts.
 *
 * - Adiciona classe .cdb-compare-table em tabelas.
 * - Envolve tabela com container de scroll para mobile.
 * - Adiciona rel seguro em botões externos com .cdb-affiliate-button.
 *
 * @param string $content Conteúdo do post.
 * @return string
 */
function cdb_enhance_top3_content_markup( $content ) {
	if ( ! is_singular( 'post' ) || ! is_string( $content ) || '' === trim( $content ) ) {
		return $content;
	}

	if ( false === strpos( $content, '<table' ) && false === strpos( $content, 'cdb-affiliate-button' ) ) {
		return $content;
	}

	if ( ! class_exists( 'DOMDocument' ) ) {
		return $content;
	}

	libxml_use_internal_errors( true );
	$doc = new DOMDocument();
	$doc->loadHTML(
		'<?xml encoding="utf-8" ?><div id="cdb-enhance-root">' . $content . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();

	$root = $doc->getElementById( 'cdb-enhance-root' );
	if ( ! $root ) {
		return $content;
	}

	$tables = $root->getElementsByTagName( 'table' );
	$table_nodes = array();
	foreach ( $tables as $table ) {
		$table_nodes[] = $table;
	}

	foreach ( $table_nodes as $table ) {
		$existing = trim( (string) $table->getAttribute( 'class' ) );
		$classes  = array_filter( array_map( 'trim', explode( ' ', $existing ) ) );
		if ( ! in_array( 'cdb-compare-table', $classes, true ) ) {
			$classes[] = 'cdb-compare-table';
			$table->setAttribute( 'class', trim( implode( ' ', $classes ) ) );
		}

		$parent = $table->parentNode;
		if ( ! $parent ) {
			continue;
		}

		$wrapper = $doc->createElement( 'div' );
		$wrapper->setAttribute( 'class', 'cdb-compare-table-wrap' );
		$parent->replaceChild( $wrapper, $table );
		$wrapper->appendChild( $table );
	}

	$links = $root->getElementsByTagName( 'a' );
	foreach ( $links as $link ) {
		$class_attr = ' ' . $link->getAttribute( 'class' ) . ' ';
		if ( false === strpos( $class_attr, ' cdb-affiliate-button ' ) ) {
			continue;
		}

		$href = trim( (string) $link->getAttribute( 'href' ) );
		if ( '' === $href ) {
			continue;
		}

		$host = wp_parse_url( $href, PHP_URL_HOST );
		if ( empty( $host ) ) {
			continue;
		}

		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( ! $site_host || $host === $site_host ) {
			continue;
		}

		$rel_current = strtolower( (string) $link->getAttribute( 'rel' ) );
		$rels        = array_filter( array_map( 'trim', explode( ' ', $rel_current ) ) );
		foreach ( array( 'sponsored', 'nofollow', 'noopener' ) as $rel ) {
			if ( ! in_array( $rel, $rels, true ) ) {
				$rels[] = $rel;
			}
		}

		$link->setAttribute( 'rel', implode( ' ', $rels ) );

		if ( '_blank' === $link->getAttribute( 'target' ) && ! in_array( 'noreferrer', $rels, true ) ) {
			$rels[] = 'noreferrer';
			$link->setAttribute( 'rel', implode( ' ', $rels ) );
		}
	}

	$enhanced = '';
	foreach ( $root->childNodes as $child ) {
		$enhanced .= $doc->saveHTML( $child );
	}

	return $enhanced ?: $content;
}
add_filter( 'the_content', 'cdb_enhance_top3_content_markup', 12 );

/**
 * Marca visualmente blocos de FAQ dentro do conteúdo, sem alterar schema.
 *
 * @param string $content Conteúdo do post.
 * @return string
 */
function cdb_mark_faq_blocks( $content ) {
	if ( ! is_singular( 'post' ) || ! is_string( $content ) || '' === trim( $content ) ) {
		return $content;
	}

	if ( ! class_exists( 'DOMDocument' ) || false === stripos( $content, 'faq' ) && false === stripos( $content, 'perguntas frequentes' ) ) {
		return $content;
	}

	libxml_use_internal_errors( true );
	$doc = new DOMDocument();
	$doc->loadHTML(
		'<?xml encoding="utf-8" ?><div id="cdb-faq-root">' . $content . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();

	$root = $doc->getElementById( 'cdb-faq-root' );
	if ( ! $root ) {
		return $content;
	}

	$headings = $root->getElementsByTagName( 'h2' );
	$faq_titles = array( 'faq', 'perguntas frequentes', 'duvidas frequentes', 'dúvidas frequentes' );
	$faq_sections = array();

	foreach ( $headings as $heading ) {
		$text = strtolower( remove_accents( trim( wp_strip_all_tags( $heading->textContent ) ) ) );
		foreach ( $faq_titles as $faq_title ) {
			if ( false !== strpos( $text, strtolower( remove_accents( $faq_title ) ) ) ) {
				$faq_sections[] = $heading;
				break;
			}
		}
	}

	foreach ( $faq_sections as $faq_heading ) {
		$faq_heading->setAttribute( 'class', trim( $faq_heading->getAttribute( 'class' ) . ' cdb-faq-section-title' ) );
		$current = $faq_heading->nextSibling;

		while ( $current ) {
			if ( XML_ELEMENT_NODE === $current->nodeType && in_array( strtolower( $current->nodeName ), array( 'h2' ), true ) ) {
				break;
			}

			if ( XML_ELEMENT_NODE === $current->nodeType ) {
				$node_name = strtolower( $current->nodeName );
				if ( in_array( $node_name, array( 'h3', 'h4' ), true ) ) {
					$current->setAttribute( 'class', trim( $current->getAttribute( 'class' ) . ' cdb-faq-question' ) );
				} elseif ( in_array( $node_name, array( 'p', 'ul', 'ol', 'div' ), true ) ) {
					$current->setAttribute( 'class', trim( $current->getAttribute( 'class' ) . ' cdb-faq-answer' ) );
				}
			}

			$current = $current->nextSibling;
		}
	}

	$enhanced = '';
	foreach ( $root->childNodes as $child ) {
		$enhanced .= $doc->saveHTML( $child );
	}

	return $enhanced ?: $content;
}
add_filter( 'the_content', 'cdb_mark_faq_blocks', 13 );

/**
 * Detecta se o conteúdo já possui nota de afiliado para evitar repetição visual.
 *
 * @param int $post_id ID do post.
 * @return bool
 */
function cdb_content_has_affiliate_note( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	if ( ! $post_id ) {
		return false;
	}

	$content = wp_strip_all_tags( (string) get_post_field( 'post_content', $post_id ) );
	$content = strtolower( remove_accents( $content ) );

	$signals = array(
		'links de afiliado',
		'link de afiliado',
		'comissao',
		'sem custo adicional',
	);

	$matches = 0;
	foreach ( $signals as $signal ) {
		if ( false !== strpos( $content, strtolower( remove_accents( $signal ) ) ) ) {
			$matches++;
		}
	}

	return $matches >= 2;
}

/**
 * Gera sumário automático baseado em H2 e injeta IDs únicos/sanitizados.
 *
 * @param int $post_id ID do post.
 * @return array{
 *   content:string,
 *   toc:array<int, array{id:string, text:string}>
 * }
 */
function cdb_prepare_content_with_toc( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();

	$result = array(
		'content' => '',
		'toc'     => array(),
	);

	if ( ! $post_id ) {
		return $result;
	}

	$content = (string) get_post_field( 'post_content', $post_id );
	if ( '' === trim( $content ) || ! class_exists( 'DOMDocument' ) ) {
		$result['content'] = $content;
		return $result;
	}

	libxml_use_internal_errors( true );
	$doc = new DOMDocument();
	$doc->loadHTML(
		'<?xml encoding="utf-8" ?><div id="cdb-toc-root">' . $content . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();

	$root = $doc->getElementById( 'cdb-toc-root' );
	if ( ! $root ) {
		$result['content'] = $content;
		return $result;
	}

	$headings = $root->getElementsByTagName( 'h2' );
	$used_ids = array();

	foreach ( $headings as $heading ) {
		$text = trim( wp_strip_all_tags( $heading->textContent ) );
		if ( '' === $text ) {
			continue;
		}

		$base_id = sanitize_title( $text );
		if ( '' === $base_id ) {
			$base_id = 'secao';
		}

		$id      = $base_id;
		$counter = 2;
		while ( isset( $used_ids[ $id ] ) ) {
			$id = $base_id . '-' . $counter;
			$counter++;
		}

		$used_ids[ $id ] = true;
		$heading->setAttribute( 'id', $id );
		$result['toc'][] = array(
			'id'   => $id,
			'text' => $text,
		);
	}

	$prepared_content = '';
	foreach ( $root->childNodes as $child ) {
		$prepared_content .= $doc->saveHTML( $child );
	}

	$result['content'] = $prepared_content ?: $content;
	return $result;
}

/**
 * Busca link interno manual para box "Continue lendo".
 *
 * @param int $post_id ID do post.
 * @return array{url:string,label:string}|null
 */
function cdb_get_continue_reading_link( $post_id = 0 ) {
	$post_id = $post_id ? absint( $post_id ) : get_the_ID();
	if ( ! $post_id ) {
		return null;
	}

	$url   = trim( (string) get_post_meta( $post_id, 'cdb_continue_reading_url', true ) );
	$label = trim( (string) get_post_meta( $post_id, 'cdb_continue_reading_label', true ) );

	if ( '' === $url ) {
		return null;
	}

	$host = wp_parse_url( $url, PHP_URL_HOST );
	if ( ! empty( $host ) ) {
		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
		if ( $site_host && $host !== $site_host ) {
			return null;
		}
	}

	return array(
		'url'   => esc_url_raw( $url ),
		'label' => '' !== $label ? sanitize_text_field( $label ) : __( 'Continuar leitura', 'compras-do-bebe-seo-theme' ),
	);
}

/**
 * Fallback do menu principal com categorias estratégicas.
 */
function cdb_primary_menu_fallback() {
	$items = array(
		'Sono'                    => '/category/sono/',
		'Alimentação'             => '/category/alimentacao/',
		'Higiene e Organização'   => '/category/higiene-e-organizacao/',
		'Guias de Compra'         => '/category/guias-de-compra/',
		'Comparativos'            => '/category/comparativos/',
		'Melhores Top 3'          => '/category/melhores-top-3/',
	);
	echo '<ul id="primary-menu" class="menu">';
	foreach ( $items as $label => $path ) {
		printf(
			'<li class="menu-item"><a href="%1$s">%2$s</a></li>',
			esc_url( home_url( $path ) ),
			esc_html( $label )
		);
	}
	echo '</ul>';
}
