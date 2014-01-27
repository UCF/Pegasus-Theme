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

			var PostTypeSearchDataManager = {
                'searches' : [],
                'register' : function(search) {
                    this.searches.push(search);
                }
            }
            var PostTypeSearchData = function(column_count, column_width, data) {
                this.column_count = column_count;
                this.column_width = column_width;
                this.data = data;
            }
		</script>

		<?=output_header_markup($post);?>
		
	</head>

	<? extract(get_navigation_stories()); ?>

	<body class="<?=body_classes()?> <? if ($post->post_type == 'page' || is_404()) { print 'subpage'; } ?>">
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

		<aside id="pulldown">
			<div class="container-wide pulldown-container pulldown-stories">
				<span class="pulldown-title">In This Issue:</span>
				<div class="items"></div>
				<span class="error hidden">Stories could not be found at this time. Please try again later.</span>
				<div class="controls">
					<a class="close" href="#">×</a>
					<a class="backward" href="#"> &laquo; </a>
					<a class="forward" href="#"> &raquo; </a>
				</div>
			</div>
			<div class="container-wide pulldown-container pulldown-archives">
				<span class="pulldown-title">Archives:</span>
				<div class="items"></div>
				<span class="error hidden">Previous issues could not be found at this time. Please try again later.</span>
				<div class="controls">
					<a class="close" href="#">×</a>
					<a class="backward" href="#"> &laquo; </a>
					<a class="forward" href="#"> &raquo; </a>
				</div>
			</div>
		</aside>

		<header class="container-wide" id="header-navigation">
			<div class="container">
				<div class="row">
					<nav class="span12" role="navigation">
						<?php if ($post->post_type == 'issue') { ?>
						<h1 class="sprite header-logo">
							<a href="<?=get_site_url()?>">Pegasus</a>
						</h1>
						<?php } else { ?>
						<span class="sprite header-logo">
							<a href="<?=get_site_url()?>">Pegasus</a>
						</span>
						<?php } ?>

						<ul class="navigation">
							<li id="nav-about">
								<a href="<?=get_permalink(get_page_by_title('About the Magazine'))?>" alt="About Pegasus Magazine" title="About Pegasus Magazine">The Magazine of the University of Central Florida</a>
							</li>
							<li id="nav-mobile">
								<a href="<?=get_issue_feed_url(get_relevant_issue($post))?>"></a>
							</li>
							<li id="nav-issue">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" data-pulldown-src="<?=get_issue_feed_url(get_relevant_issue($post))?>" data-type="xml" href="<?=get_permalink(get_relevant_issue($post))?>"><?=get_relevant_issue($post)->post_title?></a>
							</li>
							<li id="nav-archives">
								<a href="<?=get_permalink(get_page_by_title('Archives'))?>">Archives</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

		<?php if (is_fall_2013_or_older($post)) { ?>
		<div class="container" id="body_content">
		<?php } else { ?>
		<main>
		<?php } ?>