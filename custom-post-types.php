<?php

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
		$built_in       = False;
	
	
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
		$use_thumbnails = False,
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
				'desc' => 'The note\'s author\'s name',
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
				'desc' => 'The year in which the author graduated from UCF',
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
		$taxonomies     = array('editions');

	public function fields() {
		$prefix = $this->options('name').'_';
		return array(
			array(
				'name' => 'Story Subtitle',
				'desc' => 'A subtitle for the story.  This will be displayed next to the story title where stories are listed; i.e., the site header and footer.',
				'id'   => $prefix.'subtitle',
				'type' => 'text',
			),
			array(
				'name' => 'Stylesheet',
				'desc' => '',
				'id'   => $prefix.'stylesheet',
				'type' => 'file',
			),
			array(
				'name' => 'Home Page Feature',
				'desc' => 'Check this box if this story is a main featured story on the home page.  It will not appear as a duplicate story in the footer on the home page if this box is checked.',
				'id'   => $prefix.'isfeatured',
				'type' => 'checkbox',
			)
		);
	}
}
?>