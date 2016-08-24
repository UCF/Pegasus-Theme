		</main>
		<footer class="site-footer">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer-menu',
				'container' => false,
				'menu_class' => 'list-inline site-footer-menu'
			) );
			?>
			<?php wp_footer(); ?>
		</footer>
	</body>
</html>
