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

ul.inline-bulleted-list {
	text-align: center;	
	margin: 0 0 16px;
	padding: 0;
}

ul.inline-bulleted-list li {
	display: inline-block;
	margin: 0 0.2em;
}

ul.inline-bulleted-list li:before {
	content: '\2022';
	margin-right: 0.4em;
}

ul.inline-bulleted-list > :first-child:before {
	content: '';
	margin: 0;
}

<?php require('../../../../../wp-load.php'); ?>
<?php echo get_webfont_css_classes(); ?>