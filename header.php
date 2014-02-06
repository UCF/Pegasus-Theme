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
			var PROTOCOL = '<?=is_ssl() ? "http://" : "https://"?>';
			var THEME_JS_URL = PROTOCOL + '<?=str_replace(array("http://", "https://"), '', THEME_JS_URL)?>';

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

	<?php $relevant_issue = get_relevant_issue($post); ?>
	<? extract(get_navigation_stories()); ?>

	<body class="<?=body_classes()?> <? if ($post->post_type == 'page' || is_404() || is_search() ) { print 'subpage'; } ?>">
		<div id="ipad" class="modal">
			<div class="modal-header">
				<strong>Pegasus Magazine is available on the iPad!</strong>
				</div>
			<div class="modal-body">
				<a href="<?=get_theme_option('ipad_app_url')?>" class="btn btn-primary">Go to iTunes</a>
				<a href="#" class="btn" data-dismiss="modal">Continue to Web Version</a>
			</div>
			<div class="modal-footer">
			</div>
		</div>

		<div class="container-wide" id="pulldown">
			<div class="pulldown-container pulldown-stories">
				<div class="container">
					<div class="row">
						<div class="span12">
							<span class="pulldown-title">In This Issue:</span>
						</div>
					</div>
				</div>
				<div class="items">
					<?php
						$relevant_issue_stories = get_issue_stories($relevant_issue);
						if ($relevant_issue_stories) {
					?>
					<ul>
						<?php
							foreach ($relevant_issue_stories as $story) {
								$title = $story->post_title;
								$subtitle = get_post_meta($story->ID, 'story_subtitle', TRUE);
								$thumb = get_featured_image_url($story->ID);
						?>
						<li>
							<a href="<?=get_permalink($story)?>">
								<?php if ($thumb) { ?>
								<img class="lazy" data-original="<?=$thumb?>" alt="<?=$title?>" title="<?=$title?>" />
								<?php } ?>
								<span class="story-title"><?=$title?></span>
								<span class="subtitle"><?=$subtitle?></span>
							</a>
						</li>
						<?php } ?>
					</ul>
					<?php } else { ?>
					<p>No stories found.</p>
					<?php } ?>
				</div>
				<div class="controls">
					<a class="close pulldown-toggle" data-pulldown-container=".pulldown-stories" href="#">×</a>
					<a class="backward icon-caret-left" href="#" alt="Backward"></a>
					<a class="forward icon-caret-right" href="#" alt="Forward"></a>
				</div>
			</div>
		</div>

		<header class="container-wide" id="header-navigation">
			<div class="container">
				<div class="row">
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
							<li id="nav-mobile">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?=get_permalink(get_relevant_issue($post))?>"></a>
							</li>
							<li id="nav-issue">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?=get_permalink(get_relevant_issue($post))?>"><?=$relevant_issue->post_title?></a>
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