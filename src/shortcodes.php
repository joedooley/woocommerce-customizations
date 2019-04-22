<?php

namespace DevDesigns\WoocommerceCustomizations;

use WP_Query;



add_shortcode( 'wc_product_cat_flickity_slider', function ( $atts ): string {
	$a = shortcode_atts(
		[ 'category' => '', 'heading' => 'true' ],
		$atts,
		'wc_product_cat_flickity_slider'
	);

	$id = uniqueId();
	$termSlug = $a['category'] ?? false;
	$heading = $a['heading'];
	$noHeadingClass = $heading !== 'true' ? ' no-heading' : '';

	if ( ! $termSlug ) {
		errorMessage(
			'Missing shortcode attributes: category or id parameter required. See examples...',
			'[wc_product_cat_flickity_slider category="category-slug" heading="false"]'
		);

		return '';
	}

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


		ob_start(); ?>

		<section class="product-category-slider-flickity woocommerce is-hidden">
			<?php if ( $heading === 'true' ) : ?>
				<?php echo renderHeading( $termSlug ) ?>
			<?php endif; ?>

			<div id="<?php echo $id ?>" class="products flickity-slider <?php echo $noHeadingClass ?>">
				<?php while ( $query->have_posts() ) : $query->the_post();
					$initialIndex = (int) round( $query->post_count / 2 );
					$cssClasses = $initialIndex === $query->current_post ? [ 'flickity-slide', 'flickity-initial-slide' ] : 'flickity-slide'; ?>

					<article <?php wc_product_class( $cssClasses, $query->post->ID ) ?>>
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
						do_action( 'woocommerce_after_shop_loop_item' ); ?>

						<p class="buttons-wrap">
							<a href="#" class="triangle arrow-right"></a>
						</p>
					</article>
				<?php endwhile; ?>
			</div>
		</section>

	<?php endif; ?>

	<?php

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
