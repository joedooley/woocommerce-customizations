<?php

namespace DevDesigns\WoocommerceCustomizations;

use WP_Query;



add_shortcode( 'wc_product_cat_swiper_slider', function ( $atts ): string {
	$a = shortcode_atts(
		[
			'category' => '',
		],
		$atts,
		'wc_product_cat_slider'
	);

	if ( ! isset( $a['category'] ) ) {
		errorMessage(
			'Missing shortcode attributes: category or id parameter required. See examples...',
			'[wc_product_cat_slider category="category-slug"]'
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
				'terms'    => $a['category'],
				'operator' => 'IN'
			],
		],
	];

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :
		wp_enqueue_style( 'woocommerce-customizations/swiper.css' );
		wp_enqueue_script( 'woocommerce-customizations/swiper.js' );

		ob_start(); ?>

		<div class="product-category-slider">
			<aside class="swiper-navigation-container">
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</aside>
			<div class="product-slider-container swiper-container woocommerce">
				<ul class="swiper-wrapper products">
					<?php while ( $query->have_posts() ) : $query->the_post();
						// $product = wc_get_product( $query->post->ID );
						// $post_thumbnail_id = get_post_thumbnail_id();
						// $product_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, $size = 'shop-feature' );
						// $product_thumbnail_alt = get_post_meta( $post_thumbnail_id, '_wp_attachment_image_alt', true ); ?>

						<li
							<?php wc_product_class( 'swiper-slide', $query->post->ID ) ?>>
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
						</li>
					<?php endwhile; ?>
				</ul>

				<div class="swiper-pagination"></div>
				<div class="swiper-scrollbar"></div>

			</div>
		</div>

	<?php endif; ?>

	<?php wp_reset_query();

	return ob_get_clean();
} );




add_shortcode( 'wc_product_cat_flickity_slider', function ( $atts ): string {
	$a = shortcode_atts(
		[ 'category' => '' ],
		$atts,
		'wc_product_cat_slider'
	);

	if ( ! isset( $a['category'] ) ) {
		errorMessage(
			'Missing shortcode attributes: category or id parameter required. See examples...',
			'[wc_product_cat_flickity_slider category="category-slug"]'
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
				'terms'    => $a['category'],
				'operator' => 'IN'
			],
		],
	];

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) :
		wp_enqueue_style( 'woocommerce-customizations/flickity.css' );
		wp_enqueue_script( 'woocommerce-customizations/flickity.js' );

		ob_start(); ?>

		<section class="product-category-slider-flickity woocommerce">
			<div class="flickity-slider products">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<article <?php wc_product_class( 'flickity-slide', $query->post->ID ) ?>>
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

	<?php wp_reset_query();

	return ob_get_clean();
} );
