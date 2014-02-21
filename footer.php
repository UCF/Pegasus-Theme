	<?php
	// Determine if this post requires pre-Spring 2014 footer markup
	// and apply it if necessary.

	if (is_fall_2013_or_older($post)) {
	?>

		</div><!-- close #body_content or last .container -->

		<?php $issue = get_relevant_issue($post); ?>
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
						                    <?
						                    extract(get_navigation_stories());
						                    foreach($top_stories as $story) {
						                    ?>
						                        <li class="span3">
						                            <a href="<?=get_permalink($story->ID)?>">
						                                <div class="thumbnail">
						                                    <img src="<?=get_featured_image_url($story->ID)?>" />
						                                </div>
						                                <div class="title">
						                                    <span class="title_text">
						                                    <?=apply_filters('the_title', $story->post_title)?>
						                                    <?php if (get_post_meta($story->ID, 'story_subtitle', True)) { ?>
						                                            <span class="title_colon">:</span> </span>
						                                            <span class="subtitle_text"><?=get_post_meta($story->ID, 'story_subtitle', True)?></span>
						                                    <?php } else { ?></span><?php } ?>
						                                </div>
						                            </a>
						                        </li>
						                    <? } ?>
						                    </ul>
						                </div>
						            </div>
			            	        <div class="row footer_stories bottom">
			                            <div class="span12">
			                                <ul class="thumbnails">
			                                <?
			                                foreach($bottom_stories as $story) {
			                                ?>
			                                    <li class="span2">
			                                        <a href="<?=get_permalink($story->ID)?>">
			                                            <div class="thumbnail">
			                                                <img src="<?=get_featured_image_url($story->ID)?>" />
			                                            </div>
			                                            <div class="title">
			                                                <span class="title_text">
			                                                    <?=apply_filters('the_title', $story->post_title)?>
			                                                    <?php if (get_post_meta($story->ID, 'story_subtitle', True)) { ?>
			                                                        <span class="title_colon">:</span> </span>
			                                                        <span class="subtitle_text"><?=get_post_meta($story->ID, 'story_subtitle', True)?></span>
			                                                    <?php } else { ?></span><?php } ?>
			                                            </div>
			                                        </a>
			                                    </li>
			                                <? } ?>
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

	<?php } else { 
		$issue = get_relevant_issue($post);
		$stories = get_issue_stories($issue);
		$perrow = 3; // .span4's
	?>

		</main>
		<aside class="container-wide" id="more-stories">
			<div class="container">
				<div class="row">
					<div class="span12">
						<span class="section-title">
							More UCF Stories
						</span>
					</div>
				</div>
			</div>
			<div class="container story-list-grid hidden-tablet hidden-phone">
				<div class="row">
				<?php
				$count = 0;
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
						<a href="<?=get_permalink($story)?>">
							<?php if ($thumb) { ?>
							<img class="lazy" data-original="<?=$thumb?>" alt="<?=$title?>" title="<?=$title?>" />
							<?php } ?>
							<span class="story-title"><?=$title?></span>
							<span class="subtitle"><?=$subtitle?></span>
						</a>
					</article>
				<?php
					}
				}
				?>
				</div>
			</div>
			<?=display_story_list($issue, 'hidden-desktop')?>
			<div class="controls hidden-desktop">
				<a class="backward icon-caret-left" href="#" alt="Backward"></a>
				<a class="forward icon-caret-right" href="#" alt="Forward"></a>
			</div>
		</aside>

	<?php } ?>

		<footer class="container-wide" id="footer-navigation">
			<div class="container">
				<div class="row">
					<div class="span12">
						<span class="footer-logo <?php if(ipad_deployed()) { ?>pull-left<?php } ?>">
							<a class="sprite logo-large-white <?php if(ipad_deployed()) { ?>pull-right<?php } ?>" href="<?=get_site_url()?>">
								Pegasus Magazine
							</a>
						</span>

						<? if(ipad_deployed()) {?>
						<span class="footer-ipad-app pull-right">
							<a class="sprite ipad-app-btn pull-left" href="<?=get_theme_option('ipad_app_url')?>">
								Download on the App Store
							</a>
						</span>
                        <?}?>
						
						<?php
						$defaults = array(
							'theme_location'  => 'footer-menu',
							'container'       => false,
							'menu_class'      => 'navigation',
						);

						wp_nav_menu( $defaults );
						?>
						<p class="copyright">
							&copy; University of Central Florida
						</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?="\n".footer_()."\n"?>
</html>