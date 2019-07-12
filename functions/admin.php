<?php 
function js_css_path() {
	echo '<script>var THEME_CSS_URL = "' . THEME_CSS_URL . '";</script>';
}
add_action( 'admin_head', 'js_css_path' );


// Used to import the color picker for the shortcode_callout_html
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
add_action( 'admin_enqueue_scripts', 'callout_enqueue_color_picker' );


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
add_action( 'media_buttons', 'add_shortcode_interface', 11 );

function add_shortcode_interface_modal() {
	$page = basename( $_SERVER['PHP_SELF'] );
	if ( in_array( $page, array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) ) ) {
		$shortcodes = array(
			'blockquote',
			'button',
			'callout',
			'caption',
			'clearfix',
			'divider',
			'google-remarketing',
			'lead',
			'photo_essay',
			'sidebar',
			'slideshow',
			'well'
		);

		// Dummy text to place between shortcodes that are enclosing.
		$enclosing = array(
			'blockquote' => 'Your blockquote text here...',
			'button'     => 'Your button text or shortcode content here...',
			'callout'    => 'Your callout text or shortcode content here...',
			'caption'    => 'Your caption text here...',
			'lead'       => 'Your lead text here...',
			'sidebar'    => 'Your sidebar text or shortcode content here...',
			'well'       => 'Your well text or shortcode content here...'
		);
?>
		<div id="select-shortcode-form" style="display:none;">
			<div id="select-shortcode-form-inner">
				<h1>Select a Shortcode</h1>
				<p>
					This shortcode will be inserted into the text editor when you click the
					"Insert into Post" button.
					<br/>
					For more information about shortcodes and what they do, please see the
					<a target="_blank" href="<?php echo get_admin_url(); ?>admin.php?page=theme-help#shortcodes">help
					documentation for shortcodes</a>.
				</p>

				<div class="cols">
					<div class="col-left">
						<select name="shortcode-select" id="shortcode-select">
							<option value="">--Choose Shortcode--</option>
							<?php 		foreach ( $shortcodes as $name ):
?>
								<option class="shortcode" value="<?php echo $name; ?>" <?php if ( isset( $enclosing[$name] ) ) { ?>data-enclosing="<?php echo $enclosing[$name]; ?>"<?php } ?>><?php echo $name; ?></option>
							<?php 		endforeach;
?>
						</select>
					</div>
					<div class="col-right">
						<ul id="shortcode-descriptions">
							<li class="shortcode-blockquote">
								Styles a line of text as a blockquote.
							</li>
							<li class="shortcode-button">
								Styles a line of text as a clickable button.
							</li>
							<li class="shortcode-callout">
								Creates a full-width callout box, in which any text, media or shortcode content can be added.
							</li>
							<li class="shortcode-caption">
								Styles a line of text as a caption for a photo, video or other media.
							</li>
							<li class="shortcode-clearfix">
								Forces hanging floats to clear above and below the shortcode.  Useful when a long sidebar
								hangs past the desired end point of the story content.
							</li>
							<li class="shortcode-divider">
								Adds a full-width divider line between content.
							</li>
							<li class="shortcode-google-remarketing">
								Inserts a Google Remarketing tag.  Ideally should be inserted at the bottom of post content.
							</li>
							<li class="shortcode-lead">
								Styles a line of text intended for use as the lead paragraph of an entire story or a major
								section of a story.  The first letter of this line will be styled as a dropcap.
							</li>
							<li class="shortcode-photo_essay">
								Creates a running photo gallery with captions and side navigation. Slideshows should be created as Photo
								Essays and then be selected here.
							</li>
							<li class="shortcode-sidebar">
								Creates a floating sidebar, in which any text, media or shortcode content can be added.
							</li>
							<li class="shortcode-slideshow">
								Creates a slideshow embed of photos with captions.  Slideshows should be created as Photo
								Essays and then be selected here.
							</li>
							<li class="shortcode-well">
								Wraps contents (text, media or shortcode content) in a box.  Useful for bringing focus to
								a chunk of content without using a full-width Callout.
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

						<h3>Text Color:</h3>
						<p class="help">(Optional) This will change the text color of the quote, source and cite. If it is left blank then the default text color will be used.</p>
						<input type="text" name="blockquote-color" class="shortcode-color" data-default-color="#ffffff" data-parameter="color">

						<h3>CSS Classes:</h3>
						<p class="help">(Optional) CSS classes to apply to the blockquote. Separate classes with a space.</p>
						<input type="text" name="blockquote-css_class" value="" data-default-value="btn-default" data-parameter="css_class">
					</li>
					<li class="shortcode-button">
						<h2>Button Options</h2>

						<h3>CSS Classes:</h3>
						<p class="help">(Optional) CSS classes to apply to the button. Separate classes with a space.</p>
						<input type="text" name="button-css_class" value="" data-default-value="btn-default" data-parameter="css_class">

						<h3>Link href:</h3>
						<p class="help">The URL the button should link out to.</p>
						<input type="text" name="button-href" value="" data-default-value="" data-parameter="href">

						<h3>Open in New Window:</h3>
						<p class="help">Whether or not this button's link should open in a new window when clicked.</p>
						<select name="button-new_window" data-parameter="new_window">
							<option value="false" selected>False</option>
							<option value="true">True</option>
						</select>

						<h3>Google Analytics - Interaction:</h3>
						<p class="help">"Interaction" parameter for this link's click event. Requires the "ga-event-link" class to be added to the button.</p>
						<input type="text" name="button-ga_interaction" value="" data-default-value="" data-parameter="ga_interaction">

						<h3>Google Analytics - Category:</h3>
						<p class="help">"Category" parameter for this link's click event. Requires the "ga-event-link" class to be added to the button.</p>
						<input type="text" name="button-ga_category" value="" data-default-value="" data-parameter="ga_category">

						<h3>Google Analytics - Action:</h3>
						<p class="help">"Action" parameter for this link's click event. Requires the "ga-event-link" class to be added to the button.</p>
						<input type="text" name="button-ga_action" value="" data-default-value="" data-parameter="ga_action">

						<h3>Google Analytics - Label:</h3>
						<p class="help">"Label" parameter for this link's click event. Requires the "ga-event-link" class to be added to the button.</p>
						<input type="text" name="button-ga_label" value="" data-default-value="" data-parameter="ga_label">
					</li>
					<li class="shortcode-callout">
						<h2>Callout Options</h2>

						<h3>Select a background color:</h3>
						<p class="help">
							(Optional) The background color of the callout box.  Font color can be modified by selecting text
							within this shortcode and picking a color from the text editor's Font Color dropdown menu.
						</p>
						<input type="text" name="callout-color" class="shortcode-color" value="#eeeeee" data-default-color="#ffffff" data-parameter="background">

						<h3>Content Alignment:</h3>
						<select name="callout-content_align" data-parameter="content_align">
							<option selected="selected" value="left">Left</option>
							<option value="center">Center</option>
							<option value="right">Right</option>
						</select>

						<h3>Enable affixing:</h3>
						<p class="help">
							When set to 'True', this callout box will affix to the top of the page when scrolled to. It will
							stay affixed until another affixable callout box is scrolled to, or when the end of the page is
							reached.
						</p>
						<select name="callout-affix" data-parameter="affix">
							<option value="false" selected>False</option>
							<option value="true">True</option>
						</select>

						<h3>CSS Classes:</h3>
						<p class="help">(Optional) CSS classes to apply to the callout. Separate classes with a space.</p>
						<input type="text" name="callout-css_class" value="" data-default-value="btn-default" data-parameter="css_class">
					</li>
					<li class="shortcode-google-remarketing">
						<h2>Google-Remarketing Options</h2>

						<h3>Conversion ID:</h3>
						<p class="help">The conversion ID to be tracked.</p>
						<input type="text" name="google-remarketing-conversion_id" value="" data-default-value="" data-parameter="conversion_id">

						<h3>Fallback image source:</h3>
						<p class="help">The image source to be used to track conversion if javascript is disabled.</p>
						<input type="text" name="google-remarketing-img_src" value="" data-default-value="" data-parameter="img_src">
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

						<h3>Content Alignment:</h3>
						<select name="sidebar-content_align" data-parameter="content_align">
							<option selected="selected" value="left">Left</option>
							<option value="center">Center</option>
							<option value="right">Right</option>
						</select>
					</li>
					<li class="shortcode-photo_essay">
						<h2>Photo Essay Options</h2>

						<h3>Select a Photo Essay:</h3>
						<select name="photo-essay-select" id="photo-essay-select" data-parameter="slug">
							<option value="">--Choose Photo Essay--</option>

							<?php 		$photo_essays = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'photo_essay',
			) );
		foreach ( $photo_essays as $photo_essay ):
