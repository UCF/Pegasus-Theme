<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>

	<?php $relevant_issue = get_relevant_issue($post); ?>

	<body <?php echo body_class(); ?>>
		<a class="skip-navigation bg-complementary text-inverse" href="#content">Skip to main content</a>
		<div id="ucfhb" style="min-height: 50px; background-color: #000;"></div>
		<header class="site-header" id="header-navigation">
			<div class="header-pulldown collapse bg-faded" id="pulldown" tabindex="-1">
				<div class="container pt-3 hidden-md-up">
					<div class="d-flex flex-row align-items-center justify-content-center">
						<a class="font-serif small text-secondary d-inline-block" href="<?php echo get_permalink( get_page_by_title( 'About the Magazine' ) ); ?>">
							The Magazine of the University of Central Florida
						</a>
						<span class="d-inline-block mx-3" aria-hidden="true">|</span>
						<a class="navbar-link nav-archives" href="<?php echo get_permalink( get_page_by_title( 'Archives' ) ); ?>">
							<span class="navbar-link-icon" aria-hidden="true"></span>
							<span class="navbar-link-text">Archives</span>
						</a>
					</div>
				</div>
				<hr role="presentation" class="mt-3 mb-4 hidden-md-up">
				<div class="pulldown-container pulldown-stories">
					<div class="story-list-controls hidden-xs-down">
						<button id="pulldown-close" type="button" class="btn story-list-control close" data-toggle="collapse" data-target="#pulldown" aria-expanded="true" aria-controls="pulldown" aria-label="Close article pulldown">
							<span class="fas fa-2x fa-times" aria-hidden="true"></span>
						</button>
						<button type="button" class="btn story-list-control story-list-control-backward" aria-label="Scroll articles left">
							<span class="fas fa-2x fa-caret-left" aria-hidden="true"></span>
						</button>
						<button type="button" class="btn story-list-control story-list-control-forward" aria-label="Scroll articles right">
							<span class="fas fa-2x fa-caret-right" aria-hidden="true"></span>
						</button>
					</div>
					<div class="container">
						<h2 class="text-default-aw text-uppercase my-4 py-2" id="pulldown-heading">
							<span class="mr-1">In This Issue</span>
							<span class="d-inline-block font-size-lg text-transform-none"><?php echo $relevant_issue->post_title; ?></span>
						</h2>
					</div>
					<div class="story-list-wrap">
						<?php echo display_story_list( $relevant_issue ); ?>
					</div>
				</div>
			</div>
			<nav class="site-nav navbar navbar-light bg-secondary">
				<div class="container px-0 px-sm-3">
					<div class="d-flex flex-row align-items-center">
						<a href="<?php echo get_site_url(); ?>">
							<?php if ( is_home() || $post->post_type === 'issue' ) : ?>
							<h1 class="header-logo">
								<span class="sr-only">Pegasus</span>
							</h1>
							<?php else: ?>
							<span class="header-logo">
								<span class="sr-only">Pegasus</span>
							</span>
							<?php endif; ?>
						</a>

						<a class="font-serif small text-secondary text-decoration-none hover-text-underline d-inline-block hidden-sm-down ml-3 mt-1" href="<?php echo get_permalink( get_page_by_title( 'About the Magazine' ) ); ?>">
							The Magazine of the University of Central Florida
						</a>
					</div>
					<div class="mb-0 d-flex flex-row align-items-center">
						<button class="navbar-toggler collapsed nav-pulldown-toggle hidden-md-up" type="button" data-toggle="collapse" data-target="#pulldown" aria-controls="pulldown" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
						</button>
						<button class="navbar-link nav-pulldown-toggle nav-issue btn btn-link p-0 mr-3 hidden-sm-down" data-toggle="collapse" aria-expanded="false" aria-controls="pulldown" data-target="#pulldown">
							<span class="navbar-link-icon" aria-hidden="true"></span>
							<span class="navbar-link-text">In This Issue</span>
						</button>
						<a class="navbar-link nav-archives p-0 hidden-sm-down" href="<?php echo get_permalink( get_page_by_title( 'Archives' ) ); ?>">
							<span class="navbar-link-icon" aria-hidden="true"></span>
							<span class="navbar-link-text">Archives</span>
						</a>
					</div>
				</div>
			</nav>
		</header>

		<main id="content">
