<?php /**
 * Return the URL of a feed item's thumbnail, or the first image in the item's content
 * if a thumbnail doesn't exist.
 **/
function get_article_image( $article ) {
	$image = $article->get_enclosure();
	if ( $image ) {
		return ( $image->get_thumbnail() ) ? $image->get_thumbnail() : $image->get_link();
	} else {
		$matches = array();
		$found   = preg_match( '/<img[^>]+src=[\'\"]([^\'\"]+)[\'\"][^>]+>/i', $article->get_content(), $matches );
		if ( $found ) {
			return $matches[1];
		}
	}
	return null;
}

/**
 * Check to see if an external image exists (via curl.)
 * Alternative to getimagesize() that allows us to specify a timeout.
 * via http://stackoverflow.com/questions/1363925/check-whether-image-exists-on-remote-url
 *
 * @return bool
 **/
function check_remote_file( $url ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, $url ); // specify URL
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, FEED_FETCH_TIMEOUT ); // specify timeout
	curl_setopt( $ch, CURLOPT_NOBODY, 1 ); // don't download content
	curl_setopt( $ch, CURLOPT_FAILONERROR, 1 );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	if ( curl_exec( $ch ) !== FALSE ) {
		return true;
	}
	return false;
}

/**
 * Fetches an external url's contents with a timeout applied to the request.
 **/
function fetch_with_timeout( $url ) {
	// Set a timeout
	$opts = array(
		'http' => array(
			'method'  => 'GET',
			'timeout' => FEED_FETCH_TIMEOUT
		)
	);
	$context = stream_context_create( $opts );
	// Grab the file
	return wp_remote_retrieve_body( wp_remote_get( $url ) );
}


/**
 * Handles fetching and processing of feeds.  Currently uses SimplePie to parse
 * retrieved feeds, and automatically handles caching of content fetches.
 * Multiple calls to the same feed url will not result in multiple parsings, per
 * request as they are stored in memory for later use.
 **/
class FeedManager {
	static private
		$feeds = array(),
		$cache_length = 60; // 1 minute

	/**
	 * Provided a URL, will return an array representing the feed item for that
	 * URL.  A feed item contains the content, url, simplepie object, and failure
	 * status for the URL passed.  Handles caching of content requests.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static protected function __new_feed($url){
		require_once ABSPATH . '/wp-includes/class-simplepie.php';

		$simplepie = null;
		$failed    = False;
		$cache_key = 'feedmanager-' . md5( $url );
		$content   = get_site_transient( $cache_key );

		if ($content === False){
			$content = fetch_with_timeout( $url );
			if ($content === False || empty($content)){
				$failed  = True;
				$content = null;
				error_log( 'FeedManager failed to fetch data using url of ' . $url );
			} else {
				set_site_transient( $cache_key, $content, self::$cache_length );
			}
		}

		if ( $content ) {
			$simplepie = new SimplePie();
			$simplepie->set_raw_data( $content );
			$simplepie->set_timeout( FEED_FETCH_TIMEOUT ); // seconds
			$simplepie->init();
			$simplepie->handle_content_type();

			if ( $simplepie->error ) {
				error_log( $simplepie->error );
				$simplepie = null;
				$failed    = True;
			}
		} else {
			$failed = True;
		}

		return array(
			 'content' => $content,
			'url' => $url,
			'simplepie' => $simplepie,
			'failed' => $failed
		);
	}


	/**
	 * Returns all the items for a given feed defined by URL
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static protected function __get_items( $url ) {
		if ( !array_key_exists( $url, self::$feeds ) ) {
			self::$feeds[$url] = self::__new_feed( $url );
		}
		if ( !self::$feeds[$url]['failed'] ) {
			return self::$feeds[$url]['simplepie']->get_items();
		} else {
			return array();
		}
	}


	/**
	 * Retrieve the current cache expiration value.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	static public function get_cache_expiration() {
		return self::$cache_length;
	}


	/**
	 * Set the cache expiration length for all feeds from this manager.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	static public function set_cache_expiration( $expire ) {
		if ( is_number( $expire ) ) {
			self::$cache_length = (int) $expire;
		}
	}


	/**
	 * Returns all items from the feed defined by URL and limited by the start
	 * and limit arguments.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static public function get_items( $url, $start = null, $limit = null ) {
		if ( $start === null ) {
			$start = 0;
		}

		$items = self::__get_items( $url );
		$items = array_slice( $items, $start, $limit );
		return $items;
	}
}


function display_news() {
?>
	<?php 	$options = get_option( THEME_OPTIONS_NAME );
	$count   = $options['news_max_items'];
	$news    = get_news( 0, ( $count ) ? $count : 3 );
	if ( $news !== NULL && count( $news ) ):
?>
		<ul class="news">
			<?php foreach ( $news as $key => $item ):
			$image = get_article_image( $item );
			if ( !( $image ) ) {
				$image = 'https://today.ucf.edu/widget/thumbnail.png';
			} else {
				if ( preg_match( '/\.jpeg$/i', $image ) ) {
					$end_of_str_length = 5;
				} else {
					// assume .jpeg is the only potential 5-character file extension being used
					$end_of_str_length = 4;
				}
				// Grab Today's 66x66px thumbnails if they're available
				$image_small = substr( $image, 0, ( strlen( $image ) - $end_of_str_length ) ) . '-66x66' . substr( $image, ( strlen( $image ) - $end_of_str_length ) );
				$image       = check_remote_file( $image_small ) !== false ? $image_small : $image;
			}
			$first = ( $key == 0 );
?>
			<li class="item<?php if($first):?> first<?php else:?> not-first<?php endif;?>">
				<a class="image ignore-external" href="<?php echo $item->get_link() ?>">
					<?php if ( $image ): ?>
					<img class="print-only news-thumb" src="<?php echo $image ?>" />
					<div class="screen-only news-thumb" style="background-image:url('<?php echo $image ?>'); filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='<?php echo $image ?>',sizingMethod='scale');">Feed image for <?php echo $item->get_title() ?></div>
					<?php endif; ?>
				</a>
				<h3 class="title"><a href="<?php echo $item->get_link() ?>" class="ignore-external title"><?php echo $item->get_title() ?></a></h3>
				<div class="clearfix"></div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="clearfix"></div>
	<?php else: ?>
		<p>News items could not be retrieved at this time.  Please try again later.</p>
	<?php 	endif;
?>
<?php }


function get_news( $start=null, $limit=null, $url='' ){
	$options = get_option( THEME_OPTIONS_NAME );
	$url = $url ?: $options['news_url'];
	$news = FeedManager::get_items( $url, $start, $limit );
	return $news;
}

?>
