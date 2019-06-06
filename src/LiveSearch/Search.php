<?php

namespace DevDesigns\WoocommerceCustomizations\src\LiveSearch;

use WP_Error;
use WP_Query;
use DevDesigns\WoocommerceCustomizations\src\HookInterface;



class Search implements HookInterface {

	/**
	 * Product query reference.
	 *
	 * @var WP_Query
	 */
	protected $query;

	/**
	 * Reference to product_cat terms
	 *
	 * @var array
	 */
	protected $productCatTerms;

	/**
	 * Reference to product_cat parent terms
	 *
	 * @var array
	 */
	protected $productCatParentTerms;

	/**
	 * Reference to product_cat child terms with term parent of $id arg.
	 *
	 * @var array
	 */
	protected $productCatChildTerms;

	/**
	 * Reference to product_tag terms
	 *
	 * @var array
	 */
	protected $productTagTerms;

	/**
	 * Reference to current product_cat term slug.
	 *
	 * @var string
	 */
	protected $productCatTermSlug;

	/**
	 * Reference to current product_cat parent term id.
	 *
	 * @var int
	 */
	protected $productCatParentTermId;

	/**
	 * Reference to current product_tag term slug.
	 *
	 * @var string
	 */
	protected $productTagTermSlug;


	/**
	 * Search constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->init();
	}


	/**
	 * Add actions and filters.
	 *
	 * @since  1.0.0
	 */
	public function addHooks(): void {
		remove_action( 'woocommerce_after_shop_loop_item', [ $GLOBALS['WC_Quick_View'], 'quick_view_button' ], 5 );
	}


	/**
	 * Setup shortcode.
	 *
	 * @since 1.0.0
	 */
	private function init(): void {
		self::enqueueAssets();
	}


	public function getProductCatTerms (): array {
		$this->productCatTerms = get_terms( [
			'taxonomy'   => 'product_cat',
		] );

		return ( is_array( $this->productCatTerms ) && ! $this->productCatTerms instanceof WP_Error )
			? $this->productCatTerms
			: []
		;
	}


	public function getProductCatParentTerms(): array {
		$this->productCatParentTerms = get_terms( [
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
		] );

		return ( is_array( $this->productCatParentTerms ) && ! $this->productCatParentTerms instanceof WP_Error )
			? $this->productCatParentTerms
			: [];
	}


	/**
	 * Get all child terms with matching parent term id.
	 *
	 * @return array
	 */
	public function getProductCatChildTerms(): array {
		$query = get_queried_object();

		$this->productCatChildTerms = get_terms( [
			'taxonomy'   => 'product_cat',
			'parent'     => $query->term_id,
		] );

		return ( is_array( $this->productCatChildTerms ) && ! $this->productCatChildTerms instanceof WP_Error )
			? $this->productCatChildTerms
			: [];
	}


	public function getProductTagTerms (): array {
		$this->productTagTerms = get_terms( [
			'taxonomy'   => 'product_tag',
			'hide_empty' => false,
		] );

		return ( is_array( $this->productTagTerms ) && ! $this->productTagTerms instanceof WP_Error )
			? $this->productTagTerms
			: [];
	}


	/**
	 * @return string
	 */
	public function getProductCatTermSlug(): string {
		return $this->productCatTermSlug;
	}


	/**
	 * @param string $termSlug
	 */
	public function setProductCatTermSlug( string $termSlug ): void {
		$this->productCatTermSlug = $termSlug;
	}


	/**
	 * @return string
	 */
	public function getProductTagTermSlug(): string {
		return $this->productTagTermSlug;
	}


	/**
	 * @param string $productTagTermSlug
	 */
	public function setProductTagTermSlug( string $productTagTermSlug ): void {
		$this->productTagTermSlug = $productTagTermSlug;
	}


	/**
	 * @return int
	 */
	public function getProductCatParentTermId(): int {
		return $this->productCatParentTermId;
	}


	/**
	 * @param int $productCatParentTermId
	 */
	public function setProductCatParentTermId( int $productCatParentTermId ): void {
		$this->productCatParentTermId = $productCatParentTermId;
	}


	/**
	 * Enqueue assets only on pages where shortcode is being used.
	 *
	 * @since 1.0.0
	 */
	private static function enqueueAssets(): void {
		wp_enqueue_style( 'woocommerce-customizations/google-fonts' );
		wp_enqueue_style( 'woocommerce-customizations/main.css' );

		wp_enqueue_script( 'woocommerce-customizations/isotope.js' );
		wp_enqueue_script( 'woocommerce-customizations/main.js' );
	}
}
