<?php
/**
 * Responsible for ACF related functionality
 **/

// Exit early if ACF Pro is not activated
if ( ! class_exists( 'acf_pro' ) ) return;


/**
 * Adds a Story Version rule type.
 *
 * @since 6.0.1
 * @author Cadie Stockman
 * @param array $choices Array of location rule choices
 * @return array $choices
 **/
function acf_location_rules_types( $choices ) {
	$choices['Story']['story_version'] = 'Story Version';

	return $choices;
}

add_filter('acf/location/rule_types', 'acf_location_rules_types');


/**
 * Adds a 'greater than or equal to' operator
 * to the Story Version location rule.
 *
 * @since 6.0.1
 * @author Cadie Stockman
 * @param array $choices Array of operator choices for the Story Version location choices.
 * @return array $choices
 **/
function acf_location_rules_operators_story_version( $choices ) {
	$choices['>='] = 'greater than or equal to';

	return $choices;
}

add_filter('acf/location/rule_operators/story_version', 'acf_location_rules_operators_story_version');


/**
 * Adds 6 as a choice for the Story Version location rule.
 *
 * @since 6.0.1
 * @author Cadie Stockman
 * @param array $choices Array of values for the Story Version location rule.
 * @return array $choices
 **/
function acf_location_rule_values_story_version( $choices ) {
	$choices[6] = 6;

	return $choices;
}

add_filter('acf/location/rule_values/story_version', 'acf_location_rule_values_story_version');


/**
 * Adds functionality to match the custom Story Version
 * location rules depending on the selected operator and value.
 *
 * @since 6.0.1
 * @author Cadie Stockman
 * @param bool $match The match result.
 * @param array $rule The location rule.
 * @param array $options The current edit screen data.
 * @param array $field_group The field group array.
 * @return bool $match
 **/
function acf_location_rule_match_story_version( $match, $rule, $options, $field_group ) {
	// Check screen args for "post_id" which will exist when editing a post.
	// Return false for all other edit screens.
	if ( isset( $options['post_id'] ) ) {
		$post_id = $options['post_id'];
	} else {
		return false;
	}

	// Load the post object for this edit screen.
	$post = get_post( $post_id );
	if ( !$post ) {
		return false;
	}

	$post_version   = get_relevant_version( $post );
	$acf_rule_value = intval( $rule['value'] );

	$match = false;

	if ( $rule['operator'] == ">=" ) {
		$match = ( $post_version >= $acf_rule_value );
	}

	return $match;
}
add_filter('acf/location/rule_match/story_version', 'acf_location_rule_match_story_version', 10, 4);


/**
 * Adds the ACF fields for the story sidebar
 * and related fields.
 *
 * @since 6.0.0
 */
