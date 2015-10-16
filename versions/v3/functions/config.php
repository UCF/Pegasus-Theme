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

?>
