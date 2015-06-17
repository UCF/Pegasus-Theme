<?php

/***************************************************************************
 * CLASSES
 *
 ***************************************************************************/

/**
 * The Config class provides a set of static properties and methods which store
 * and facilitate configuration of the theme.
 **/
class ArgumentException extends Exception{}
class Config{
	static
		$body_classes      = array(), # Body classes
		$theme_settings    = array(), # Theme settings
		$custom_post_types = array(), # Custom post types to register
		$custom_taxonomies = array(), # Custom taxonomies to register
		$styles            = array(), # Stylesheets to register
		$scripts           = array(), # Scripts to register
		$links             = array(), # <link>s to include in <head>
		$metas             = array(); # <meta>s to include in <head>


	/**
	 * Creates and returns a normalized name for a resource url defined by $src.
	 **/
	static function generate_name($src, $ignore_suffix=''){
		$base = basename($src, $ignore_suffix);
		$name = slug($base);
		return $name;
	}


	/**
	 * Registers a stylesheet with built-in wordpress style registration.
	 * Arguments to this can either be a string or an array with required css
	 * attributes.
	 *
	 * A string argument will be treated as the src value for the css, and all
	 * other attributes will default to the most common values.  To override
	 * those values, you must pass the attribute array.
	 *
	 * Array Argument:
	 * $attr = array(
	 *    'name'  => 'theme-style',  # Wordpress uses this to identify queued files
	 *    'media' => 'all',          # What media types this should apply to
	 *    'admin' => False,          # Should this be used in admin as well?
	 *    'src'   => 'http://some.domain/style.css',
	 * );
	 **/
	static function add_css($attr){
		# Allow string arguments, defining source.
		if (is_string($attr)){
			$new        = array();
			$new['src'] = $attr;
			$attr       = $new;
		}

		if (!isset($attr['src'])){
			throw new ArgumentException('add_css expects argument array to contain key "src"');
		}
		$default = array(
			'name'  => self::generate_name($attr['src'], '.css'),
			'media' => 'all',
			'admin' => False,
		);
		$attr = array_merge($default, $attr);

		$is_admin = (is_admin() or is_login());

		if (
			($attr['admin'] and $is_admin) or
			(!$attr['admin'] and !$is_admin)
		){
			wp_deregister_style($attr['name']);
			wp_enqueue_style($attr['name'], $attr['src'], null, null, $attr['media']);
		}
	}


	/**
	 * Functions similar to add_css, but appends scripts to the footer instead.
	 * Accepts a string or array argument, like add_css, with the string
	 * argument assumed to be the src value for the script.
	 *
	 * Array Argument:
	 * $attr = array(
	 *    'name'  => 'jquery',  # Wordpress uses this to identify queued files
	 *    'admin' => False,     # Should this be used in admin as well?
	 *    'src'   => 'http://some.domain/style.js',
	 * );
	 **/
	static function add_script($attr){
		# Allow string arguments, defining source.
		if (is_string($attr)){
			$new        = array();
			$new['src'] = $attr;
			$attr       = $new;
		}

		if (!isset($attr['src'])){
			throw new ArgumentException('add_script expects argument array to contain key "src"');
		}
		$default = array(
			'name'  => self::generate_name($attr['src'], '.js'),
			'admin' => False,
		);
		$attr = array_merge($default, $attr);

		$is_admin = (is_admin() or is_login());

		if (
			($attr['admin'] and $is_admin) or
			(!$attr['admin'] and !$is_admin)
		){
			# Override previously defined scripts
			wp_deregister_script($attr['name']);
			wp_enqueue_script($attr['name'], $attr['src'], null, null, True);
		}
	}
}

/**
 * Abstracted field class, all form fields should inherit from this.
 *
 * @package default
 * @author Jared Lang
 **/
abstract class Field{
	protected function check_for_default(){
		if ( ( $this->value === null || $this->value === '' ) && isset( $this->default ) ) {
			$this->value = $this->default;
		}
	}

	function __construct($attr){
		$this->name        = @$attr['name'];
		$this->id          = @$attr['id'];
		$this->value       = @$attr['value'];
		$this->description = @$attr['description'];
		$this->default     = @$attr['default'];

		$this->check_for_default();
	}

	function label_html(){
		ob_start();
		?>
		<label class="block" for="<?=htmlentities($this->id)?>"><?=__($this->name)?></label>
		<?php
		return ob_get_clean();
	}

	function input_html(){
		return "Abstract Input Field, Override in Descendants";
	}

	function description_html(){
		ob_start();
		?>
		<?php if($this->description):?>
		<p class="description"><?=__($this->description)?></p>
		<?php endif;?>
		<?php
		return ob_get_clean();
	}

	function html(){
		$label       = $this->label_html();
		$input       = $this->input_html();
		$description = $this->description_html();

		return $label.$input.$description;
	}
}


/**
 * Abstracted choices field.  Choices fields have an additional attribute named
 * choices which allow a selection of values to be chosen from.
 *
 * @package default
 * @author Jared Lang
 **/
abstract class ChoicesField extends Field{
	// Ensure 'default' value is added to choices if it isn't already
	protected function add_default_to_choices() {
		if ( isset( $this->default ) && !array_key_exists( $this->default, $this->choices ) ) {
			$this->choices = array( $this->default => '' ) + $this->choices;
		}
	}

	function __construct($attr){
		$this->choices = @$attr['choices'];
		parent::__construct($attr);
		$this->add_default_to_choices();
	}
}


/**
 * TextField class represents a simple text input
 *
 * @package default
 * @author Jared Lang
 **/
class TextField extends Field{
	protected $type_attr = 'text';

	function input_html(){
		ob_start();
		?>
		<input type="<?=$this->type_attr?>" id="<?=htmlentities($this->id)?>" name="<?=htmlentities($this->id)?>" value="<?=htmlentities($this->value)?>" />
		<?php
		return ob_get_clean();
	}
}


/**
 * PasswordField can be used to accept sensitive information, not encrypted on
 * wordpress' end however.
 *
 * @package default
 * @author Jared Lang
 **/
