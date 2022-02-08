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
					<h2 class="font-serif font-italic font-weight-normal my-4 py-2" id="more-stories-heading">
						More UCF Stories
					</h2>
				</div>

				<!-- -lg+ grid of stories -->
				<div class="container story-list-grid d-none d-lg-block">
					<div class="row">
						<?php
						foreach ( $stories as $story ) :
							$title = $story->post_title;
							$subtitle = get_post_meta( $story->ID, 'story_subtitle', TRUE );
							$thumb = get_featured_image_url( $story->ID, 'single-post-thumbnail-3x2' );
						?>
						<div class="col-lg-4">
							<article>
								<div class="position-relative">
									<?php if ( $thumb ) : ?>
									<img class="lazy" data-original="<?php echo $thumb; ?>" alt="" />
									<?php endif; ?>

									<h3 class="story-title">
										<a class="stretched-link text-secondary" href="<?php echo get_permalink( $story ); ?>">
											<?php echo wptexturize( $title ); ?>
										</a>
									</h3>

									<?php if ( !empty( $subtitle ) ) : ?>
									<span class="subtitle">
										<?php echo wptexturize( strip_tags( $subtitle, '<b><em><i><u><strong>' ) ); ?>
									</span>
									<?php endif; ?>
								</div>
							</article>
						</div>
						<?php endforeach; ?>
					</div>
				</div>

				<!-- -xs-md horizontally-scrolling story list -->
				<?php echo display_story_list( $issue, 'd-lg-none', $stories ); ?>
				<div class="controls d-lg-none">
					<a class="backward icon icon-caret-left" href="#">Back</a>
					<a class="forward icon icon-caret-right" href="#">Forward</a>
				</div>
			</aside>
			<?php endif; ?>

			<aside id="footer-social" aria-label="Follow UCF on social media">
				<div class="bg-secondary">
					<div class="container py-4 text-center">
						<?php if ( ! is_singular( 'issue' ) ) : ?>
						<hr class="w-75 mb-4" role="presentation">
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