?>

							<option class="shortcode" value="<?php echo $photo_essay->post_name; ?>"><?php echo $photo_essay->post_title; ?></option>

							<?php 		endforeach;
?>

						</select>
					</li>
					<li class="shortcode-slideshow">
						<h2>Slideshow Options</h2>

						<h3>Select a Photo Essay:</h3>
						<select name="photo-essay-select" id="photo-essay-select" data-parameter="slug">
							<option value="">--Choose Photo Essay--</option>

							<?php 		$photo_essays = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'photo_essay',
			) );
		foreach ( $photo_essays as $photo_essay ):
?>

							<option class="shortcode" value="<?php echo $photo_essay->post_name; ?>"><?php echo $photo_essay->post_title; ?></option>

							<?php 		endforeach;
?>

						</select>

						<h3>Select a caption color:</h3>
						<p class="help">
							(Optional) The color of caption text in this slideshow. Defaults to black (#000).
						</p>
						<input type="text" name="caption-color" class="shortcode-color" value="#000" data-default-color="#000" data-parameter="caption_color">
					</li>
					<li class="shortcode-well">
						<h2>Well Options</h2>

						<h3>CSS Classes:</h3>
						<p class="help">(Optional) CSS classes to apply to the well. Separate classes with a space.</p>
						<input type="text" name="well-css_class" value="" data-default-value="btn-default" data-parameter="css_class">
					</li>
				</ul>

				<button class="button-primary">Insert into Post</button>
			</div>
		</div>
	<?php 	}
}
add_action( 'admin_footer', 'add_shortcode_interface_modal' );


