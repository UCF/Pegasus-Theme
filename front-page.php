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

	<hr class="fp-divider visible-xs-block">

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

	<hr class="fp-divider visible-xs-block">

	<div class="row">
		<div class="col-sm-7 col-md-8">
			<aside class="fp-today-feed">
				<a href="https://today.ucf.edu/">
					<h2 class="fp-heading fp-today-heading">The Feed <span class="fa fa-caret-right ucf-gold"></span></h2>
					<span class="fp-today-feed-more hidden-xs">Check out more stories at UCFToday <span class="fa fa-share-square-o ucf-gold"></span></span>
				</a>
				<!-- TODO replace with function output, grabbing actual Today stories (use display_front_page_today_story() for each story) -->
				<?php
				$articles = range(0,9); // replace value with get_news() or something, returning 10 feed items
				?>
				<div class="fp-today-feed-wrap">
					<div class="fp-today-feed-col">
						<?php echo display_front_page_today_story( $articles[0] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[1] ); ?>
						<hr class="fp-divider fp-today-feed-divider visible-xs-block">
					</div>
					<div class="fp-today-feed-col">
						<?php echo display_front_page_today_story( $articles[2] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[3] ); ?>
					</div>
					<div class="fp-today-feed-col hidden-xs hidden-sm">
						<?php echo display_front_page_today_story( $articles[4] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[5] ); ?>
					</div>
					<div class="fp-today-feed-col hidden-xs hidden-sm">
						<?php echo display_front_page_today_story( $articles[6] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[7] ); ?>
					</div>
					<div class="fp-today-feed-col hidden-xs hidden-sm hidden-md">
						<?php echo display_front_page_today_story( $articles[8] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[9] ); ?>
					</div>
				</div>
				<!-- /TODO -->
			</aside>
		</div>
		<div class="col-sm-5 col-md-4 hidden-xs">
			<aside class="fp-trending-feed">
				<h2 class="fp-subheading-underline fp-trending-heading">What&rsquo;s Trending</h2>
				<div style="background-color: #fff; border: 1px solid #ddd; padding: 15px; height: 300px;">
					TODO replace this div with a generic Twitter widget, probably
				</div>
			</aside>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3 text-center">
			<?php
			$current_issue = get_current_issue();
			$current_issue_title = wptexturize( $current_issue->post_title );
			$current_issue_thumbnail = get_featured_image_url( $current_issue->ID, 'full' );
			$current_issue_description = get_post_meta( $current_issue->ID, 'issue_description', true ); // TODO update with new description meta name
			$current_issue_cover_story = get_post_meta( $current_issue->ID, 'issue_cover_story', true );
			?>
			<a class="fp-issue-link" href="<?php echo get_permalink( $current_issue->ID ); ?>">
				<h2 class="h3 fp-subheading fp-issue-title">In This Issue</h2>

				<?php if ( $current_issue_thumbnail ): ?>
				<img class="img-responsive center-block fp-issue-img" src="<?php echo $current_issue_thumbnail; ?>" alt="<?php echo $current_issue_title; ?>" title="<?php echo $current_issue_title; ?>">
				<?php endif; ?>
			</a>

			<!-- TODO remove after desc. meta field is added -->
			<div class="fp-issue-description center-block text-left">
				<strong>The Space Issue</strong> is a look at how UCF, one of America’s first “space universities” since 1963, have focused to investigate our cosmos.
			</div>
			<!-- /TODO -->

			<?php if ( $current_issue_description ): ?>
			<div class="fp-issue-description text-left">
				<?php echo wptexturize( $current_issue_description ); ?>
			</div>
			<?php endif; ?>

			<hr class="fp-divider fp-divider-short">
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
