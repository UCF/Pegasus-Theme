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

		# Optional default ordering for generic shortcode if not specified by user.
		$default_orderby = null,
		$default_order   = null;


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
			'labels'     => $this->labels(),
			'supports'   => $this->supports(),
			'public'     => $this->options('public'),
			'taxonomies' => $this->options('taxonomies'),
			'_builtin'   => $this->options('built_in')
		);

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


class Document extends CustomPostType{
	public
		$name           = 'document',
		$plural_name    = 'Documents',
		$singular_name  = 'Document',
		$add_new_item   = 'Add New Document',
		$edit_item      = 'Edit Document',
		$new_item       = 'New Document',
		$use_title      = True,
		$use_editor     = False,
		$use_shortcode  = True,
		$use_metabox    = True;

	public function fields(){
		$fields   = parent::fields();
		$fields[] = array(
			'name' => __('URL'),
			'desc' => __('Associate this document with a URL.  This will take precedence over any uploaded file, so leave empty if you want to use a file instead.'),
			'id'   => $this->options('name').'_url',
			'type' => 'text',
		);
		$fields[] = array(
			'name'    => __('File'),
			'desc'    => __('Associate this document with an already existing file.'),
			'id'      => $this->options('name').'_file',
			'type'    => 'file',
		);
		return $fields;
	}


	static function get_document_application($form){
		return mimetype_to_application(self::get_mimetype($form));
	}


	static function get_mimetype($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}

		$prefix   = post_type($form);
		$document = get_post(get_post_meta($form->ID, $prefix.'_file', True));

		$is_url = get_post_meta($form->ID, $prefix.'_url', True);

