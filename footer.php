	<?php
	// Determine if this post requires pre-Spring 2014 footer markup
	// and apply it if necessary.

	if (is_before_fall_2013($post)) {
	?>
	
		</div><!-- close #body_content or last .container -->

		<?php $issue = get_relevant_issue($post); ?>
		<div id="footer" class="container-wide">
			<div class="container">
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
						        </div>
			                </div>
			            </div>
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

	<?php } else { ?>

		</main>

		<aside>
			List of more stories here...
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
							&copy; <?=date('Y')?> University of Central Florida.
						</p>
					</div>
				</div>
			</div>
		</footer>
	</body>
	<?="\n".footer_()."\n"?>
</html>