<?php disallow_direct_load( 'front-page.php' ); ?>

<?php
if ( 'page' !== get_option( 'show_on_front' ) ):
	get_version_front_page();
else:
?>

<?php get_version_header( 'front' ); ?>

<div class="container">
	<?php if ( $feature_1 = get_theme_option( 'front_page_featured_story_1' ) ): ?>
	<?php echo display_front_page_story( get_post( $feature_1 ), 'fp-feature-top', false, 'full' ); ?>
	<?php endif; ?>

	<div class="row">
		<?php if ( $feature_2 = get_theme_option( 'front_page_featured_story_2' ) ): ?>
		<div class="col-sm-3">
			<?php echo display_front_page_story( get_post( $feature_2 ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $feature_3 = get_theme_option( 'front_page_featured_story_3' ) ): ?>
		<div class="col-sm-3">
			<?php echo display_front_page_story( get_post( $feature_3 ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $feature_4 = get_theme_option( 'front_page_featured_story_4' ) ): ?>
		<div class="col-sm-3">
			<?php echo display_front_page_story( get_post( $feature_4 ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $feature_5 = get_theme_option( 'front_page_featured_story_5' ) ): ?>
		<div class="col-sm-3">
			<?php echo display_front_page_story( get_post( $feature_5 ) ); ?>
		</div>
		<?php endif; ?>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<h2 class="fp-heading"><a href="https://today.ucf.edu/">The Feed <span class="fa fa-caret-right"></span></a></h2>
			<a href="https://today.ucf.edu/">Check out more stories at UCFToday <span class="fa fa-share-square-o"></span></a>
			TODO Today stories
		</div>
		<div class="col-sm-4 hidden-xs">
			TODO trending section
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3">
			TODO this issue thumb + details
		</div>
		<div class="col-sm-9">
			TODO in this issue
		</div>
	</div>

	TODO ad/whatever here

	<div class="row">
		<div class="col-sm-6">
			<h2 class="fp-heading">Events</h2>
			TODO events
		</div>

		<?php if ( $gallery_1 = get_theme_option( 'front_page_featured_gallery_1' ) ): ?>
		<div class="col-sm-6">
			<?php echo display_front_page_gallery( get_post( $gallery_1 ) ); ?>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php get_version_footer( 'front' ); ?>

<?php endif; ?>