function add_story_sidebar_acf_fields() {
	$fields = array();

	$fields[] = array(
		'key'       => 'author_tab',
		'label'     => 'Author',
		'type'      => 'tab',
		'required'  => 0,
		'placement' => 'top',
		'endpoint'  => 0,
	);

	$fields[] = array(
		'key'          => 'author_byline',
		'label'        => 'Author Byline',
		'name'         => 'author_byline',
		'type'         => 'wysiwyg',
		'required'     => 1,
		'toolbar'      => 'inline_text',
		'media_upload' => 0,
	);



	$fields[] = array(
		'key'       => 'spotlight_tab',
		'label'     => 'Spotlight',
		'type'      => 'tab',
		'required'  => 0,
		'placement' => 'top',
		'endpoint'  => 0,
	);

	$fields[] = array(
		'key'           => 'sidebar_spotlight',
		'label'         => 'Spotlight',
		'name'          => 'sidebar_spotlight',
		'type'          => 'post_object',
		'instructions'  => 'Select a spotlight to display in the sidebar of this story.',
		'required'      => 0,
		'post_type'     => array(
			0 => 'ucf_spotlight',
		),
		'return_format' => 'object',
		'ui'            => 1,
	);

	$fields[] = array(
		'key'       => 'related_stories_tab',
		'label'     => 'Related Stories',
		'type'      => 'tab',
		'required'  => 0,
		'placement' => 'top',
		'endpoint'  => 0,
	);

	// Add List Type Field
	$fields[] = array(
		'key'           => 'related_stories_list_type',
		'label'         => 'List Type',
		'name'          => 'related_stories_list_type',
		'type'          => 'radio',
		'instructions'  => 'Choose the type of related stories list to display in the sidebar of this story.',
		'required'      => 0,
		'choices'       => array(
			'auto'        => 'Automatic (Related Pegasus Stories)',
			'pegasus-tag' => 'Related Pegasus Stories by Tag',
			'today-feed'  => 'Today Stories Feed',
			'curated'     => 'Curated List',
		),
		'default_value' => 'related-pegasus',
		'layout'        => 'vertical',
		'return_format' => 'value',
	);

	// Add Pegasus Tag field
	$fields[] = array(
		'key'               => 'related_stories_pegasus_tag',
		'label'             => 'Pegasus Tag',
		'name'              => 'related_stories_pegasus_tag',
		'type'              => 'taxonomy',
		'instructions'      => 'Choose a tag to filter related stories by.',
		'required'          => 0,
		'conditional_logic' => array(
			array(
				array(
					'field'    => 'related_stories_list_type',
					'operator' => '==',
					'value'    => 'pegasus-tag',
				),
			),
		),
		'taxonomy'         => 'post_tag',
		'field_type'       => 'select',
		'allow_null'       => 0,
		'add_term'         => 0,
		'save_terms'       => 0,
		'load_terms'       => 0,
		'return_format'    => 'id',
		'multiple'         => 0,
	);

	// Add Today Section/Topic field
	$fields[] = array(
		'key'          => 'today_section_topic',
		'label'        => 'Today Section/Topic',
		'name'         => 'today_section_topic',
		'type'         => 'url',
		'instructions' => 'The URL of the UCF Today section or topic to pull stories from, e.g. https://www.ucf.edu/news/arts/ or https://www.ucf.edu/news/tag/health/.',
		'required'     => 0,
		'conditional_logic' => array(
			array(
				array(
					'field' => 'related_stories_list_type',
					'operator' => '==',
					'value' => 'today-feed',
				),
			),
		)
	);

	/**
	 * Created array to hold the "curated"
	 * list type subfields
	 */
	$curated_subfields = array();

	// Create Story Type subfield
	$curated_subfields[] = array(
		'key'          => 'curated_sf_story_type',
		'label'        => 'Story Type',
		'name'         => 'story_type',
		'type'         => 'radio',
		'choices'      => array(
			'pegasus' => 'Pegasus Story',
			'today'   => 'Today Story',
		),
		'allow_null'    => 0,
		'other_choice'  => 0,
		'default_value' => 'pegasus',
		'layout'        => 'vertical',
		'return_format' => 'value'
	);

	// Create Pegasus Story subfield
	$curated_subfields[] = array(
		'key'               => 'curated_sf_pegasus_story',
		'label'             => 'Pegasus Story',
		'name'              => 'pegasus_story',
		'type'              => 'post_object',
		'instructions'      => 'Select the Pegasus story',
		'conditional_logic' => array(
			array(
				array(
					'field'    => 'curated_sf_story_type',
					'operator' => '==',
					'value'    => 'pegasus',
				),
			),
		),
		'post_type' => array(
			0 => 'story',
		),
		'return_format' => 'object',
		'ui' => 1
	);

	$curated_subfields[] = array(
		'key'               => 'curated_sf_today_story_url',
		'label'             => 'Today Story',
		'name'              => 'today_story_url',
		'type'              => 'url',
		'instructions'      => 'The published URL of the today story to include, e.g. https://www.ucf.edu/news/what-is-kwanzaa/.',
		'conditional_logic' => array(
			array(
				array(
					'field'    => 'curated_sf_story_type',
					'operator' => '==',
					'value'    => 'today',
				),
			),
		)
	);

	// Create curated stories field
	$fields[] = array(
		'key'               => 'related_stories_curated_stories',
		'label'             => 'Curated Stories',
		'name'              => 'related_stories_curated_stories',
		'type'              => 'repeater',
		'conditional_logic' => array(
			array(
				array(
					'field'    => 'related_stories_list_type',
					'operator' => '==',
					'value'    => 'curated',
				),
			),
		),
		'collapsed'        => 'curated_sf_story_type',
		'min'              => 3,
		'max'              => 5,
		'layout'           => 'row',
		'button_label'     => 'Add Story',
		'sub_fields'       => $curated_subfields
	);

	$group = array(
		'key'                   => 'story_sidebar_options',
		'title'                 => 'Sidebar Options',
		'fields'                => $fields,
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'story',
				),
				array(
					'param'    => 'page_template',
					'operator' => '!=',
					'value'    => 'template-fullwidth.php'
				),
				array(
					'param' => 'story_version',
					'operator' => '>=',
					'value' => '6',
				)
			),
		),
		'position'              => 'normal',
		'style'                 => 'default',
		'active'                => true
	);

	acf_add_local_field_group( $group );
}

add_action( 'acf/init', 'add_story_sidebar_acf_fields', 10, 0 );


/**
 * Adds the ACF fields for the full width
 * story options.
 *
 * @since 6.0.0
 */
function add_full_width_story_acf_fields() {
	$fields = array();

	$fields[] = array(
		'key'               => 'story_fw_display_standard_header',
		'label'             => 'Display Standard Header',
		'name'              => 'story_fw_display_standard_header',
		'type'              => 'true_false',
		'instructions'      => 'Whether to display the standard story header. This includes the story\'s title (H1), deck, and header image (Featured Image or the Header Image, if set).',
		'default_value'     => 1,
		'ui'                => 1,
	);

	$group = array(
		'key'                   => 'full_width_story_options',
		'title'                 => 'Full Width Story Options',
		'fields'                => $fields,
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'story',
				),
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => 'template-fullwidth.php'
				),
				array(
					'param' => 'story_version',
					'operator' => '>=',
					'value' => '6',
				)
			),
		),
		'position'              => 'normal',
		'style'                 => 'default',
		'active'                => true
	);

	acf_add_local_field_group( $group );

}

add_action( 'acf/init', 'add_full_width_story_acf_fields', 10, 0 );
