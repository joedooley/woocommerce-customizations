<?php
/**
 * The template for displaying the Isotope product category term filters on product archives.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/filters-isotope.php.
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

use DevDesigns\WoocommerceCustomizations\Shortcodes\LiveSearch;


defined( 'ABSPATH' ) || exit;

$liveSearch = new LiveSearch();
$terms = $liveSearch->getProductCatTerms();

?>
<div class="wc-isotope-filters">
	<?php if ( $terms ): ?>
		<div class="ui-group button-group product-cat-terms">
			<div class="button-group js-radio-button-group" data-filter-group="product_cat">
				<button class="button is-checked" data-filter="*">All Products</button>
				<?php foreach ( $terms as $term ): ?>
					<?php if ( $term->count !== 0 ): ?>
						<?php
							$termSlug = sprintf( '.product_cat-%s', $term->slug );
							$liveSearch->setTermSlug( $termSlug );
						?>
						<button class="button" data-filter="<?php echo $liveSearch->getTermSlug(); ?>"><?php echo $term->name; ?></button>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
