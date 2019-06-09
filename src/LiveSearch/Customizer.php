<?php
/**
 * Adds options to the customizer in WooCommerce section.
 *
 * @package DevDesigns\WoocommerceCustomizations
 * @author  Developing Designs
 * @since   1.0.0
 */

namespace DevDesigns\WoocommerceCustomizations\LiveSearch;

use WP_Customize_Manager;



class Customizer {

	/**
	 * Add Live Search section to WooCommerce panel.
	 *
	 * @param \WP_Customize_Manager $api
	 */
	public function addSection( WP_Customize_Manager $api ): void {
		$settings = $this->settingsConfig();
		$this->addSettings( $settings, $api );

		$api->add_section(
			'wc_live_search',
			[
				'title'    => __( 'Live Search', 'woocommerce' ),
				'priority' => 999,
				'panel'    => 'woocommerce',
				'description' => __( 'Update headings for each filter.', 'woocommerce' ),
			]
		);

		foreach ( $settings as $id => $label ) {
			$this->addControl( $id, $label, $api );
		}

		if ( isset( $wp_customize->selective_refresh ) ) {
			$api->selective_refresh->add_partial(
				'wc_product_cats_heading', [
					'selector'            => '.product-cats-heading',
					'container_inclusive' => false,
					'render_callback'     => function () {
						echo __( get_option( 'wc_product_cats_heading', 'All Products' ) );
					},
				]
			);
		}

		// d( '' );
		// d( $api );
		// d( $api->selective_refresh );
		// d( $api->selective_refresh->partials() );
	}


	/**
	 * Create control to update filter heading.
	 *
	 * @param string $id
	 * @param string $label
	 * @param \WP_Customize_Manager $api
	 */
	private function addControl( string $id, string $label, WP_Customize_Manager $api ): void {
		$api->add_control(
			$id,
			[
				'type'        => 'text',
				'section'     => 'wc_live_search',
				'label'       => __( $label, 'woocommerce' ),
				'settings'    => $id
			]
		);
	}


	/**
	 * Add settings.
	 *
	 * @param array $ids
	 * @param \WP_Customize_Manager $api
	 */
	private function addSettings ( array $ids, WP_Customize_Manager $api ): void {
		foreach ( $ids as $id => $default ) {
			$api->add_setting(
				$id,
				[
					'type' => 'option',
					'default' => $default,
					'transport' => 'postMessage',
					'sanitize_callback' => 'wp_filter_nohtml_kses',
				]
			);
		}
	}


	/**
	 * Setting ID's and default values.
	 *
	 * @return array
	 */
	private function settingsConfig (): array {
		return [
			'wc_product_cats_heading'    => 'Categories',
			'wc_product_subcats_heading' => 'Subcategories',
			'wc_product_tags_heading'    => 'Tags',
			'wc_product_prices_heading'  => 'Prices',
		];
	}
}
