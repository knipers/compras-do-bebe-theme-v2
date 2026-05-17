<?php get_header(); ?>
<section class="hero"><div class="cdb-container"><p class="eyebrow">Guias práticos para pais</p><h1>Compras do Bebê: guias práticos para escolher produtos de bebê</h1><p>Guias, comparativos e listas rápidas para ajudar você a escolher itens de sono, alimentação, higiene e organização para o bebê.</p><div class="hero-actions"><a class="btn" href="<?php echo esc_url( home_url( '/category/melhores-top-3/' ) ); ?>">Ver melhores Top 3</a><a class="btn btn-secondary" href="<?php echo esc_url( home_url( '/category/comparativos/' ) ); ?>">Ver comparativos</a></div></div></section>
<?php $blocks=array('Sono'=>'home-sono','Alimentação'=>'home-alimentacao','Higiene e Organização'=>'home-higiene-organizacao'); ?>
<section class="cdb-container grid-3"><?php foreach($blocks as $title=>$slug): $q=new WP_Query(array('post_type'=>'post','posts_per_page'=>3,'category_name'=>$slug)); ?><article class="card-section"><h2><?php echo esc_html($title); ?></h2><?php if($q->have_posts()): while($q->have_posts()):$q->the_post(); get_template_part('template-parts/content-card'); endwhile; else: ?><p>Sem posts selecionados ainda.</p><?php endif; wp_reset_postdata(); ?></article><?php endforeach; ?></section>
<?php
$sections=array('Comece por aqui'=>'guias-de-compra','Comparativos para decidir rápido'=>'comparativos');
foreach($sections as $title=>$slug): $q=new WP_Query(array('post_type'=>'post','posts_per_page'=>4,'category_name'=>$slug)); ?>
<section class="cdb-container"><h2><?php echo esc_html($title); ?></h2><div class="post-grid"><?php while($q->have_posts()):$q->the_post(); get_template_part('template-parts/content-card'); endwhile; ?></div></section>
<?php wp_reset_postdata(); endforeach; ?>
<?php get_template_part('template-parts/popular-posts'); ?>
<section class="cdb-container"><h2>Todos os posts</h2><div class="post-grid"><?php if(have_posts()): while(have_posts()):the_post(); get_template_part('template-parts/content-card'); endwhile; endif; ?></div><?php get_template_part('template-parts/pagination'); ?></section>
<?php get_footer(); ?>