class PasswordField extends TextField{
	protected $type_attr = 'password';
}


/**
 * TextareaField represents a textarea form element
 *
 * @package default
 * @author Jared Lang
 **/
class TextareaField extends Field{
	function input_html(){
		ob_start();
		?>
		<textarea cols="60" rows="4" id="<?php echo htmlentities( $this->id ); ?>" name="<?php echo htmlentities( $this->id ); ?>"><?php echo $this->value; ?></textarea>
		<?php
		return ob_get_clean();
	}
}


/**
 * Textarea field with simple WYSIWYG editor capabilities.
 **/
class WysiwygField extends Field {
	function input_html() {
		ob_start();
	?>
		<div class="wysihtml5-editor" id="wysihtml5-toolbar-<?php echo htmlentities( $this->id ); ?>" data-textarea-id="<?php echo htmlentities( $this->id ); ?>" style="display: none;">
			<a class="wysihtml5-strong" data-wysihtml5-command="formatInline" data-wysihtml5-command-value="strong">strong</a>
			<a class="wysihtml5-em" data-wysihtml5-command="formatInline" data-wysihtml5-command-value="em">em</a>
			<a class="wysihtml5-u" data-wysihtml5-command="underline" data-wysihtml5-command-value="u">underline</a>

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
		<textarea name="<?php echo htmlentities( $this->id ); ?>" id="<?php echo htmlentities( $this->id ); ?>" cols="48" rows="8"><?php echo $this->value; ?></textarea>
	<?php
		return ob_get_clean();
	}
}


/**
 * Select form element
 *
 * @package default
 * @author Jared Lang
 **/
class SelectField extends ChoicesField{
	function input_html(){
		ob_start();
		?>
		<select name="<?=htmlentities($this->id)?>" id="<?=htmlentities($this->id)?>">
			<?php foreach($this->choices as $key=>$value):?>
			<option<?php if($this->value == $value):?> selected="selected"<?php endif;?> value="<?=htmlentities($value)?>"><?=htmlentities($key)?></option>
			<?php endforeach;?>
		</select>
		<?php
		return ob_get_clean();
	}
}


/**
 * Radio form element
 *
 * @package default
 * @author Jared Lang
 **/
class RadioField extends ChoicesField{
	function input_html(){
		ob_start();
		?>
		<ul class="radio-list">
			<?php $i = 0; foreach($this->choices as $key=>$value): $id = htmlentities($this->id).'_'.$i++;?>
			<li>
				<input<?php if($this->value == $value):?> checked="checked"<?php endif;?> type="radio" name="<?=htmlentities($this->id)?>" id="<?=$id?>" value="<?=htmlentities($value)?>" />
				<label for="<?=$id?>"><?=htmlentities($key)?></label>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		return ob_get_clean();
	}
}


/**
 * Checkbox form element
 *
 * @package default
 * @author Jared Lang
 **/
class CheckboxField extends ChoicesField{
	function input_html(){
		ob_start();
		?>
		<ul class="checkbox-list">
			<?php $i = 0; foreach($this->choices as $key=>$value): $id = htmlentities($this->id).'_'.$i++;?>
			<li>
				<input<?php if(is_array($this->value) and in_array($value, $this->value)):?> checked="checked"<?php endif;?> type="checkbox" name="<?=htmlentities($this->id)?>[]" id="<?=$id?>" value="<?=htmlentities($value)?>" />
				<label for="<?=$id?>"><?=htmlentities($key)?></label>
			</li>
			<?php endforeach;?>
		</ul>
		<?php
		return ob_get_clean();
	}
}



/**
 * Convenience class to calculate total execution times.
 *
 * @package default
 * @author Jared Lang
 **/
class Timer{
	private $start_time  = null;
	private $end_time    = null;

	public function start_timer(){
		$this->start_time = microtime(True);
		$this->end_time   = null;
	}

	public function stop_timer(){
		$this->end_time = microtime(True);
	}

	public function clear_timer(){
		$this->start_time = null;
		$this->end_time   = null;
	}

	public function reset_timer(){
		$this->clear_timer();
		$this->start_timer();
	}

	public function elapsed(){
		if ($this->end_time !== null){
			return $this->end_time - $this->start_time;
		}else{
			return microtime(True) - $this->start_time;
		}
	}

	public function __toString(){
		return $this->elapsed;
	}

	/**
	 * Returns a started instance of timer
	 *
	 * @return instance of Timer
	 * @author Jared Lang
	 **/
	public static function start(){
		$timer_instance = new self();
		$timer_instance->start_timer();
		return $timer_instance;
	}
}




/***************************************************************************
 * DEBUGGING FUNCTIONS
 *
 * Functions to assist in theme debugging.
 *
 ***************************************************************************/

/**
 * Given an arbitrary number of arguments, will return a string with the
 * arguments dumped recursively, similar to the output of print_r but with pre
 * tags wrapped around the output.
 *
 * @return string
 * @author Jared Lang
 **/
function dump(){
	$args = func_get_args();
	$out  = array();
	foreach($args as $arg){
		$out[] = print_r($arg, True);
	}
	$out = implode("<br />", $out);
	return "<pre>{$out}</pre>";
}


/**
 * Will add a debug comment to the output when the debug constant is set true.
 * Any value, including null, is enough to trigger it.
 *
 * @return void
 * @author Jared Lang
 **/
if ( defined( 'DEBUG' ) ) {
	function debug( $string ) { /*
		print "<!-- DEBUG: {$string} -->\n"; */
	}
}
else {
	function debug( $string ) {
		return;
	}
}


/**
 * Will execute the function $func with the arguments passed via $args if the
 * debug constant is set true.  Returns whatever value the called function
 * returns, or void if debug is not set active.
 *
 * @return mixed
 * @author Jared Lang
 **/
if ( defined( 'DEBUG' ) ) {
	function debug_callfunc( $func, $args ) {
		return call_user_func_array( $func, $args );
	}
}
else {
	function debug_callfunc( $func, $args ) {
		return;
	}
}


/**
 * Indent contents of $html passed by $n indentations.
 *
 * @return string
 * @author Jared Lang
 **/
