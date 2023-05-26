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
function v5_enqueue_frontend_theme_assets() {
	// Re-register jquery in document head
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', '//code.jquery.com/jquery-1.11.2.min.js' );
	wp_enqueue_script( 'jquery' );
}
add_action( 'wp_enqueue_scripts', 'v5_enqueue_frontend_theme_assets' );


/**
 * Hook frontend theme script output into wp_head().
 **/
function v5_hook_frontend_theme_scripts() {
	ob_start();
?>
	<!--[if lte IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<?php if ( GTM_ID ): ?>
<script>
	dataLayer = [];
</script>
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','<?php echo GTM_ID; ?>');</script>
<!-- End Google Tag Manager -->
</script>
	<?php endif; ?>

	<?php if ( GA4_ACCOUNT ) : ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo GA4_ACCOUNT; ?>"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', '<?php echo GA4_ACCOUNT; ?>');
	</script>
	<?php elseif( GA_ACCOUNT or CB_UID ): ?>

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
		var IPAD_DEPLOYED = false;
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
add_action( 'wp_head', 'v5_hook_frontend_theme_scripts' );

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
 * Disable various content/WYSIWYG formatting options
 * provided by the Athena Shortcode plugin, since v5
 * and prior roll their own WYSIWYG and formatting
 * options.
 *
 * @since 6.0.0
 * @author Jo Dickson
 */
add_filter( 'option_athena_sc_enable_tinymce_formatting', '__return_false' );
add_filter( 'option_athena_sc_enable_optin_classes', '__return_false' );
add_filter( 'option_athena_sc_enable_responsive_embeds', '__return_false' );
add_filter( 'option_athena_sc_remove_image_dims', '__return_false' );


/**
 * Disable shortcodes provided by the Athena Shortcodes plugin,
 * since this version has its own which may result in conflicts.
 *
 * @since 6.0.0
 * @author Jo Dickson
 */
add_filter( 'athena_sc_add_shortcode', '__return_empty_array' );

?>
