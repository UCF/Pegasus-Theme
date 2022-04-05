			</div><!-- close #body_content or last .container -->
		</main>

		<footer>
			<?php $issue = get_relevant_issue( $post ); ?>
			<div id="footer">
				<div class="container wide">
					<div class="row">
						<div class="span12">
							<div id="issue-carousel" class="carousel">
								<div class="carousel-inner">
									<div class="item active">
										<div class="row footer_stories">
											<div class="span12">
												<h3 class="issue-title"><?php echo $issue->post_title; ?></h3>
											</div>
											<div class="span12">
												<ul class="thumbnails">
												<?php
												extract( get_navigation_stories() );
												foreach( $top_stories as $story ) :
												?>
													<li class="span3">
														<a href="<?php echo get_permalink($story->ID); ?>">
															<div class="thumbnail">
																<img src="<?php echo get_featured_image_url($story->ID); ?>" />
															</div>
															<div class="title">
																<span class="title_text">
																<?php echo apply_filters( 'the_title', $story->post_title ); ?>
																<?php if (get_post_meta( $story->ID, 'story_subtitle', true ) ) : ?>
																		<span class="title_colon">:</span> </span>
																		<span class="subtitle_text"><?php echo get_post_meta( $story->ID, 'story_subtitle', true); ?></span>
																<?php else: ?>
																</span>
																<?php endif; ?>
															</div>
														</a>
													</li>
												<?php endforeach; ?>
												</ul>
											</div>
										</div>
										<div class="row footer_stories bottom">
											<div class="span12">
												<ul class="thumbnails">
												<?php foreach($bottom_stories as $story) : ?>
													<li class="span2">
														<a href="<?php echo get_permalink($story->ID); ?>">
															<div class="thumbnail">
																<img src="<?php echo get_featured_image_url( $story->ID ); ?>" />
															</div>
															<div class="title">
																<span class="title_text">
																	<?php echo apply_filters('the_title', $story->post_title); ?>
																	<?php if ( get_post_meta( $story->ID, 'story_subtitle', true ) ) : ?>
																		<span class="title_colon">:</span> </span>
																		<span class="subtitle_text"><?php echo get_post_meta( $story->ID, 'story_subtitle', true); ?></span>
																	<?php else : ?>
																	</span>
																	<?php endif; ?>
															</div>
														</a>
													</li>
												<?php endforeach; ?>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="container-wide" id="footer-navigation">
				<div class="container">
					<div class="row">
						<div class="span12">
							<span class="footer-logo">
								<a class="sprite logo-large-white" href="<?php echo get_site_url(); ?>">
									Pegasus Magazine
								</a>
							</span>

							<?php
							$defaults = array(
								'theme_location'  => 'footer-menu',
								'container'       => false,
								'menu_class'      => 'navigation',
							);

							wp_nav_menu( $defaults );
							?>
							<p class="copyright">
								&copy; <?php echo get_theme_option( 'org_name' ); ?>
							</p>
							<p class="address">
								<?php echo nl2br( get_theme_option( 'org_address' ) ) ; ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?php echo "\n".footer_()."\n"; ?>
</html>
