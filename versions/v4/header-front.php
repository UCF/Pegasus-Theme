<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>

	<body <?php echo body_class( 'front-page' ); ?>>
		<header class="front-page-header">
			<div class="container">
				<div class="row hidden-xs">
					<div class="col-sm-12">
						<div class="fp-social-links pull-right">
							<?php echo display_social_header(); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 col-sm-3 col-md-2 text-center">
						<?php 						$current_issue = get_current_issue();
						$current_issue_title = wptexturize( $current_issue->post_title );
						?>
						<a class="fp-header-link fp-header-link-issue hidden-xs" href="<?php echo get_permalink( $current_issue->ID ); ?>">
							<?php echo str_replace(' ', '<br>', $current_issue_title); ?>
						</a>
						<a class="fp-header-link fp-header-link-issue visible-xs-block" href="<?php echo get_permalink( $current_issue->ID ); ?>">
							<?php echo $current_issue_title; ?>
						</a>
					</div>
					<div class="col-xs-6 col-sm-3 col-sm-push-6 col-md-2 col-md-push-8 text-center">
						<a class="fp-header-link fp-header-link-archives" href="<?php echo get_permalink( get_page_by_title( 'Archives' ) ); ?>">Archives</a>
					</div>
					<div class="clearfix visible-xs-block"></div>
					<div class="col-sm-6 col-sm-pull-3 col-md-8 col-md-pull-2 text-center">
						<h1 class="fp-header-title">
							<img class="img-responsive fp-header-title-img" src="<?php echo THE_POST_VERSION_URL; ?>/static/img/pegasus-gray.png" srcset="<?php echo THE_POST_VERSION_URL; ?>/static/img/pegasus-gray.png 384w, <?php echo THE_POST_VERSION_URL; ?>/static/img/pegasus-gray-r.png 769w" alt="Pegasus" title="Pegasus">
						</h1>
						<a class="fp-header-subtitle" href="<?php echo get_permalink( get_page_by_title( 'About the Magazine' ) ); ?>">
							<span class="fp-header-subtitle-pre">The Magazine of </span>the University of Central Florida
						</a>
					</div>
				</div>

				<div class="visible-xs-block text-center">
					<div class="fp-social-links">
						<?php echo display_social_header(); ?>
					</div>
				</div>

				<div class="hidden-xs fp-header-border"></div>
			</div>
		</header>

		<main class="front-page-main">
