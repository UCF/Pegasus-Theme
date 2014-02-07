<?php

if (is_login()){
	add_action('login_head', 'login_scripts', 0);
}

if (is_admin()){
	add_action('admin_menu', 'create_utility_pages');
	add_action('admin_init', 'init_theme_options');
}

// Used to import the color picker for the shortcode_callout_html
add_action( 'admin_enqueue_scripts', 'callout_enqueue_color_picker' );
function callout_enqueue_color_picker( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script(
        'iris',
        admin_url( 'js/iris.min.js' ),
        array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),
        false,
        1
    );
}

function add_shortcode_interface() {
	$text = '<style>
		.shortcode-media-icon{
			background:url("' . THEME_IMG_URL . '/shortcode-media-icon.png") no-repeat top left;
			display: inline-block;
			height: 16px;
			margin: 0 2px 0 0;
			vertical-align: text-top;
			width: 16px;
		}
		#add-shortcode {
			padding-left: .4em;
		}
	</style>
	<a href="#TB_inline?width=600&height=700&inlineId=select-shortcode-form" class="thickbox button" id="add-shortcode" title="Add Shortcode"><span class="shortcode-media-icon"></span>Add Shortcode</a>';
	echo $text;
}
add_action('media_buttons', 'add_shortcode_interface', 11);

function add_shortcode_interface_modal() {
	$page = basename($_SERVER['PHP_SELF']);
	if (in_array($page, array('post.php', 'page.php', 'page-new.php', 'post-new.php'))) {
		global $shortcode_tags;
		$shortcodes = $shortcode_tags;
		$ignore     = array(
			'wp_caption'       => null,
			//'caption'          => null,
			'gallery'          => null,
			'embed'            => null,
			'archive-search'   => null,
			'donotprint'       => null,
			'gravityform'      => null,
			'gravityforms'     => null,
			'image'            => null,
			'issue-list'       => null,
			'media'            => null,
			'photo'            => null,
			'post-type-search' => null,
			'print_link'       => null,
			'search_form'      => null,
			'static-image'     => null,
			'story-list'       => null,
			'audio'            => null,
			'video'            => null
		);
		// Dummy text to place between shortcodes that are enclosing.
		$enclosing = array(
			'blockquote' 	=> 'Your blockquote text here...',
			'callout' 		=> 'Your callout text or shortcode content here...',
			'caption' 		=> 'Your caption text here...',
			'lead' 			=> 'Your lead text here...',
			'sidebar' 		=> 'Your sidebar text or shortcode content here...'
		);

		$shortcodes = array_diff_key($shortcodes, $ignore);
		ksort($shortcodes);
	?>
		<div id="select-shortcode-form" style="display:none;">
			<div id="select-shortcode-form-inner">
				<h1>Select a Shortcode</h1>
				<p>
					This shortcode will be inserted into the text editor when you click the 
					"Insert into Post" button.
					<br/>
					For more information about shortcodes and what they do, please see the 
					<a target="_blank" href="<?=get_admin_url()?>admin.php?page=theme-help#shortcodes">help 
					documentation for shortcodes</a>.
				</p>

				<div class="cols">
					<div class="col-left">
						<select name="shortcode-select" id="shortcode-select">
							<option value="">--Choose Shortcode--</option>
							<?php
							foreach($shortcodes as $name=>$callback):
							?>
								<option class="shortcode" value="<?=$name?>" <?php if (isset($enclosing[$name])) { ?>data-enclosing="<?=$enclosing[$name]?>"<?php } ?>><?=$name?></option>
							<?php
							endforeach;
							?>
						</select>
					</div>
					<div class="col-right">
						<ul id="shortcode-descriptions">
							<li class="shortcode-blockquote">
								Styles a line of text as a blockquote.
							</li>
							<li class="shortcode-callout">
								Creates a full-width callout box, in which any text, media or shortcode content can be added.
							</li>
							<li class="shortcode-caption">
								Styles a line of text as a caption for a photo, video or other media.
							</li>
							<li class="shortcode-divider">
								Adds a full-width divider line between content.
							</li>
							<li class="shortcode-lead">
								Styles a line of text intended for use as the lead paragraph of an entire story or a major 
								section of a story.  The first letter of this line will be styled as a dropcap.
							</li>
							<li class="shortcode-sidebar">
								Creates a floating sidebar, in which any text, media or shortcode content can be added.
							</li>
							<li class="shortcode-slideshow">
								Creates a slideshow embed of photos with captions.  Slideshows should be created as Photo
								Essays and then be selected here.
							</li>
						</ul>
					</div>
				</div>

				<ul id="shortcode-editors">
					<li class="shortcode-blockquote">
						<h2>Blockquote Options</h2>

						<h3>Source:</h3>
						<p class="help">(Optional) Who said this quote?</p>
						<input type="text" name="blockquote-source" value="" data-default-value="" data-parameter="source">

						<h3>Cite:</h3>
						<p class="help">(Optional) If this quote originated from some publication, specify it here.</p>
						<input type="text" name="blockquote-cite" value="" data-default-value="" data-parameter="cite">
					</li>
					<li class="shortcode-callout">
						<h2>Callout Options</h2>

						<h3>Select a background color:</h3>
						<p class="help">
							(Optional) The background color of the callout box.  Font color can be modified by selecting text
							within this shortcode and picking a color from the text editor's Font Color dropdown menu.
						</p>
						<input type="text" name="callout-color" class="shortcode-color" value="#eeeeee" data-default-color="#ffffff" data-parameter="background">
					</li>
					<li class="shortcode-sidebar">
						<h2>Sidebar Options</h2>

						<h3>Select a background color:</h3>
						<p class="help">
							(Optional) The background color of the sidebar.  Font color can be modified by selecting text
							within this shortcode and picking a color from the text editor's Font Color dropdown menu.
						</p>
						<input type="text" name="sidebar-color" class="shortcode-color" value="#eeeeee" data-default-color="#ffffff" data-parameter="background">

						<h3>Select a position:</h3>
						<select name="sidebar-position" data-parameter="position">
							<option value="left">Left</option>
							<option selected="selected" value="right">Right</option>
						</select>
					</li>
					<li class="shortcode-slideshow">
						<h2>Slideshow Options</h2>

						<h3>Select a Photo Essay:</h3>
						<select name="photo-essay-select" id="photo-essay-select" data-parameter="slug">
							<option value="">--Choose Photo Essay--</option>

							<?php
							$photo_essays = get_posts(array(
								'posts_per_page' => -1,
								'post_type' => 'photo_essay',
							));
							foreach($photo_essays as $photo_essay):
							?>

							<option class="shortcode" value="<?=$photo_essay->post_name?>"><?=$photo_essay->post_title?></option>

							<?php
							endforeach;
							?>

						</select>
					</li>
				</ul>

				<button class="button-primary">Insert into Post</button>
			</div>
		</div>
	<?php
	}
}
add_action('admin_footer', 'add_shortcode_interface_modal');


