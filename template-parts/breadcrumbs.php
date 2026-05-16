<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<nav class="cdb-breadcrumbs" aria-label="Breadcrumb"><a href="<?php echo esc_url(home_url('/')); ?>">Início</a> <span>/</span> <span><?php echo esc_html(wp_strip_all_tags(wp_get_document_title())); ?></span></nav>
