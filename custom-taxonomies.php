<?php

/**
 * Abstract class for defining custom taxonomies.  
 * 
 **/
abstract class CustomTaxonomy {
	public
		$name			= 'custom_taxonomy',
		
		// Do not register the taxonomy with the post type here.
		// Register it on the `taxonomies` attribute of the post type in
		// custom-post-types.php
		$object_type	= Array(), 
		
		$general_name               = 'Post Tags',
		$singular_name              = 'Post Tag',
		$search_items               = 'Search Tags',
		$popular_items              = 'Popular Tags',
		$all_items                  = 'All Tags',
		$parent_item                = 'Parent Category',
		$parent_item_colon          = 'Parent Category:',
		$edit_item                  = 'Edit Tag',
		$update_item                = 'Update Tag',
		$add_new_item               = 'Add New Tag',
		$new_item_name              = 'New Tag Name',
		$separate_items_with_commas = True,
		$add_or_remove_items        = 'Add or Remove Tag',
		$choose_from_most_used      = 'Choose from Most Used Tag',
		$menu_name                  = NULL,
		
		$public                = True,
		$show_in_nav_menus     = True,
		$show_in_name_menus    = NULL,
		$show_ui               = NULL,
		$show_tagcloud         = NULL,
		$hierarchical          = False,
		$update_count_callback = '',
		$rewrite               = True,
		$query_var             = NULL,
		$capabilities          = Array();
	
	function __construct() {
		if(is_null($this->show_in_name_menus)) $this->show_in_name_menus = $this->public;
		if(is_null($this->show_ui)) $this->show_ui = $this->public;
		if(is_null($this->show_tagcloud)) $this->show_tagcloud = $this->show_ui;
		if(is_null($this->menu_name)) $this->menu_name = $this->general_name;
	}
	
	public function options($key){
		$vars = get_object_vars($this);
		return $vars[$key];
	}
	
	public function labels() {
		return Array(
				'name'                       => _x($this->options('general_name'), 'taxonomy general name'),
				'singular_name'              => _x($this->options('singular_name'), 'taxonomy singular name'),
				'search_items'               => __($this->options('search_items')),
				'popular_items'              => __($this->options('popular_items')),
				'all_items'                  => __($this->options('all_items')),
				'parent_item'                => __($this->options('popular_items')),
				'parent_item_colon'          => __($this->options('parent_item_colon')),
				'edit_item'                  => __($this->options('edit_item')),
				'update_item'                => __($this->options('update_item')),
				'add_new_item'               => __($this->options('add_new_item')),
				'new_item_name'              => __($this->options('new_item_name')),
				'separate_items_with_commas' => __($this->options('separate_items_with_commas')),
				'add_or_remove_items'        => __($this->options('add_or_remove_items')),
				'choose_from_most_used'      => __($this->options('choose_from_most_used')),
				'menu_name'                  => __($this->options('menu_name'))
				);
	}
	
	public function register() {
		$args = Array(
				'labels'                => $this->labels(),
				'public'                => $this->options('public'),
				'show_in_nav_menus'     => $this->options('show_in_nav_menus'),
				'show_ui'               => $this->options('show_ui'),
				'show_tagcloud'         => $this->options('show_tagcloud'),
				'hierarchical'          => $this->options('hierarchical'),
				'update_count_callback' => $this->options('update_count_callback'),
				'rewrite'               => $this->options('rewrite'),
				'query_var'             => $this->options('query_var'),
				'capabilities'          => $this->options('capabilities')
			);
		register_taxonomy($this->options('name'), $this->options('object_type'), $args);
	}
}


/**
 * Describes organizational groups
 *
 * @author Chris Conover
 **/
class OrganizationalGroups extends CustomTaxonomy
{
	public
		$name               = 'org_groups',
		$general_name       = 'Organizational Groups',
		$singular_name      = 'Organizational Group',
		$search_items       = 'Search Organizational Groups',
		$popular_items      = 'Popular Organizational Groups',
		$all_times          = 'All Organizational Groups',
		$parent_item        = 'Parent Organizational Group',
		$parent_item_colon  = 'Parent Organizational Group:',
		$edit_item          = 'Edit Organizational Group',
		$update_item        = 'Update Organizational Group',
		$add_new_item       = 'Add New Organizational Group',
		$new_item_name      = 'New Tag Organizational Group',
		
		$hierarchical = True;
} // END class 

/**
 * Describes an issues of Pegasus Magazine
 *
 * @author Chris Conover
 **/
class Issues extends CustomTaxonomy
{
	public
		$name               = 'issues',
		$general_name       = 'Issues',
		$singular_name      = 'Issue',
		$search_items       = 'Search Issues',
		$popular_items      = 'Popular Issues',
		$all_times          = 'All Issues',
		$parent_item        = 'Parent Issue',
		$parent_item_colon  = 'Parent Issue:',
		$edit_item          = 'Edit Issue',
		$update_item        = 'Update Issue',
		$add_new_item       = 'Add New Issue',
		$new_item_name      = 'New Tag Issue',
		
		$hierarchical 		= True,
		$query_var 			= 'issues';
} // END class 
?>