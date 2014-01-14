<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta name="viewport" content="width=device-width">
		<?="\n".header_()."\n"?>
		<?php if(GA_ACCOUNT or CB_UID):?>

		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>
			
			var GA_ACCOUNT = '<?=GA_ACCOUNT?>';
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>
	
			var CB_UID = '<?=CB_UID?>';
			var CB_DOMAIN = '<?=CB_DOMAIN?>';
			<?php endif?>
		</script>
		<?php endif;?>

		<!--[if IE]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		
		<script type="text/javascript">
			var IPAD_DEPLOYED = <?=ipad_deployed() ? 'true' : 'false'?>;
			var THEME_JS_URL = '<?=THEME_JS_URL?>';
		</script>

		<?=output_header_markup($post);?>
		
	</head>

	<? extract(get_navigation_stories()); ?>

	<!--[if IE 7 ]>     <body class="ie ie7 <?=body_classes()?> <? if ($post->post_type == 'page') { print 'subpage'; } ?>"> <![endif]-->
	<!--[if IE 8 ]>     <body class="ie ie8 <?=body_classes()?> <? if ($post->post_type == 'page') { print 'subpage'; } ?>"> <![endif]-->
	<!--[if IE 9 ]>     <body class="ie ie9 <?=body_classes()?> <? if ($post->post_type == 'page') { print 'subpage'; } ?>"> <![endif]-->
	<!--[if (gt IE 9)|!(IE)]><!--> <body class="<?=body_classes()?> <? if ($post->post_type == 'page') { print 'subpage'; } ?>"> <!--<![endif]-->

		<div id="ipad" class="modal">
			<div class="modal-header">
				<h3>Pegasus Magazine is available on the iPad!</h3>
				</div>
			<div class="modal-body">
				<a href="<?=get_theme_option('ipad_app_url')?>" class="btn btn-primary">Go to iTunes</a>
				<a href="#" class="btn" data-dismiss="modal">Continue to Web Version</a>
			</div>
			<div class="modal-footer">
			</div>
		</div>

		<div class="container-wide" id="header-navigation">
			<div class="container">
				<div class="row">
					<header>
						<?php if ($post->post_type == 'issue') { ?>
						<h1 class="sprite header-logo">
							<a href="<?=get_site_url()?>">Pegasus</a>
						</h1>
						<?php } else { ?>
						<span class="sprite header-logo">
							<a href="<?=get_site_url()?>">Pegasus</a>
						</span>
						<?php } ?>

						<nav class="span12" role="navigation">
							<ul class="navigation">
								<li id="nav-about">
									<a href="<?=get_permalink(get_page_by_title('About the Magazine'))?>" alt="About Pegasus Magazine" title="About Pegasus Magazine">The Magazine of the University of Central Florida</a>
								</li>
								<li id="nav-issue">
									<a href="<?=get_permalink(get_relevant_issue($post))?>"><?=get_relevant_issue($post)->post_title?></a>
								</li>
								<li id="nav-archives">
									<a href="<?=get_permalink(get_page_by_title('Archives'))?>">Archives</a>
								</li>
							</ul>
						</nav>
					</header>
				</div>
			</div>
		</div>

		<div class="container" id="body_content">