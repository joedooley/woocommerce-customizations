<?php

namespace DevDesigns\WoocommerceCustomizations\Shortcodes;

use WP_Error;
use WP_Query;
use DevDesigns\WoocommerceCustomizations\src\Shortcodes\HookInterface;



class LiveSearch implements HookInterface {

	/**
	 * Shortcode attributes.
	 *
	 * @var array
	 */
	protected $atts;

	/**
	 * Product query reference.
	 *
	 * @var WP_Query
	 */
	protected $query;

	/**
	 * Reference to current product in the loop
	 *
	 * @var object
	 */
	protected $product;

	/**
	 * Reference to current product in the loop
	 *
	 * @var array
	 */
	protected $productCatTerms;

	/**
	 * Name of product_cat taxonomy.
	 *
	 * @var string
	 */
	private const PRODUCT_CAT_TAX_NAME = 'Categories';

	/**
	 * Name of product_tag taxonomy.
	 *
	 * @var string
	 */
	private const PRODUCT_TAG_TAX_NAME = 'Tags';

	/**
	 * Name of product_tag taxonomy.
	 *
	 * @var string
	 */
	private const PRODUCT_ATTS_TAX_NAME = 'Attributes';


	/**
	 * LiveSearch constructor.
	 *
	 * @param array $atts
	 *
	 * @since 1.0.0
	 */
	public function __construct( array $atts = [] ) {
		$this->atts = $atts;

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

		$this->getProducts();
	}


	/**
	 * Render slider.
	 *
	 * @return string
	 */
	public function render(): string {
		$filtersId = $this->uniqueId( 'filters' );
		$productsId = $this->uniqueId( 'products' );

		$products = $this->query->posts;
		$terms = $this->getProductCatTerms();
		$atts = $this->getAttributesTerms();

		d( $terms );
		d( $atts );
		d( wc_get_attribute_taxonomies() );

		// d( $this->query );

		if ( ! $products ) {
			return false;
		}

		if ( $this->query->have_posts() ) :
			ob_start(); ?>

			<div id="<?php echo $filtersId ?>" class="wc-isotope-filters">
				<div class="ui-group button-group product-cat-terms">
					<h3><?php echo self::PRODUCT_CAT_TAX_NAME; ?></h3>
					<div class="button-group js-radio-button-group" data-filter-group="color">
						<button class="button is-checked" data-filter="">any</button>
						<button class="button" data-filter=".red">red</button>
						<button class="button" data-filter=".blue">blue</button>
						<button class="button" data-filter=".yellow">yellow</button>
					</div>
				</div>

				<div class="ui-group button-group wcml-product-attributes-terms">
					<h3><?php echo self::PRODUCT_ATTS_TAX_NAME; ?></h3>
					<div class="button-group js-radio-button-group" data-filter-group="size">
						<button class="button is-checked" data-filter="">any</button>
						<button class="button" data-filter=".small">small</button>
						<button class="button" data-filter=".wide">wide</button>
						<button class="button" data-filter=".big">big</button>
						<button class="button" data-filter=".tall">tall</button>
					</div>
				</div>

				<div class="ui-group button-group product-attributes">
					<h3>Shape</h3>
					<div class="button-group js-radio-button-group" data-filter-group="shape">
						<button class="button is-checked" data-filter="">any</button>
						<button class="button" data-filter=".round">round</button>
						<button class="button" data-filter=".square">square</button>
					</div>
				</div>
			</div>

			<div id="<?php echo $productsId ?>" class="wc-isotope-product-grid woocommerce">
				<?php woocommerce_product_loop_start() ?>
					<?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>
						<?php
							$this->product = wc_get_product( $this->query->post );
							wc_get_template_part( 'content', 'product' );
						?>
					<?php endwhile; ?>
				<?php woocommerce_product_loop_end() ?>
			</div>

		<?php endif;

		$slider = ob_get_clean();
		wp_reset_query();

		return $slider;
	}


	private function getProducts(): void {
		$this->query = new WP_Query( [
			'post_type'      => 'product',
			'posts_per_page' => - 1,
		] );
	}


	private function getProductCatTerms (): array {
		$this->productCatTerms = get_terms( [
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
		] );

		return ( is_array( $this->productCatTerms ) && ! $this->productCatTerms instanceof WP_Error )
			? $this->productCatTerms
			: []
		;
	}


	/**
	 * Get cached/fresh attributes.
	 *
	 * @since 1.0.0
	 */
	private function getAttributesTerms (): array {
		return wc_get_attribute_taxonomies();
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


	/**
	 * Create unique id for flickity element.
	 *
	 * @since 1.0.0
	 */
	private function uniqueId( string $prefix ): string {
		return uniqid( sprintf( '%s-', $prefix ), false );
	}
}
