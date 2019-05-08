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
	 * Reference to active term slug.
	 *
	 * @var string
	 */
	protected $termSlug;

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

		d( $terms );

		if ( ! $products ) {
			return false;
		}

		if ( $this->query->have_posts() ) :
			ob_start(); ?>

			<div class="wc-live-search">
				<div class="wc-isotope-search">
					<label for="wc-isotope-search">
						<input id="wc-isotope-search" class="search-input" placeholder="Search" type="search" />
					</label>
				</div>
				<div id="<?php echo $filtersId ?>" class="wc-isotope-filters">
					<?php if ( $terms ): ?>
						<div class="ui-group button-group product-cat-terms">
							<h3><?php echo self::PRODUCT_CAT_TAX_NAME; ?></h3>
							<div class="button-group js-radio-button-group" data-filter-group="product_cat">
								<button class="button is-checked" data-filter="*">All Products</button>
								<?php foreach ( $terms as $term ): ?>
									<?php if ( $term->count !== 0 ): ?>
										<?php $this->termSlug = sprintf( '.product_cat-%s', $term->slug ); ?>
										<button class="button" data-filter="<?php echo $this->termSlug; ?>"><?php echo $term->name; ?></button>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<div id="<?php echo $productsId ?>" class="wc-isotope-product-grid woocommerce">
					<?php woocommerce_product_loop_start() ?>
						<?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>
							<?php
								$this->product = wc_get_product( $this->query->post );
								wc_get_template( 'content-product-isotope.php', [ 'termSlug' => $this->termSlug ] );
							?>
						<?php endwhile; ?>
					<?php woocommerce_product_loop_end() ?>
				</div>
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
