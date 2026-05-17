<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<div class="post-meta"><span>Publicado em <?php echo esc_html( get_the_date() ); ?></span><span>Atualizado em <?php echo esc_html( get_the_modified_date() ); ?></span><span>por <?php the_author_posts_link(); ?></span></div>
