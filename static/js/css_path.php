<?php header( "Content-type: application/x-javascript" ); ?>
<?php if( !empty( $_GET["THEME_CSS_URL"] ) ) : ?>
	var THEME_CSS_URL = "<?php echo urldecode( $_GET["THEME_CSS_URL"] ); ?>";
<?php endif ?>