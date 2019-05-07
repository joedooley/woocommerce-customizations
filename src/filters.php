<?php

namespace DevDesigns\WoocommerceCustomizations;



/**
 * Override WooCommerce templates from plugins templates dir.
 *
 * @param $template
 * @param $template_name
 * @param $templates_directory
 *
 * @return string
 */
add_filter( 'woocommerce_locate_template', function ( $template, $template_name, $templates_directory ):string {
	$original_template = $template;

	if ( ! $templates_directory ) {
		$templates_directory = WC()->template_url;
	}

	// Plugin's custom templates/ directory
	$plugin_path = WOO_CUSTOMIZATIONS_PATH . '/templates/';

	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		[
			$templates_directory . $template_name,
			$template_name,
		]
	);

	// Get the template from this plugin under /templates/ directory, if it exists.
	if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
		$template = $plugin_path . $template_name;
	}

	// Use default template if not found a suitable template under plugin's /templates/ directory.
	if ( ! $template ) {
		$template = $original_template;
	}

	// Return what we found.
	return $template;
}, 10, 3 );
