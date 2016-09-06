					</main>
					<footer>
						<div class="container">
							<div class="row">
								<div class="col-sm-4">
									<a class="footer-logo" href="<?php echo get_site_url(); ?>">
										Pegasus
									</a>

									<p class="copyright">
										&copy; <?php echo get_theme_option( 'org_name' ); ?>
									</p>
									<p class="address">
										<?php echo nl2br( get_theme_option( 'org_address' ) ); ?>
									</p>

									<?php
									$fb_url = get_theme_option( 'fb_url' );
									$twitter_url = get_theme_option( 'twitter_url' );
									$instagram_url = get_theme_option( 'instagram_url' );
									if (
										!empty( $fb_url ) ||
										!empty( $twitter_url ) ||
										!empty( $instagram_url )
									):
									?>
									<ul class="footer-social-links">
										<?php if ( !empty( $fb_url ) ) { ?>
										<li>
											<a target="_blank" class="" href="<?php echo $fb_url; ?>">Follow UCF on Facebook</a>
										</li>
										<?php } if ( !empty( $twitter_url ) ) { ?>
										<li>
											<a target="_blank" class="" href="<?php echo $twitter_url; ?>">Follow UCF on Twitter</a>
										</li>
										<?php } if ( !empty( $instagram_url ) ) { ?>
										<li>
											<a target="_blank" class="" href="<?php echo $instagram_url; ?>">Follow UCF on Instagram</a>
										</li>
										<?php } ?>
									</ul>
									<?php endif; ?>

									<?php if ( ipad_deployed() ) { ?>
									<span class="footer-ipad-app">
										<a class="ipad-app-btn" href="<?php echo get_theme_option( 'ipad_app_url' ); ?>">
											Download the Digital Edition
										</a>
									</span>
									<?php } ?>
								</div>
								<div class="col-sm-3 col-sm-offset-1">
									Other Stories (TODO)
								</div>
								<div class="col-sm-3 col-sm-offset-1">
									More Info (TODO)
									<?php
									$defaults = array(
										'theme_location' => 'footer-menu',
										'container'      => false,
										'menu_class'     => 'navigation',
									);

									wp_nav_menu( $defaults );
									?>
								</div>
							</div>
						</div>
					</footer>
				</body>
				<?php wp_footer(); ?>
			</html>
