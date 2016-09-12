					</main>
					<footer class="front-page-footer">
						<div class="container">
							<div class="row">
								<div class="col-sm-3 col-sm-offset-1 col-md-offset-1 col-sm-push-8">
									<h2 class="fp-subheading-underline fp-footer-heading">More Info</h2>
									<?php
									$defaults = array(
										'theme_location' => 'footer-menu',
										'container'      => false,
										'menu_class'     => 'fp-footer-menu list-unstyled',
									);
									wp_nav_menu( $defaults );
									?>
								</div>
								<div class="col-sm-3 col-sm-offset-1 hidden-xs">
									<aside class="fp-other-stories">
										<h2 class="fp-subheading-underline fp-footer-heading">Other Stories</h2>
										<ul class="list-unstyled">
											<?php
											$other_story_title = null;
											$other_story_url = null;
											$i = 1;

											while ( $i < 4 ):
												$other_story_title = get_theme_option( 'front_page_other_story_' . $i . '_title' );
												$other_story_url = get_theme_option( 'front_page_other_story_' . $i . '_url' );

												if ( $other_story_title && $other_story_url ):
											?>
											<li>
												<?php echo display_front_page_other_story( $other_story_title, $other_story_url ); ?>
											</li>
											<?php
												endif;
												$i++;
											endwhile;
											?>
										</ul>
									</aside>
								</div>
								<div class="col-sm-4 col-sm-pull-8">
									<a class="fp-footer-logo" href="<?php echo get_site_url(); ?>">
										Pegasus
									</a>

									<p class="fp-copyright">
										&copy; <?php echo get_theme_option( 'org_name' ); ?>
									</p>
									<p class="fp-address">
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
									<div class="fp-footer-social-links">
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
							</div>
						</div>
					</footer>
				</body>
				<?php wp_footer(); ?>
			</html>
