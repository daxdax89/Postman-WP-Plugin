<?php
/**
 * A unique identifier is defined to store the options in the database and reference them from the theme.
 */
function iv_postman_optionsframework_option_name()
{
    // Change this to use your theme slug
    return 'iv_postman';
}

/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'iv_postman'
 * with the actual text domain for your theme.  Read more:
 * http://codex.wordpress.org/Function_Reference/load_theme_textdomain
 */
function iv_postman_optionsframework() {
	// Test data
	$test_array = array(
		'one' => __( 'One', 'iv_postman' ),
		'two' => __( 'Two', 'iv_postman' ),
		'three' => __( 'Three', 'iv_postman' ),
		'four' => __( 'Four', 'iv_postman' ),
		'five' => __( 'Five', 'iv_postman' )
	);
	// Multicheck Array
	$multicheck_array = array(
		'one' => __( 'French Toast', 'iv_postman' ),
		'two' => __( 'Pancake', 'iv_postman' ),
		'three' => __( 'Omelette', 'iv_postman' ),
		'four' => __( 'Crepe', 'iv_postman' ),
		'five' => __( 'Waffle', 'iv_postman' )
	);
	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);
	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );
	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );
	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);
	// Pull all the categories into an array
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}
	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages( 'sort_column=post_parent,menu_order' );
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/images/';
	$options = array();
	$options[] = array(
		'name' => __( 'Basic Settings', 'iv_postman' ),
		'type' => 'heading'
	);

	$options[] = array(
		'name' => __( 'Currency', 'iv_postman' ),
		'desc' => __( 'Currency symbol to be shown on currency input field', 'iv_postman' ),
		'id' => 'currency_symbol',
		'placeholder' => '$',
		'std' => '$',
		'type' => 'text'
	);

	$options[] = array(
		'name' => __( 'Post per page', 'iv_postman' ),
		'desc' => __( 'Posts per page in admin table view', 'iv_postman' ),
		'id' => 'posts_per_page',
		'placeholder' => '5',
		'std' => '5',
		'type' => 'text'
	);

	/**
	 * 
	 * 		DONE SETTINGS
	 * 
	 * 
	 */



