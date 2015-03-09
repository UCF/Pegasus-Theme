<?php

Config::$body_classes = array();


array_push( Config::$styles,
	array('name' => 'bootstrap-css', 'src' => THE_POST_VERSION_URL . '/static/bootstrap/css/bootstrap.css'),
	array('name' => 'bootstrap-responsive-css', 'src' => THE_POST_VERSION_URL . '/static/bootstrap/css/bootstrap-responsive.css'),
	array('name' => 'style-css', 'src' => THE_POST_VERSION_URL . '/static/css/style.css'),
	array('name' => 'style-responsive-css', 'src' => THE_POST_VERSION_URL.'/static/css/style-responsive.css')
);


array_push( Config::$scripts,
	THE_POST_VERSION_URL.'/static/bootstrap/js/bootstrap.js',
	array('name' => 'base-script',  'src' => THE_POST_VERSION_URL.'/static/js/webcom-base.js',),
	array('name' => 'version-script', 'src' => THE_POST_VERSION_URL.'/static/js/script.js',),
	array('name' => 'inview', 'src' => THEME_JS_URL.'/inview.js',),
	array('name' => 'lazyload', 'src' => THEME_JS_URL.'/jquery.lazyload.min.js',)
);


function jquery_in_header() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', '//code.jquery.com/jquery-1.7.1.min.js');
    wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'jquery_in_header');
