<?php

/**
 * Abstract class for defining custom post types.
 *
 **/
abstract class CustomPostType{
	public
		$name           = 'custom_post_type',
		$plural_name    = 'Custom Posts',
		$singular_name  = 'Custom Post',
		$add_new_item   = 'Add New Custom Post',
		$edit_item      = 'Edit Custom Post',
		$new_item       = 'New Custom Post',
		$public         = True,  # I dunno...leave it true
		$use_title      = True,  # Title field
		$use_editor     = True,  # WYSIWYG editor, post content field
		$use_revisions  = True,  # Revisions on post content and titles
		$use_thumbnails = False, # Featured images
		$use_order      = False, # Wordpress built-in order meta data
		$use_metabox    = False, # Enable if you have custom fields to display in admin
		$use_shortcode  = False, # Auto generate a shortcode for the post type
		                         # (see also objectsToHTML and toHTML methods)
		$taxonomies     = array('post_tag'),
		$built_in       = False,
		$menu_icon      = 'dashicons-admin-post',
		$show_in_rest   = False,

		# Optional default ordering for generic shortcode if not specified by user.
		$default_orderby = null,
		$default_order   = null,

		# Whether or not the post type uses cloneable fields and requires special saving
		# functionality and nonce handling.
		$cloneable_fields = False;


	static function get_file_url($object, $field_name) {
		if( ($file_id = get_post_meta($object->ID, $field_name, True)) !== False
				&& ($file_url = wp_get_attachment_url($file_id)) !== False) {
			return $file_url;
		} else {
			return False;
		}
	}

	/**
	 * Wrapper for get_posts function, that predefines post_type for this
	 * custom post type.  Any options valid in get_posts can be passed as an
	 * option array.  Returns an array of objects.
	 **/
	public function get_objects($options=array()){

		$defaults = array(
			'numberposts'   => -1,
			'orderby'       => 'title',
			'order'         => 'ASC',
			'post_type'     => $this->options('name'),
		);
		$options = array_merge($defaults, $options);
		$objects = get_posts($options);
		return $objects;
	}


	/**
	 * Similar to get_objects, but returns array of key values mapping post
	 * title to id if available, otherwise it defaults to id=>id.
	 **/
	public function get_objects_as_options($options=array()){
		$objects = $this->get_objects($options);
		$opt     = array();
		foreach($objects as $o){
			switch(True){
				case $this->options('use_title'):
					$opt[$o->post_title] = $o->ID;
					break;
				default:
					$opt[$o->ID] = $o->ID;
					break;
			}
		}
		return $opt;
	}


	/**
	 * Return the instances values defined by $key.
	 **/
	public function options($key){
		$vars = get_object_vars($this);
		return $vars[$key];
	}


	/**
	 * Additional fields on a custom post type may be defined by overriding this
	 * method on an descendant object.
	 **/
	public function fields(){
		return array();
	}


	/**
	 * Using instance variables defined, returns an array defining what this
	 * custom post type supports.
	 **/
	public function supports(){
		#Default support array
		$supports = array();
		if ($this->options('use_title')){
			$supports[] = 'title';
		}
		if ($this->options('use_order')){
			$supports[] = 'page-attributes';
		}
		if ($this->options('use_thumbnails')){
			$supports[] = 'thumbnail';
		}
		if ($this->options('use_editor')){
			$supports[] = 'editor';
		}
		if ($this->options('use_revisions')){
			$supports[] = 'revisions';
		}
		return $supports;
	}


	/**
	 * Creates labels array, defining names for admin panel.
	 **/
	public function labels(){
		return array(
			'name'          => __($this->options('plural_name')),
			'singular_name' => __($this->options('singular_name')),
			'add_new_item'  => __($this->options('add_new_item')),
			'edit_item'     => __($this->options('edit_item')),
			'new_item'      => __($this->options('new_item')),
		);
	}


	/**
	 * Creates metabox array for custom post type. Override method in
	 * descendants to add or modify metaboxes.
	 **/
	public function metabox(){
		if ($this->options('use_metabox')){
			return array(
				'id'       => $this->options('name').'_metabox',
				'title'    => __($this->options('singular_name').' Fields'),
				'page'     => $this->options('name'),
				'context'  => 'normal',
				'priority' => 'high',
				'fields'   => $this->fields(),
			);
		}
		return null;
	}


