<?php

namespace DevDesigns\WoocommerceCustomizations;

use WP_Query;



/**
 * Disable WooCommerce pagination and load all products.
 *
 * @since 1.0.0
 */
add_action( 'woocommerce_product_query', function ( WP_Query $q ): void {
	$q->set( 'posts_per_page', - 1 );
} );



remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
add_action( 'woocommerce_after_live_search', 'woocommerce_catalog_ordering' );
