<?php

/**
 * Add the demo import files.
 *
 * @since   0.1.0
 *
 * @return  array  Data for MerlinWP import.
 */
add_filter( 'merlin_import_files', function() {

	$dir = trailingslashit( MAI_DEMO_IMPORTER_DIR );
	$url = trailingslashit( MAI_DEMO_IMPORTER_URL );

	$files = array(
		array(
			'import_file_name'             => 'Mai Business',
			'local_import_file'            => $dir . 'demos/business/business.xml',
			'local_import_widget_file'     => $dir . 'demos/business/business.wie',
			'local_import_customizer_file' => $dir . 'demos/business/business.dat',
			'import_preview_image_url'     => $url . 'demos/business/business.png',
			'import_notice'                => __( 'A Business configuration for Mai Theme.', 'mai-demo-importer' ),
			'preview_url'                  => 'https://demo.maitheme.com/business/',
		),
		array(
			'import_file_name'             => 'Mai Law',
			'local_import_file'            => $dir . 'demos/law/law.xml',
			'local_import_widget_file'     => $dir . 'demos/law/law.wie',
			'local_import_customizer_file' => $dir . 'demos/law/law.dat',
			'import_preview_image_url'     => $url . 'demos/law/law.png',
			'import_notice'                => __( 'A Law configuration for Mai Theme.', 'mai-demo-importer' ),
			'preview_url'                  => 'https://demo.maitheme.com/law/',
		),
		array(
			'import_file_name'             => 'Mai Lifestyle',
			'local_import_file'            => $dir . 'demos/lifestyle/lifestyle.xml',
			'local_import_widget_file'     => $dir . 'demos/lifestyle/lifestyle.wie',
			'local_import_customizer_file' => $dir . 'demos/lifestyle/lifestyle.dat',
			'import_preview_image_url'     => $url . 'demos/lifestyle/lifestyle.png',
			'import_notice'                => __( 'A Lifestyle configuration for Mai Theme.', 'mai-demo-importer' ),
			'preview_url'                  => 'https://maitheme.com/mai-lifestyle-pro/',
		),
		array(
			'import_file_name'             => 'Mai News',
			'local_import_file'            => $dir . 'demos/news/news.xml',
			'local_import_widget_file'     => $dir . 'demos/news/news.wie',
			'local_import_customizer_file' => $dir . 'demos/news/news.dat',
			'import_preview_image_url'     => $url . 'demos/news/news.png',
			'import_notice'                => __( 'A News configuration for Mai Theme.', 'mai-demo-importer' ),
			'preview_url'                  => 'https://demo.maitheme.com/news/',
		),
	);

	$config = false;

	if ( defined( 'CHILD_THEME_NAME' ) ) {

		switch ( CHILD_THEME_NAME ) {
			case 'Mai Law Pro':
				$config = array( 'import_file_name' => 'Mai Law' );
				break;
			case 'Mai Lifestyle Pro':
				$config = array( 'import_file_name' => 'Mai Lifestyle' );
				break;
		}
	}

	if ( $config ) {
		$files = wp_list_filter( $files, $config );
	}

	return $files;
});

/**
 * Set the menus.
 *
 * @since   0.1.0
 *
 * @return  void
 */
add_action( 'merlin_after_all_import', function( $selected_import_index ) {

	$locations = $nav_menu_locations = array();

	// Get menus.
	$locations['header_left']  = maiconfigurations_get_menu_by_slug( 'header-left' );
	$locations['header_right'] = maiconfigurations_get_menu_by_slug( 'header-right' );
	$locations['primary']      = maiconfigurations_get_menu_by_slug( 'primary' );
	$locations['secondary']    = maiconfigurations_get_menu_by_slug( 'footer' );
	$locations['mobile']       = maiconfigurations_get_menu_by_slug( 'mobile' );

	// Loop through our nav menus.
	foreach ( $locations as $location => $menu ) {

		// Skip if no menu.
		if ( ! $menu ) {
			continue;
		}

		// Set as a valid menu.
		$nav_menu_locations[ $location ] = $menu->term_id;
	}

	// Bail if no menus.
	if ( ! $nav_menu_locations ) {
		return;
	}

	// Set the menus.
	set_theme_mod( 'nav_menu_locations', $nav_menu_locations );
});

/**
 * Set the static blog page.
 *
 * @since   0.1.0
 *
 * @return  void
 */
add_action( 'merlin_after_all_import', function( $selected_import_index ) {

	$page_for_posts = get_option( 'page_for_posts' );
	if ( $page_for_posts ) {
		return;
	}

	$blog_page = get_page_by_title( 'Blog' );
	if ( ! $blog_page ) {
		$blog_page = get_page_by_title( 'News' );
	}

	if ( ! $blog_page ) {
		return;
	}

	update_option( 'page_for_posts', $blog_page->ID );
});

/**
 * Get a menu object by its slug.
 *
 * @since   0.1.0
 *
 * @return  object|false
 */
function maiconfigurations_get_menu_by_slug( $slug ) {

	if ( $menu = get_term_by( 'slug', $slug, 'nav_menu' ) ) {
		return $menu;
	}

	if ( $menu = get_term_by( 'slug', $slug . '-nav', 'nav_menu' ) ) {
		return $menu;
	}

	if ( $menu = get_term_by( 'slug', $slug . '-menu', 'nav_menu' ) ) {
		return $menu;
	}

	return false;
}

/**
 * Remove the child theme step.
 * This is for a Genesis child theme.
 *
 * @since   0.1.0
 *
 * @return  $array  The merlin import steps.
 */
add_filter( 'genesis_merlin_steps', function( $steps ) {
	unset( $steps['child'] );
	return $steps;
});

/**
 * Set theme specific widget defaults during import.
 * We need to have this option set prior to importing widgets or they do not immediately show up after import.
 *
 * @since   0.1.0
 *
 * @link    https://github.com/richtabor/MerlinWP/issues/129
 *
 * @param   array  $data  The widget import data.
 *
 * @return  array  The widget data.
 */
add_action( 'merlin_widget_importer_before_widgets_import', function( $data ) {

	// Disable existing widgets.
	update_option( 'sidebars_widgets', array() );

	$footer_widgets = 0;

	foreach ( $data as $id => $widgets ) {
		switch ( $id ) {
			case 'footer-1':
				$footer_widgets++;
				break;
			case 'footer-2':
				$footer_widgets++;
				break;
			case 'footer-3':
				$footer_widgets++;
				break;
			case 'footer-4':
				$footer_widgets++;
				break;
			case 'footer-5':
				$footer_widgets++;
				break;
			case 'footer-6':
				$footer_widgets++;
				break;
		}
	}

	// Make sure the theme supports the right amount of footer widgets.
	add_theme_support( 'genesis-footer-widgets', $footer_widgets );

	// Make sure all the footer widgets are registered.
	genesis_register_footer_widget_areas();

	// Set footer widget count option.
	genesis_update_settings( array(
		'footer_widget_count' => $footer_widgets,
	) );

});