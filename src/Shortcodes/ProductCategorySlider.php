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
use DevDesigns\WoocommerceCustomizations\Shortcodes\ProductPreview;
use WP_Query;


class ProductCategorySlider implements HookInterface {

	/**
	 * Shortcode type.
	 *
	 * @var   string
	 */
	protected $type;

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
	 * Reference to current product in the loop
	 *
	 * @var object
	 */
	protected $product;


	/**
	 * ProductCategorySlider constructor.
	 *
	 * @param array $atts
	 * @param string $type
	 */
	public function __construct( array $atts = [], string $type = 'products' ) {
		$this->atts = $atts;
		$this->type = $type;

		$this->init();
	}


	/**
	 * Add actions and filters.
	 *
	 * @since  1.0.0
	 */
	public function addHooks(): void {
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

	}


	private function init (): void {
		$this->shortcodeAtts();
		$this->getProducts();
	}


	/**
	 * @return string
	 */
	public function render(): string {
		$id = $this->uniqueId();
		$noHeadingClass = $this->heading !== 'true' ? ' no-heading' : '';
		$products = $this->query->posts;
		$ids = wp_list_pluck( $products, 'ID' );

		if ( ! $products || ! $ids ) {
			return false;
		}

		if ( $this->query->have_posts() ) :

			ob_start();

			self::enqueueAssets(); ?>

			<section class="product-category-slider-flickity woocommerce is-hidden">
				<?php if ( $this->heading === 'true' ) : ?>
					<?php echo $this->renderHeading( $this->termSlug ) ?>
				<?php endif; ?>
				<div id="<?php echo $id ?>" class="products flickity-slider <?php echo $noHeadingClass ?>">
					<?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>
						<?php $this->product = wc_get_product( $this->query->post );

						$args = [
							'previewButton' => ProductPreview::get_preview_button(),
							'addToCartButton' => $this->getAddToCartButton() ?? '',
							'permalink'       => $this->product->get_permalink() ?? ''
						];

						wc_get_template( 'content-product.php', $args ); ?>
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


	public function renderButtonGroup(): void { ?>
		<div class="button-group-container">
			<?php echo self::previewButton() ?>
			<a href="#" class="square-button gallery-popup">Gallery Popup</a>
			<?php echo $this->getAddToCartButton(); ?>
			<a href="<?php echo $this->product->get_permalink() ?>" class="square-button open-product">Open Product</a>
		</div>
		<?php
	}


	/**
	 * @param $id
	 *
	 * @return string
	 */
	public static function previewButton( int $id ) {
		global $product, $wp_query, $br_preview_rand, $br_preview_id;
		wp_enqueue_script( 'wc-single-product' );

		$product_id = br_wc_get_product_id( $product );

		if ( $br_preview_id !== $id ) {
			$br_preview_rand = rand();
		}

		$br_preview_id = $id;
		$dataId = $product_id . $br_preview_rand;

		$button = ProductPreview::get_preview_button();
		d( $button );

		return sprintf(
			'<a data-id="%d" class="br_product_preview_button button" href="#preview">%s</a>',
			$dataId,
			__( 'Preview Product', 'woocommerce' )
		);
	}



	public function getAddToCartButton(): string {
		$isAjax = $this->product->supports( 'ajax_add_to_cart' ) && $this->isPurchasable() ? 'ajax_add_to_cart' : '';
		$disabled = $this->isPurchasable() ? '' : 'disabled';
		$classes = "square-button add-to-cart add_to_cart_button {$disabled} {$isAjax}";
		$addToCartUrl = $disabled ? '#' : $this->product->add_to_cart_url();

		return sprintf(
			'<a href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow" class="%s">%s</a>',
			esc_url( $addToCartUrl ),
			esc_attr( 1 ),
			$this->product->get_id(),
			$this->product->get_sku(),
			$this->product->add_to_cart_description(),
			esc_attr( $classes ),
			esc_html( __( 'Add to Cart', 'wc-customizations' ) )
		);
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


	/**
	 *
	 */
	private function isPurchasable(): bool {
		return $this->product->is_purchasable() && $this->product->is_in_stock();
	}


	private static function enqueueAssets(): void {
		wp_enqueue_style( 'woocommerce-customizations/flickity.css' );
		wp_enqueue_script( 'woocommerce-customizations/flickity.js' );
	}
}