	/**
	 * Registers metaboxes defined for custom post type.
	 **/
	public function register_metaboxes(){
		if ($this->options('use_metabox')){
			$metabox = $this->metabox();
			add_meta_box(
				$metabox['id'],
				$metabox['title'],
				'show_meta_boxes',
				$metabox['page'],
				$metabox['context'],
				$metabox['priority']
			);
		}
	}


	/**
	 * Registers the custom post type and any other ancillary actions that are
	 * required for the post to function properly.
	 **/
	public function register(){
		$registration = array(
			'labels'          => $this->labels(),
			'supports'        => $this->supports(),
			'public'          => $this->options('public'),
			'taxonomies'      => $this->options('taxonomies'),
			'_builtin'        => $this->options('built_in'),
			'menu_icon'       => $this->options('menu_icon'),
			'show_in_rest'    => $this->options('show_in_rest')
		);

		if ($this->options('name') !== 'post' && $this->options('name') !== 'page') {
			$registration['capability_type'] = $this->options('name');
			$registration['map_meta_cap'] = true;
		}

		if ($this->options('use_order')){
			$registration = array_merge($registration, array('hierarchical' => True,));
		}

		register_post_type($this->options('name'), $registration);

		if ($this->options('use_shortcode')){
			add_shortcode($this->options('name').'-list', array($this, 'shortcode'));
		}
	}


	/**
	 * Shortcode for this custom post type.  Can be overridden for descendants.
	 * Defaults to just outputting a list of objects outputted as defined by
	 * toHTML method.
	 **/
	public function shortcode($attr){
		$default = array(
			'type' => $this->options('name'),
		);
		if (is_array($attr)){
			$attr = array_merge($default, $attr);
		}else{
			$attr = $default;
		}
		return sc_object_list($attr);
	}


	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}

		$class = get_custom_post_type($objects[0]->post_type);
		$class = new $class;

		ob_start();
		?>
		<ul class="<?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li>
				<?=$class->toHTML($o)?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}


	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 **/
	public function toHTML($object){
		$html = '<a href="'.get_permalink($object->ID).'">'.$object->post_title.'</a>';
		return $html;
	}
}

class Page extends CustomPostType {
	public
		$name           = 'page',
		$plural_name    = 'Pages',
		$singular_name  = 'Page',
		$add_new_item   = 'Add New Page',
		$edit_item      = 'Edit Page',
		$new_item       = 'New Page',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = False,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True,
		$built_in       = True;

	static function get_stylesheet_url($issue) {
		return Issue::get_file_url($issue, 'page_stylesheet');
	}

	public function fields() {
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Stylesheet',
				'desc' => '',
				'id'   => $prefix.'stylesheet',
				'type' => 'file',
			)
		);
	}
}


/**
 * Describes a story
 *
 * @author Chris Conover
 **/
class Story extends CustomPostType {
	public
		$name           = 'story',
		$plural_name    = 'Stories',
		$singular_name  = 'Story',
		$add_new_item   = 'Add Story',
		$edit_item      = 'Edit Story',
		$new_item       = 'New Story',
		$public         = True,
		$use_shortcode  = True,
		$use_metabox    = True,
		$use_thumbnails = True,
		$use_order      = False,
		$menu_icon      = 'dashicons-media-document',
		$show_in_rest   = True,
		$taxonomies     = array('issues', 'post_tag', 'category');

	static function get_javascript_url($story) {
		return Story::get_file_url($story, 'story_javascript');
	}

	static function get_stylesheet_url($issue) {
		return Issue::get_file_url($issue, 'story_stylesheet');
	}

