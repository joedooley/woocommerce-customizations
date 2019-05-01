<?php
/**
 * Plugin Name: Woocommerce Customizations
 * Description: Adds functionality for product category slider.
 * Version:     1.0.0
 * Author:      Developing Designs
 *
 * @package     DevDesigns\WoocommerceCustomizations
 * @author      Developing Designs
 * @since       1.0.0
 */

use DevDesigns\WoocommerceCustomizations\src\Shortcodes\ProductCategorySlider;


/**
 * Require Composer autoloader.
 *
 * @since 1.0.0
 */
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
}


/*
 * Plugin directory.
 *
 * @since 1.0.0
 */
if ( ! defined( 'WOO_CUSTOMIZATIONS_VERSION' ) ) {
	define( 'WOO_CUSTOMIZATIONS_VERSION', '1.0.0' );
}


if ( ! defined( 'WOO_CUSTOMIZATIONS_URL' ) ) {
	define( 'WOO_CUSTOMIZATIONS_URL', plugin_dir_url( __FILE__ ) );
}


/*
 * Bootstrap plugin files that aren't autoloaded by composer.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', function (): void {
	if ( ! class_exists( 'WooCommerce' ) || class_exists( 'BeRocket_Product_Preview' )) {
		return;
	}

	add_action( 'wp_enqueue_scripts', 'DevDesigns\WoocommerceCustomizations\Assets\Enqueue::enqueue' );
} );
