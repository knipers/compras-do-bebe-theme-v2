<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<section class="author-box"><h2>Sobre o autor</h2><div class="author-wrap"><?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?><div><strong><?php echo esc_html(get_the_author()); ?></strong><p><?php echo esc_html(get_the_author_meta('description')); ?></p></div></div></section>
