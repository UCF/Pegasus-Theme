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
			TODO
		</header>

		<main>
