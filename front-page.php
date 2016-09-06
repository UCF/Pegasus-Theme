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
			<a href="https://today.ucf.edu/">
				<h2 class="fp-heading">The Feed <span class="fa fa-caret-right"></span></h2>
				<span class="">Check out more stories at UCFToday <span class="fa fa-share-square-o"></span></span>
			</a>
			TODO Today stories
		</div>
		<div class="col-sm-4 hidden-xs">
			TODO trending section
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3">
			<?php
			$current_issue = get_current_issue();
			$current_issue_title = wptexturize( $current_issue->post_title );
			$current_issue_thumbnail = get_featured_image_url( $current_issue->ID, 'full' );
			$current_issue_description = get_post_meta( $current_issue->ID, 'issue_description', true ); // TODO update with new description meta name
			$current_issue_cover_story = get_post_meta( $current_issue->ID, 'issue_cover_story', true );
			?>
			<a class="fp-issue-link" href="<?php echo get_permalink( $current_issue->ID ); ?>">
				<h2 class="h3 fp-subheading">In This Issue</h2>

				<?php if ( $current_issue_thumbnail ): ?>
				<img class="img-responsive fp-issue-img" src="<?php echo $current_issue_thumbnail; ?>" alt="<?php echo $current_issue_title; ?>" title="<?php echo $current_issue_title; ?>">
				<?php endif; ?>

				<?php if ( $current_issue_description ): ?>
				<div class="fp-issue-description">
					<?php echo wptexturize( $current_issue_description ); ?>
				</div>
				<?php endif; ?>
			</a>
		</div>
		<div class="col-sm-9">
			<div class="row">
				<?php
				$current_issue_stories = get_current_issue_stories( array( $current_issue_cover_story ), 12 );

				if ( $current_issue_stories ):
				?>
					<?php $i = 1; ?>
					<?php foreach ( $current_issue_stories as $issue_story ): ?>
						<div class="col-sm-4">
							<?php echo display_front_page_story( $issue_story, 'fp-issue-list-item', true ); ?>
						</div>

						<?php if ( $i !== count( $current_issue_stories ) && $i % 3 === 0 ): ?>
							<div class="clearfix hidden-xs"></div>
						<?php endif; ?>

						<?php $i++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php
	if ( $banner_ad = get_theme_option( 'front_page_ad_contents' ) ) {
		echo wptexturize( do_shortcode( $banner_ad ) );
	}
	?>

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
