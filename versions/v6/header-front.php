<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>

	<body <?php echo body_class( 'front-page' ); ?>>
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

		<header class="front-page-header">
			<div class="container mt-4 mt-lg-5 mb-5 mb-md-0">
				<div class="row">
					<div class="col-6 col-md-3 col-lg-2 text-md-center">
						<?php
						$current_issue = get_current_issue();
						$current_issue_title = wptexturize( $current_issue->post_title );
						?>
						<a class="fp-header-link fp-header-link-issue d-none d-md-block" href="<?php echo get_permalink( $current_issue->ID ); ?>">
							<?php echo str_replace(' ', '<br>', $current_issue_title); ?>
						</a>
						<a class="fp-header-link fp-header-link-issue d-md-none" href="<?php echo get_permalink( $current_issue->ID ); ?>">
							<?php echo $current_issue_title; ?>
						</a>
					</div>
					<div class="col-6 col-md-3 flex-md-last col-lg-2 text-right text-md-center">
						<a class="fp-header-link fp-header-link-archives" href="<?php echo get_permalink( get_page_by_title( 'Archives' ) ); ?>">Archives</a>
					</div>
					<div class="col-md-6 col-lg-8 text-center">
						<h1 class="fp-header-title">
							<img class="img-fluid fp-header-title-img" src="<?php echo THE_POST_VERSION_URL; ?>/static/img/pegasus-gray.png" srcset="<?php echo THE_POST_VERSION_URL; ?>/static/img/pegasus-gray.png 384w, <?php echo THE_POST_VERSION_URL; ?>/static/img/pegasus-gray-r.png 769w" alt="Pegasus" title="Pegasus">
						</h1>
						<a class="fp-header-subtitle" href="<?php echo get_permalink( get_page_by_title( 'About the Magazine' ) ); ?>">
							<span class="fp-header-subtitle-pre">The Magazine of </span>the University of Central Florida
						</a>
					</div>
				</div>

				<div class="d-none d-md-block fp-header-border"></div>
			</div>
		</header>

		<main class="front-page-main">
