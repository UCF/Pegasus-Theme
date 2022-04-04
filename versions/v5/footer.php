			</main>

			<?php
			$issue = get_relevant_issue( $post );
			$args = array();

			if ( $post->post_type == 'story' ) {
				$args['exclude'] = $post->ID;
			}

			$stories = get_issue_stories( $issue, $args );
			$perrow = 3; // .col-md-4 col-sm-4's

			if ( $post->post_type !== 'page' && $post->post_type !== 'post' && $post->post_type !== 'issue' && !is_home() ):
			?>
			<aside class="container-wide" id="more-stories" aria-labelledby="more-stories-heading">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<h2 class="section-title" id="more-stories-heading">More UCF Stories</h2>
						</div>
					</div>
				</div>
				<div class="container story-list-grid hidden-sm hidden-xs">
					<div class="row">
					<?php
					$count = 0;
					if ( $stories ) {
						foreach ( $stories as $story ) {
							if ( $count % $perrow == 0 && $count !== 0 ) {
								print '</div><div class="row">';
							}
							$count++;

							$title = $story->post_title;
							$subtitle = get_post_meta( $story->ID, 'story_subtitle', TRUE );
							$thumb = get_featured_image_url( $story->ID, 'single-post-thumbnail-3x2' );
					?>
						<article class="col-md-4 col-sm-4">
							<a href="<?php echo get_permalink( $story ); ?>">
								<?php if ( $thumb ) { ?>
								<img class="lazy" data-original="<?php echo $thumb; ?>" alt="" />
								<?php } ?>
								<h3 class="story-title"><?php echo wptexturize( $title ); ?></h3>
								<?php if ( !empty( $subtitle ) ) { ?>
								<span class="subtitle"><?php echo wptexturize( strip_tags( $subtitle, '<b><em><i><u><strong>' ) ); ?></span>
								<?php } ?>
							</a>
						</article>
					<?php
						}
					}
					?>
					</div>
				</div>
				<?php echo display_story_list( $issue, 'hidden-md hidden-lg' ); ?>
				<div class="controls hidden-md hidden-lg">
					<a class="backward icon icon-caret-left" href="#">Back</a>
					<a class="forward icon icon-caret-right" href="#">Forward</a>
				</div>
			</aside>
			<?php endif; ?>

			<?php
			$fb_url = get_theme_option( 'fb_url' );
			$twitter_url = get_theme_option( 'twitter_url' );
			$flickr_url = get_theme_option( 'flickr_url' );
			$youtube_url = get_theme_option( 'youtube_url' );
			if (
				!empty( $fb_url ) ||
				!empty( $twitter_url ) ||
				!empty( $flickr_url ) ||
				!empty( $youtube_url )
			):
			?>
			<aside class="container-wide" id="footer-social" aria-label="Follow UCF on social media">
				<div class="container">
					<div class="row">
						<div class="col-md-10 col-sm-10 col-md-offset-1 col-sm-offset-1 border-top">
							<ul class="social-icon-list">
								<?php if ( !empty( $fb_url ) ) { ?>
								<li class="social-icon-list-item">
									<a target="_blank" class="sprite facebook" href="<?php echo $fb_url; ?>">Follow UCF on Facebook</a>
								</li>
								<?php } if ( !empty( $twitter_url ) ) { ?>
								<li class="social-icon-list-item">
									<a target="_blank" class="sprite twitter" href="<?php echo $twitter_url; ?>">Follow UCF on Twitter</a>
								</li>
								<?php } if ( !empty( $flickr_url ) ) { ?>
								<li class="social-icon-list-item">
									<a target="_blank" class="sprite flickr" href="<?php echo $flickr_url; ?>">Follow UCF on Flickr</a>
								</li>
								<?php } if ( !empty( $youtube_url ) ) { ?>
								<li class="social-icon-list-item">
									<a target="_blank" class="sprite youtube" href="<?php echo $youtube_url; ?>">Follow UCF on YouTube</a>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</div>
			</aside>
			<?php endif; ?>

			<footer class="container-wide" id="footer-navigation">
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