/*
	$options[] = array(
		'name' => __( 'Input Text Mini', 'iv_postman' ),
		'desc' => __( 'A mini text input field.', 'iv_postman' ),
		'id' => 'example_text_mini',
		'std' => 'Default',
		'class' => 'mini',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( 'Input Text', 'iv_postman' ),
		'desc' => __( 'A text input field.', 'iv_postman' ),
		'id' => 'example_text',
		'std' => 'Default Value',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( 'Input with Placeholder', 'iv_postman' ),
		'desc' => __( 'A text input field with an HTML5 placeholder.', 'iv_postman' ),
		'id' => 'example_placeholder',
		'placeholder' => 'Placeholder',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( 'Textarea', 'iv_postman' ),
		'desc' => __( 'Textarea description.', 'iv_postman' ),
		'id' => 'example_textarea',
		'std' => 'Default Text',
		'type' => 'textarea'
	);
	$options[] = array(
		'name' => __( 'Input Select Small', 'iv_postman' ),
		'desc' => __( 'Small Select Box.', 'iv_postman' ),
		'id' => 'example_select',
		'std' => 'three',
		'type' => 'select',
		'class' => 'mini', //mini, tiny, small
		'options' => $test_array
	);
	$options[] = array(
		'name' => __( 'Input Select Wide', 'iv_postman' ),
		'desc' => __( 'A wider select box.', 'iv_postman' ),
		'id' => 'example_select_wide',
		'std' => 'two',
		'type' => 'select',
		'options' => $test_array
	);
	if ( $options_categories ) {
		$options[] = array(
			'name' => __( 'Select a Category', 'iv_postman' ),
			'desc' => __( 'Passed an array of categories with cat_ID and cat_name', 'iv_postman' ),
			'id' => 'example_select_categories',
			'type' => 'select',
			'options' => $options_categories
		);
	}
	if ( $options_tags ) {
		$options[] = array(
			'name' => __( 'Select a Tag', 'options_check' ),
			'desc' => __( 'Passed an array of tags with term_id and term_name', 'options_check' ),
			'id' => 'example_select_tags',
			'type' => 'select',
			'options' => $options_tags
		);
	}
	$options[] = array(
		'name' => __( 'Select a Page', 'iv_postman' ),
		'desc' => __( 'Passed an pages with ID and post_title', 'iv_postman' ),
		'id' => 'example_select_pages',
		'type' => 'select',
		'options' => $options_pages
	);
	$options[] = array(
		'name' => __( 'Input Radio (one)', 'iv_postman' ),
		'desc' => __( 'Radio select with default options "one".', 'iv_postman' ),
		'id' => 'example_radio',
		'std' => 'one',
		'type' => 'radio',
		'options' => $test_array
	);
	$options[] = array(
		'name' => __( 'Example Info', 'iv_postman' ),
		'desc' => __( 'This is just some example information you can put in the panel.', 'iv_postman' ),
		'type' => 'info'
	);
	$options[] = array(
		'name' => __( 'Input Checkbox', 'iv_postman' ),
		'desc' => __( 'Example checkbox, defaults to true.', 'iv_postman' ),
		'id' => 'example_checkbox',
		'std' => '1',
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => __( 'Advanced Settings', 'iv_postman' ),
		'type' => 'heading'
	);
	$options[] = array(
		'name' => __( 'Check to Show a Hidden Text Input', 'iv_postman' ),
		'desc' => __( 'Click here and see what happens.', 'iv_postman' ),
		'id' => 'example_showhidden',
		'type' => 'checkbox'
	);
	$options[] = array(
		'name' => __( 'Hidden Text Input', 'iv_postman' ),
		'desc' => __( 'This option is hidden unless activated by a checkbox click.', 'iv_postman' ),
		'id' => 'example_text_hidden',
		'std' => 'Hello',
		'class' => 'hidden',
		'type' => 'text'
	);
	$options[] = array(
		'name' => __( 'Uploader Test', 'iv_postman' ),
		'desc' => __( 'This creates a full size uploader that previews the image.', 'iv_postman' ),
		'id' => 'example_uploader',
		'type' => 'upload'
	);
	$options[] = array(
		'name' => "Example Image Selector",
		'desc' => "Images for layout.",
		'id' => "example_images",
		'std' => "2c-l-fixed",
		'type' => "images",
		'options' => array(
			'1col-fixed' => $imagepath . '1col.png',
			'2c-l-fixed' => $imagepath . '2cl.png',
			'2c-r-fixed' => $imagepath . '2cr.png'
		)
	);
	$options[] = array(
		'name' =>  __( 'Example Background', 'iv_postman' ),
		'desc' => __( 'Change the background CSS.', 'iv_postman' ),
		'id' => 'example_background',
		'std' => $background_defaults,
		'type' => 'background'
	);
	$options[] = array(
		'name' => __( 'Multicheck', 'iv_postman' ),
		'desc' => __( 'Multicheck description.', 'iv_postman' ),
		'id' => 'example_multicheck',
		'std' => $multicheck_defaults, // These items get checked by default
		'type' => 'multicheck',
		'options' => $multicheck_array
	);
	$options[] = array(
		'name' => __( 'Colorpicker', 'iv_postman' ),
		'desc' => __( 'No color selected by default.', 'iv_postman' ),
		'id' => 'example_colorpicker',
		'std' => '',
		'type' => 'color'
	);
	$options[] = array( 'name' => __( 'Typography', 'iv_postman' ),
		'desc' => __( 'Example typography.', 'iv_postman' ),
		'id' => "example_typography",
		'std' => $typography_defaults,
		'type' => 'typography'
	);
	$options[] = array(
		'name' => __( 'Custom Typography', 'iv_postman' ),
		'desc' => __( 'Custom typography options.', 'iv_postman' ),
		'id' => "custom_typography",
		'std' => $typography_defaults,
		'type' => 'typography',
		'options' => $typography_options
	);
	$options[] = array(
		'name' => __( 'Text Editor', 'iv_postman' ),
		'type' => 'heading'
	);
	
	$wp_editor_settings = array(
		'wpautop' => true, // Default
		'textarea_rows' => 5,
		'tinymce' => array( 'plugins' => 'wordpress,wplink' )
	);
	$options[] = array(
		'name' => __( 'Default Text Editor', 'iv_postman' ),
		'desc' => sprintf( __( 'You can also pass settings to the editor.  Read more about wp_editor in <a href="%1$s" target="_blank">the WordPress codex</a>', 'iv_postman' ), 'http://codex.wordpress.org/Function_Reference/wp_editor' ),
		'id' => 'example_editor',
		'type' => 'editor',
		'settings' => $wp_editor_settings
	);*/
	return $options;
}