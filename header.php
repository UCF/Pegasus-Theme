<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta name="viewport" content="width=1024">
		<?="\n".header_()."\n"?>
		<?php if(GA_ACCOUNT or CB_UID):?>
		
		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>
			
			var GA_ACCOUNT  = '<?=GA_ACCOUNT?>';
			var _gaq        = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>
			
			var CB_UID      = '<?=CB_UID?>';
			var CB_DOMAIN   = '<?=CB_DOMAIN?>';
			<?php endif?>
			
		</script>
		<?php endif;?>
		<? if($post->post_type == 'story'
			&& ($stylesheet_id = get_post_meta($post->ID, 'story_stylesheet', True)) !== False
			&& ($stylesheet_url = wp_get_attachment_url($stylesheet_id)) !== False) { ?>
			<link rel='stylesheet' href="<?=$stylesheet_url?>" type='text/css' media='all' />
		<? } ?>
		
	</head>
	<!--[if lt IE 7 ]>  <body class="ie ie6 <?=body_classes()?>"> <![endif]-->
	<!--[if IE 7 ]>     <body class="ie ie7 <?=body_classes()?>"> <![endif]-->
	<!--[if IE 8 ]>     <body class="ie ie8 <?=body_classes()?>"> <![endif]-->
	<!--[if IE 9 ]>     <body class="ie ie9 <?=body_classes()?>"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body class="<?=body_classes()?>"> <!--<![endif]-->
		<div id="ipad" class="modal">
			<div class="modal-header">
				<h3>Pegasus Magazine is Available on the iPad!</h3>
			</div>
			<div class="modal-body">
				<a href="#" class="btn btn-primary">Go to iTunes</a>
				<a href="#" class="btn" data-dismiss="modal">Continue to Web Version</a>
			</div>
			<div class="modal-footer">
			</div>
		</div>
		<div class="container wide" id="story_nav">
			<div class="row">
				<div class="span12">
					<h3>More in this Issue</h3>
				</div>
				<div class="span12">
					<div class="row">
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
				</div>
			</div>
		</div>
		<div class="container wide" id="header">
			<div class="row">
				<div class="span12">
					<div class="row" style="position:relative;">
						<a href="<?=site_url()?>" class="span3 title">
							PEGASUS
						</a>
						<div class="span5 edition">
							Summer 2012
						</div>

						<div class="span4 description">
							The Magazine of the University of Central Florida
						</div>
						<a class="toggle_story_nav">
							&#9660;
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			