function indent($html, $n){
	$tabs = str_repeat("\t", $n);
	$html = explode("\n", $html);
	foreach($html as $key=>$line){
		$html[$key] = $tabs.trim($line);
	}
	$html = implode("\n", $html);
	return $html;
}



/***************************************************************************
 * GENERAL USE FUNCTIONS
 *
 * Theme-wide general use functions. (Alphabetized)
 *
 ***************************************************************************/

/**
 * Walker function to add Bootstrap classes to nav menus using wp_nav_menu()
 *
 * based on https://gist.github.com/1597994
 **/
function bootstrap_menus() {
	class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {


			function start_lvl( &$output, $depth ) {

				$indent = str_repeat( "\t", $depth );
				$output	   .= "\n$indent<ul class=\"dropdown-menu\">\n";

			}

			function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

				$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

				$li_attributes = '';
				$class_names = $value = '';

				$classes = empty( $item->classes ) ? array() : (array) $item->classes;
				$classes[] = ($args->has_children) ? 'dropdown' : '';
				$classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
				$classes[] = 'menu-item-' . $item->ID;


				$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
				$class_names = ' class="' . esc_attr( $class_names ) . '"';

				$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
				$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

				$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';

				$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
				$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
				$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
				$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
				$attributes .= ($args->has_children) 	    ? ' class="dropdown-toggle" data-toggle="dropdown"' : '';

				$item_output = $args->before;
				$item_output .= '<a'. $attributes .'>';
				$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
				$item_output .= ($args->has_children) ? ' <b class="caret"></b></a>' : '</a>';
				$item_output .= $args->after;

				$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
			}

			function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {

				if ( !$element )
					return;

				$id_field = $this->db_fields['id'];

				//display this element
				if ( is_array( $args[0] ) )
					$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
				else if ( is_object( $args[0] ) )
					$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'start_el'), $cb_args);

				$id = $element->$id_field;

				// descend only when the depth is right and there are childrens for this element
				if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {

					foreach( $children_elements[ $id ] as $child ){

						if ( !isset($newlevel) ) {
							$newlevel = true;
							//start the child delimiter
							$cb_args = array_merge( array(&$output, $depth), $args);
							call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
						}
						$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
					}
						unset( $children_elements[ $id ] );
				}

				if ( isset($newlevel) && $newlevel ){
					//end the child delimiter
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
				}

				//end this element
				$cb_args = array_merge( array(&$output, $element, $depth), $args);
				call_user_func_array(array(&$this, 'end_el'), $cb_args);

			}

		}
}
add_action( 'after_setup_theme', 'bootstrap_menus' );


/**
 * Strings passed to this function will be modified under the assumption that
 * they were outputted by wordpress' the_output filter.  It checks for a handful
 * of things like empty, unnecessary, and unclosed tags.
 *
 * @return string
 * @author Jared Lang
 **/
function cleanup($content){
	# Balance auto paragraphs
	$lines = explode("\n", $content);
	foreach($lines as $key=>$line){
		$null = null;
		$found_closed = preg_match_all('/<\/p>/', $line, $null);
		$found_opened = preg_match_all('/<p[^>]*>/', $line, $null);

		$diff = $found_closed - $found_opened;
		# Balanced tags
		if ($diff == 0){continue;}

		# missing closed
		if ($diff < 0){
			$lines[$key] = $lines[$key] . str_repeat('</p>', abs($diff));
		}

		# missing open
		if ($diff > 0){
			$lines[$key] = str_repeat('<p>', abs($diff)) . $lines[$key];
		}
	}
	$content = implode("\n", $lines);

	#Remove incomplete tags at start and end
	$content = preg_replace('/^<\/p>[\s]*/i', '', $content);
	$content = preg_replace('/[\s]*<p>$/i', '', $content);
	$content = preg_replace('/^<br \/>/i', '', $content);
	$content = preg_replace('/<br \/>$/i', '', $content);

	#Remove paragraph and linebreak tags wrapped around shortcodes
	$content = preg_replace('/(<p>|<br \/>)\[/i', '[', $content);
	$content = preg_replace('/\](<\/p>|<br \/>)/i', ']', $content);

	#Remove empty paragraphs
	$content = preg_replace('/<p><\/p>/i', '', $content);

	return $content;
}


/**
 * Creates a string of attributes and their values from the key/value defined by
 * $attr.  The string is suitable for use in html tags.
 *
 * @return string
 * @author Jared Lang
 **/
function create_attribute_string($attr){
	$attr_string = '';
	foreach($attr as $key=>$value){
		$attr_string .= " {$key}='{$value}'";
	}
	return $attr_string;
}


/**
 * Creates an arbitrary html element.  $tag defines what element will be created
 * such as a p, h1, or div.  $attr is an array defining attributes and their
 * associated values for the tag created. $content determines what data the tag
 * wraps.  And $self_close defines whether or not the tag should close like
 * <tag></tag> (False) or <tag /> (True).
 *
 * @return string
 * @author Jared Lang
 **/
function create_html_element($tag, $attr=array(), $content=null, $self_close=True){
	$attr_str = create_attribute_string($attr);
	if ($content){
		$element = "<{$tag}{$attr_str}>{$content}</{$tag}>";
	}else{
		if ($self_close){
			$element = "<{$tag}{$attr_str}/>";
		}else{
			$element = "<{$tag}{$attr_str}></{$tag}>";
		}
	}

	return $element;
}


/**
 * When called, prevents direct loads of the value of $page.
 **/
function disallow_direct_load($page){
	if ($page == basename($_SERVER['SCRIPT_FILENAME'])){
		die('No');
	}
}


/**
 * Given a name will return the custom post type's class name, or null if not
 * found
 *
 * @return string
 * @author Jared Lang
 **/
function get_custom_post_type($name){
	$installed = installed_custom_post_types();
	foreach($installed as $object){
		if ($object->options('name') == $name){
			return get_class($object);
		}
	}
	return null;
}


