		</div><!-- close body .container -->
			<div class="container" id="footer">
			
				<div class="row" id="footer_stories">
					<div class="span12"><h2>More in Pegasus:</h2></div>
                    
                    <? foreach(get_current_edition_stories($post->ID) as $story) {?> 
						<div class="span3">
							<a href="<?=get_permalink($story->ID)?>">
								<div class="thumb">
									<img src="<?=get_featured_image_url($story->ID)?>" />
								</div>
								<div class="title">
									<?=apply_filters('the_title', $story->post_title)?>
								</div>
							</a>
						</div>
					<? } ?>
                    
				</div>
				
				<div class="row" id="footer_navigation">
					<div class="span3">
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
	</body>
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<?="\n".footer_()."\n"?>
</html>