		return ($is_url) ? "text/html" : $document->post_mime_type;
	}


	static function get_title($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}

		$prefix = post_type($form);

		return $form->post_title;
	}

	static function get_url($form){
		if (is_numeric($form)){
			$form = get_post($form);
		}

		$prefix = post_type($form);

		$x = get_post_meta($form->ID, $prefix.'_url', True);
		$y = wp_get_attachment_url(get_post_meta($form->ID, $prefix.'_file', True));

		if (!$x and !$y){
			return '#';
		}

		return ($x) ? $x : $y;
	}


	/**
	 * Handles output for a list of objects, can be overridden for descendants.
	 * If you want to override how a list of objects are outputted, override
	 * this, if you just want to override how a single object is outputted, see
	 * the toHTML method.
	 **/
	public function objectsToHTML($objects, $css_classes){
		if (count($objects) < 1){ return '';}

		$class_name = get_custom_post_type($objects[0]->post_type);
		$class      = new $class_name;

		ob_start();
		?>
		<ul class="nobullet <?php if($css_classes):?><?=$css_classes?><?php else:?><?=$class->options('name')?>-list<?php endif;?>">
			<?php foreach($objects as $o):?>
			<li class="document <?=$class_name::get_document_application($o)?>">
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
		$title = Document::get_title($object);
		$url   = Document::get_url($object);
		$html = "<a href='{$url}'>{$title}</a>";
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
 * Describes an Alumni Note
 *
 * @author Jo Greybill
 *
**/
class AlumniNote extends CustomPostType{
	public
		$name           = 'alumninote',
		$plural_name    = 'Alumni Notes',
		$singular_name  = 'Alumni Note',
		$add_new_item   = 'Add New Alumni Note',
		$edit_item      = 'Edit Alumni Note',
		$new_item       = 'New Alumni Note',
		$public         = True,
		$use_editor     = True,
		$use_thumbnails = True,
		$use_order      = True,
		$use_title      = True,
		$use_metabox    = True;

	public function toHTML($alumninote){
		return sc_alumninote(array('alumninote' => $alumninote));
	}

	public function fields(){
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name'  => 'Author',
				'desc' => 'The note\'s author(\'s) name',
				'id'   => $prefix.'author',
				'type' => 'text',
			),
			array(
				'name'  => 'Email',
				'desc' => 'The author\'s email address',
				'id'   => $prefix.'email',
				'type' => 'text',
			),
			array(
				'name' => 'Class Year',
				'desc' => 'The year(s) in which the author graduated from UCF',
				'id'   => $prefix.'class_year',
				'type' => 'text',
			),
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
		$taxonomies     = array('issues', 'post_tag');

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
					'Default (generic story)' => 'default',
					'Photo gallery' => 'gallery',
					'Custom story (requires custom CSS/JS)' => 'custom',
				)
			),
			array(
				'name' => 'Story Subtitle',
				'desc' => 'A subtitle for the story.  This will be displayed next to the story title where stories are listed; i.e., the site header and footer.',
				'id'   => $prefix.'subtitle',
				'type' => 'text',
			),
			array(
				'name' => 'Story Description',
				'desc' => 'A one to two sentence description for the story.  This will be displayed underneath the story\'s title in default story templates, and potentially on
							default issue templates.',
				'id'   => $prefix.'description',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Family',
				'desc' => 'The font family to use for title lines in this story.  Font sizes/line heights are determined automatically based on the font selected.',
				'id'   => $prefix.'default_font',
				'type'    => 'select',
				'options' => $font_options,
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Color',
				'desc' => 'Color for h1-h6 titles, as well as blockquotes and dropcaps.  Hex values preferred.',
				'id'   => $prefix.'default_color',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Image',
				'desc' => 'Large feature image to go at the very top of the story.  Recommended dimensions: 1200x600px',
				'id'   => $prefix.'default_header_img',
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
							(functions/config.php).  Fonts should be referenced by name and be comma-separated.',
				'id'   => $prefix.'fonts',
				'type' => 'textarea',
			),
		);
		if (DEV_MODE == true) {
			array_unshift($fields, array(
				'name' => '<strong>Developer Mode:</strong> Directory URL',
				'desc' => 'Directory to this story in the theme\'s dev folder (include trailing slash, relative to <code>/dev/</code>).  Properly named html, css and javascript files
							(story-slug.html/css/js) in this directory will be automatically referenced for this story if they are available.<br/><br/>
							<strong>NOTE:</strong>
							<ul style="list-style: disc !important;">
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
		foreach(get_issue_stories($post) as $story) {
			$story_options[$story->post_title] = $story->ID;
		}
		$font_options = array();
		foreach (unserialize(TEMPLATE_FONT_STYLES) as $key => $val) {
			$font_options[$key] = $key;
		}

		$fields = array(
			array(
				'name'    => 'Cover Story',
				'desc'    => '',
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
					'Default' => 'default',
					'Custom (requires custom CSS/JS)' => 'custom',
				)
			),

			array(
				'name' => '<strong>Default Template:</strong> Header Font Family',
				'desc' => 'The font family to use for primary title lines in this issue cover.',
				'id'   => $prefix.'default_font',
				'type' => 'select',
				'options' => $font_options,
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Color',
				'desc' => 'Color for h1 title.  Hex values preferred.',
				'id'   => $prefix.'default_color',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Size (Desktop)',
				'desc' => 'Font size for h1 title at desktop sizes.  Specify "px", "em", etc. in this value (e.g. "20px")',
				'id'   => $prefix.'default_fontsize_d',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Size (Tablet)',
				'desc' => 'Font size for h1 title at tablet sizes.  Specify "px", "em", etc. in this value (e.g. "20px")',
				'id'   => $prefix.'default_fontsize_t',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Size (Mobile)',
				'desc' => 'Font size for h1 title at mobile sizes.  Specify "px", "em", etc. in this value (e.g. "20px")',
				'id'   => $prefix.'default_fontsize_m',
				'type' => 'text',
			),
			array(
				'name' => '<strong>Default Template:</strong> Header Font Text Align',
				'desc' => 'Alignment of the h1 title within its container.',
				'id'   => $prefix.'default_textalign',
				'type' => 'select',
				'options' => array(
					'Left' => 'left',
					'Center' => 'center',
					'Right' => 'right',
				),
			),

			array(
				'name' => '<strong>Custom Issue Template:</strong> Home Page Stylesheet',
				'desc' => 'Stylesheet specifically for the issue cover/home page.',
				'id'   => $prefix.'stylesheet_home',
				'type' => 'file',
			),
			array(
				'name' => '<strong>Custom Issue Template:</strong> Home Page JavaScript File',
				'desc' => 'JavaScript file that runs exclusively on the issue cover/home page for this issue.',
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

		if (DEV_MODE == true) {
			array_unshift($fields, array(
				'name' => '<strong>Developer Mode:</strong> Issue\'s Home Page Asset Directory',
				'desc' => 'Directory to this issue\'s home page assets in the theme\'s dev folder (include trailing slash).  Properly named html, css and javascript files
							(home.html/css/js) in this directory will be automatically referenced for the issue home page if they are available.<br/><br/>
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
		$use_editor     = False,
		$use_thumbnails = False,
		$use_order      = False,
		$use_title      = True,
		$use_metabox    = True,
		$use_revisions	= False,
		$taxonomies     = array('issues');

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
	  * The 'type' val is just for determining which fields are file fields;
	  * 'default' is an arbitrary name for 'anything else' which gets saved
	  * via the save_default() function in functions/base.php. File fields
	  * need a type of 'file' to be saved properly.
	  **/
	public static function get_single_slide_meta() {
		$single_slide_meta = array(
			array(
				'id'	=> 'ss_slide_caption',
				'type'	=> 'default',
				'val'	=> $_POST['ss_slide_caption'],
			),
			array(
				'id'	=> 'ss_slide_image',
				'type'	=> 'file',
				'val' 	=> $_POST['ss_slide_image'],
			),
		);
		return $single_slide_meta;
	}


	/**
	  * Show meta box fields for Slider post type (generic field loop-through)
	  * Copied from _show_meta_boxes (functions/base.php)
	 **/
	public static function display_meta_fields($post, $field) {
		$current_value = get_post_meta($post->ID, $field['id'], true);
	?>
		<tr>
			<th><label for="<?=$field['id']?>"><?=$field['name']?></label></th>
				<td>
				<?php switch ($field['type']):
					case 'text':?>
				<input type="text" name="<?=$field['id']?>" id="<?=$field['id']?>" value="<?=($current_value) ? htmlentities($current_value) : $field['std']?>" />
				<?php break; case 'textarea':?>
					<textarea name="<?=$field['id']?>" id="<?=$field['id']?>" cols="60" rows="4"><?=($current_value) ? htmlentities($current_value) : $field['std']?></textarea>

				<?php break; case 'file':?>
					<?php
						$document_id = get_post_meta($post->ID, $field['id'], True);
						if ($document_id){
							$document = get_post($document_id);
							$url      = wp_get_attachment_url($document->ID);
						}else{
							$document = null;
						}
					?>
					<?php if($document):?>
					<a href="<?=$url?>"><?=$document->post_title?></a><br /><br />
					<?php endif;?>
					<input type="file" id="file_<?=$post->ID?>" name="<?=$field['id']?>"><br />

				<?php break; default:?>
					<p class="error">Don't know how to handle field of type '<?=$field['type']?>'</p>
				<?php break; endswitch;?>
				</td>
			</tr>
	<?php
	}


	/**
	 * Show fields for single slides:
	 **/
	public static function display_slide_meta_fields($post) {

		// Get any already-existing values for these fields:
		$slide_caption		= get_post_meta($post->ID, 'ss_slide_caption', TRUE);
		$slide_image		= get_post_meta($post->ID, 'ss_slide_image', TRUE);
		$slide_order 		= get_post_meta($post->ID, 'ss_slider_slideorder', TRUE);
		?>
		<div id="ss_slides_wrapper">
			<ul id="ss_slides_all">
				<?php

					// Loop through slides_array for existing slides. Else, display
					// a single empty slide 'widget'.
					if ($slide_order) {
						$slide_array = explode(",", $slide_order);

						foreach ($slide_array as $s) {
							if ($s !== '') {
					?>
							<li class="custom_repeatable postbox">

								<div class="handlediv" title="Click to toggle"> </div>
									<h3 class="hndle">
									<span>Slide</span>
								</h3>

								<table class="form-table">
								<input type="hidden" name="meta_box_nonce" value="<?=wp_create_nonce('nonce-content')?>"/>
									<tr>
										<th><label for="ss_slide_caption[<?=$s?>]">Caption</label></th>
										<td>
											<span class="description">Caption for this slide.</span><br/>
											<textarea name="ss_slide_caption[<?=$s?>]" id="ss_slide_caption[<?=$s?>]" cols="60" rows="4"><?php ($slide_caption[$s] !== '') ? print $slide_caption[$s] : ''; ?></textarea>
										</td>
									</tr>
									<tr>
										<th><label for="ss_slide_image[<?=$s?>]">Slide Image</label></th>
										<td>
											<span class="description">Description here.</span><br/>
											<?php
												if ($slide_image[$s]){
													$image = get_post($slide_image[$s]);
													$url   = wp_get_attachment_url($image->ID);
												}else{
													$image= null;
												}
											?>
											<?php if($image):?>
											<a href="<?=$url?>"><?=$image->post_title?></a><br /><br />
											<?php endif;?>
											<input type="file" <?php if($image){ ?>class="has-value"<?php } ?> id="file_img_<?=$post->ID?>" name="ss_slide_image[<?=$s?>]"><br />
										</td>
									</tr>
								</table>
								<a class="repeatable-remove button" href="#">Remove Slide</a>
							</li>

					<?php
							}
						}

					} else {
						$i = 0;
						?>
						<li class="custom_repeatable postbox">

							<div class="handlediv" title="Click to toggle"> </div>
								<h3 class="hndle">
								<span>Slide</span>
							</h3>
							<table class="form-table">
							<input type="hidden" name="meta_box_nonce" value="<?=wp_create_nonce('nonce-content')?>"/>
								<tr>
									<th><label for="ss_slide_caption[<?=$i?>]">Slide Caption</label></th>
									<td>
										<span class="description">Caption for this slide.</span><br/>
										<textarea name="ss_slide_caption[<?=$i?>]" id="ss_slide_caption[<?=$i?>]" cols="60" rows="4"></textarea>
									</td>
								</tr>
								<tr>
									<th><label for="ss_slide_image[<?=$i?>]">Slide Image</label></th>
									<td>
										<span class="description">Description here.</span><br/>
										<input type="file" id="file_<?=$post->ID?>" name="ss_slide_image[<?=$i?>]"><br />
									</td>
								</tr>
							</table>
							<a class="repeatable-remove button" href="#">Remove Slide</a>
						</li>
						<?php

					}
				?>
						<a class="repeatable-add button-primary" href="#">Add New Slide</a><br/>
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
			foreach($meta_box['fields'] as $field):
				$this->display_meta_fields($post, $field);
			endforeach;
		print "</table>";
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
