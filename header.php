<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
		<? if(is_page() 
			&& ($stylesheet_id = get_post_meta($post->ID, 'page_stylesheet', True)) !== False
			&& ($stylesheet_url = wp_get_attachment_url($stylesheet_id)) !== False) { ?>
			<link rel='stylesheet' href="<?=$stylesheet_url?>" type='text/css' media='all' />
		<? } ?>
		
	</head>
	<body class="<?=body_classes()?>">
		<div id="header">
			<div class="container">
				<div class="row">
					<div class="span12">
						<div class="row hidden-tablet hidden-phone">
							<div class="span3 title">
								PEGASUS
							</div>
							<div class="span4 edition">
								Summer 2012
							</div>
							<div class="span5 description">
								The magazine of the University of Central Florida
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			