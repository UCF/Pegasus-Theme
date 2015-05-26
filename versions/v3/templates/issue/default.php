<?php disallow_direct_load('default.php');?>
<?php add_filter('the_content', 'kill_empty_p_tags', 999); ?>
<?php
	$fallback_featured_stories = get_issue_stories($post, array('numberposts'=>3));
	$story_1_id = intval(get_post_meta($post->ID, 'issue_story_1', TRUE));
	$story_2_id = intval(get_post_meta($post->ID, 'issue_story_2', TRUE));
	$story_3_id = intval(get_post_meta($post->ID, 'issue_story_3', TRUE));
	$featured_stories = array($story_1_id, $story_2_id, $story_3_id);
	$past_issues = get_posts(array(
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

	// Make sure story 1, 2, and 3 IDs are set.  If not, grab the first posts
	// that appear in $fallback_featured_stories and set them as the featured stories.
	// NOTE: IDs set previously are replaced with post objects here.
	$count = 0;
	if ( !empty( $featured_stories ) ) {
		foreach ($featured_stories as $key=>$val) {
			if ( $val !== 0 ) {
				$story = get_post($val);
				$story->thumb = wp_get_attachment_url(get_post_meta($story->ID, 'story_issue_featured_thumb', TRUE), 'issue-cover-feature');
				$featured_stories[$key] = $story;
			}
			else {
				$featured_stories[$key] = null;
			}
		}
	}
	else {
		for ( $i = 0; $i < 3; $i++ ) {
			if ( isset( $fallback_featured_stories[$i] ) ) {
				$story = $fallback_featured_stories[$i];
				$story->thumb = wp_get_attachment_url( get_post_meta( $story->ID, 'story_issue_featured_thumb', TRUE ), 'issue-cover-feature' );
				$featured_stories[$i] = $story;
			}
			else {
				$featured_stories[$i] = null;
			}
		}
	}

	$story_1 = $featured_stories[0];
	$story_2 = $featured_stories[1];
	$story_3 = $featured_stories[2];
	$story_1_id = $story_1 ? $story_1->ID : 0;
	$story_2_id = $story_2 ? $story_2->ID : 0;
	$story_3_id = $story_3 ? $story_3->ID : 0;

	$other_stories = get_issue_stories( $post, array( 'exclude' => array( $story_1_id, $story_2_id, $story_3_id ) ) );

	// Number of story/issue thumbnails per 'faux' row.
	$per_row_m = 5;   // 980px+
	$per_row_s = 5;   // 768-979px
	$per_row_xs = 3;  // 481-767px
	$per_row_xss = 2; // >480px
?>

<div class="container-wide" id="home">
	<section class="container home-hero">
		<div class="row">
			<div class="col-md-8 col-sm-8 col-xs-12">
				<article>
					<div class="home-article-1 thumb">
					<?php if ( $story_1 ): ?>
						<a href="<?php echo get_permalink( $story_1->ID ); ?>"></a>
						<?php if ( $story_1->thumb ) { ?>
						<img class="img-responsive" src="<?php echo $story_1->thumb; ?>" alt="<?php echo $story_1->post_title; ?>" title="<?php echo $story_1->post_title; ?>" />
						<?php } ?>
						<div class="description">
							<h2><?php echo $story_1->post_title; ?></h2>
						</div>
					<?php endif; ?>
					</div>
				</article>
			</div>
			<div class="col-md-4 col-sm-4 col-xs-12">
				<article>
					<div class="home-article-2 thumb featured">
					<?php if ( $story_2 ): ?>
						<a href="<?php echo get_permalink( $story_2->ID ); ?>"></a>
						<?php if ( $story_2->thumb ) { ?>
						<img class="img-responsive" src="<?php echo $story_2->thumb; ?>" alt="<?php echo $story_2->post_title; ?>" title="<?php echo $story_2->post_title; ?>" />
						<?php } ?>
						<div class="description">
							<h2><?php echo $story_2->post_title; ?></h2>
						</div>
					<?php endif; ?>
					</div>
				</article>
				<article>
					<div class="home-article-3 thumb featured">
					<?php if ( $story_3 ): ?>
						<a href="<?php echo get_permalink( $story_3->ID ); ?>"></a>
						<?php if ( $story_3->thumb ) { ?>
						<img class="img-responsive" src="<?php echo $story_3->thumb; ?>" alt="<?php echo $story_3->post_title; ?>" title="<?php echo $story_3->post_title; ?>" />
						<?php } ?>
						<div class="description">
							<h2><?php echo $story_3->post_title; ?></h2>
						</div>
					<?php endif; ?>
					</div>
				</article>
			</div>
		</div>
	</section>
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
			foreach ($other_stories as $story):
				$classes = '';
				if ($count % $per_row_m == 0) { $classes .= ' thumb-1-m'; }
				if ($count % $per_row_s == 0) { $classes .= ' thumb-1-s'; }
				if ($count % $per_row_xs == 0) { $classes .= ' thumb-1-xs'; }
				if ($count % $per_row_xss == 0) { $classes .= ' thumb-1-xss'; }
			?>
			<article class="thumb-wrapper col-md-20percent col-sm-20percent col-xs-4 <?php // echo $classes; ?>">
				<div class="thumb">
					<a href="<?php echo get_permalink( $story->ID ); ?>"></a>
					<img src="<?php echo get_featured_image_url( $story->ID ); ?>" alt="<?php echo $story->post_title; ?>" title="<?php echo $story->post_title; ?>" />
					<div class="description">
						<h3><?php echo $story->post_title; ?></h3>
					</div>
				</div>
			</article>
			<?php
				$count++;
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
			foreach ($past_issues as $issue):
				$classes = '';
				if ($count % $per_row_m == 0) { $classes .= ' thumb-1-m'; }
				if ($count % $per_row_s == 0) { $classes .= ' thumb-1-s'; }
				if ($count % $per_row_xs == 0) { $classes .= ' thumb-1-xs'; }
				if ($count % $per_row_xss == 0) { $classes .= ' thumb-1-xss'; }
			?>
			<div class="thumb-wrapper col-md-20percent col-sm-20percent col-xs-4 <?php // echo $classes; ?>">
				<div class="thumb">
					<a href="<?php echo get_permalink( $issue->ID ); ?>"><img src="<?php echo get_featured_image_url( $issue->ID, 'issue-thumbnail' ); ?>" alt="<?php echo $issue->post_title; ?>" title="<?php echo $issue->post_title; ?>" /></a>
				</div>
			</div>
			<?php
				$count++;
			endforeach;
		endif;
		?>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<span class="pull-right archives-link">
					<a href="<?php echo get_permalink( get_page_by_title('Archives') ); ?>">View Archives &raquo;</a>
				</span>
			</div>
		</div>
	</section>
</div>
