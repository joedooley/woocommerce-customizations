<?php

namespace DevDesigns\WoocommerceCustomizations\Shortcodes;

use BeRocket_Product_Preview;



class ProductPreview extends BeRocket_Product_Preview {
	public static function get_preview_button( $modify = '' ) {
		wp_enqueue_script( 'wc-single-product' );
		global $product, $wp_query, $br_preview_rand, $br_preview_id;
		$product_id = br_wc_get_product_id( $product );

		if ( $br_preview_id != $product_id ) {
			$br_preview_rand = rand();
		}

		$br_preview_id = $product_id;

		$modify .= $br_preview_rand;
		$dataId = $product_id . $modify;

		$text_options = BeRocket_Product_Preview::get_product_preview_option( 'br_product_preview_text_settings' );

		$return = sprintf(
			'<a data-id="%d" class="br_product_preview_button button" href="#preview">%s</a>',
			$dataId,
			__( 'Preview Product', 'woocommerce' )
		);

		do_action( 'berocket_pp_after_preview_button' );

		return $return;
	}
}

