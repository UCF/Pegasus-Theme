		</main>
		<footer class="front-page-footer bg-inverse">
			<div class="container">
				<div class="row">
					<div class="col-md-3 offset-md-1 flex-md-last">
						<h2 class="fp-subheading-underline fp-footer-heading">Connect</h2>
						<?php
						$defaults = array(
							'theme_location' => 'footer-menu',
							'container'      => false,
							'menu_class'     => 'fp-footer-menu list-unstyled',
						);
						wp_nav_menu( $defaults );
						?>
					</div>
					<div class="col-md-3 offset-md-1 d-none d-md-block">
						<h2 class="fp-subheading-underline fp-footer-heading">About</h2>
						<?php
						$defaults = array(
							'theme_location' => 'footer-middle-menu',
							'container'      => false,
							'menu_class'     => 'fp-footer-menu list-unstyled',
						);
						wp_nav_menu( $defaults );
						?>
					</div>
					<div class="col-md-4 flex-md-first">
						<a class="fp-footer-logo" href="<?php echo get_site_url(); ?>">
							Pegasus
						</a>

						<div class="fp-copyright">
							&copy; <?php echo get_theme_option( 'org_name' ); ?>
						</div>
						<p class="fp-address">
							<?php echo nl2br( get_theme_option( 'org_address' ) ); ?>
						</p>

						<?php echo do_shortcode( '[ucf-social-icons color="grey"]' ); ?>

						<?php if ( ipad_deployed() ) : ?>
						<span class="footer-ipad-app">
							<a class="ipad-app-btn" href="<?php echo get_theme_option( 'ipad_app_url' ); ?>">
								Download the Digital Edition
							</a>
						</span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?php wp_footer(); ?>
</html>
