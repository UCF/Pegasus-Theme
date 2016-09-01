<?php
disallow_direct_load( 'home.php' );

$use_custom_homepage = filter_var( get_theme_option( 'use_custom_homepage' ), FILTER_VALIDATE_BOOLEAN );

if ( $use_custom_homepage ) {
	get_version_home();
}
else {
	$issue = get_current_issue();
	get_version_header();
	display_markup_or_template( $issue );
	get_version_footer();
}

?>
