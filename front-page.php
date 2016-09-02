<?php
disallow_direct_load( 'front-page.php' );

if ( 'page' == get_option( 'show_on_front' ) ) {
	echo 'front page enabled (latest version assets)';
}
else {
	get_version_front_page();
}

?>
