<?php

namespace DevDesigns\WoocommerceCustomizations;

use DevDesigns\WoocommerceCustomizations\src\Shortcodes\ProductCategorySlider;



add_shortcode( 'wc_product_cat_flickity_slider', function ( $atts ): string {
	$slider = new ProductCategorySlider( $atts );
	$slider->addHooks();

	return $slider->render();
} );