	public function fields() {
		$font_options = array();
		foreach (unserialize(TEMPLATE_FONT_STYLES) as $key => $val) {
			$font_options[$key] = $key;
		}

		$prefix = $this->options('name').'_';
		$fields = array(
			array(
				'name' => 'Story Template',
				'desc' => 'The type of template to use for this story.  Stories <em>not</em> set to "Custom" use a premade template and can be created/edited
							via the WYSIWYG editor above.',
				'id'   => $prefix.'template',
				'type'    => 'select',
				'options' => array(
					'Photo essay' => 'photo_essay',
					'Custom story (requires custom CSS/JS)' => 'custom',
				),
				'default' => 'Default'
			),
			array(
				'name' => 'Story Subtitle',
				'desc' => 'A subtitle for the story.  This will be displayed next to the story title where stories are listed; i.e., the site header and footer and archives.',
				'id'   => $prefix.'subtitle',
				'type' => 'textarea',
			),
			array(
				'name' => 'Front Page Small Featured Stories Thumbnail',
				'desc' => 'Displayed in the small featured stories, as well as the stories in the "In This Issue" section.  Recommended dimensions: 263x175; if using this story as the top featured story on the front page, recommended dimensions are 1140x515px.',
				'id'   => $prefix.'frontpage_thumb',
				'type' => 'file',
			),
			array(
				'name' => 'Front Page Gallery Thumbnail',
				'desc' => 'Thumbnail displayed in the bottom right of the front page.  Recommended dimensions: 515x390px.',
				'id'   => $prefix.'frontpage_gallery_thumb',
				'type' => 'file',
			),
			array(
				'name' => 'Default Issue Template Featured Story Thumbnail',
				'desc' => 'Thumbnail for default Issue template\'s featured story slots.  Recommended dimensions: 768x432px',
				'id'   => $prefix.'issue_featured_thumb',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Default Templates:</strong> Story Description',
				'desc' => 'A one to two sentence description for the story.  This will be displayed underneath the story\'s title in default story templates.',
				'id'   => $prefix.'description',
				'type' => 'wysiwyg',
			),
			array(
				'name' => '<strong>Default Templates:</strong> Header Font Family',
				'desc' => 'The font family to use for headings and dropcaps in this story.  Font sizes/line heights are determined automatically based on the font selected.',
				'id'   => $prefix.'default_font',
				'type'    => 'select',
				'options' => $font_options,
			),
			array(
				'name' => '<strong>Default Templates:</strong> Header Font Color',
				'desc' => 'Color for h1-h6 titles, as well as blockquotes and dropcaps.  Hex values preferred.',
				'id'   => $prefix.'default_color',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Templates:</strong> Header Image',
				'desc' => 'Large feature image to go at the very top of the story.  Recommended dimensions: 1600x900px',
				'id'   => $prefix.'default_header_img',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Story Template:</strong> HTML File',
				'desc' => '',
				'id'   => $prefix.'html',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Story Template:</strong> Stylesheet',
				'desc' => '',
				'id'   => $prefix.'stylesheet',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Story Template:</strong> JavaScript File',
				'desc' => '',
				'id'   => $prefix.'javascript',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Story Template:</strong> Font Includes',
				'desc' => 'Fonts from the static/fonts directory to include for this story.  All fonts here must be defined in the CUSTOM_AVAILABLE_FONTS constant
							(functions/config.php).  Fonts should be referenced by name and be comma-separated.  (Fonts from Cloud.Typography do not need to be included
							here.)',
				'id'   => $prefix.'fonts',
				'type' => 'textarea',
			),
		);
		if (DEV_MODE == 1) {
			array_unshift($fields, array(
				'name' => '<strong>Developer Mode:</strong> Directory URL',
				'desc' => 'Directory to this story in the theme\'s dev folder (include trailing slash, relative to <code>/dev/</code>).  Properly named html, css and javascript files
							(story-slug.html/css/js) in this directory will be automatically referenced for this story if they are available.<br/><br/>
							<strong>NOTE:</strong>
							<ul style="list-style: disc !important;">
							<li>An HTML file uploaded to the HTML file field below takes priority over the WYSIWYG editor AND the dev directory\'s HTML file contents.</li>
							<li>Any content in the WYSIWYG editor takes priority over the dev directory\'s HTML file contents.</li>
							<li>Any files uploaded to the stylesheet/javascript fields below take priority over the dev directory\'s contents.</li>
							<li>The Story Template field below should be either empty or set to "Custom" for custom stylesheets/javascript files to have any effect.
								<strong>Story templates still take effect in Developer Mode.</strong></li>
							</ul>
							<code>'.THEME_DEV_URL.'/...</code>',
				'id'   => $prefix.'dev_directory',
				'type' => 'text',
			));
		}
		return $fields;
	}
}

/**
 * Describes an issue
 *
 * @author Chris Conover
 **/
class Issue extends CustomPostType {
	public
		$name           = 'issue',
		$plural_name    = 'Issues',
		$singular_name  = 'Issue',
		$add_new_item   = 'Add Issue',
		$edit_item      = 'Edit Issue',
		$new_item       = 'New Issue',
		$public         = True,
		$use_shortcode  = True,
		$use_metabox    = True,
		$use_thumbnails = True,
		$use_order      = False,
		$menu_icon      = 'dashicons-book',
		$show_in_rest   = True,
		$taxonomies     = array();

