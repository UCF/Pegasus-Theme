<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>

	<?php $relevant_issue = get_relevant_issue($post); ?>

	<body class="<?php echo body_classes()?> <?php  if ($post->post_type == 'page' || is_404() || is_search() ) { print 'subpage'; } ?>">
		<a class="skip-navigation bg-complementary text-inverse" href="#content">Skip to main content</a>
		<aside class="container-wide" id="pulldown" aria-labelledby="pulldown-heading">
			<div class="pulldown-container pulldown-stories">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<h2 class="section-title" id="pulldown-heading">In This Issue</h2>
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
					<nav class="col-md-12 col-sm-12" role="navigation">
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
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?php echo get_permalink($relevant_issue)?>" aria-label="Menu"></a>
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

		<main id="content">
