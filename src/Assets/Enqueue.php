<?php
/**
 * Enqueue webpack compiled js and css.
 *
 * @package     DevDesigns\WoocommerceCustomizations
 * @author      Developing Designs
 * @since       1.0.0
 */

namespace DevDesigns\WoocommerceCustomizations\Assets;



class Enqueue {
	public static function enqueue (): void {
		self::scripts();
		self::styles();
	}


	private static function styles(): void {
		wp_register_style(
			'woocommerce-customizations/swiper.css',
			WOO_CUSTOMIZATIONS_URL . 'node_modules/swiper/dist/css/swiper.min.css',
			[],
			'4.5.0'
		);

		// wp_enqueue_style( 'woocommerce-customizations/swiper.css' );

		wp_enqueue_style(
			'woocommerce-customizations/main.css',
			WOO_CUSTOMIZATIONS_URL . 'dist/styles/main.css',
			[],
			WOO_CUSTOMIZATIONS_VERSION
		);
	}


	private static function scripts(): void {
		wp_register_script(
			'woocommerce-customizations/swiper.js',
			WOO_CUSTOMIZATIONS_URL . 'node_modules/swiper/dist/js/swiper.min.js',
			[],
			'4.5.0',
			true
		);

		// wp_enqueue_script( 'woocommerce-customizations/swiper.js' );

		wp_enqueue_script(
			'woocommerce-customizations/main.js',
			WOO_CUSTOMIZATIONS_URL . 'dist/scripts/main.js',
			[ 'jquery', 'woocommerce-customizations/swiper.js' ],
			WOO_CUSTOMIZATIONS_VERSION,
			true
		);
	}
}
