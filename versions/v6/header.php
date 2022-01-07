<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>

	<?php $relevant_issue = get_relevant_issue($post); ?>

	<body class="<?php echo body_classes()?> <?php  if ($post->post_type == 'page' || is_404() || is_search() ) { print 'subpage'; } ?>">
		<div id="ipad" class="modal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<strong>Pegasus Magazine is available on the iPad!</strong>
					</div>
					<div class="modal-body">
						<a href="<?php echo get_theme_option( 'ipad_app_url' ); ?>" class="btn btn-primary">Go to iTunes</a>
						<a href="#" class="btn" data-dismiss="modal">Continue to Web Version</a>
					</div>
				</div>
			</div>
		</div>

		<aside class="container-wide" id="pulldown">
			<div class="pulldown-container pulldown-stories">
				<div class="container">
					<div class="row">
						<div class="col-lg-12 col-md-12">
							<h2 class="section-title">In This Issue</h2>
						</div>
					</div>
				</div>
				<?php echo display_story_list($relevant_issue)?>
				<div class="controls">
					<a class="close pulldown-toggle" data-pulldown-container=".pulldown-stories" href="#">Close</a>
					<a class="backward icon icon-caret-left" href="#">Back</a>
					<a class="forward icon icon-caret-right" href="#">Forward</a>
				</div>
			</div>
		</aside>

		<header class="container-wide" id="header-navigation">
			<div class="container">
				<div class="row">
					<nav class="col-lg-12 col-md-12" role="navigation">
						<?php if ( is_home() || $post->post_type == 'issue' ) { ?>
						<h1 class="header-logo">
							<a href="<?php echo get_site_url()?>">Pegasus</a>
						</h1>
						<?php } else { ?>
						<span class="header-logo">
							<a href="<?php echo get_site_url()?>">Pegasus</a>
						</span>
						<?php } ?>

						<ul class="navigation">
							<li id="nav-about">
								<a href="<?php echo get_permalink(get_page_by_title('About the Magazine'))?>">The Magazine of the University of Central Florida</a>
							</li>
							<li id="nav-mobile">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?php echo get_permalink($relevant_issue)?>"></a>
							</li>
							<li id="nav-issue">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?php echo get_permalink($relevant_issue)?>">In This Issue</a>
							</li>
							<li id="nav-archives">
								<a href="<?php echo get_permalink(get_page_by_title('Archives'))?>">Archives</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

		<main>
