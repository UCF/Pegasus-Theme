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


function jquery_in_header() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '//code.jquery.com/jquery-1.11.2.min.js');
    wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'jquery_in_header');


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
