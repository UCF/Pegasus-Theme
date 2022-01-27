<?php
/**
 * Functions related to the UCF Events Plugin
 * Ported over from Today-Child-Theme
 */

/**
 * Registers the custom "modern_date" layout
 *
 * @since 1.2.0
 * @author Jo Dickson
 * @param array $layouts Existing registered events layouts
 * @return array Modified list of events layouts
 */
function pegasus_events_get_layouts( $layouts ) {
	$layouts['modern_date'] = 'Modern Layout, with block dates';
	return $layouts;
}

add_filter( 'ucf_events_get_layouts', 'pegasus_events_get_layouts', 10, 1 );


/**
 * "modern_date" layout definitions
 */

// Modern Date - Before
function pegasus_events_display_modern_date_before( $content, $items, $args, $display_type ) {
	ob_start();
?>
	<div class="ucf-events ucf-events-modern-date">
<?php
	return ob_get_clean();
}

add_filter( 'ucf_events_display_modern_date_before', 'pegasus_events_display_modern_date_before', 10, 4 );


// Modern Date - main loop
function pegasus_events_display_modern_date( $content, $items, $args, $display_type, $fallback_message='' ) {
	if ( $items && ! is_array( $items ) ) $items = array( $items );
	ob_start();
?>
	<div class="ucf-events-list">

	<?php if ( $items ): ?>
		<?php
		foreach ( $items as $i => $event ) :
			$starts  = new DateTime( $event->starts );
		?>
		<div class="ucf-event ucf-event-row">
			<div class="ucf-event-when">
				<time class="ucf-event-start-datetime" datetime="<?php echo $starts->format( 'c' ); ?>">
					<span class="ucf-event-start-day"><?php echo $starts->format( 'D' ); ?></span>
					<span class="ucf-event-start-date"><?php echo $starts->format( 'j' ); ?></span>
					<span class="ucf-event-start-month"><?php echo $starts->format( 'M' ); ?></span>
				</time>
			</div>
			<div class="ucf-event-details">
				<div class="ucf-event-category badge badge-primary">
					<?php echo $event->category; ?>
				</div>
				<div class="ucf-event-title-wrapper">
					<a class="ucf-event-title" href="<?php echo $event->url; ?>">
						<?php echo $event->title; ?>
					</a>
				</div>
				<div class="ucf-event-description-wrapper">
					<?php echo wp_trim_words( $event->description, 40, '&hellip;' ); ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>

	<?php else: ?>
		<span class="ucf-events-error"><?php echo $fallback_message; ?></span>
	<?php endif; ?>

	</div>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_events_display_modern_date', 'pegasus_events_display_modern_date', 10, 5 );


// Modern Date - after
function pegasus_events_display_modern_date_after( $content, $items, $args, $display_type ) {
	ob_start();
?>
	</div>
<?php
	return ob_get_clean();
}

add_filter( 'ucf_events_display_modern_date_after', 'pegasus_events_display_modern_date_after', 10, 4 );
