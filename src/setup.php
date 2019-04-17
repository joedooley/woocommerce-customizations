<?php

namespace DevDesigns\WoocommerceCustomizations;




add_action( 'woocommerce_loaded', function (): void {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	includeNonClassFiles();
}, 99999 );
