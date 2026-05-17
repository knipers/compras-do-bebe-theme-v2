<?php
/**
 * Related posts block.
 *
 * @package Compras_do_Bebe_SEO_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Se YARPP estiver ativo, deixamos apenas o plugin renderizar relacionados.
if ( cdb_is_yarpp_active() ) {
	return;
}

$category_ids = wp_get_post_categories( get_the_ID() );

if ( empty( $category_ids ) ) {
	return;
}

$related_query = new WP_Query(
	array(
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'posts_per_page'      => 3,
		'post__not_in'        => array( get_the_ID() ),
		'category__in'        => $category_ids,
		'ignore_sticky_posts' => true,
		'orderby'             => 'date',
		'order'               => 'DESC',
		'no_found_rows'       => true,
	)
);

if ( $related_query->have_posts() ) :
	?>
	<section class="related-posts" aria-label="Posts relacionados">
		<h2>Posts relacionados</h2>
		<div class="post-grid">
			<?php
			while ( $related_query->have_posts() ) :
				$related_query->the_post();
				get_template_part( 'template-parts/content-card' );
			endwhile;
			?>
		</div>
	</section>
	<?php
endif;

wp_reset_postdata();