/**
 * Prints out additional login scripts, called by the login_head action
 *
 * @return void
 * @author Jared Lang
 * */
function login_scripts() {
	ob_start();?>
	<link rel="stylesheet" href="<?php echo THEME_CSS_URL; ?>/admin.css" type="text/css" media="screen" charset="utf-8" />
	<?php 	$out = ob_get_clean();
	print $out;
}
if ( is_login() ) {
	add_action( 'login_head', 'login_scripts', 0 );
}


/**
 * Called on admin init, initialize admin theme here.
 *
 * @return void
 * @author Jared Lang
 * */
function init_theme_options() {
	register_setting( THEME_OPTIONS_GROUP, THEME_OPTIONS_NAME, 'theme_options_sanitize' );
}
if ( is_admin() ) {
	add_action( 'admin_init', 'init_theme_options' );
}


/**
 * Registers the theme options page with wordpress' admin.
 *
 * @return void
 * @author Jared Lang
 * */
function create_utility_pages() {
	add_menu_page(
		__( THEME_OPTIONS_PAGE_TITLE ),
		__( THEME_OPTIONS_PAGE_TITLE ),
		'edit_theme_options',
		'theme-options',
		'theme_options_page',
		'dashicons-admin-generic'
	);
	add_menu_page(
		__( 'Help' ),
		__( 'Help' ),
		'edit_posts',
		'theme-help',
		'theme_help_page',
		'dashicons-editor-help'
	);
}
if ( is_admin() ) {
	add_action( 'admin_menu', 'create_utility_pages' );
}


/**
 * Outputs theme help page
 *
 * @return void
 * @author Jared Lang
 * */
function theme_help_page() {
	include THEME_INCLUDES_DIR.'/theme-help.php';
}


/**
 * Outputs the theme options page html
 *
 * @return void
 * @author Jared Lang
 * */
function theme_options_page() {
	include THEME_INCLUDES_DIR.'/theme-options.php';
}


/**
 * Stub, processing on theme options input
 *
 * @return void
 * @author Jared Lang
 * */
function theme_options_sanitize( $input ) {
	return $input;
}


/**
 * Modifies the default stylesheets associated with the TinyMCE editor.
 *
 * @return string
 * @author Jared Lang
 * */