/**
 * Get value of Theme Option Header Menu Styles and return relevant Boostrap
 * CSS classes.  Indended for use as wp_nav_menu()'s menu_class argument.
 * See http://codex.wordpress.org/Function_Reference/wp_nav_menu
 *
 * @author Jo Greybill
 **/
function get_header_styles() {
	$options = get_option(THEME_OPTIONS_NAME);
	$id = $options['bootstrap_menu_styles'];

	switch ($id) {
		case 'nav-tabs':
			$header_menu_class = 'nav nav-tabs';
			break;
		case 'nav-pills':
			$header_menu_class = 'nav nav-pills';
			break;
		default:
			$header_menu_class = 'horizontal';
			break;
	}
	return $header_menu_class;

}


/**
 * Return an array of choices representing all the images uploaded to the media
 * gallery.
 *
 * @return array
 * @author Jared Lang
 **/
function get_image_choices(){
	$image_mimes = array(
		'image/jpeg',
		'image/png',
	);

	$images = array('(None)' => null);
	$args   = array(
		'post_type'   => 'attachment',
		'post_status' => 'inherit',
		'numberposts' => -1,
	);

	$attachments = get_posts($args);
	$attachments = array_filter($attachments, create_function('$a', '
		$is_image = (strpos($a->post_mime_type, "image/") !== False);
		return $is_image;
	'));
	foreach($attachments as $image){
		$filename = basename(get_attached_file($image->ID));
		$value    = $image->ID;
		$key      = $image->post_title. " | {$filename}";
		$images[$key] = $value;
	}
	return $images;
}


/**
 * Wraps wordpress' native functions, allowing you to get a menu defined by
 * its location rather than the name given to the menu.  The argument $classes
 * lets you define a custom class(es) to place on the list generated, $id does
 * the same but with an id attribute.
 *
 * If you require more customization of the output, a final optional argument
 * $callback lets you specify a function that will generate the output. Any
 * callback passed should accept one argument, which will be the items for the
 * menu in question.
 *
 * @return void
 * @author Jared Lang
 **/
function get_menu($name, $classes=null, $id=null, $callback=null){
	$locations = get_nav_menu_locations();
	$menu      = @$locations[$name];

	if (!$menu){
		return "<div class='error'>No menu location found with name '{$name}'. Set up menus in the <a href='".get_admin_url()."nav-menus.php'>admin's appearance menu.</a></div>";
	}

	$items = wp_get_nav_menu_items($menu);

	if ($callback === null){
		ob_start();
		?>
		<ul<?php if($classes):?> class="<?=$classes?>"<?php endif;?><?php if($id):?> id="<?=$id?>"<?php endif;?>>
			<?php foreach($items as $key=>$item): $last = $key == count($items) - 1;?>
			<li<?php if($last):?> class="last"<?php endif;?>><a href="<?=$item->url?>"><?=$item->title?></a></li>
			<?php endforeach;?>
		</ul>
		<?php
		$menu = ob_get_clean();
	}else{
		$menu = call_user_func($callback, $items);
	}

	return $menu;

}


/**
 * Uses the google search appliance to search the current site or the site
 * defined by the argument $domain.
 *
 * @return array
 * @author Jared Lang
 **/
function get_search_results(
		$query,
		$start=null,
		$per_page=null,
		$domain=null,
		$search_url="http://google.cc.ucf.edu/search"
	){
	$start     = ($start) ? $start : 0;
	$per_page  = ($per_page) ? $per_page : 10;
	$domain    = ($domain) ? $domain : $_SERVER['SERVER_NAME'];
	$results   = array(
		'number' => 0,
		'items'  => array(),
	);
	$query     = trim($query);
	$per_page  = (int)$per_page;
	$start     = (int)$start;
	$query     = urlencode($query);
	$arguments = array(
		'num'        => $per_page,
		'start'      => $start,
		'ie'         => 'UTF-8',
		'oe'         => 'UTF-8',
		'client'     => 'default_frontend',
		'output'     => 'xml',
		'sitesearch' => $domain,
		'q'          => $query,
	);

	if (strlen($query) > 0){
		$query_string = http_build_query($arguments);
		$url          = $search_url.'?'.$query_string;
		$response     = file_get_contents($url);

		if ($response){
			$xml   = simplexml_load_string($response);
			$items = $xml->RES->R;
			$total = $xml->RES->M;

			$temp = array();

			if ($total){
				foreach($items as $result){
					$item            = array();
					$item['url']     = str_replace('https', 'http', $result->U);
					$item['title']   = $result->T;
					$item['rank']    = $result->RK;
					$item['snippet'] = $result->S;
					$item['mime']    = $result['MIME'];
					$temp[]          = $item;
				}
				$results['items'] = $temp;
			}
			$results['number'] = $total;
		}
	}

	return $results;
}


/**
 * Returns true if the current request is on the login screen.
 *
 * @return boolean
 * @author Jared Lang
 **/
function is_login(){
	return in_array($GLOBALS['pagenow'], array(
			'wp-login.php',
			'wp-register.php',
	));
}


/**
 * Destroy empty <p> tags because wpautop is dumb.
 **/
function kill_empty_p_tags($content) {
	$killme = array('<p></p>', '<p>&nbsp;</p>', '<p>  </p>');
	return str_replace($killme, '', $content);
}


/**
 * Given a mimetype, will attempt to return a string representing the
 * application it is associated with.  If the mimetype is unknown, the default
 * return is 'document'.
 *
 * @return string
 * @author Jared Lang
 **/
function mimetype_to_application($mimetype){
	switch($mimetype){
		default:
			$type = 'document';
			break;
		case 'text/html':
			$type = "html";
			break;
		case 'application/zip':
			$type = "zip";
			break;
		case 'application/pdf':
			$type = 'pdf';
			break;
		case 'application/msword':
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			$type = 'word';
			break;
		case 'application/msexcel':
		case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
			$type = 'excel';
			break;
		case 'application/vnd.ms-powerpoint':
		case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			$type = 'powerpoint';
			break;
	}
	return $type;
}


/**
 * Really get the post type.  A post type of revision will return it's parent
 * post type.
 *
 * @return string
 * @author Jared Lang
 **/
function post_type($post){
	if (is_int($post)){
		$post = get_post($post);
	}

	# check post_type field
	$post_type = $post->post_type;

	if ($post_type === 'revision'){
		$parent    = (int)$post->post_parent;
		$post_type = post_type($parent);
	}

	return $post_type;
}


/**
* Fetches objects defined by arguments passed, outputs the objects according
* to the objectsToHTML method located on the object. Used by the auto
* generated shortcodes enabled on custom post types. See also:
*
* CustomPostType::objectsToHTML
* CustomPostType::toHTML
*
* @return string
* @author Jared Lang
**/
function sc_object_list($attrs, $options = array()){
	if (!is_array($attrs)){return '';}

	$default_options = array(
		'default_content' => null,
		'sort_func' => null,
		'objects_only' => False
	);

	extract(array_merge($default_options, $options));

	# set defaults and combine with passed arguments
	$default_attrs = array(
		'type'    => null,
		'limit'   => -1,
		'join'    => 'or',
		'class'   => '',
		'orderby' => 'menu_order title',
		'order'   => 'ASC',
		'offset'  => 0
	);
	$params = array_merge($default_attrs, $attrs);

	# verify options
	if ($params['type'] == null){
		return '<p class="error">No type defined for object list.</p>';
	}
	if (!is_numeric($params['limit'])){
		return '<p class="error">Invalid limit argument, must be a number.</p>';
	}
	if (!in_array(strtoupper($params['join']), array('AND', 'OR'))){
		return '<p class="error">Invalid join type, must be one of "and" or "or".</p>';
	}
	if (null == ($class = get_custom_post_type($params['type']))){
		return '<p class="error">Invalid post type.</p>';
	}

	$class = new $class;

	# Use post type specified ordering?
	if(!isset($attrs['orderby']) && !is_null($class->default_orderby)) {
		$params['orderby'] = $class->orderby;
	}
	if(!isset($attrs['order']) && !is_null($class->default_order)) {
		$params['order'] = $class->default_order;
	}

	# get taxonomies and translation
	$translate = array(
		'tags' => 'post_tag',
		'categories' => 'category',
		'issues' => 'issues'
	);
	$taxonomies = array_diff(array_keys($attrs), array_keys($default_attrs));

	# assemble taxonomy query
	$tax_queries = array();
	$tax_queries['relation'] = strtoupper($params['join']);

	foreach($taxonomies as $tax){
		$terms = $params[$tax];
		$terms = trim(preg_replace('/\s+/', ' ', $terms));
		$terms = explode(' ', $terms);

		if (array_key_exists($tax, $translate)){
			$tax = $translate[$tax];
		}

		$tax_queries[] = array(
			'taxonomy' => $tax,
			'field' => 'slug',
			'terms' => $terms,
		);
	}

	# perform query
	$query_array = array(
		'tax_query'      => $tax_queries,
		'post_status'    => 'publish',
		'post_type'      => $params['type'],
		'posts_per_page' => $params['limit'],
		'orderby'        => $params['orderby'],
		'order'          => $params['order'],
		'offset'         => $params['offset']
	);

	$query = new WP_Query($query_array);

	global $post;
	$objects = array();
	while($query->have_posts()){
		$query->the_post();
		$objects[] = $post;
	}

	# Custom sort if applicable
	if ($sort_func !== null){
		usort($objects, $sort_func);
	}

	wp_reset_postdata();

	if($objects_only) {
		return $objects;
	}

	if (count($objects)){
		$html = $class->objectsToHTML($objects, $params['class']);
	}else{
		$html = $default_content;
	}
	return $html;
}


/**
 * Sets the default values for any theme options that are not currently stored.
 *
 * @return void
 * @author Jared Lang
 **/
function set_defaults_for_options(){
	$values  = get_option(THEME_OPTIONS_NAME);
	if ($values === False or is_string($values)){
		add_option(THEME_OPTIONS_NAME);
		$values = array();
	}

	$options = array();
	foreach(Config::$theme_settings as $option){
		if (is_array($option)){
			$options = array_merge($option, $options);
		}else{
			$options[] = $option;
		}
	}

	foreach ($options as $option){
		$key = str_replace(
			array(THEME_OPTIONS_NAME, '[', ']'),
			array('', '', ''),
			$option->id
		);
		if ($option->default !== null and !isset($values[$key])){
			$values[$key] = $option->default;
			update_option(THEME_OPTIONS_NAME, $values);
		}
	}
}


/**
 * Runs as wordpress is shutting down.
 *
 * @return void
 * @author Jared Lang
 **/
function __shutdown__(){
	global $timer;
	$elapsed = round($timer->elapsed() * 1000);
	debug("{$elapsed} milliseconds");
}
add_action('shutdown', '__shutdown__');


/**
 * Will return a string $s normalized to a slug value.  The optional argument,
 * $spaces, allows you to define what spaces and other undesirable characters
 * will be replaced with.  Useful for content that will appear in urls or
 * turning plain text into an id.
 *
 * @return string
 * @author Jared Lang
 **/
function slug($s, $spaces='-'){
	$s = strtolower($s);
	$s = preg_replace('/[-_\s\.]/', $spaces, $s);
	return $s;
}




/***************************************************************************
 * HEADER AND FOOTER FUNCTIONS
 *
 * Functions that generate output for the header and footer, including
 * <meta>, <link>, page titles, body classes and Facebook OpenGraph
 * stuff.
 *
 ***************************************************************************/

/**
 * Header content
 *
 * @return string
 * @author Jared Lang
 **/
function header_( $tabs=2 ) {
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'rel_canonical' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );

	add_action( 'wp_head', 'header_links', 10 );

	// If Yoast SEO is activated, assume we're handling ALL SEO/meta-related
	// modifications with it.  Don't use header_meta().
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( !is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
		opengraph_setup();
		add_action( 'wp_head', 'header_meta', 1 );
	}
	else {
		// When Yoast is activated, modify the default Yoast canonical function
		// (instead of pushing a new link tag to Config::$links.)
		add_filter( 'wpseo_canonical', 'get_canonical_href' );
	}

	ob_start();
	wp_head();
	return indent( ob_get_clean(), $tabs );
}


/**
 * Footer content
 *
 * @return string
 * @author Jared Lang
 **/
function footer_($tabs=2){
	ob_start();
	wp_footer();
	$html = ob_get_clean();
	return indent($html, $tabs);
}


/**
 * Assembles the appropriate meta elements for facebook's opengraph stuff.
 * Utilizes the themes Config object to queue up the created elements.
 *
 * @return void
 * @author Jared Lang
 **/
function opengraph_setup(){
	$options = get_option(THEME_OPTIONS_NAME);

	if (!(bool)$options['enable_og']){return;}
	if (is_search()){return;}

	global $post, $page;
	setup_postdata($post);

	if (is_front_page()){
		$title       = htmlentities(get_bloginfo('name'));
		$url         = get_bloginfo('url');
		$site_name   = $title;
	}else{
		$title     = htmlentities($post->post_title);
		$url       = get_permalink($post->ID);
		$site_name = htmlentities(get_bloginfo('name'));
	}

	# Set description
	if (is_front_page()){
		$description = htmlentities(get_bloginfo('description'));
	}else{
		ob_start();
		the_excerpt();
		$description = trim(str_replace('[...]', '', ob_get_clean()));
		# Generate a description if excerpt is unavailable
		if (strlen($description) < 1){
			ob_start();
			the_content();
			$description = apply_filters('the_excerpt', preg_replace(
				'/\s+/',
				' ',
				strip_tags(ob_get_clean()))
			);
			$words       = explode(' ', $description);
			$description = implode(' ', array_slice($words, 0, 60));
		}
	}

	$metas = array(
		array('property' => 'og:title'      , 'content' => $title),
		array('property' => 'og:url'        , 'content' => $url),
		array('property' => 'og:site_name'  , 'content' => $site_name),
		array('property' => 'og:description', 'content' => $description),
	);

	# Include image if available
	if (!is_front_page() and has_post_thumbnail($post->ID)){
		$image = wp_get_attachment_image_src(
			get_post_thumbnail_id( $post->ID ),
			'single-post-thumbnail'
		);
		$metas[] = array('property' => 'og:image', 'content' => $image[0]);
	}


	# Include admins if available
	$admins = trim($options['fb_admins']);
	if (strlen($admins) > 0){
		$metas[] = array('property' => 'fb:admins', 'content' => $admins);
	}

	Config::$metas = array_merge(Config::$metas, $metas);
}


/**
 * Get the canonical url for the current post.
 * Returns the site url for the home page, since Yoast apparently can't
 * do this correctly with domain mapping turned on.
 **/
function get_canonical_href( $link ) {
	// If $link isn't passed in, assign it
	if ( !isset( $link ) || empty( $link ) ) {
		// Logic copied from rel_canonical()
		if ( !is_singular() ) {
			return;
		}

		global $wp_the_query;
		if ( !$id = $wp_the_query->get_queried_object_id() ) {
			return;
		}

		$link = get_permalink( $id );

		if ( $page = get_query_var( 'cpage' ) ) {
			$link = get_comments_pagenum_link( $page );
		}
	}

	// Check if WordPress MU Domain Mapping plugin is turned on.
	// get_site_url() will NOT return a canonical home url if it is turned on.
	// The Domain Mapping plugin will overwrite get_site_url() with the mapped
	// domain.
	if ( defined( 'DOMAIN_MAPPING' ) && function_exists( 'get_original_url' ) ) {
		$home_url = get_original_url( 'siteurl' );
	}
	else {
		$home_url = get_site_url();
	}

	// Get the relative path of $link.  Strips out the root path, presuming that
	// $link is a permalink generated with a base of whatever get_site_url()
	// returns.
	$link_path = str_replace( get_site_url(), '', $link );

	$new_link = '';

	if ( $link_path !== $link ) {
		// The site url was removed as a substring of $link; prepend our canonical
		// site url before the relative path to create the full canonical url for
		// this post
		$new_link = $home_url . $link_path;
		if ( substr( $new_link, -1 ) !== '/' ) {
			$new_link = $new_link . '/'; // just for consistency
		}
	}
	else {
		// $link did not contain the site url; it must have been a custom off-site
		// url set via Yoast, so just return it
		$new_link = $link;
	}

	return $new_link;
}


/**
 * Handles generating the meta tags configured for this theme.
 *
 * @return string
 * @author Jared Lang
 **/
function header_meta(){
	$metas     = Config::$metas;
	$meta_html = array();
	$defaults  = array();

	foreach( $metas as $meta ) {
		$meta        = array_merge( $defaults, $meta );
		$meta_html[] = create_html_element( 'meta', $meta );
	}
	$meta_html = implode( "\n", $meta_html );
	echo $meta_html;
}


/**
 * Handles generating the link tags configured for this theme.
 *
 * @return string
 * @author Jared Lang
 **/
function header_links(){
	$links      = Config::$links;
	$links_html = array();
	$defaults   = array();

	// If Yoast SEO is NOT activated, we will need to handle canonicals ourselves.
	// (The canonical is modified thru a Yoast hook in header_() otherwise.)
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	if ( !is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
		array_push( Config::$links, array( 'rel' => 'canonical', 'href' => get_canonical_href() ) );
	}

	foreach( $links as $link ) {
		$link         = array_merge( $defaults, $link );
		$links_html[] = create_html_element( 'link', $link, null, True );
	}

	$links_html = implode( "\n", $links_html );
	echo $links_html;
}


/**
 * Generates a title based on context page is viewed.  Stolen from Thematic
 **/
function header_title( $title, $separator ) {
	$site_name = get_bloginfo('name');

	if ( is_single() ) {
		$content = single_post_title( '', FALSE );
	}
	elseif ( is_home() || is_front_page() ) {
		$content = get_bloginfo( 'description' );
	}
	elseif ( is_page() ) {
		$content = single_post_title( '', FALSE );
	}
	elseif ( is_search() ) {
		$content = __('Search Results for:');
		$content .= ' ' . esc_html( stripslashes( get_search_query() ) );
	}
	elseif ( is_category() ) {
		$content = __( 'Category Archives:' );
		$content .= ' ' . single_cat_title( '', false );
	}
	elseif ( is_404() ) {
		$content = __( 'Not Found' );
	}
	else {
		$content = get_bloginfo( 'description' );
	}

	if ( get_query_var( 'paged' ) ) {
		$content .= ' ' .$separator. ' ';
		$content .= 'Page';
		$content .= ' ';
		$content .= get_query_var( 'paged' );
	}

	if( $content ) {
		if ( is_home() || is_front_page() ) {
			$elements = array(
				'site_name' => $site_name,
				'separator' => $separator,
				'content' => $content,
			);
		} else {
			$elements = array(
				'content' => $content,
			);
		}
	} else {
		$elements = array(
			'site_name' => $site_name,
		);
	}

	// But if they don't, it won't try to implode
	if( is_array( $elements ) ) {
		$doctitle = implode( ' ', $elements );
	}
	else {
		$doctitle = $elements;
	}

	return $doctitle;
}
add_filter( 'wp_title', 'header_title', 10, 2 );


/**
 * Returns string to use for value of class attribute on body tag
 **/
function body_classes(){
	$classes = Config::$body_classes;
	return implode(' ', $classes);
}




/***************************************************************************
 * REGISTRATION AND INSTALLATION FUNCTIONS
 *
 * Functions that register and install custom post types, taxonomies,
 * and meta boxes.
 *
 ***************************************************************************/

/**
 * Adding custom post types to the installed array defined in this function
 * will activate and make available for use those types.
 **/
function installed_custom_post_types(){
	$installed = Config::$custom_post_types;

	return array_map(create_function('$class', '
		return new $class;
	'), $installed);
}

/**
 * Adding custom post types to the installed array defined in this function
 * will activate and make available for use those types.
 **/
function installed_custom_taxonomies(){
	$installed = Config::$custom_taxonomies;

	return array_map(create_function('$class', '
		return new $class;
	'), $installed);
}

function flush_rewrite_rules_if_necessary(){
	global $wp_rewrite;
	$start    = microtime(True);
	$original = get_option('rewrite_rules');
	$rules    = $wp_rewrite->rewrite_rules();

	if (!$rules or !$original){
		return;
	}
	ksort($rules);
	ksort($original);

	$rules    = md5(implode('', array_keys($rules)));
	$original = md5(implode('', array_keys($original)));

	if ($rules != $original){
		flush_rewrite_rules();
	}
}

/**
 * Registers all installed custom taxonomies
 *
 * @return void
 * @author Chris Conover
 **/
function register_custom_taxonomies(){
	#Register custom post types
	foreach(installed_custom_taxonomies() as $custom_taxonomy){
		$custom_taxonomy->register();
	}
}
add_action( 'after_setup_theme', 'register_custom_taxonomies', 1 );

/**
 * Registers all installed custom post types
 *
 * @return void
 * @author Jared Lang
 **/
function register_custom_post_types(){
	#Register custom post types
	foreach(installed_custom_post_types() as $custom_post_type){
		$custom_post_type->register();
	}

	#This ensures that the permalinks for custom posts work
	flush_rewrite_rules_if_necessary();
}
add_action( 'after_setup_theme', 'register_custom_post_types', 2 );

/**
 * Registers all metaboxes for install custom post types
 *
 * @return void
 * @author Jared Lang
 **/
function register_meta_boxes(){
	#Register custom post types metaboxes
	foreach(installed_custom_post_types() as $custom_post_type){
		$custom_post_type->register_metaboxes();
	}
}
add_action('do_meta_boxes', 'register_meta_boxes');




/***************************************************************************
 * POST DATA HANDLERS and META BOX FUNCTIONS
 *
 * Functions that display and save custom post types and their meta data.
 *
 ***************************************************************************/

/**
 * Saves the data for a given post type
 *
 * @return void
 * @author Jared Lang
 **/
function save_meta_data($post){
	#Register custom post types metaboxes
	foreach(installed_custom_post_types() as $custom_post_type){
		if (post_type($post) == $custom_post_type->options('name')){
			$meta_box = $custom_post_type->metabox();
			break;
		}
	}
	return _save_meta_data($post, $meta_box);

}
add_action('save_post', 'save_meta_data');


/**
 * Displays the metaboxes for a given post type
 *
 * @return void
 * @author Jared Lang
 **/
function show_meta_boxes($post){
	#Register custom post types metaboxes
	foreach(installed_custom_post_types() as $custom_post_type){
		if (post_type($post) == $custom_post_type->options('name')){
			$meta_box = $custom_post_type->metabox();
			break;
		}
	}
	return _show_meta_boxes($post, $meta_box);
}

function save_file( $post_id, $field ) {
	if ( !$post_id ) {
		$post_id = 0;
	}

	$file_uploaded = @!empty( $_FILES[$field['id']] );
	if ( $file_uploaded ){
		require_once( ABSPATH.'wp-admin/includes/file.php' );
		$override['action'] = 'editpost';
		$file               = $_FILES[$field['id']];
		$uploaded_file      = wp_handle_upload($file, $override);

		# TODO: Pass reason for error back to frontend
		if ( $uploaded_file['error'] ){ return; }

		// Array of data about the new attachment post being created.
		$attachment = array(
			'post_title'     => $file['name'],
			'post_content'   => '',
			'post_type'      => 'attachment',
			'post_parent'    => $post_id,
			'post_mime_type' => $file['type'],
			'guid'           => $uploaded_file['url'],
		);

		// Create (and return) an attachment post
		$id = wp_insert_attachment( $attachment, $uploaded_file['file'], $post_id );

		// Set the new attachment's metadata
		wp_update_attachment_metadata(
			$id,
			wp_generate_attachment_metadata( $id, $uploaded_file['file'] )
		);

		// Update the parent post's meta field value
		update_post_meta( $post_id, $field['id'], $id );
	}
}

function save_textarea( $post_id, $field ) {
	$old = get_post_meta( $post_id, $field['id'], true );
	# Make sure new value doesn't contain special chars that mysql doesn't
	# like.
	$new = iconv( 'UTF-8', 'ISO-8859-1//IGNORE', $_POST[$field['id']] );

	# Update if new is not empty and is not the same value as old
	if ( $new !== '' and $new !== null and $new != $old ) {
		update_post_meta( $post_id, $field['id'], $new );
	}
	# Delete if we're sending a new null value and there was an old value
	elseif ( ( $new === '' or is_null( $new ) ) and $old ) {
		delete_post_meta( $post_id, $field['id'], $old );
	}
	# Otherwise we do nothing, field stays the same
	return;
}

function save_default($post_id, $field){
	$old = get_post_meta($post_id, $field['id'], true);
	$new = $_POST[$field['id']];

	# Update if new is not empty and is not the same value as old
	if ($new !== "" and $new !== null and $new != $old) {
		update_post_meta($post_id, $field['id'], $new);
	}
	# Delete if we're sending a new null value and there was an old value
	elseif (($new === "" or is_null($new)) and $old) {
		delete_post_meta($post_id, $field['id'], $old);
	}
	# Otherwise we do nothing, field stays the same
	return;
}


/**
 * Handles saving a custom post as well as its custom fields and metadata.
 *
 * @return void
 * @author Jared Lang
 **/
function _save_meta_data($post_id, $meta_box){

	// Get post type object
	$post_type = get_custom_post_type(post_type($post_id));
	if ($post_type) {
		$post_type = new $post_type;
		// verify nonce
		if ($post_type->options('cloneable_fields')) {
			if (!wp_verify_nonce($_POST['meta_box_nonce'], 'nonce-content')) {
				//var_dump(wp_verify_nonce($_POST['meta_box_nonce'], 'nonce-content'));
				return $post_id;
			}
		}
	}
	else {
		if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
			return $post_id;
		}
	}

	// prevent autosave and quick/bulk edits from wiping metadata
	if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || (defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit'])) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

	$all_fields = array();
	if ($post_type && $post_type->options('cloneable_fields')) {
		foreach ($meta_box as $single_meta_box) {
			if ($single_meta_box['fields']) {
				foreach ($single_meta_box['fields'] as $field) {
					array_push($all_fields, $field);
				}
			}
		}
		$single_slide_fields = $post_type::get_single_slide_meta();
		$all_fields = array_merge($all_fields, $single_slide_fields);
	}
	else {
		$all_fields = $meta_box['fields'];
	}

	//var_dump($all_fields); die();

	foreach ($all_fields as $field) {
		switch ($field['type']){
			case 'file':
				save_file($post_id, $field);
				break;
			case 'textarea':
			case 'wysiwyg':
				save_textarea( $post_id, $field );
				break;
			default:
				save_default($post_id, $field);
				break;
		}
	}
}


/**
 * Displays meta box fields with current or default values.
 **/
function display_meta_box_field( $post_id, $field ) {
	$field_obj = null;
	$field['value'] = get_post_meta( $post_id, $field['id'], true );

	// Fix inconsistencies between CPT field array keys and Field obj property names
	if ( isset( $field['desc'] ) ) {
		$field['description'] = $field['desc'];
		unset( $field['desc'] );
	}
	if ( isset( $field['options'] ) ) {
		$field['choices'] = $field['options'];
		unset( $field['options'] );
	}

	switch ( $field['type'] ) {
		case 'text':
			$field_obj = new TextField( $field );
			break;
		case 'textarea':
			$field_obj = new TextareaField( $field );
			break;
		case 'wysiwyg':
			$field_obj = new WysiwygField( $field );
			break;
		case 'select':
			$field_obj = new SelectField( $field );
			break;
		case 'radio':
			$field_obj = new RadioField( $field );
			break;
		case 'checkbox':
			$field_obj = new CheckboxField( $field );
			break;
		case 'file':
		default:
			break;
	}

	$markup = '';

	if ( $field_obj ) {
		ob_start();
	?>
		<tr>
			<th><?php echo $field_obj->label_html(); ?></th>
			<td>
				<?php echo $field_obj->description_html(); ?>
				<?php echo $field_obj->input_html(); ?>
			</td>
		</tr>
	<?php
		$markup = ob_get_clean();
	}
	else if ( $field['type'] == 'file' ) {
		$document_id = get_post_meta( $post_id, $field['id'], True );
		$document = null;

		if ( $document_id ){
			$document = get_post( $document_id );
			$url = wp_get_attachment_url( $document_id );
		}

		ob_start();
	?>
		<tr>
			<th><label for="<?php echo $field['id']; ?>"><?php echo $field['name']; ?></label></th>
			<td>
				<?php if ( $field['description'] ): ?>
				<p class="description"><?php echo $field['description']; ?></p>
				<?php endif; ?>
				<?php if ( $document ): ?>
					<a target="_blank" href="<?php echo $url; ?>">
						<?php if ( wp_attachment_is_image( $document_id ) ): ?>
						<img src="<?php echo $url; ?>" style="max-width:400px; height:auto"; /><br/>
						<?php endif; ?>
						<?php echo $document->post_title; ?>
					</a>
					<br><br>
				<?php endif;?>
				<input type="file" id="file_<?php echo $post_id; ?>" name="<?php echo $field['id']; ?>">
				<br>
			</td>
		</tr>
	<?php
		$markup = ob_get_clean();
	}
	else {
		$markup = '<tr><th></th><td>Don\'t know how to handle field of type '. $field_type .'</td></tr>';
	}

	echo $markup;
}


/**
 * Outputs the html for the fields defined for a given post and metabox.
 *
 * @return void
 * @author Jared Lang
 **/
function _show_meta_boxes( $post, $meta_box ) {
	?>
	<input type="hidden" name="meta_box_nonce" value="<?=wp_create_nonce(basename(__FILE__))?>"/>
	<table class="form-table">
		<?php
		foreach ( $meta_box['fields'] as $field ) {
			display_meta_box_field( $post->ID, $field );
		}
		?>
	</table>
<?php
}

?>
