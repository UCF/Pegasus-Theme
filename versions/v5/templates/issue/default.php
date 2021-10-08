<?php disallow_direct_load( 'default.php' );?>
<?php add_filter( 'the_content', 'kill_empty_p_tags', 999 ); ?>

<?php
$story_1_id = intval( get_post_meta( $post->ID, 'issue_story_1', TRUE ) );
$story_2_id = intval( get_post_meta( $post->ID, 'issue_story_2', TRUE ) );
$story_3_id = intval( get_post_meta( $post->ID, 'issue_story_3', TRUE ) );
$past_issues = get_posts( array(
	'post_type' => 'issue',
	'numberposts' => 5,
	'order' => 'DESC',
	'exclude' => $post->ID,
	'date_query' => array(
		array(
			'before' => $post->post_date,
		),
	)
));

// Grab the three featured stories for this issue:
if ( $story_1_id ) {
	$story_1 = get_post( $story_1_id );
} else {
	$fallback_story = get_issue_stories( $post, array( 'numberposts' => 1 ) );
	if ( $fallback_story ) {
		$story_1 = $fallback_story[0];
		$story_1_id = $story_1->ID;
	}
}
$story_2 = $story_2_id ? get_post( $story_2_id ) : null;
$story_3 = $story_3_id ? get_post( $story_3_id ) : null;

// Fetch stories for "More in this Issue" in alphabetical order.
// Exclude the three featured stories:
$other_stories = get_issue_stories(
	$post,
	array(
		'exclude' => array(
			$story_1_id,
			$story_2_id,
			$story_3_id
		),
		'orderby' => 'title',
		'order'   => 'ASC'
	)
);

// Make sure the 2nd and 3rd featured stories are at
// the front of the $other_stories list:
array_unshift( $other_stories, $story_2, $story_3 );
?>

<div class="container-wide" id="home">
	<?php if ( $story_1 ) : ?>
	<section class="container home-hero">
		<?php
		echo display_front_page_story(
			$story_1,
			'fp-feature-top',
			false,
			'issue-cover-feature-3x2',
			'h2'
		);
		?>
	</section>
	<?php endif; ?>

	<section class="container home-stories">
		<div class="row">
			<div class="col-md-12 col-sm-12 heading-wrap">
				<h2><span>More in this Issue</span></h2>
			</div>
		</div>
		<div class="row">
		<?php
		$count = 0;
		if ( $other_stories ):
			foreach ( $other_stories as $story ):
		?>
			<div class="col-sm-6 col-md-3">
				<?php
				echo display_front_page_story( $story, '', true );
				$count++;
				?>
			</div>

			<?php if ( $count !== count( $other_stories ) && $count % 2 === 0 ): ?>
			<div class="clearfix hidden-xs hidden-md hidden-lg"></div>
			<?php endif; ?>

			<?php if ( $count !== count( $other_stories ) && $count % 4 === 0 ): ?>
			<div class="clearfix hidden-xs hidden-sm"></div>
			<?php endif; ?>
		<?php
			endforeach;
		endif;
		?>
		</div>
	</section>
	<section class="container home-past-issues">
		<div class="row">
			<div class="col-md-12 col-sm-12 heading-wrap">
				<h2><span>Recent Issues of Pegasus Magazine</span></h2>
			</div>
		</div>
		<div class="row">
		<?php
		$count = 0;
		$per_row = 5;
		if ( $past_issues ):
			foreach ( $past_issues as $issue ):
		?>
				<div class="col-md-20percent col-sm-20percent col-xs-4">
					<div class="past-issue">
						<a href="<?php echo get_permalink( $issue->ID ); ?>">
							<img class="past-issue-thumb" src="<?php echo get_featured_image_url( $issue->ID, 'issue-thumbnail' ); ?>" alt="" />
							<h3 class="past-issue-title"><?php echo wptexturize( $issue->post_title ); ?></h3>
						</a>
					</div>
				</div>
				<?php if ( $count === 2 ): ?>
				<div class="clearfix hidden-sm hidden-md hidden-lg"></div>
				<?php endif; ?>
		<?php
				$count++;
			endforeach;
		endif;
		?>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<span class="pull-right archives-link">
					<a href="<?php echo get_permalink( get_page_by_title( 'Archives' ) ); ?>">View Archives &raquo;</a>
				</span>
			</div>
		</div>
	</section>
</div>
