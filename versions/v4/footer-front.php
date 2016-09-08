					</main>
					<footer class="front-page-footer">
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
									<div class="footer-social-links">
										<?php if ( !empty( $fb_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $fb_url; ?>"><span class="fa fa-facebook"></span><span class="sr-only">Follow UCF on Facebook</span></a>
										<?php } if ( !empty( $twitter_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $twitter_url; ?>"><span class="fa fa-twitter"></span><span class="sr-only">Follow UCF on Twitter</span></a>
										<?php } if ( !empty( $instagram_url ) ) { ?>
											<a target="_blank" class="social-icon" href="<?php echo $instagram_url; ?>"><span class="fa fa-instagram"></span><span class="sr-only">Follow UCF on Instagram</span></a>
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
								<div class="col-sm-3 col-sm-offset-1 other-stories">
									<div class="footer-header">Other Stories</div>
									<ul>
										<?php
											$i = 1;
											while ($i < 3):
												echo display_other_stories($i++);
											endwhile;
										?>
									</ul>
								</div>
								<div class="col-sm-3 col-sm-offset-1">
									<div class="footer-header">More Info</div>
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
