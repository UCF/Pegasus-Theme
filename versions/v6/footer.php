			</main>

			<?php
			$issue = get_relevant_issue( $post );
			$args = array();

			if ( $post->post_type === 'story' ) {
				$args['exclude'] = $post->ID;
			}

			$stories = get_issue_stories( $issue, $args );

			if (
				! in_array( $post->post_type, array( 'page', 'post', 'issue' ) )
				&& ! is_home()
				&& $stories
			) :
			?>
			<aside id="more-stories" aria-labelledby="more-stories-heading">
				<div class="container">
					<h2 class="h4 text-default-aw text-uppercase mb-4" id="more-stories-heading">
						More from the <?php echo get_the_title( $issue ); ?> Issue
					</h2>
				</div>
				<div class="more-stories-list-wrap">
					<?php echo display_story_list( $issue, 'more-stories-list mb-5', $stories ); ?>
					<div class="story-list-controls hidden-xs-down d-lg-none">
						<button type="button" class="btn story-list-control story-list-control-backward" aria-label="Back">
							<span class="fas fa-2x fa-caret-left" aria-hidden="true"></span>
						</button>
						<button type="button" class="btn story-list-control story-list-control-forward" aria-label="Forward">
							<span class="fas fa-2x fa-caret-right" aria-hidden="true"></span>
						</button>
					</div>
				</div>
			</aside>
			<?php endif; ?>

			<aside id="footer-social" aria-label="Follow UCF on social media">
				<div class="bg-secondary">
					<div class="container py-4 text-center">
						<?php if ( ! is_singular( 'issue' ) ) : ?>
						<hr class="w-md-75 mb-4" role="presentation">
						<?php endif; ?>

						<?php echo do_shortcode( '[ucf-social-icons]' ); ?>
					</div>
				</div>
			</aside>

			<footer id="footer-navigation">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<span class="footer-logo <?php if ( ipad_deployed() ) { ?>pull-left<?php } ?>">
								<a class="logo-large-white <?php if ( ipad_deployed() ) { ?>pull-right<?php } ?>" href="<?php echo get_site_url(); ?>">
									Pegasus Magazine
								</a>
							</span>

							<?php if ( ipad_deployed() ) { ?>
							<span class="footer-ipad-app pull-right">
								<a class="ipad-app-btn pull-left" href="<?php echo get_theme_option( 'ipad_app_url' ); ?>">
									Download on the App Store
								</a>
							</span>
							<?php } ?>

							<?php
							$defaults = array(
								'theme_location' => 'footer-menu',
								'container'      => false,
								'menu_class'     => 'navigation',
							);

							wp_nav_menu( $defaults );
							?>
							<p class="copyright">
								&copy; <?php echo get_theme_option( 'org_name' ); ?>
							</p>
							<p class="address">
								<?php echo nl2br( get_theme_option( 'org_address' ) ); ?>
							</p>
						</div>
					</div>
				</div>
			</footer>
		</body>
		<?php wp_footer(); ?>
	</html>
