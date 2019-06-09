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


	private static function styles (): void {
		wp_register_style(
			'woocommerce-customizations/flickity.css',
			WOO_CUSTOMIZATIONS_URL . 'dist/styles/vendor/flickity.min.css',
			[],
			'2.2.0'
		);

		wp_register_style(
			'woocommerce-customizations/google-fonts',
			'//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700',
			[],
			WOO_CUSTOMIZATIONS_VERSION
		);

		wp_enqueue_style(
			'woocommerce-customizations/main.css',
			WOO_CUSTOMIZATIONS_URL . 'dist/styles/main.css',
			[ 'woocommerce-customizations/flickity.css' ],
			WOO_CUSTOMIZATIONS_VERSION
		);

		if ( is_shop() || is_product_taxonomy() || is_product() ) {
			wp_enqueue_style(
				'woocommerce-customizations/overrides.css',
				WOO_CUSTOMIZATIONS_URL . 'dist/styles/overrides.css',
				[ 'woocommerce-customizations/main.css' ],
				WOO_CUSTOMIZATIONS_VERSION
			);
		}
	}


	private static function scripts (): void {
		$deps = is_shop() || is_product_taxonomy()
			? [ 'woocommerce-customizations/flickity.js', 'woocommerce-customizations/isotope.js' ]
			: [ 'woocommerce-customizations/flickity.js' ]
		;

		if ( is_shop() || is_product_taxonomy() ) {
			wp_enqueue_script(
				'woocommerce-customizations/isotope.js',
				WOO_CUSTOMIZATIONS_URL . 'dist/scripts/vendor/isotope.pkgd.min.js',
				[],
				'3.0.6',
				true
			);
		}

		wp_register_script(
			'woocommerce-customizations/flickity.js',
			WOO_CUSTOMIZATIONS_URL . 'dist/scripts/vendor/flickity.pkgd.min.js',
			[],
			'2.2.0',
			true
		);

		wp_enqueue_script(
			'woocommerce-customizations/main.js',
			WOO_CUSTOMIZATIONS_URL . 'dist/scripts/main.js',
			$deps,
			WOO_CUSTOMIZATIONS_VERSION,
			true
		);
	}


	public static function customizer (): void {
		wp_enqueue_script(
			'woocommerce-customizations/customizer.js',
			WOO_CUSTOMIZATIONS_URL . 'dist/scripts/customizer.js',
			[ 'jquery', 'customize-preview' ],
			WOO_CUSTOMIZATIONS_VERSION,
			true
		);
	}
}
