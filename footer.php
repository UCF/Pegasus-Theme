		</div><!-- close body .container -->
		<div id="footer">
			<div class="container wide">
				<div class="row">
					<div class="span12">
						<div id="issue-carousel" class="carousel slide">
							<!-- Carousel items -->
							<div class="carousel-inner">
								<? 	
									if($post->post_type == 'story') {
										$active_issue = get_story_issue($post);
									} else {
										$active_issue = get_current_issue();
									}
									$prev_issue   = NULL;
									$next_issue   = NULL;
									$found_active = False;
									foreach(get_posts(array('post_type'=>'issue')) as $issue) { ?>
										<?php 
											if($active_issue->ID == $issue->ID) { 
												$found_active = True; 
												echo '<div class="active item">';
											} else {
												echo '<div class="item">';
												if($found_active === False) {
													$prev_issue = $issue;
												} else if($found_active === True) {
													$next_issue   = $issue;
													$found_active = NULL;
												}
											} ?>
											<div class="row">
												<div class="span12">
													<h3 class="issue-title"><?php echo $issue->post_title; ?></h3>
												</div>
											</div>
											<div class="row footer_stories">
												<div class="span12">
													<ul class="thumbnails">
													<? 
														extract(get_navigation_stories($issue));
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
								<? } ?>
							</div>
							<!-- Carousel nav -->
							<a class="carousel-control left" href="#issue-carousel" data-slide="prev"<?php if(is_null($prev_issue)) echo ' style="display:none;"'; ?>>
								<i class="icon-chevron-left icon-white"></i> <span class="issue-title"><? if(!is_null($prev_issue)) echo $prev_issue->post_title; ?></span>
							</a>
							<a class="carousel-control right" href="#issue-carousel" data-slide="next"<?php if(is_null($next_issue)) echo ' style="display:none;"'; ?>>
								<span class="issue-title"><?php if(!is_null($next_issue)) echo $next_issue->post_title; ?></span> <i class="icon-chevron-right icon-white"></i>
							</a>
						</div>
					</div>
				</div>

				<div class="row" id="footer_hr"><!-- --></div>
				
				<div class="row" id="footer_navigation">
					<div class="span12">
						<div class="row">
							<div class="span3">
								<a href="<?=site_url()?>"><h2 id="footer_logo">Pegasus</h2></a>
							</div>
							<div class="span9">
								<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column One')):?><?php endif;?>
								<? if(ipad_deployed()) {?>
									<a href="<?=get_theme_option('ipad_app_url')?>" id="ipad_app">
										Available on the App Store
									</a>
								<?}?>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</body>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
</html>