<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$productCatTermSlug = '';
$productCatTerms = get_the_terms( $product->get_id(), 'product_cat' );

$productTagTermSlug = '';
$productTagTerms = get_the_terms( $product->get_id(), 'product_tag' );

if ( $productCatTerms && ! is_wp_error( $productCatTerms ) ) {
	$productCatTermSlug = sprintf( '.product_cat-%s', $productCatTerms[0]->slug );
}

if ( $productTagTerms && ! is_wp_error( $productTagTerms ) ) {
	$productTagTermSlug = sprintf( '.product_tag-%s', $productTagTerms[0]->slug );
}

$previewButton = sprintf(
	'<a href="%s" class="square-button preview-button quick-view-button button">%s</a>',
	$GLOBALS['WC_Quick_View']->get_quick_view_url(),
	__( 'Preview Product', 'woocommerce' )
);

$productButton = sprintf(
	'<a href="%s" class="square-button open-product">%s</a>',
	$product->get_permalink(),
	__( 'Open Product', 'woocommerce' )
);

$price = (int) $product->get_price();
$isotopePriceClass = '';

if ( $price <= 50 ) {
	$isotopePriceClass = 'upTo50';
} elseif ( $price >= 50 && $price <= 100 ) {
	$isotopePriceClass = 'between50and100';
} elseif ( $price >= 100 && $price <= 250 ) {
	$isotopePriceClass = 'between100and250';
} elseif ( $price >= 250 && $price <= 500 ) {
	$isotopePriceClass = 'between250and500';
} elseif ( $price >= 500 ) {
	$isotopePriceClass = 'greaterThan500';
}

?>

<li <?php wc_product_class( $isotopePriceClass ); ?> xmlns="http://www.w3.org/1999/html">
	<div class="inner">
		<?php
		/**
		 * Hook: woocommerce_before_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_open - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item' );

		/**
		 * Hook: woocommerce_before_shop_loop_item_title.
		 *
		 * @hooked woocommerce_show_product_loop_sale_flash - 10
		 * @hooked woocommerce_template_loop_product_thumbnail - 10
		 */
		do_action( 'woocommerce_before_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_product_title - 10
		 */
		do_action( 'woocommerce_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_after_shop_loop_item_title.
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		do_action( 'woocommerce_after_shop_loop_item_title' );

		/**
		 * Hook: woocommerce_after_shop_loop_item.
		 *
		 * @hooked woocommerce_template_loop_product_link_close - 5
		 * @hooked woocommerce_template_loop_add_to_cart - 10
		 */
		// do_action( 'woocommerce_after_shop_loop_item' );
		?>
		</a>
		<div class="button-group-container">
			<?php echo $previewButton; ?>
			<?php echo woocommerce_template_loop_add_to_cart(); ?>
			<?php echo $productButton; ?>
		</div>

	</div>
</li>
