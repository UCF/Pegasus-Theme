<?php header('Content-type: text/css;'); ?>
body,
p,
ol,
ul {
	font-family: Georgia, serif;
	font-size: 16px;
	line-height: 1.38em;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
}

.text-uppercase {
	text-transform: uppercase;
}

.text-lowercase {
	text-transform: lowercase;
}

<?php require('../../../../../wp-load.php'); ?>
<?php echo get_webfont_css_classes(); ?>