	static function get_home_javascript_url($issue) {
		return Issue::get_file_url($issue, 'issue_javascript_home');
	}

	static function get_issue_javascript_url($issue) {
		return Issue::get_file_url($issue, 'issue_javascript_issue');
	}

	static function get_home_stylesheet_url($issue) {
		return Issue::get_file_url($issue, 'issue_stylesheet_home');
	}

	static function get_issue_stylesheet_url($issue) {
		return Issue::get_file_url($issue, 'issue_stylesheet_issue');
	}

	public function fields() {
		global $post;

		$prefix = $this->options('name').'_';

		$story_options = array();
		$args = array(
			'post_status' => array('publish', 'pending', 'draft'),
			'orderby' => 'title',
			'order' => 'ASC'
		);

		$issue_stories = get_issue_stories( $post, $args );
		if ( $issue_stories ) {
			foreach( get_issue_stories( $post, $args ) as $story ) {
				$story_options[$story->post_title] = $story->ID;
			}
		}

		$versions = unserialize( VERSIONS );
		$versions = array_combine( $versions, $versions ); // Force identical key/val pairs

		$fields = array(
			array(
				'name'    => 'Description',
				'desc'    => 'Short description describing what is included in the issue.',
				'id'      => $prefix.'description',
				'type'    => 'textarea'
			),
			array(
				'name'    => 'Issue Version',
				'desc'    => 'The theme version to use for this issue and its stories.',
				'id'      => $prefix.'version',
				'type'    => 'select',
				'options' => $versions,
				'default' => strval( $versions[LATEST_VERSION] ) // int is converted to str when saved as post meta, so compare against str here
			),
			array(
				'name'    => 'Cover Story',
				'desc'    => 'The story featured on the front cover of the print magazine.  This is listed as the "featured story" for the issue in the site archives,
								and is used by older issue covers with custom styling.',
				'id'      => $prefix.'cover_story',
				'type'    => 'select',
				'options' => $story_options
			),
			array(
				'name' => 'Issue Template',
				'desc' => 'The type of template to use for this issue.  Issues <em>not</em> set to "Custom" use a premade template and can be modified
							via the "Default" options below.',
				'id'   => $prefix.'template',
				'type'    => 'select',
				'options' => array(
					'Custom (requires custom CSS/JS)' => 'custom',
				),
				'default' => 'Default'
			),
			array(
				'name' => '<strong>Default Template:</strong> Featured Story #1',
				'desc' => 'The story that appears in the top-left featured story slot of the default Issue template.',
				'id'   => $prefix.'story_1',
				'type' => 'select',
				'options' => $story_options,
			),
			array(
				'name' => '<strong>Default Template:</strong> Featured Story #2',
				'desc' => 'The story that appears in the top-right featured story slot of the default Issue template.',
				'id'   => $prefix.'story_2',
				'type' => 'select',
				'options' => $story_options,
			),
			array(
				'name' => '<strong>Default Template:</strong> Featured Story #3',
				'desc' => 'The story that appears in the bottom-right featured story slot of the default Issue template.',
				'id'   => $prefix.'story_3',
				'type' => 'select',
				'options' => $story_options,
			),
			array(
				'name' => '<strong>Custom Issue Template:</strong> Issue Cover HTML File',
				'desc' => 'HTML markup specifically for the issue cover. Also used on the front page if this issue is the latest issue, and a custom front page is not enabled.',
				'id'   => $prefix.'html',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Issue Template:</strong> Issue Cover Stylesheet',
				'desc' => 'Stylesheet specifically for the issue cover. Also used on the front page if this issue is the latest issue, and a custom front page is not enabled.',
				'id'   => $prefix.'stylesheet_home',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Issue Template:</strong> Issue Cover JavaScript File',
				'desc' => 'JavaScript file that runs exclusively on the issue cover for this issue. Also used on the front page if this issue is the latest issue, and a custom front page is not enabled.',
				'id'   => $prefix.'javascript_home',
				'type' => 'file',
			),

			array(
				'name' => '<strong>DEPRECATED:</strong> Issue-Wide Stylesheet',
				'desc' => '<strong><em>This feature is deprecated as of Spring 2014 and is left in place for backward compatibility.
							This field will have no effect on issues or stories from Spring 2014 onward.</em></strong><br/>
							Stylesheet that will affect all stories for this issue.',
				'id'   => $prefix.'stylesheet_issue',
				'type' => 'file',
			),
			array(
				'name' => '<strong>DEPRECATED:</strong> Issue-Wide JavaScript File',
				'desc' => '<strong><em>This feature is deprecated as of Spring 2014 and is left in place for backward compatibility.
							This field will have no effect on issues or stories from Spring 2014 onward.</em></strong><br/>
							JavaScript file that runs on all stories for this issue, including the home page.',
				'id'   => $prefix.'javascript_issue',
				'type' => 'file',
			),
		);

		if (DEV_MODE == 1) {
			array_unshift($fields, array(
				'name' => '<strong>Developer Mode:</strong> Issue Cover Asset Directory',
				'desc' => 'Directory to this issue\'s cover page assets in the theme\'s dev folder (include trailing slash).  Properly named html, css and javascript files
							(issue-cover.html/css/js) in this directory will be automatically referenced for the issue home page if they are available.<br/><br/>
							<strong>NOTE:</strong>
							<ul style="list-style: disc !important;">
							<li>Any content in the WYSIWYG editor takes priority over the dev directory\'s HTML file contents.</li>
							<li>Any files uploaded to the stylesheet/javascript fields below take priority over the dev directory\'s contents.</li>
							<li>The Issue Template field below should be either empty or set to "Custom" for custom stylesheets/javascript files to have any effect.
								<strong>Issue templates still take effect in Developer Mode.</strong></li>
							</ul>
							<code>'.THEME_DEV_URL.'/...</code>',
				'id'   => $prefix.'dev_home_asset_directory',
				'type' => 'text',
				)
			);
		}
		return $fields;
	}


	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		$args = array(
			'orderby' => 'post_date',
			'order' => 'DESC',
			'post_type' => 'issue',
			'numberposts' => -1
		);
		$objects = get_posts($args);
		$class = new Issue;

