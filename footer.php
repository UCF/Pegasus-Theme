		</div><!-- close body .container -->
		<div id="footer">
			<div class="container wide">
				<div class="row">
					<div class="span12">
						<h2>More Stories</h2>
					</div>
				</div>
				<div class="row">
					<div class="span12">
						<div id="issue-carousel" class="carousel slide">
							<!-- Carousel items -->
							<div class="carousel-inner">
								<? 	$first = True;
									foreach(get_posts(array('post_type'=>'issue')) as $issue) { ?>
										<? if($first) { ?>
										<div class="active item">
										<? 
											$first = False;
											} else { 
										?>
										<div class="item">
										<? } ?>
											<div class="row">
												<div class="span12">
													<h3 class="issue-title"><?php echo $issue->post_title; ?></h3>
												</div>
											</div>
											<div class="row footer_stories">
											<? 
												extract(get_navigation_stories($issue));
												foreach($top_stories as $story) {
												?> 
													<div class="span3 clearfix">
														<a href="<?=get_permalink($story->ID)?>">
															<div class="thumb">
																<img src="<?=get_featured_image_url($story->ID)?>" />
															</div>
															<div class="title">
																<span class="title_text"><?=apply_filters('the_title', $story->post_title)?><?php if (get_post_meta($story->ID, 'story_subtitle', True)) { ?>: </span><span class="subtitle_text"><?=get_post_meta($story->ID, 'story_subtitle', True)?></span><?php } else { ?></span><?php } ?>
															</div>
														</a>
													</div>
												<? } ?>
											</div>
											<div class="row footer_stories bottom">
												<?
												foreach($bottom_stories as $story) {
												?> 
													<div class="span2 clearfix">
														<a href="<?=get_permalink($story->ID)?>">
															<div class="thumb">
																<img src="<?=get_featured_image_url($story->ID)?>" />
															</div>
															<div class="title">
																<span class="title_text"><?=apply_filters('the_title', $story->post_title)?><?php if (get_post_meta($story->ID, 'story_subtitle', True)) { ?>: </span><span class="subtitle_text"><?=get_post_meta($story->ID, 'story_subtitle', True)?></span><?php } else { ?></span><?php } ?>
															</div>
														</a>
													</div>
												<? } ?>
											</div>
										</div>
							<? } ?>
							</div>
							<!-- Carousel nav -->
							<a class="carousel-control left" href="#issue-carousel" data-slide="prev"><span class="arrow">&lsaquo;</span> <span class="issue-title"></span></a>
							<a class="carousel-control right" href="#issue-carousel" data-slide="next"><span class="issue-title">Summer 2012</span> <span class="arrow">&rsaquo;</span></a>
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