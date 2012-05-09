		</div><!-- close body .container -->
			<div class="container" id="footer">
            
				<div class="row" id="footer_stories">
                	<div class="span12">More in Pegasus:</div>
                	<div class="span3">
                    	<div style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
                        <a href="#">Story Title Here</a>
                    </div>
                    <div class="span3">
                    	<div style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
                        <a href="#">Story Title Here</a>
                    </div>
                    <div class="span3">
                    	<div style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
                        <a href="#">Story Title Here</a>
                    </div>
                    <div class="span3">
                    	<div style="background: #333; width: 220px; height: 275px;">Story Image Here</div>
                        <a href="#">Story Title Here</a>
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