		ob_start();
		?>
		<ul class="<?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li>
				<?=$class->toHTML($o)?>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		$html = ob_get_clean();
		return $html;
	}


	/**
	 * Outputs this item in HTML.  Can be overridden for descendants.
	 **/
	public function toHTML($object){
		$html = '<a href="'.get_permalink($object->ID).'">';
			$html .= get_the_post_thumbnail($object->ID, 'issue-thumbnail');
			$html .= $object->post_title;
		$html .= '</a>';
		return $html;
	}
} // END class


/**
 * Describes a Photo Essay
 *
 * @author Jo Greybill
 * pieces borrowed from SmartStart theme
 **/

class PhotoEssay extends CustomPostType {
	public
		$name           = 'photo_essay',
		$plural_name    = 'Photo Essays',
		$singular_name  = 'Photo Essay',
		$add_new_item   = 'Add New Photo Essay',
		$edit_item      = 'Edit Photo Essay',
		$new_item       = 'New Photo Essay',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = False,
		$use_order      = False,
		$use_title      = True,
		$use_metabox    = True,
		$use_revisions	= False,
		$menu_icon      = 'dashicons-images-alt2',
		$taxonomies     = array('issues'),
		$cloneable_fields = True;

	public function fields(){
	//
	}

	public function metabox(){
		if ($this->options('use_metabox')){
			$prefix = 'ss_';

			$all_slides =
				// Container for individual slides:
				array(
					'id'       => 'slider-slides',
					'title'    => 'All Slides',
					'page'     => 'photo_essay',
					'context'  => 'normal',
					'priority' => 'default',
				);
			$single_slide_count =
				// Single Slide Count (and order):
				array(
					'id'       => 'slider-slides-settings-count',
					'title'    => 'Slides Count',
					'page'     => 'photo_essay',
					'context'  => 'normal',
					'priority' => 'default',
					'fields'   => array(
						array(
							'name' => __('Total Slide Count'),
							'id'   => $prefix . 'slider_slidecount',
							'type' => 'text',
							'std'  => '0',
							'desc' => ''
						),
						array(
							'name' => __('Slide Order'),
							'id'   => $prefix . 'slider_slideorder',
							'type' => 'text',
							'desc' => ''
						)
					), // fields
				);
			$all_metaboxes = array(
				'slider-all-slides' => $all_slides,
				'slider-slides-settings-count' => $single_slide_count,
			);
			return $all_metaboxes;
		}
		return null;
	}