/**
 * Prints out additional login scripts, called by the login_head action
 *
 * @return void
 * @author Jared Lang
 **/
function login_scripts(){
	ob_start();?>
	<link rel="stylesheet" href="<?=THEME_CSS_URL?>/admin.css" type="text/css" media="screen" charset="utf-8" />
	<?php
	$out = ob_get_clean();
	print $out;
}


/**
 * Called on admin init, initialize admin theme here.
 *
 * @return void
 * @author Jared Lang
 **/
function init_theme_options(){
	register_setting(THEME_OPTIONS_GROUP, THEME_OPTIONS_NAME, 'theme_options_sanitize');
}


/**
 * Registers the theme options page with wordpress' admin.
 *
 * @return void
 * @author Jared Lang
 **/
function create_utility_pages() {
	add_utility_page(
		__(THEME_OPTIONS_PAGE_TITLE),
		__(THEME_OPTIONS_PAGE_TITLE),
		'edit_theme_options',
		'theme-options',
		'theme_options_page',
		THEME_IMG_URL.'/pegasus.png'
	);
	add_utility_page(
		__('Help'),
		__('Help'),
		'publish_posts',
		'theme-help',
		'theme_help_page',
		THEME_IMG_URL.'/help.png'
	);
}


/**
 * Outputs theme help page
 *
 * @return void
 * @author Jared Lang
 **/
function theme_help_page(){
	include(THEME_INCLUDES_DIR.'/theme-help.php');
}


/**
 * Outputs the theme options page html
 *
 * @return void
 * @author Jared Lang
 **/
function theme_options_page(){
	include(THEME_INCLUDES_DIR.'/theme-options.php');
}


/**
 * Stub, processing on theme options input
 *
 * @return void
 * @author Jared Lang
 **/
function theme_options_sanitize($input){
	return $input;
}


/**
 * Modifies the default stylesheets associated with the TinyMCE editor.
 *
 * @return string
 * @author Jared Lang
 **/
function editor_styles($css){
	$css   = array_map('trim', explode(',', $css));
	$css[] = THEME_CSS_URL.'/formatting.css';
	$css   = implode(',', $css);
	return $css;
}
add_filter('mce_css', 'editor_styles');


/**
 * Edits second row of buttons in tinyMCE editor. Removing/adding actions
 *
 * @return array
 * @author Jared Lang
 **/
function editor_format_options($row){
	$found = array_search('underline', $row);
	if (False !== $found){
		unset($row[$found]);
	}
	return $row;
}
add_filter('mce_buttons_2', 'editor_format_options');

/**
 * Remove paragraph tag from excerpts
 **/
remove_filter('the_excerpt', 'wpautop');