function editor_styles( $css ) {
	$css   = array_map( 'trim', explode( ',', $css ) );
	$css[] = THEME_CSS_URL.'/formatting.php';
	$css   = implode( ',', $css );
	return $css;
}
add_filter( 'mce_css', 'editor_styles' );


/**
 * Edits second row of buttons in tinyMCE editor. Removing/adding actions
 *
 * @return array
 * @author Jared Lang
 * */
function editor_format_options( $row ) {
	$found = array_search( 'underline', $row );
	if ( False !== $found ) {
		unset( $row[$found] );
	}
	return $row;
}
add_filter( 'mce_buttons_2', 'editor_format_options' );


/**
 * Remove paragraph tag from excerpts
 * */
remove_filter( 'the_excerpt', 'wpautop' );


/**
 * Enqueue the scripts and css necessary for the WP Media Uploader on
 * all admin pages
 * */
function enqueue_wpmedia_throughout_admin() {
	wp_enqueue_script( 'jquery' );
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'enqueue_wpmedia_throughout_admin' );


/**
 * Add 'iconOrThumb' value to js-based attachment objects (for wp.media)
 * */
function add_icon_or_thumb_to_attachmentjs( $response, $attachment, $meta ) {
	$response['iconOrThumb'] = wp_attachment_is_image( $attachment->ID ) ? $response['sizes']['thumbnail']['url'] : $response['icon'];
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'add_icon_or_thumb_to_attachmentjs', 10, 3 );


/**
 * Dynamically set up font styles for Heading Font Family dropdowns
 * and TinyMCE font selection dropdown
 * */
function custom_font_styles() {
	$html = '<style type="text/css">';
	$html .= 'select[id*="_default_font"] option,
			#menu_content_content_fontselect_menu_tbl .mceText:not([title="Font family"]),
			#menu_content_content_styleselect_menu_tbl .mceText:not([title="Styles"]) {
				font-size: 20px;
				padding: 5px 0;
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
			 }
			';
	foreach ( unserialize( TEMPLATE_FONT_STYLES ) as $font => $val ) {
		$selector = 'select[id*="_default_font"] option[value="'.$font.'"],	#menu_content_content_fontselect_menu_tbl .mceText[title="'.$font.'"], #menu_content_content_styleselect_menu_tbl .mceText[title="'.$font.'"]';
		$html .= get_font_class( $font, $selector, null, true );
	}
	$html .= '</style>';

	print $html;
}
if ( is_admin() ) {
	add_action( 'admin_head', 'custom_font_styles' );
}



/**
 * Replace default WordPress markup for image insertion with
 * markup to generate a [photo] shortcode.
 * */
function editor_insert_image_as_shortcode( $html, $id, $caption, $title, $align, $url, $size, $alt ) {
	$s_id = $id;
	$s_title = '';
	$s_alt = $alt;
	$s_position = null;
	$s_width = '100%';
	$s_caption = '';

	$attachment = get_post( $id );

	// Get usable image position
	if ( $align && $align !== 'none' ) {
		$s_position = $align;
	}
	// Get usable image width.
	// Force aligned images to have a fixed width.
	$attachment_src = wp_get_attachment_image_src( $id, $size );
	if ( $s_position || $size !== 'full' ) {
		$s_width = $attachment_src[1].'px';
	}

	// Get usable image title (passed $title doesn't always work here?)
	$s_title = $attachment->post_title;
	// Get usable image caption
	$s_caption = $attachment->post_excerpt;

	// Create markup
	$html = '[photo id="'.$s_id.'" title="'.$s_title.'" alt="'.$s_alt.'" ';
	if ( $s_position ) {
		$html .= 'position="'.$s_position.'" ';
	}
	$html .= 'width="'.$s_width.'"]'.$s_caption.'[/photo]';

	return $html;
}
add_filter( 'image_send_to_editor', 'editor_insert_image_as_shortcode', 10, 8 );


/**
 * Set some default values when inserting photos in the Media Uploader.
 * Particularly, prevents images being linked to themselves by default.
 * */
function editor_default_photo_values() {
	update_option( 'image_default_align', 'none' );
	update_option( 'image_default_link_type', 'none' );
	update_option( 'image_default_size', 'full' );
}
add_action( 'after_setup_theme', 'editor_default_photo_values' );