	/** Function used for defining single slide meta values; primarily
	  * for use in saving meta data (_save_meta_data(), functions/base.php).
	  **/
	public static function get_single_slide_meta() {
		$single_slide_meta = array(
			array(
				'id'	=> 'ss_slide_alt',
				'val'	=> $_POST['ss_slide_alt'],
				'type'  => 'text',
			),
			array(
				'id'	=> 'ss_slide_title',
				'val'	=> $_POST['ss_slide_title'],
				'type'  => 'text',
			),
			array(
				'id'	=> 'ss_slide_caption',
				'val'	=> $_POST['ss_slide_caption'],
				'type'  => 'text',
			),
			array(
				'id'	=> 'ss_slide_image',
				'val' 	=> $_POST['ss_slide_image'],
				'type'  => 'text',
			),
		);
		return $single_slide_meta;
	}


	/**
	 * Generate markup for a cloneable set of meta fields
	 **/
	public static function display_cloneable_fieldset($fields, $id=null) {
		$id             = $id !== null ? intval($id) : 'xxxxxx';
		$slide_alt    	= $fields['slide_alt'][$id] ? $fields['slide_alt'][$id] : '';
		$slide_title    = $fields['slide_title'][$id]   ? $fields['slide_title'][$id] : '';
		$slide_caption  = $fields['slide_caption'][$id] ? $fields['slide_caption'][$id] : '';
		$slide_image_id = !is_string($id) ? intval($fields['slide_image'][$id]) : $id;
		$slide_image    = !is_string($slide_image_id) ? get_post($slide_image_id) : null;
	?>
		<li class="custom_repeatable postbox<?php if (is_string($id)) {?> cloner" style="display:none;<?php } ?>">
			<div class="handlediv" title="Click to toggle"> </div>
				<h3 class="hndle">
				<span>Slide - </span><span class="slide-handle-header"><?=$slide_title?></span>
			</h3>
			<table class="form-table">
			<input type="hidden" name="meta_box_nonce" value="<?=wp_create_nonce('nonce-content')?>"/>
				<tr>
					<th><label for="ss_slide_alt[<?php echo $id; ?>]">Image Alt Text</label></th>
					<td>
						<input type="text" name="ss_slide_alt[<?php echo $id; ?>]" id="ss_slide_alt[<?php echo $id; ?>]" value="<?php echo $slide_alt; ?>" />
					</td>
				</tr>
				<tr>
					<th><label for="ss_slide_caption[<?=$id?>]">Slide Caption</label></th>
					<td>
                        <div id="wysihtml5-toolbar[<?=$id?>]" style="display: none;">
                            <a class="wysihtml5-strong" data-wysihtml5-command="formatInline" data-wysihtml5-command-value="strong">strong</a>
                            <a class="wysihtml5-em" data-wysihtml5-command="formatInline" data-wysihtml5-command-value="em">em</a>
                            <a class="wysihtml5-u" data-wysihtml5-command="underline" data-wysihtml5-command-value="u">underline</a>
                            <a class="wysihtml5-align-center" data-wysihtml5-command="justifyCenter" data-wysihtml5-command-value="jc">center</a>

                          <!-- Some wysihtml5 commands like 'createLink' require extra parameters specified by the user (eg. href) -->
                            <a class="wysihtml5-createlink" data-wysihtml5-command="createLink">insert link</a>
                            <div class="wysihtml5-createlink-form" data-wysihtml5-dialog="createLink" style="display: none;">
                                <label>
                                    Link:
                                    <input data-wysihtml5-dialog-field="href" value="http://" class="text">
                                </label>
                                <a class="wysihtml5-createlink-save" data-wysihtml5-dialog-action="save">OK</a> <a class="wysihtml5-createlink-cancel" data-wysihtml5-dialog-action="cancel">Cancel</a>
                            </div>
                            <a class="wysihtml5-html" data-wysihtml5-action="change_view">HTML</a>
                        </div>
						<textarea name="ss_slide_caption[<?=$id?>]" id="ss_slide_caption[<?=$id?>]" cols="60" rows="4"><?=$slide_caption?></textarea>
					</td>
				</tr>
				<tr>
					<th><label for="ss_slide_image[<?=$id?>]">Slide Image</label></th>
					<td>
						<?php
							if (!empty($slide_image)) {
								$url = wp_get_attachment_url($slide_image->ID);
							} else {
								$url = False;
							}
							// Attempt to catch an attachment that was deleted
							if ($url == False) {
								$url = THEME_IMG_URL.'/slide-deleted.jpg';
								$slide_image_id = '';
							}
						?>
						<a target="_blank" href="<?=$url?>">
							<img src="<?=$url?>" style="max-width: 400px; height: auto;" /><br/>
							<span><?php if (!empty($slide_image)) { print $slide_image->post_title; }?></span>
						</a><br />
						<input type="text" id="file_img_<?=$slide_image_id?>" value="<?=$slide_image_id?>" name="ss_slide_image[<?=$id?>]">
					</td>
				</tr>
				<tr>
					<th><label for="ss_slide_title[<?php echo $id; ?>]">DEPRECATED: Title</label></th>
					<td>
						<p class="description"><strong><em>This feature is deprecated and is left in place for backward compatibility. You can now use the Alt Text field above to add alt text to each slide image.</em></strong></p>
						<input type="text" name="ss_slide_title[<?php echo $id; ?>]" id="ss_slide_title[<?php echo $id; ?>]" value="<?php echo $slide_title; ?>" />
					</td>
				</tr>
			</table>
			<a class="repeatable-remove button" href="#">Remove Slide</a>
		</li>
	<?php
	}


