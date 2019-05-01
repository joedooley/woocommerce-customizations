<?php

namespace DevDesigns\WoocommerceCustomizations;

use BeRocket_Product_Preview;
use WC_Product;
use WP_Query;



add_shortcode( 'wc_product_cat_flickity_slider', function ( $atts ): string {
	$a = shortcode_atts(
		[ 'category' => '', 'heading' => 'true' ],
		$atts,
		'wc_product_cat_flickity_slider'
	);

	d($a);

	$id = uniqueId();
	$termSlug = $a['category'] ?? false;
	$heading = $a['heading'];
	$noHeadingClass = $heading !== 'true' ? ' no-heading' : '';

	$args = [
		'post_type'      => 'product',
		'posts_per_page' => - 1,
		'tax_query'      => [
			[
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $termSlug,
				'operator' => 'IN'
			],
		],
	];

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :
		wp_enqueue_style( 'woocommerce-customizations/flickity.css' );
		wp_enqueue_script( 'woocommerce-customizations/flickity.js' );

		$product = wc_get_product( $query->post );
		d( $product );
		ob_start(); ?>

		<section class="product-category-slider-flickity woocommerce is-hidden">
			<?php if ( $heading === 'true' ) : ?>
				<?php echo renderHeading( $termSlug ) ?>
			<?php endif; ?>

			<div id="<?php echo $id ?>" class="products flickity-slider <?php echo $noHeadingClass ?>">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<?php wc_get_template_part( 'content', 'product' ); ?>
				<?php endwhile; ?>
			</div>
		</section>

	<?php endif;

	$slider = ob_get_clean();
	wp_reset_query();

	return $slider;
} );


/**
 * Render header section of slider.
 *
 * @since 1.0.0
 *
 * @param string $slug
 */
function renderHeading( string $slug ): string {
	$heading = termHeading( $slug );
	$termArchive = termArchive( $slug );

	return $slug
		? vsprintf( '<header class="heading">%1$s %2$s</header>', [ $heading, $termArchive ] )
		: ''
	;
}


/**
 * Creates heading element for slider.
 *
 * @since 1.0.0
 *
 * @param string $termSlug
 */
function termHeading ( string $termSlug ): string {
	return $termSlug
		? "<h2 class='term-heading'>{$termSlug}</h2>"
		: ''
	;
}


/**
 * Create link to product_cat term archive.
 *
 * @since 1.0.0
 *
 * @param string $termSlug
 * @param string $tax
 */
function termArchive( string $termSlug, string $tax = 'product_cat' ) {
	$termLink = get_term_link( $termSlug, $tax );
	$termArchiveLabel = __( 'View Collection ', 'woo-customizations' );
	$icon = '<span class="fa fa-chevron-right"></span>';

	return $termLink
		? vsprintf( '<a class="term-link" href="%1$s">%2$s %3$s</a>', [ $termLink, $termArchiveLabel, $icon ] )
		: ''
	;
}


/**
 * Create unique id for flickity element.
 *
 * @since 1.0.0
 */
function uniqueId (): string {
	return uniqid( 'flickity-slider-', false );
}


/**
 * Render error is shortcode attribute isn't provided.
 *
 * @since 1.0.0
 */
function missingAttributeError () {
	errorMessage(
		'Missing shortcode attributes: category or id parameter required. See examples...',
		'[wc_product_cat_flickity_slider category="category-slug" heading="false"]'
	);
}


/**
 * Render button groups.
 *
 * @since 1.0.0
 */
add_action( 'woocommerce_after_shop_loop_item', function (): void {
	global $wp_query;

	$product = wc_get_product( $wp_query->post );
	$permalink = get_permalink( $product ); ?>
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
		<?php echo woocommerce_template_loop_add_to_cart() ?>
	</div>
	<?php
} );

function renderButtonGroups ( WC_Product $product ): void {
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
		<?php echo woocommerce_template_loop_add_to_cart() ?>
	</div>
	<?php
}



