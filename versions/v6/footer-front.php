					</main>
					<footer class="front-page-footer">
						<div class="container">
							<div class="row">
								<div class="col-md-3 offset-md-1 flex-md-last">
									<h2 class="fp-subheading-underline fp-footer-heading">Connect</h2>
									<?php 									$defaults = array(
										'theme_location' => 'footer-menu',
										'container'      => false,
										'menu_class'     => 'fp-footer-menu list-unstyled',
									);
									wp_nav_menu( $defaults );
									?>
								</div>
								<div class="col-md-3 offset-md-1 d-none d-md-block">
									<h2 class="fp-subheading-underline fp-footer-heading">About</h2>
									<ul class="list-unstyled">
									<?php 									$defaults = array(
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

									<p class="fp-copyright">
										&copy; <?php echo get_theme_option( 'org_name' ); ?>
									</p>
									<p class="fp-address">
										<?php echo nl2br( get_theme_option( 'org_address' ) ); ?>
									</p>

									<?php 									$fb_url = get_theme_option( 'fb_url' );
									$twitter_url = get_theme_option( 'twitter_url' );
									$instagram_url = get_theme_option( 'instagram_url' );
									$share_url = get_theme_option( 'share_url' );
									if (
										!empty( $fb_url ) ||
										!empty( $twitter_url ) ||
										!empty( $instagram_url ) ||
										!empty( $share_url )
									):
									?>
									<div class="fp-footer-social-links">
										<?php if ( !empty( $fb_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $fb_url; ?>"><span class="fa fa-facebook"></span><span class="sr-only">Follow UCF on Facebook</span></a>
										<?php } if ( !empty( $twitter_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $twitter_url; ?>"><span class="fa fa-twitter"></span><span class="sr-only">Follow UCF on Twitter</span></a>
										<?php } if ( !empty( $instagram_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $instagram_url; ?>"><span class="fa fa-instagram"></span><span class="sr-only">Follow UCF on Instagram</span></a>
										<?php } if ( !empty( $share_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $share_url; ?>"><span class="fa fa-share-alt"></span><span class="sr-only">Share</span></a>
										<?php } ?>
									</div>
									<?php endif; ?>

									<?php if ( ipad_deployed() ) { ?>
									<span class="footer-ipad-app">
										<a class="ipad-app-btn" href="<?php echo get_theme_option( 'ipad_app_url' ); ?>">
											Download the Digital Edition
										</a>
									</span>
									<?php } ?>
								</div>
							</div>
						</div>
					</footer>
				</body>
				<?php wp_footer(); ?>
			</html>
