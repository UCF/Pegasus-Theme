<?php
array_push( Config::$styles,
	array('name' => 'style-fa', 'src' => THE_POST_VERSION_URL . '/static/css/font-awesome-5.min.css'),
	array('name' => 'style-css', 'src' => THE_POST_VERSION_URL . '/static/css/style.min.css')
);


array_push( Config::$scripts,
	array('name' => 'inview', 'src' => THEME_COMPONENTS_URL . '/inview.js',),
	array('name' => 'lazyload', 'src' => THEME_COMPONENTS_URL . '/jquery.lazyload.min.js',),
	array('name' => 'tether', 'src' => 'https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.7/js/tether.min.js',),
	array('name' => 'version-script', 'src' => THE_POST_VERSION_URL . '/static/js/script.min.js',)
);


/**
 * Register extra frontend scripts and stylesheets.
 **/
function v6_enqueue_frontend_theme_assets() {
	// Re-register jquery in document head
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', '//code.jquery.com/jquery-3.6.0.min.js' );
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'v6_enqueue_frontend_theme_assets' );


/**
 * Hook frontend theme script output into wp_head().
 **/
function v6_hook_frontend_theme_scripts() {
	ob_start();
?>
	<!--[if lte IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php if( GA_ACCOUNT or CB_UID ): ?>

	<script type="text/javascript">
		var _sf_startpt = (new Date()).getTime();
		<?php if( GA_ACCOUNT ): ?>

		var GA_ACCOUNT = '<?php echo GA_ACCOUNT; ?>';
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', GA_ACCOUNT]);
		_gaq.push(['_setDomainName', 'none']);
		_gaq.push(['_setAllowLinker', true]);
		_gaq.push(['_trackPageview']);
		<?php endif; ?>
		<?php if( CB_UID ): ?>

		var CB_UID = '<?php echo CB_UID; ?>';
		var CB_DOMAIN = '<?php echo CB_DOMAIN; ?>';
		<?php endif; ?>
	</script>

	<?php endif; ?>

	<script type="text/javascript">
		var PROTOCOL = '<?php echo is_ssl() ? 'https://' : 'http://'; ?>';
		var THEME_COMPONENTS_URL = PROTOCOL + '<?php echo str_replace( array( 'http://', 'https://'), '', THEME_COMPONENTS_URL ); ?>';

		var PostTypeSearchDataManager = {
			'searches' : [],
			'register' : function(search) {
				this.searches.push(search);
			}
		}
		var PostTypeSearchData = function(column_count, column_width, data) {
			this.column_count = column_count;
			this.column_width = column_width;
			this.data = data;
		}
	</script>

	<?php 	global $post;
	echo output_header_markup( $post );
	?>
<?php 	echo ob_get_clean();
}
add_action( 'wp_head', 'v6_hook_frontend_theme_scripts' );


/**
 * Add ID attribute to registered University Header script.
 **/
function add_id_to_ucfhb($url) {
	if ( (false !== strpos($url, 'bar/js/university-header.js')) || (false !== strpos($url, 'bar/js/university-header-full.js')) ) {
		remove_filter('clean_url', 'add_id_to_ucfhb', 10, 3);
		return "$url' id='ucfhb-script";
	}
	return $url;
}
add_filter('clean_url', 'add_id_to_ucfhb', 10, 3);


/**
 * Define custom body classes for page/post content.
 * @since 6.0.0
 * @author Jo Dickson
 * @param string[] $classes An array of body class names.
 * @return string[] A modified array of body class names.
 */
function v6_body_classes( $classes ) {
	global $post;

	if ( ( $post && $post->post_type === 'page' ) || is_404() || is_search() ) {
		$classes[] = 'subpage';
	}
	if ( $post && $post->post_type === 'page' ) {
		$classes[] = "page-$post->post_name";
	}

	return $classes;
}

add_filter( 'body_class', 'v6_body_classes', 10, 1 );


/**
 * Force enable/disable various content/WYSIWYG formatting options
 * provided by the Athena Shortcode plugin.
 *
 * @since 6.0.0
 * @author Jo Dickson
 */
add_filter( 'option_athena_sc_enable_tinymce_formatting', '__return_true' );
add_filter( 'option_athena_sc_enable_optin_classes', '__return_false' );
add_filter( 'option_athena_sc_enable_responsive_embeds', '__return_true' );
add_filter( 'option_athena_sc_remove_image_dims', '__return_false' );


/**
 * Enqueue admin editor styles.
 * Adapted from 'today_enqueue_admin_assets()'
 * in the Today Child Theme.
 *
 * @since 6.0.0
 * @author Cadie Stockman
 */
function pegasus_enqueue_admin_editor_styles() {
	// get_current_screen() returns null on this hook,
	// so sniff the request URI instead when is_admin() is true
	if ( is_admin() ) {

		// If debug mode is enabled, force editor stylesheets to
		// reload on every page refresh.  Caching of these stylesheets
		// is very aggressive
		$cache_bust = '';
		if ( WP_DEBUG === true ) {
			$cache_bust = date( 'YmdHis' );
		}
		else {
			$theme = wp_get_theme();
			$cache_bust = $theme->get( 'Version' );
		}

		// Enqueue assets on New Post screen
		if ( stristr( $_SERVER['REQUEST_URI'], 'post-new.php' ) !== false ) {
			// Enqueue story-specific editor styles
			if ( ! isset( $_GET['post_type'] ) ) {
				add_editor_style( THE_POST_VERSION_URL . '/static/css/editor-story.min.css?v=' . $cache_bust );
			}
		}
		// Enqueue assets on Edit Post screen
		else if ( stristr( $_SERVER['REQUEST_URI'], 'post.php' ) !== false ) {
			// Enqueue story-specific editor styles
			global $post;
			if ( is_object( $post ) && get_post_type( $post->ID ) === 'story' ) {
				add_editor_style( THE_POST_VERSION_URL . '/static/css/editor-story.min.css?v=' . $cache_bust );
			}
		}

	}
}

add_action( 'init', 'pegasus_enqueue_admin_editor_styles', 99 ); // Enqueue late to ensure styles are enqueued after Athena SC Plugin's styles
add_action( 'pre_get_posts', 'pegasus_enqueue_admin_editor_styles' ); // Also register on this hook for Edit Post view, so that $post is defined at the correct time
