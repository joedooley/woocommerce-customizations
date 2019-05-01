<?php
/**
 * Controller for product_category_slider shortcode.
 *
 * @package DevDesigns\WoocommerceCustomizations
 * @author  Developing Designs
 * @since   1.0.0
 */

namespace DevDesigns\WoocommerceCustomizations\src\Shortcodes;

use WP_Query;


class ProductCategorySlider implements HookInterface {

	/**
	 * Shortcode attributes.
	 *
	 * @var array
	 */
	protected $atts;


	public function __construct( array $atts = [] ) {
		$this->atts = $atts;

		d( $this->getProducts() );
	}


	/**
	 * Add actions and filters.
	 *
	 * @since  1.0.0
	 */
	public function addHooks(): void {
		// TODO: Implement addHooks() method.
	}


	public function render () {
		d( $this->getProducts() );
	}


	private function getProducts(): WP_Query {
		return new WP_Query( $this->queryArgs() );
	}


	private function queryArgs(): array {
		return [
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $this->shortcodeAtts()['termSlug'],
					'operator' => 'IN'
				],
			],
		];
	}


	private function shortcodeAtts(): array {
		$a = shortcode_atts(
			[ 'category' => '', 'heading' => 'true' ],
			$this->atts,
			'wc_product_cat_flickity_slider'
		);

		$termSlug = $a['category'] ?? false;
		$heading = $a['heading'];

		return [
			'termSlug' => $termSlug,
			'heading'  => $heading,
		];
	}

}
