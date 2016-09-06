<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php wp_head(); ?>
	</head>

	<body <?php echo body_class(); ?>>
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

		<header>
			<div class="container text-center">
				<div class="row hidden-xs">
					<div class="col-sm-12">
						<div class="social-share-links pull-right">
							TODO social sharing btns (desktop)
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 col-sm-2">
						<a class="fp-header-link" href="#CURRENT_ISSUE_URL_HERE">CURRENT ISSUE TITLE HERE</a>
					</div>
					<div class="col-xs-6 col-sm-2 col-sm-push-8">
						<a class="fp-header-link" href="<?php echo get_permalink( get_page_by_title( 'Archives' ) ); ?>">Archives</a>
					</div>
					<div class="clearfix visible-xs-block"></div>
					<div class="col-sm-8 col-sm-pull-2">
						<h1 class="fp-header-title">Pegasus</h1>
						<a class="fp-header-subtitle" href="<?php echo get_permalink( get_page_by_title( 'About the Magazine' ) ); ?>">The Magazine of the University of Central Florida</a>
					</div>
				</div>

				<div class="visible-xs-block social-share-links text-center">
					TODO social sharing btns (mobile)
				</div>
			</div>
		</header>

		<main>
