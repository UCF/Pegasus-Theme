<?php
// Feed functions that are included for versions 5 and below.

/* Modified for main site theme (for JSON instead of RSS feed): */
function display_events( $start = null, $limit = null ) {
?>
	<?php 	$options = get_option( THEME_OPTIONS_NAME );
	$qstring = (bool) strpos( $options['events_url'], '?' );
	$url     = $options['events_url'];
	if ( !$qstring ) {
		$url .= '?';
	} else {
		$url .= '&';
	}
	$start = ( $start ) ? $start : 0;
	// Check for a given limit, then a set Options value, then if none exist, set to 4
	if ( $limit ) {
		$limit = intval( $limit );
	} elseif ( $options['events_max_items'] ) {
		$limit = $options['events_max_items'];
	} else {
		$limit = 4;
	}
	$events = get_events( $start, $limit );

	if ( $events !== NULL && count( $events ) ): ?>
		<table class="events table">
			<thead>
				<td>Date</td>
				<td>Description</td>
			</thead>
			<tbody class="vcalendar">
				<?php 		foreach ( $events as $item ):
			$start    = new DateTime( $item['starts'] );
			$day      = $start->format( 'M d' );
			$time     = $start->format( 'h:i a' );
			$link     = $item['url'];
			$loc_link = $item['location_url'];
			$location = $item['location'];
			$title    = $item['title'];
		?>
				<tr class="item vevent">
					<td class="date">
						<div class="day"><?php echo $day ?></div>
						<div class="dtstart">
							<abbr class="dtstart" title="<?php echo $start->format( 'c' ) ?>"><?php echo $time ?></abbr>
						</div>
					</td>
					<td class="eventdata">
						<div class="summary"><a href="<?php echo $link ?>" class="wrap url"><?php echo $title ?></a></div>
						<div class="location">
							<?php if ( $loc_link ) { ?><a href="<?php echo $loc_link ?>" class="wrap"><?php } ?><?php echo $location ?><?php if ($loc_link) { ?></a><?php } ?>
						</div>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php else: ?>
		<p>Events could not be retrieved at this time.  Please try again later.</p>
	<?php endif; ?>
<?php }

/* Modified function for main site theme: */
function get_events( $start=0, $limit=4, $url='' ) {
	$options = get_option( THEME_OPTIONS_NAME );
	$url = $url ?: $options['events_url'];

	// Remove any query strings attached to the url provided.
	$qstring = ( bool )strpos( $url, '?' );
	if ( $qstring ) {
		$url_parts = explode( '?', $url );
		$url = $url_parts[0];
	}

	if ( substr( $url, -9 ) !== 'feed.json' ) {
		// Append trailing end slash to url.
		if ( substr( $url, -1 ) !== '/' ) {
			$url .= '/';
		}

		// Append /upcoming/ to the end of the url, if it's not already present.
		if ( substr( $url, -9 ) !== 'upcoming/' ) {
			$url .= 'upcoming/';
		}

		// Append /feed.json to the end of the url.
		$url .= 'feed.json';
	}

	// Grab the feed
	$raw_events = fetch_with_timeout( $url );
	if ( $raw_events ) {
		$events = json_decode( $raw_events, TRUE );
		$events = array_slice( $events, $start, $limit );
		return $events;
	} else {
		return NULL;
	}
}