	/**
	 * Show fields for single slides:
	 **/
	public static function display_slide_meta_fields($post) {
		// Get any already-existing values for these fields:
		$slide_alt		= get_post_meta($post->ID, 'ss_slide_alt', TRUE);
		$slide_title	= get_post_meta($post->ID, 'ss_slide_title', TRUE);
		$slide_caption	= get_post_meta($post->ID, 'ss_slide_caption', TRUE);
		$slide_image	= get_post_meta($post->ID, 'ss_slide_image', TRUE);
		$slide_order    = get_post_meta($post->ID, 'ss_slider_slideorder', TRUE);
		$args = array(
			'slide_alt' 	=> $slide_alt,
			'slide_title' 	=> $slide_title,
			'slide_caption' => $slide_caption,
			'slide_image' 	=> $slide_image
		);
		?>
		<div id="ss_slides_wrapper">
			<a class="button-primary" id="slide_modal_toggle" href="#">Create New Slides</a>
			<ul id="ss_slides_all">
				<?php
				// Print a cloner slide
				PhotoEssay::display_cloneable_fieldset($args);

				// Loop through slides_array for existing slides.
				if ($slide_order) {
					$slide_array = explode(",", $slide_order);
					foreach ($slide_array as $s) {
						if ($s !== '') {
							print PhotoEssay::display_cloneable_fieldset($args, $s);
						}
					}
				}
				?>
			</ul>
		</div>
		<?php
	}

 	// Individual slide container:
	public function show_meta_box_slide_all($post) {
		$this->display_slide_meta_fields($post);
	}

	// Slide Count:
	public function show_meta_box_slide_count($post) {
		if ($this->options('use_metabox')) {
			$meta_box = $this->metabox();
		}
		$meta_box = $meta_box['slider-slides-settings-count'];
		// Use one nonce for Slider post:
		?>
		<table class="form-table">
		<input type="hidden" name="meta_box_nonce" value="<?=wp_create_nonce('nonce-content')?>"/>
		<?php
			foreach( $meta_box['fields'] as $field ) {
				display_meta_box_field( $post->ID, $field );
			}
		?>
		</table>
	<?php
	}


	public function register_metaboxes(){
		if ($this->options('use_metabox')){
			$metabox = $this->metabox();
			foreach ($metabox as $key => $single_metabox) {
				switch ($key) {
					case 'slider-all-slides':
						$metabox_view_function = 'show_meta_box_slide_all';
						break;
					case 'slider-slides-settings-count':
						$metabox_view_function = 'show_meta_box_slide_count';
						break;
					default:
						break;
				}
				add_meta_box(
					$single_metabox['id'],
					$single_metabox['title'],
					array( &$this, $metabox_view_function ),
					$single_metabox['page'],
					$single_metabox['context'],
					$single_metabox['priority']
				);
			}
		}
	}


}
?>
