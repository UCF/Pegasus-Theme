<?php

Config::$body_classes = array();


array_push( Config::$styles,
	array('name' => 'style-css', 'src' => THE_POST_VERSION_URL . '/static/css/style.min.css')
);


array_push( Config::$scripts,
	array('name' => 'inview', 'src' => THEME_COMPONENTS_URL . '/inview.js',),
	array('name' => 'lazyload', 'src' => THEME_COMPONENTS_URL . '/jquery.lazyload.min.js',),
	array('name' => 'version-script', 'src' => THE_POST_VERSION_URL . '/static/js/script.min.js',)
);


/**
 * Register extra frontend scripts and stylesheets.
 **/
function v4_enqueue_frontend_theme_assets() {
	// Re-register jquery in document head
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', '//code.jquery.com/jquery-1.11.2.min.js' );
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'v4_enqueue_frontend_theme_assets' );


/**
 * Hook frontend theme script output into wp_head().
 **/
function v4_hook_frontend_theme_scripts() {
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
		var IPAD_DEPLOYED = <?php echo ipad_deployed() ? 'true' : 'false'; ?>;
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

	<?php
	global $post;
	echo output_header_markup( $post );
	?>
<?php
	echo ob_get_clean();
}
add_action( 'wp_head', 'v4_hook_frontend_theme_scripts' );

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

?>
