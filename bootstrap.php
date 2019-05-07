<?php
/**
 * Plugin Name:  Woocommerce Customizations
 * Plugin URI:   https://github.com/joedooley/woocommerce-customizations
 * Description:  Adds functionality for product category slider.
 * Author:       Developing Designs
 * Author URI:   https://developingdesigns.com/
 * Version:      1.0.0
 * Tested up to: 5.1.1
 * Text Domain:  wc-customizations
 * Domain Path:  /lang
 *
 * @package     DevDesigns\WoocommerceCustomizations
 * @author      Developing Designs
 * @since       1.0.0
 */

/**
 * Plugin Name: WooCommerce Product Preview
 * Plugin URI: http://berocket.com/product/woocommerce-product-preview
 * Description: Plugin for WooCommerce which add quick product preview
 * Version: 2.0.3.9
 * Author: BeRocket
 * Requires at least: 4.0
 * Author URI: http://berocket.com
 * Text Domain: BeRocket_Product_Preview_domain
 * Domain Path: /languages/
 * 333
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


if ( ! defined( 'WOO_CUSTOMIZATIONS_PATH' ) ) {
	define( 'WOO_CUSTOMIZATIONS_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
}


/*
 * Bootstrap plugin files that aren't autoloaded by composer.
 *
 * @since 1.0.0
 */
add_action( 'plugins_loaded', function (): void {
	if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'BeRocket_Product_Preview' )) {
		return;
	}

	add_action( 'wp_enqueue_scripts', 'DevDesigns\WoocommerceCustomizations\Assets\Enqueue::enqueue' );
} );
