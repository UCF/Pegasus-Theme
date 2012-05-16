		</div><!-- close body .container -->
		<div id="footer">
			<div class="container wide">
				<div class="row" id="footer_stories">
					<div class="span12"><h2>More in this Issue</h2></div>
					
					<? 
					foreach(get_navigation_stories() as $story) {
					?> 
						<div class="span3">
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
				
				<div class="row" id="footer_navigation">
					<div class="span12">
						<div class="row">
							<div class="span3">
								<a href="<?=site_url()?>"><h2 id="footer_logo">Pegasus</h2></a>
								<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column One')):?><?php endif;?>
							</div>
							<div class="span3">
								<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Two')):?><?php endif;?>
							</div>
							<div class="span3">
								<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column Three')):?><?php endif;?>
							</div>
							<div class="span3">
								<?php if(!function_exists('dynamic_sidebar') or !dynamic_sidebar('Footer - Column four')):?><?php endif;?>
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