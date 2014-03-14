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

<?php require('../../../../../wp-load.php'); ?>
<?=get_webfont_css_classes()?>