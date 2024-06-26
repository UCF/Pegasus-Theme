		</main>

		<?php 		$issue = get_relevant_issue($post);
		$args = array();

		if ($post->post_type == 'story') {
			$args['exclude'] = $post->ID;
		}

		$stories = get_issue_stories($issue, $args);
		$perrow = 3; // .span4's

		if ($post->post_type !== 'page' && $post->post_type !== 'post' && $post->post_type !== 'issue' && !is_home()):
		?>
		<aside class="container-wide" id="more-stories" aria-labelledby="more-stories-heading">
			<div class="container">
				<div class="row">
					<div class="span12">
						<h2 class="section-title" id="more-stories-heading">More UCF Stories</h2>
					</div>
				</div>
			</div>
			<div class="container story-list-grid hidden-tablet hidden-phone">
				<div class="row">
				<?php 				$count = 0;
				if ($stories) {
					foreach ($stories as $story) {
						if ($count % $perrow == 0 && $count !== 0) {
							print '</div><div class="row">';
						}
						$count++;

						$title = $story->post_title;
						$subtitle = get_post_meta($story->ID, 'story_subtitle', TRUE);
						$thumb = get_featured_image_url($story->ID);
				?>
					<article class="span4">
						<a href="<?php echo get_permalink($story)?>">
							<?php if ($thumb) { ?>
							<img class="lazy" data-original="<?php echo $thumb?>" alt="<?php echo $title?>" title="<?php echo $title?>" />
							<?php } ?>
							<h3 class="story-title"><?php echo $title?></h3>
							<?php if (!empty($subtitle)) { ?>
							<span class="subtitle"><?php echo $subtitle?></span>
							<?php } ?>
						</a>
					</article>
				<?php 					}
				}
				?>
				</div>
			</div>
			<?php echo display_story_list($issue, 'hidden-desktop')?>
			<div class="controls hidden-desktop">
				<a class="backward icon-caret-left" href="#">Back</a>
				<a class="forward icon-caret-right" href="#">Forward</a>
			</div>
		</aside>
		<?php endif; ?>

		<?php 		$fb_url = get_theme_option('fb_url');
		$twitter_url = get_theme_option('twitter_url');
		$flickr_url = get_theme_option('flickr_url');
		$youtube_url = get_theme_option('youtube_url');
		if (
			!empty($fb_url) ||
			!empty($twitter_url) ||
			!empty($flickr_url) ||
			!empty($youtube_url)
		):
		?>
		<aside class="container-wide" id="footer-social" aria-label="Follow UCF on social media">
			<div class="container">
				<div class="row">
					<div class="span10 offset1 border-top">
						<ul>
							<?php if (!empty($fb_url)) { ?>
							<li>
								<a target="_blank" class="sprite facebook" href="<?php echo $fb_url?>">Follow UCF on Facebook</a>
							</li>
							<?php } if (!empty($twitter_url)) { ?>
							<li>
								<a target="_blank" class="sprite twitter" href="<?php echo $twitter_url?>">Follow UCF on Twitter</a>
							</li>
							<?php } if (!empty($flickr_url)) { ?>
							<li>
								<a target="_blank" class="sprite flickr" href="<?php echo $flickr_url?>">Follow UCF on Flickr</a>
							</li>
							<?php } if (!empty($youtube_url)) { ?>
							<li>
								<a target="_blank" class="sprite youtube" href="<?php echo $youtube_url?>">Follow UCF on YouTube</a>
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
					<div class="span12">
						<span class="footer-logo">
							<a class="sprite logo-large-white" href="<?php echo get_site_url()?>">
								Pegasus Magazine
							</a>
						</span>

						<?php 						$defaults = array(
							'theme_location'  => 'footer-menu',
							'container'       => false,
							'menu_class'      => 'navigation',
						);

						wp_nav_menu( $defaults );
						?>
						<p class="copyright">
							&copy; <?php echo get_theme_option('org_name')?>
						</p>
						<p class="address">
							<?php echo nl2br(get_theme_option('org_address'))?>
						</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?php echo "\n".footer_()."\n"?>
</html>
