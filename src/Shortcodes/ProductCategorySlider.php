<?php
/**
 * Controller for product_category_slider shortcode.
 *
 * @package DevDesigns\WoocommerceCustomizations
 * @author  Developing Designs
 * @since   1.0.0
 */

namespace DevDesigns\WoocommerceCustomizations\src\Shortcodes;

use BeRocket_Product_Preview;
use WC_Product;
use WP_Query;


class ProductCategorySlider implements HookInterface {

	/**
	 * Shortcode attributes.
	 *
	 * @var array
	 */
	protected $atts;

	/**
	 * Term slug for WP_Query
	 *
	 * @var string
	 */
	protected $termSlug;

	/**
	 * Slider section heading
	 *
	 * @var string
	 */
	protected $heading;

	/**
	 * Product query reference.
	 *
	 * @var WP_Query
	 */
	protected $query;


	/**
	 * ProductCategorySlider constructor.
	 *
	 * @param array $atts
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
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'renderButtonGroups'] );
	}


	private function init (): void {
		$this->shortcodeAtts();
		$this->getProducts();
	}


	public function render (): string {
		if ( $this->query->have_posts() ) :
			wp_enqueue_style( 'woocommerce-customizations/flickity.css' );
			wp_enqueue_script( 'woocommerce-customizations/flickity.js' );

			$id = $this->uniqueId();
			$noHeadingClass = $this->heading !== 'true' ? ' no-heading' : '';
			$product = wc_get_product( $this->query->post );
			ob_start(); ?>

			<section class="product-category-slider-flickity woocommerce is-hidden">
				<?php if ( $this->heading === 'true' ) : ?>
					<?php echo $this->renderHeading( $this->termSlug ) ?>
				<?php endif; ?>
				<div id="<?php echo $id ?>" class="products flickity-slider <?php echo $noHeadingClass ?>">
					<?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>
					<?php endwhile; ?>
				</div>
			</section>

		<?php endif;

		$slider = ob_get_clean();
		wp_reset_query();

		return $slider;
	}


	private function getProducts(): void {
		$this->query = new WP_Query( $this->queryArgs() );
	}


	private function queryArgs(): array {
		return [
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'tax_query'      => [
				[
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $this->termSlug,
					'operator' => 'IN'
				],
			],
		];
	}


	public function renderButtonGroups(): void {
		$product = wc_get_product( $this->query->post );
		$permalink = get_permalink( $product->get_id() ); ?>

		<div class="button-group-container">
			<div class="button-group front">
				<div class="triangle right"></div>
			</div>
			<div class="button-group back">
				<div class="triangle left"></div>
				<?php BeRocket_Product_Preview::get_preview_button() ?>
				<a href="#" class="square-button gallery-popup">Gallery Popup</a>
				<a href="<?php echo $product->add_to_cart_url() ?>" class="square-button add-to-cart">Add to Cart</a>
				<a href="<?php echo $permalink ?>" class="square-button open-product">Open Product</a>
			</div>
		</div>
		<?php
	}


	/**
	 * Render header section of slider.
	 *
	 * @param string $slug
	 *
	 * @since 1.0.0
	 */
	private function renderHeading( string $slug ): string {
		$heading = $this->termHeading( $slug );
		$termArchive = $this->termArchive( $slug );

		return $slug
			? vsprintf( '<header class="heading">%1$s %2$s</header>', [ $heading, $termArchive ] )
			: '';
	}


	/**
	 * Creates heading element for slider.
	 *
	 * @param string $termSlug
	 *
	 * @since 1.0.0
	 */
	private function termHeading( string $termSlug ): string {
		return $termSlug
			? "<h2 class='term-heading'>{$termSlug}</h2>"
			: '';
	}


	/**
	 * Create link to product_cat term archive.
	 *
	 * @param string $termSlug
	 * @param string $tax
	 *
	 * @since 1.0.0
	 */
	private function termArchive( string $termSlug, string $tax = 'product_cat' ): string {
		$termLink = get_term_link( $termSlug, $tax );
		$termArchiveLabel = __( 'View Collection ', 'woo-customizations' );
		$icon = '<span class="fa fa-chevron-right"></span>';

		return $termLink
			? vsprintf( '<a class="term-link" href="%1$s">%2$s %3$s</a>', [ $termLink, $termArchiveLabel, $icon ] )
			: '';
	}


	private function shortcodeAtts(): void {
		$a = shortcode_atts(
			[ 'category' => '', 'heading' => 'true' ],
			$this->atts,
			'wc_product_cat_flickity_slider'
		);

		$this->termSlug = $a['category'] ?? false;
		$this->heading = $a['heading'];
	}


	/**
	 * Create unique id for flickity element.
	 *
	 * @since 1.0.0
	 */
	private function uniqueId(): string {
		return uniqid( 'flickity-slider-', false );
	}

}
