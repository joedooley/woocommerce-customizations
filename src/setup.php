<?php

namespace DevDesigns\WoocommerceCustomizations;



/**
 * Include plugin files.
 *
 * @since 1.0.0
 */
add_action( 'woocommerce_loaded', function (): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	include_once __DIR__ . '/actions.php';
	include_once __DIR__ . '/filters.php';
	include_once __DIR__ . '/shortcodes.php';
}, 99999 );
