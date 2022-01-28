<?php
/**
 * Latest-version admin functions
 */

/**
 * Force enable/disable various content/WYSIWYG formatting options
 * provided by the Athena Shortcode plugin.
 *
 * @since 6.0.0
 * @author Jo Dickson
 */
add_filter( 'option_athena_sc_enable_tinymce_formatting', '__return_true' );
add_filter( 'option_athena_sc_enable_optin_classes', '__return_true' );
add_filter( 'option_athena_sc_enable_responsive_embeds', '__return_true' );
add_filter( 'option_athena_sc_remove_image_dims', '__return_false' );


function set_admin_js_vars() {
	ob_start();
?>
<script>
var THEME_CSS_URL = "<?php echo THEME_CSS_URL; ?>";
var USE_SC_INTERFACE = false;
</script>
<?php
	echo ob_get_clean();
}
add_action( 'admin_head', 'set_admin_js_vars' );


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
 * Remove Tools admin menu item for everybody but admins.
 * */
function remove_menus() {
	if ( !current_user_can( 'manage_sites' ) ) {
		remove_menu_page( 'tools.php' );
	}
}
add_action( 'admin_menu', 'remove_menus' );


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
