<?php

namespace DevDesigns\WoocommerceCustomizations;

use DevDesigns\WoocommerceCustomizations\src\Shortcodes\ProductCategorySlider;



/**
 * Create `wc_product_cat_flickity_slider` shortcode.
 *
 * Example usage: `[wc_product_cat_flickity_slider category="neu" heading="Optional Custom Heading"]`.
 *
 * @since 1.0.0
 */
add_shortcode( 'wc_product_cat_flickity_slider', function ( $atts ): string {
	$slider = new ProductCategorySlider( $atts );
	$slider->addHooks();

	return $slider->render();
} );
