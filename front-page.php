<?php disallow_direct_load( 'front-page.php' ); ?>
<?php get_version_header( 'front' );

$events = get_home_events();
$featured_gallery = get_home_gallery( get_theme_option( 'front_page_featured_gallery_1' ) );
$twitter_url = get_theme_option( 'twitter_url' );
?>

<div class="container">
	<?php if ( $feature_1 = get_theme_option( 'front_page_featured_story_1' ) ): ?>
		<?php
		$feature_1_post = get_post( $feature_1 );
		echo display_story_callout( $feature_1_post, 'story-callout-overlay', false, 'full' );
		?>
	<?php endif; ?>

	<div class="row justify-content-center hidden-md-up">
		<div class="col-10">
			<hr class="hr-primary hr-2 w-75 mt-0 mb-4">
		</div>
	</div>

	<div class="row justify-content-center">
		<?php if ( $feature_2 = get_theme_option( 'front_page_featured_story_2' ) ): ?>
		<div class="col-8 col-sm-6 col-md-3">
			<?php echo display_story_callout( get_post( $feature_2 ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $feature_3 = get_theme_option( 'front_page_featured_story_3' ) ): ?>
		<div class="col-8 col-sm-6 col-md-3">
			<?php echo display_story_callout( get_post( $feature_3 ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $feature_4 = get_theme_option( 'front_page_featured_story_4' ) ): ?>
		<div class="col-8 col-sm-6 col-md-3">
			<?php echo display_story_callout( get_post( $feature_4 ) ); ?>
		</div>
		<?php endif; ?>

		<?php if ( $feature_5 = get_theme_option( 'front_page_featured_story_5' ) ): ?>
		<div class="col-8 col-sm-6 col-md-3">
			<?php echo display_story_callout( get_post( $feature_5 ) ); ?>
		</div>
		<?php endif; ?>
	</div>

	<div class="row justify-content-center hidden-sm-up">
		<div class="col-10">
			<hr class="hr-primary hr-2 w-75">
		</div>
	</div>

	<div class="row align-items-start mb-4 mb-md-5">
		<div class="col d-md-flex flex-md-column mb-5 mb-md-0 <?php if ( $twitter_url ) {?>pt-md-4<?php } ?>">
			<div class="d-md-flex align-items-center justify-content-between mb-4">
				<h2 class="font-weight-black mb-0 ml-sm-2">The Feed<span class="fa fa-caret-right text-primary ml-2" aria-hidden="true"></span></h2>
				<a class="text-default-aw text-uppercase font-size-sm font-weight-bold" href="https://www.ucf.edu/news/">Check out more stories at <span class="text-secondary">UCFToday</span> <span class="fas fa-share-square text-primary" aria-hidden="true"></span></a>
			</div>
			<aside class="fp-today-feed">
				<?php
				$articles = get_news( 0, 10, get_theme_option( 'front_page_today_feed_url' ) );

				if ( $articles ):
				?>
				<div class="fp-today-feed-wrap">
					<div class="fp-today-feed-col">
						<?php echo display_front_page_today_story( $articles[0] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[5] ); ?>
						<hr class="fp-divider fp-today-feed-divider hidden-md-up">
					</div>
					<div class="fp-today-feed-col">
						<?php echo display_front_page_today_story( $articles[1] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[6] ); ?>
					</div>
					<div class="fp-today-feed-col hidden-md-down">
						<?php echo display_front_page_today_story( $articles[2] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[7] ); ?>
					</div>
					<div class="fp-today-feed-col hidden-md-down">
						<?php echo display_front_page_today_story( $articles[3] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[8] ); ?>
					</div>
					<div class="fp-today-feed-col hidden-lg-down">
						<?php echo display_front_page_today_story( $articles[4] ); ?>
						<hr class="fp-divider fp-today-feed-divider">
						<?php echo display_front_page_today_story( $articles[9] ); ?>
					</div>
				</div>
				<?php else: ?>
				News articles could not be loaded. Please try again later.
				<?php endif; ?>
			</aside>
		</div>
		<?php if ( $twitter_url ) : ?>
		<div class="col-md-6 col-lg-4">
			<div class="card border-0 bg-faded h-100">
				<div class="card-block px-4 pt-4 pb-2">
					<h2 class="h6 heading-underline letter-spacing-2 mb-4">What's Trending</h2>
					<a class="twitter-timeline"
						href="<?php echo $twitter_url; ?>"
						height="428"
						data-chrome="nofooter noborders">
						Tweets by @UCF
					</a>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>

	<div class="row fp-issue-story justify-content-center">
		<div class="col-10 col-md-3 text-center fp-issue-wrapper">
			<?php echo display_front_page_issue_details(); ?>
		</div>
		<div class="col-md-9">
			<div class="row justify-content-center justify-content-sm-start">
				<?php
				$current_issue_stories = get_front_page_issue_stories();
				if ( $current_issue_stories ):
				?>
					<?php $i = 1; ?>
					<?php foreach ( $current_issue_stories as $issue_story ): ?>
						<div class="col-8 col-sm-6 col-md-4">
							<?php echo display_story_callout( $issue_story, '', true ); ?>
						</div>

						<?php if ( $i !== count( $current_issue_stories ) && $i % 3 === 0 ): ?>
							<div class="clearfix hidden-sm-down"></div>
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
		<div class="col-lg pt-lg-4 mb-5 mb-lg-0 <?php if ( $featured_gallery ) { ?>pr-lg-5<?php } ?>">
			<h2 class="font-weight-black">Events</h2>
			<hr role="presentation">
			<?php echo $events; ?>
		</div>

		<?php if ( $featured_gallery ) : ?>
		<div class="col-lg">
			<?php echo $featured_gallery; ?>
		</div>
		<?php endif; ?>
	</div>
</div>

<?php get_version_footer( 'front' ); ?>
