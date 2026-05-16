<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
</main>
<footer class="site-footer">
	<div class="cdb-container">
		<nav aria-label="Categorias principais" class="footer-links">
			<a href="<?php echo esc_url( home_url( '/category/melhores-top-3/' ) ); ?>">Melhores Top 3</a>
			<a href="<?php echo esc_url( home_url( '/category/comparativos/' ) ); ?>">Comparativos</a>
			<a href="<?php echo esc_url( home_url( '/category/guias-de-compra/' ) ); ?>">Guias de Compra</a>
		</nav>
		<p class="footer-transparency">O Compras do Bebê publica guias e comparativos para ajudar famílias a escolherem produtos com mais segurança e praticidade. Alguns links podem gerar comissão de afiliado, sem custo adicional para você.</p>
		<?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'nav', 'container_aria_label' => 'Menu de rodapé', 'fallback_cb' => false ) ); ?>
		<p class="copyright">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
