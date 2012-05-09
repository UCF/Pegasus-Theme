		</div><!-- close body .container -->
			<div class="container" id="footer">
			
				<div class="row" id="footer_stories">
					<div class="span12"><h2>More in Pegasus:</h2></div>
					<div class="span3 firststory">
						<a href="#"><div class="storyimg" style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
						Story Title Here</a>
					</div>
					<div class="span3">
						<a href="#"><div class="storyimg" style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
						Story Title Here</a>
					</div>
					<div class="span3">
						<a href="#"><div class="storyimg" style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
						Story Title Here</a>
					</div>
					<div class="span3">
						<a href="#"><div class="storyimg" style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
						Story Title Here</a>
					</div>
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