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
	<body class="<?=body_classes()?>">
		<div id="header">
			<div class="container">
				<div class="row">
					<div class="span12">
						<div class="row">
							<a href="<?=site_url()?>" class="span3 title">
								PEGASUS
							</a>
							<div class="span5 edition">
								<ul class="nav">
									<li class="dropdown">
										<a class="dropdown-toggle" data-toggle="dropdown" href="#">
											Summer 2012
										<b class="caret"></b>
										</a>
										<ul class="dropdown-menu">
											<li>
												<a href="#">
													Harris Rosen
													<span>From Hell's Kitchen in New York to a Quality Inn in Orlando</span>
												</a>
											</li>
											<li>
												<a href="#">
													58,578
													<span>Is Bigger Better?</span>
												</a>
											</li>
											<li>
												<a href="#">
													Nano
													<span>Will the Science of Small Ever Have its Big Moment?</span>
												</a>
											</li>
										</ul>
									</li>
								</ul>
							</div>

							<div class="span4 description">
								The Magazine of the University of Central Florida
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container">
			