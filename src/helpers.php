<?php

namespace DevDesigns\WoocommerceCustomizations;



/**
 * Include non-autoloaded files.
 *
 * @since 1.0.0
 */
function includeNonClassFiles(): void {
	include_once __DIR__ . '/actions.php';
	include_once __DIR__ . '/filters.php';
	include_once __DIR__ . '/shortcodes.php';

	include_once __DIR__ . '/admin.php';
}


/**
 * Renders a formatted error message.
 *
 * @since 1.0.0
 *
 * @param $message
 * @param bool $code
 * @param string $title
 */
function errorMessage( $message, $code, $title = '' ): void {
	$title = $title ? "<h4>{$title}</h4>" : '<h4>WooCommerce Customizations &rsaquo; Error</h4>';
	$message = $message ? "<strong>{$message}</strong>" : '';
	$code = $code ? "<pre>{$code}</pre>" : '';

	_e( "{$title}{$message}{$code}", 'woo-customizations' );
}
