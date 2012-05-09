<?php

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
		if ($this->value === null){
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
	function __construct($attr){
		$this->choices = @$attr['choices'];
		parent::__construct($attr);
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
		<textarea id="<?=htmlentities($this->id)?>" name="<?=htmlentities($this->id)?>"><?=htmlentities($this->value)?></textarea>
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
 * Fetches objects defined by arguments passed, outputs the objects according
 * to the objectsToHTML method located on the object.  Used by the auto
 * generated shortcodes enabled on custom post types. See also:
 * 
 *   CustomPostType::objectsToHTML
 *   CustomPostType::toHTML
 *
 * @return string
 * @author Jared Lang
 **/
function sc_object_list($attr, $default_content=null){
	if (!is_array($attr)){return '';}
	
	# set defaults and combine with passed arguments
	$defaults = array(
		'type'  => null,
		'limit' => -1,
		'join'  => 'or',
		'class' => '',
	);
	$options = array_merge($defaults, $attr);
	
	# verify options
	if ($options['type'] == null){
		return '<p class="error">No type defined for object list.</p>';
	}
	if (!is_numeric($options['limit'])){
		return '<p class="error">Invalid limit argument, must be a number.</p>';
	}
	if (!in_array(strtoupper($options['join']), array('AND', 'OR'))){
		return '<p class="error">Invalid join type, must be one of "and" or "or".</p>';
	}
	if (null == ($class = get_custom_post_type($options['type']))){
		return '<p class="error">Invalid post type.</p>';
	}
	
	# get taxonomies and translation
	$translate  = array(
		'tags'       => 'post_tag',
		'categories' => 'category',
	);
	$taxonomies = array_diff(array_keys($attr), array_keys($defaults));
	
	# assemble taxonomy query
	$tax_queries             = array();
	$tax_queries['relation'] = strtoupper($options['join']);
	
	foreach($taxonomies as $tax){
		$terms = $options[$tax];
		$terms = trim(preg_replace('/\s+/', ' ', $terms));
		$terms = explode(' ', $terms);
		
		if (array_key_exists($tax, $translate)){
			$tax = $translate[$tax];
		}
		
		$tax_queries[] = array(
			'taxonomy' => $tax,
			'field'    => 'slug',
			'terms'    => $terms,
		);
	}
	
	# perform query
	$query_array = array(
		'tax_query'      => $tax_queries,
		'post_status'    => 'publish',
		'post_type'      => $options['type'],
		'posts_per_page' => $options['limit'],
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	);

	$class = new $class;
	$objects = $class->get_objects($query_array);
	
	if (count($objects)){
		$html = $class->objectsToHTML($objects, $options['class']);
	}else{
		$html = $default_content;
	}
	return $html;
}


/**
 * Creates an array of shortcodes mapped to a documentation string for that
 * shortcode.  Used to generate the auto shortcode documentation.
 * 
 * @return array
 * @author Jared Lang
 **/
function shortcodes(){
	$file = file_get_contents(THEME_DIR.'/shortcodes.php');
	
	$documentation = "\/\*\*(?P<documentation>.*?)\*\*\/";
	$declaration   = "function[\s]+(?P<declaration>[^\(]+)";
	
	# Auto generated shortcode documentation.
	$codes = array();
	$auto  = array_filter(installed_custom_post_types(), create_function('$c', '
		return $c->options("use_shortcode");
	'));
	foreach($auto as $code){
		$scode  = $code->options('name').'-list';
		$plural = $code->options('plural_name');
		$doc = <<<DOC
 Outputs a list of {$plural} filtered by arbitrary taxonomies, for example a tag
or category.  A default output for when no objects matching the criteria are
found.

 Example:
 # Output a maximum of 5 items tagged foo or bar, with a default output.
 [{$scode} tags="foo bar" limit="5"]No {$plural} were found.[/{$scode}]

 # Output all objects categorized as foo
 [{$scode} categories="foo"]

 # Output all objects matching the terms in the custom taxonomy named foo
 [{$scode} foo="term list example"]

 # Outputs all objects found categorized as staff and tagged as small.
 [{$scode} limit="5" join="and" categories="staff" tags="small"]
DOC;
		$codes[] = array(
			'documentation' => $doc,
			'shortcode'     => $scode,
		);
	}
	
	# Defined shortcode documentation
	$found = preg_match_all("/{$documentation}\s*{$declaration}/is", $file, $matches);
	if ($found){
		foreach ($matches['declaration'] as $key=>$match){
			$codes[$match]['documentation'] = $matches['documentation'][$key];
			$codes[$match]['shortcode']     = str_replace(
				array('sc_', '_',),
				array('', '-',),
				$matches['declaration'][$key]
			);
		}
	}
	return $codes;
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
if (DEBUG){
	function debug($string){
		print "<!-- DEBUG: {$string} -->\n";
	}
}else{
	function debug($string){return;}
}


/**
 * Will execute the function $func with the arguments passed via $args if the
 * debug constant is set true.  Returns whatever value the called function
 * returns, or void if debug is not set active.
 *
 * @return mixed
 * @author Jared Lang
 **/
if (DEBUG){
	function debug_callfunc($func, $args){
		return call_user_func_array($func, $args);
	}
}else{
	function debug_callfunc($func, $args){return;}
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
 * Responsible for running code that needs to be executed as wordpress is
 * initializing.  Good place to register scripts, stylesheets, theme elements,
 * etc.
 * 
 * @return void
 * @author Jared Lang
 **/
function __init__(){
	add_theme_support('menus');
	add_theme_support('post-thumbnails');
	add_image_size('homepage', 620);
	add_image_size('homepage-secondary', 540);
	register_nav_menu('header-menu', __('Header Menu'));
	register_nav_menu('footer-menu', __('Footer Menu'));
	register_sidebar(array(
		'name'          => __('Sidebar'),
		'id'            => 'sidebar',
		'description'   => 'Sidebar found on two column page templates and search pages',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	/*
	register_sidebar(array(
		'name'          => __('Below the Fold - Left'),
		'id'            => 'bottom-left',
		'description'   => 'Left column on the bottom of pages, after flickr images if enabled.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	register_sidebar(array(
		'name'          => __('Below the Fold - Center'),
		'id'            => 'bottom-center',
		'description'   => 'Center column on the bottom of pages, after news if enabled.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	register_sidebar(array(
		'name'          => __('Below the Fold - Right'),
		'id'            => 'bottom-right',
		'description'   => 'Right column on the bottom of pages, after events if enabled.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	*/
	register_sidebar(array(
		'name'          => __('Footer - Column One'),
		'id'            => 'bottom-one',
		'description'   => 'Far left column in footer on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	register_sidebar(array(
		'name'          => __('Footer - Column Two'),
		'id'            => 'bottom-two',
		'description'   => 'Second column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	register_sidebar(array(
		'name'          => __('Footer - Column Three'),
		'id'            => 'bottom-three',
		'description'   => 'Third column from the left in footer, on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	register_sidebar(array(
		'name'          => __('Footer - Column Four'),
		'id'            => 'bottom-four',
		'description'   => 'Far right in footer on the bottom of pages.',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
	));
	foreach(Config::$styles as $style){Config::add_css($style);}
	foreach(Config::$scripts as $script){Config::add_script($script);}
	
	global $timer;
	$timer = Timer::start();
	
	wp_deregister_script('l10n');
	set_defaults_for_options();
}
add_action('after_setup_theme', '__init__');


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
 * Using the user defined value for Flickr ID set in the admin, will return the 
 * photostream URL for that ID.  Will return null if no id is set.
 *
 * @return string
 * @author Jared Lang
 **/
function get_flickr_feed_url(){
	$rss_url = "http://api.flickr.com/services/feeds/photos_public.gne?id=%s&amp;lang=en-us&amp;format=rss_200";
	$options = get_option(THEME_OPTIONS_NAME);
	$id = $options['flickr_id'];
	
	if ($id){
		return sprintf($rss_url, $id);
	}else{
		return null;
	}
}


function get_flickr_stream_url(){
	$rss_url = "http://flickr.com/photos/%s";
	$options = get_option(THEME_OPTIONS_NAME);
	$id = $options['flickr_id'];
	
	if ($id){
		return sprintf($rss_url, $id);
	}else{
		return null;
	}
}


function get_article_image($article){
	$image = $article->get_enclosure();
	if ($image){
		return ($image->get_thumbnail()) ? $image->get_thumbnail() : $image->get_link();
	}else{
		$matches = array();
		$found   = preg_match('/<img[^>]+src=[\'\"]([^\'\"]+)[\'\"][^>]+>/i',  $article->get_content(), $matches);
		if($found){ 
			return $matches[1];
		}
	}
	return null;
}


/**
 * Handles fetching and processing of feeds.  Currently uses SimplePie to parse
 * retrieved feeds, and automatically handles caching of content fetches.
 * Multiple calls to the same feed url will not result in multiple parsings, per
 * request as they are stored in memory for later use.
 **/
class FeedManager{
	static private
		$feeds        = array(),
		$cache_length = 0xD2F0;
	
	/**
	 * Provided a URL, will return an array representing the feed item for that
	 * URL.  A feed item contains the content, url, simplepie object, and failure
	 * status for the URL passed.  Handles caching of content requests.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static protected function __new_feed($url){
		$timer = Timer::start();
		require_once(THEME_DIR.'/third-party/simplepie.php');
		
		$simplepie = null;
		$failed    = False;
		$cache_key = 'feedmanager-'.md5($url);
		$content   = get_site_transient($cache_key);
		
		if ($content === False){
			$content = @file_get_contents($url);
			if ($content === False){
				$failed  = True;
				$content = null;
				error_log('FeedManager failed to fetch data using url of '.$url);
			}else{
				set_site_transient($cache_key, $content, self::$cache_length);
			}
		}
		
		if ($content){
			$simplepie = new SimplePie();
			$simplepie->set_raw_data($content);
			$simplepie->init();
			$simplepie->handle_content_type();
			
			if ($simplepie->error){
				error_log($simplepie->error);
				$simplepie = null;
				$failed    = True;
			}
		}else{
			$failed = True;
		}
		
		$elapsed = round($timer->elapsed() * 1000);
		debug("__new_feed: {$elapsed} milliseconds");
		return array(
			'content'   => $content,
			'url'       => $url,
			'simplepie' => $simplepie,
			'failed'    => $failed,
		);
	}
	
	
	/**
	 * Returns all the items for a given feed defined by URL
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static protected function __get_items($url){
		if (!array_key_exists($url, self::$feeds)){
			self::$feeds[$url] = self::__new_feed($url);
		}
		if (!self::$feeds[$url]['failed']){
			return self::$feeds[$url]['simplepie']->get_items();
		}else{
			return array();
		}
		
	}
	
	
	/**
	 * Retrieve the current cache expiration value.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	static public function get_cache_expiration(){
		return self::$cache_length;
	}
	
	
	/**
	 * Set the cache expiration length for all feeds from this manager.
	 *
	 * @return void
	 * @author Jared Lang
	 **/
	static public function set_cache_expiration($expire){
		if (is_number($expire)){
			self::$cache_length = (int)$expire;
		}
	}
	
	
	/**
	 * Returns all items from the feed defined by URL and limited by the start
	 * and limit arguments.
	 *
	 * @return array
	 * @author Jared Lang
	 **/
	static public function get_items($url, $start=null, $limit=null){
		if ($start === null){$start = 0;}
		
		$items = self::__get_items($url);
		$items = array_slice($items, $start, $limit);
		return $items;
	}
}


class FlickrManager extends FeedManager{
	static protected $sizes = array(
		'large'     => 'b',
		'medium'    => 'z',
		'small'     => 'm',
		'thumbnail' => 't',
		'square'    => 's',
	);
	
	static protected function __items_to_photos($items){
		$photos = array();
		
		foreach($items as $item){
			$title = $item->get_title();
			$urls  = array();
			try{
				$url = $item->get_enclosure()->get_link();
			}catch (Exception $e){
				continue;
			}
			
			foreach(FlickrManager::$sizes as $key=>$size){
				$size             = "_{$size}.jpg";
				$urls[$key]       = str_replace('_b.jpg', $size, $url);
				$urls['original'] = $url;
				$urls['title']    = $title;
				$urls['page']     = $item->get_link();
			}
			$photos[] = $urls;
		}
		return $photos;
	}
	
	
	static public function get_photos($url, $start=null, $limit=null){
		if ($start === null){$start = 0;}
		
		$items  = self::__get_items($url);
		$photos = array_slice(self::__items_to_photos($items), $start, $limit);
		return $photos;
	}
}


function display_flickr($header='h2'){
	$options  = get_option(THEME_OPTIONS_NAME);
	$count    = $options['flickr_max_items'];
	$feed_url = get_flickr_feed_url();
	$photos   = FlickrManager::get_photos($feed_url, 0, $count);
	
	if(count($photos)):?>
		<<?=$header?>><a href="<?=get_flickr_stream_url()?>">Flickr Stream</a></<?=$header?>>
		<ul class="flickr-stream">
			<?php foreach($photos as $photo):?>
			<li><a class="ignore-external" href="<?=$photo['page']?>"><img height="75" width="75" src="<?=$photo['square']?>" title="<?=$photo['title']?>" /></a></li>
			<?php endforeach;?>
		</ul>
	<?php else:?>
		<p>Unable to fetch flickr feed.</p>
	<?php endif;?>
<?php
}


function display_events($header='h2'){?>
	<?php $options = get_option(THEME_OPTIONS_NAME);?>
	<?php $count   = $options['events_max_items']?>
	<?php $events  = get_events(0, ($count) ? $count : 3);?>
	<?php if(count($events)):?>
		<<?=$header?>><a href="<?=$events[0]->get_feed()->get_link()?>"><?=$events[0]->get_feed()->get_title()?></a></<?=$header?>>
		<table class="events">
			<?php foreach($events as $item):?>
			<tr class="item">
				<td class="date">
					<?php
						$month = $item->get_date("M");
						$day   = $item->get_date("j");
					?>
					<div class="month"><?=$month?></div>
					<div class="day"><?=$day?></div>
				</td>
				<td class="title">
					<a href="<?=$item->get_link()?>" class="wrap ignore-external"><?=$item->get_title()?></a>
				</td>
			</tr>
			<?php endforeach;?>
		</table>
	<?php else:?>
		<p>Unable to fetch events</p>
	<?php endif;?>
<?php
}


function display_news($header='h2'){?>
	<?php $options = get_option(THEME_OPTIONS_NAME);?>
	<?php $count   = $options['news_max_items'];?>
	<?php $news    = get_news(0, ($count) ? $count : 2);?>
	<?php if(count($news)):?>
		<<?=$header?>><a href="<?=$news[0]->get_feed()->get_link()?>"><?=$news[0]->get_feed()->get_title()?></a></<?=$header?>>
		<ul class="news">
			<?php foreach($news as $key=>$item): $image = get_article_image($item); $first = ($key == 0);?>
			<li class="item<?php if($first):?> first<?php else:?> not-first<?php endif;?>">
				<h3 class="title"><a href="<?=$item->get_link()?>" class="ignore-external title"><?=$item->get_title()?></a></h3>
				<p>
					<a class="image ignore-external" href="<?=$item->get_link()?>">
						<?php if($image):?>
						<img src="<?=$image?>" alt="Feed image for <?=$item->get_title()?>" />
						<?php endif;?>
					</a>
					<a class="description ignore-external"  href="<?=$item->get_link()?>">
						<?= $item->get_description();?>
					</a>
				</p>
				<div class="end"><!-- --></div>
			</li>
			<?php endforeach;?>
		</ul>
		<div class="end"><!-- --></div>
	<?php else:?>
		<p>Unable to fetch news.</p>
	<?php endif;?>
<?php
}


function get_events($start=null, $limit=null){
	$options = get_option(THEME_OPTIONS_NAME);
	$qstring = (bool)strpos($options['events_url'], '?');
	$url     = $options['events_url'];
	if (!$qstring){
		$url .= '?';
	}else{
		$url .= '&';
	}
	$url    .= 'upcoming=upcoming&format=rss';
	$events  = array_reverse(FeedManager::get_items($url));
	$events  = array_slice($events, $start, $limit);
	return $events;
}


function get_news($start=null, $limit=null){
	$options = get_option(THEME_OPTIONS_NAME);
	$url     = $options['news_url'];
	$news    = FeedManager::get_items($url, $start, $limit);
	return $news;
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
		$menu = call_user_func($callback, array($items));
	}
	
	return $menu;
	
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
 * Header content
 * 
 * @return string
 * @author Jared Lang
 **/
function header_($tabs=2){
	opengraph_setup();
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'rel_canonical');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	
	ob_start();
	print header_meta()."\n";
	wp_head();
	print header_links()."\n";
	print header_title()."\n";
	
	return indent(ob_get_clean(), $tabs);
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
 * Handles generating the meta tags configured for this theme.
 * 
 * @return string
 * @author Jared Lang
 **/
function header_meta(){
	$metas     = Config::$metas;
	$meta_html = array();
	$defaults  = array();
	
	foreach($metas as $meta){
		$meta        = array_merge($defaults, $meta);
		$meta_html[] = create_html_element('meta', $meta);
	}
	$meta_html = implode("\n", $meta_html);
	return $meta_html;
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
	
	foreach($links as $link){
		$link         = array_merge($defaults, $link);
		$links_html[] = create_html_element('link', $link, null, True);
	}
	
	$links_html = implode("\n", $links_html);
	return $links_html;
}


/**
 * Generates a title based on context page is viewed.  Stolen from Thematic
 **/
function header_title(){
	$site_name = get_bloginfo('name');
	$separator = '|';

	if ( is_single() ) {
		$content = single_post_title('', FALSE);
	}
	elseif ( is_home() || is_front_page() ) { 
		$content = get_bloginfo('description');
	}
	elseif ( is_page() ) { 
		$content = single_post_title('', FALSE); 
	}
	elseif ( is_search() ) { 
		$content = __('Search Results for:'); 
		$content .= ' ' . esc_html(stripslashes(get_search_query()));
	}
	elseif ( is_category() ) {
		$content = __('Category Archives:');
		$content .= ' ' . single_cat_title("", false);;
	}
	elseif ( is_404() ) { 
		$content = __('Not Found'); 
	}
	else { 
		$content = get_bloginfo('description');
	}

	if (get_query_var('paged')) {
		$content .= ' ' .$separator. ' ';
		$content .= 'Page';
		$content .= ' ';
		$content .= get_query_var('paged');
	}

	if($content) {
		if (is_home() || is_front_page()) {
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
	if(is_array($elements)) {
	$doctitle = implode(' ', $elements);
	}
	else {
	$doctitle = $elements;
	}

	$doctitle = "<title>". $doctitle ."</title>";

	return $doctitle;
}


/**
 * Returns string to use for value of class attribute on body tag
 **/
function body_classes(){
	$classes = Config::$body_classes;
	return implode(' ', $classes);
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
add_action('init', 'register_custom_taxonomies');

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
add_action('init', 'register_custom_post_types');

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

function save_file($post_id, $field){
	$file_uploaded = @!empty($_FILES[$field['id']]);
	if ($file_uploaded){
		require_once(ABSPATH.'wp-admin/includes/file.php');
		$override['action'] = 'editpost';
		$file               = $_FILES[$field['id']];
		$uploaded_file      = wp_handle_upload($file, $override);
		
		# TODO: Pass reason for error back to frontend
		if ($uploaded_file['error']){return;}
		
		$attachment = array(
			'post_title'     => $file['name'],
			'post_content'   => '',
			'post_type'      => 'attachment',
			'post_parent'    => $post_id,
			'post_mime_type' => $file['type'],
			'guid'           => $uploaded_file['url'],
		);
		$id = wp_insert_attachment($attachment, $file['file'], $post_id);
		wp_update_attachment_metadata(
			$id,
			wp_generate_attachment_metadata($id, $file['file'])
		);
		update_post_meta($post_id, $field['id'], $id);
	}
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
 * Handles saving a custom post as well as it's custom fields and metadata.
 *
 * @return void
 * @author Jared Lang
 **/
function _save_meta_data($post_id, $meta_box){
	// verify nonce
	if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
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
	
	foreach ($meta_box['fields'] as $field) {
		switch ($field['type']){
			case 'file':
				save_file($post_id, $field);
				break;
			default:
				save_default($post_id, $field);
				break;
		}
	}
}

/**
 * Outputs the html for the fields defined for a given post and metabox.
 *
 * @return void
 * @author Jared Lang
 **/
function _show_meta_boxes($post, $meta_box){
	?>
	<input type="hidden" name="meta_box_nonce" value="<?=wp_create_nonce(basename(__FILE__))?>"/>
	<table class="form-table">
	<?php foreach($meta_box['fields'] as $field):
		$current_value = get_post_meta($post->ID, $field['id'], true);?>
		<tr>
			<th><label for="<?=$field['id']?>"><?=$field['name']?></label></th>
			<td>
			<?php if($field['desc']):?>
				<div class="description">
					<?=$field['desc']?>
				</div>
			<?php endif;?>
			
			<?php switch ($field['type']): 
				case 'text':?>
				<input type="text" name="<?=$field['id']?>" id="<?=$field['id']?>" value="<?=($current_value) ? htmlentities($current_value) : $field['std']?>" />
			
			<?php break; case 'textarea':?>
				<textarea name="<?=$field['id']?>" id="<?=$field['id']?>" cols="60" rows="4"><?=($current_value) ? htmlentities($current_value) : $field['std']?></textarea>
			
			<?php break; case 'select':?>
				<select name="<?=$field['id']?>" id="<?=$field['id']?>">
					<option value=""><?=($field['default']) ? $field['default'] : '--'?></option>
				<?php foreach ($field['options'] as $k=>$v):?>
					<option <?=($current_value == $v) ? ' selected="selected"' : ''?> value="<?=$v?>"><?=$k?></option>
				<?php endforeach;?>
				</select>
			
			<?php break; case 'radio':?>
				<?php foreach ($field['options'] as $k=>$v):?>
				<label for="<?=$field['id']?>_<?=slug($k, '_')?>"><?=$k?></label>
				<input type="radio" name="<?=$field['id']?>" id="<?=$field['id']?>_<?=slug($k, '_')?>" value="<?=$v?>"<?=($current_value == $v) ? ' checked="checked"' : ''?> />
				<?php endforeach;?>
			
			<?php break; case 'checkbox':?>
				<input type="checkbox" name="<?=$field['id']?>" id="<?=$field['id']?>"<?=($current_value) ? ' checked="checked"' : ''?> />
			
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
			
			<?php break; case 'help':?><!-- Do nothing for help -->
			<?php break; default:?>
				<p class="error">Don't know how to handle field of type '<?=$field['type']?>'</p>
			<?php break; endswitch;?>
			<td>
		</tr>
	<?php endforeach;?>
	</table>
	
	<?php if($meta_box['helptxt']):?>
	<p><?=$meta_box['helptxt']?></p>
	<?php endif;?>
	<?php
}

?>