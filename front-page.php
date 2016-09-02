<?php
disallow_direct_load( 'front-page.php' );

if ( 'page' == get_option( 'show_on_front' ) ) {
	get_version_header( 'front' );
	echo 'front page enabled (latest version assets)';
	get_version_footer( 'front' );
}
else {
	get_version_front_page();
}

?>