/**
 * Force the WYSIWYG editor's kitchen sink to always be open.
 * */
function unhide_kitchensink( $args ) {
	$args['wordpress_adv_hidden'] = false;
	return $args;
}
add_filter( 'tiny_mce_before_init', 'unhide_kitchensink' );


/**
 * Remove Tools admin menu item for everybody but admins.
 * */
function remove_menus() {
	if ( !current_user_can( 'manage_sites' ) ) {
		remove_menu_page( 'tools.php' );
	}
}
add_action( 'admin_menu', 'remove_menus' );


/**
 * Add font size dropdown to TinyMCE editor; move it
 * and style select dropdown close together.
 * */
function add_options_to_tinymce( $buttons ) {
	array_unshift( $buttons, 'fontsizeselect' );
	array_unshift( $buttons, 'styleselect' );
	return $buttons;
}
add_filter( 'mce_buttons_2', 'add_options_to_tinymce' );


/**
 * Add custom formats, including webfont options and their stylesheets,
 * to the TinyMCE editor.
 *
 * NOTE: Webfonts are added as "Formats" options (NOT in "Font Family"
 * dropdown) to allow us to use class-based font styling, which prevents a mess
 * of inline styles and lets us apply IE8-specific overrides per-font when
 * necessary.
 * */
function add_formats_to_tinymce( $settings ) {
	$fonts = unserialize( TEMPLATE_FONT_STYLES );
	$cloud_font_key = get_theme_option( 'cloud_font_key' );
	$style_formats = array();
	$font_families = array();
	$content_css = array();

	// Text, list formatting options
	$style_formats = array(
		array(
			'title' => 'Text Formatting',
			'items' => array(
				array(
					'title' => 'UPPERCASE',
					'inline' => 'span',
					'classes' => 'text-uppercase'
				),
				array(
					'title' => 'lowercase',
					'inline' => 'span',
					'classes' => 'text-lowercase'
				),
			),
		),
		array(
			'title' => 'List Style',
			'items' => array(
				array(
					'title' => 'Inline Bulleted List',
					'selector' => 'ul',
					'classes' => 'list-inline-styled'
				),
			),
		),
	);

	// Webfont family list
	if ( $fonts ) {
		foreach ( $fonts as $font=>$styles ) {
			$styles = get_font_styles( $font );

			$font_families[] = array(
				'title' => $font,
				'inline' => 'span',
				'classes' => sanitize_title( $font )
			);

			if ( $styles['url'] !== null ) {
				$content_css[] = $styles['url'];
			}
		}

		$style_formats[] = array(
			'title' => 'Font Family',
			'items' => $font_families,
		);
	}

	if ( !empty( $cloud_font_key ) ) {
		$content_css[] .= $cloud_font_key;
	}
	$content_css = implode( ',', array_unique( $content_css ) );

	$settings['content_css'] = $settings['content_css'].','.$content_css; // Append font stylesheets to list of loaded stylesheets for tinymce
	$settings['style_formats'] = json_encode( $style_formats );
	$settings['theme_advanced_buttons2_add_before'] = 'styleselect'; // Add 'Format' button at beginning of 2nd row of btns
	$settings['fontsize_formats'] = '10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 36px 42px 48px 52px 58px 62px';

	return $settings;
}
add_filter( 'tiny_mce_before_init', 'add_formats_to_tinymce' );


/**
 * Add "template" column to Stories admin tables to easily distinguish custom
 * stories from those with non-custom templates.
 * */
function add_story_columns( $columns ) {
	return array_merge( $columns, array(
			'template' => __( 'Template' ),
		) );
}
add_filter( 'manage_story_posts_columns' , 'add_story_columns' );

function add_story_column_content( $column, $post_id ) {
	switch ( $column ) {
	case 'template':
		$template = get_post_meta( $post_id, 'story_template', true );
		switch ( $template ) {
		case 'photo_essay':
			echo 'Photo essay';
			break;
		case 'custom':
			echo 'Custom';
			break;
		default:
			echo 'Default';
			break;
		}
		break;
	}
}
add_action( 'manage_story_posts_custom_column', 'add_story_column_content', 10, 2 );

?>
