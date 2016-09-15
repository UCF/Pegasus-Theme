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
					<span class="fp-today-feed-more pull-right hidden-xs hidden-sm">Check out more stories at <span class="ucf-today">UCFToday</span> <span class="fa fa-share-square-o ucf-gold"></span></span>
					<h2 class="fp-heading fp-today-heading">The Feed <span class="fa fa-caret-right ucf-gold"></span></h2>
				</a>
				<?php
				$articles = get_news( 0, 10, get_theme_option( 'front_page_today_feed_url' ) );

				if ( $articles ):
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
				<?php else: ?>
				News articles could not be loaded. Please try again later.
				<?php endif; ?>
			</aside>
		</div>
		<?php
		if ( $twitter_url = get_theme_option( 'twitter_url' ) ):
		?>
			<div class="col-sm-5 col-md-4 hidden-xs">
				<aside class="fp-trending-feed">
					<h2 class="fp-subheading-underline fp-trending-heading">What&rsquo;s Trending</h2>
					<div class="twitter-widget">
						<a class="twitter-timeline"
							href="<?php echo $twitter_url; ?>"
							height="428"
							data-chrome="nofooter noborders">
							Tweets by @UCF
						</a>
					</div>
				</aside>
			</div>
		<?php endif; ?>
	</div>

	<div class="row">
		<div class="col-sm-3 text-center">
			<?php echo display_front_page_issue_details(); ?>
			<hr class="fp-divider fp-divider-short">
		</div>
		<div class="col-sm-9">
			<div class="row">
				<?php
				$current_issue_stories = get_front_page_issue_stories();

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

	<?php if ( $banner_ad = get_theme_option( 'front_page_ad_contents' ) ): ?>
	<div class="fp-banner">
		<?php echo wptexturize( do_shortcode( $banner_ad ) ); ?>
	</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-sm-6">
			<h2 class="fp-heading fp-events-heading">Events</h2>
			<div class="fp-events">
				<?php
				$events = get_events( 0, 3, get_theme_option( 'front_page_events_feed_url' ) );
				if ( $events ) {
					foreach ( $events as $event ) {
						echo display_front_page_event( $event );
					}
				}
				else {
					echo 'Events could not be loaded. Please try again later.';
				}
				?>
			</div>
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
