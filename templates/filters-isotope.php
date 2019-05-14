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
$parentTerms = $liveSearch->getProductCatParentTerms();
$childTerms = $liveSearch->getProductCatChildTerms();
$tags = $liveSearch->getProductTagTerms();
$current = get_queried_object();
$currentTerm = $current->name;

?>
<div class="wc-isotope-filters is-hidden">
	<?php if ( $parentTerms ): ?>
		<div class="ui-group button-group">
			<div class="product-cat-terms" data-filter-group="product_cat">
				<h6>All Products</h6>
				<?php foreach ( $parentTerms as $parentTerm ): ?>
					<?php
						$productCatTermSlug = sprintf( '.product_cat-%s', $parentTerm->slug );
						$liveSearch->setProductCatTermSlug( $productCatTermSlug );
						$liveSearch->setProductCatParentTermId( $parentTerm->term_id );
						$class = $currentTerm === $parentTerm->name ? 'is-checked' : '';
						$termLink = get_term_link( $parentTerm, 'product_cat' );

					?>
					<a href="<?php echo $termLink ?>>" class="filter-link <?php echo $class ?>"><?php echo $parentTerm->name; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( count( $childTerms ) > 0 ): ?>
		<div class="ui-group button-group">
			<div class="filter-link-group product-cat-terms" data-filter-group="product_cat">
				<h6>Subcategories</h6>
				<?php foreach ( $childTerms as $childTerm ): ?>
					<?php
						$slug = sprintf( '.product_cat-%s', $childTerm->slug );
						$liveSearch->setProductCatTermSlug( $slug );
					?>
					<a href="#" class="filter-link" data-filter="<?php echo $liveSearch->getProductCatTermSlug(); ?>"><?php echo $childTerm->name; ?></a>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( $tags ): ?>
		<div class="ui-group button-group">
			<div class="filter-link-group product-tag-terms" data-filter-group="product_tag">
				<h6>Tags</h6>
				<?php foreach ( $tags as $tag ): ?>
					<?php if ( $tag->count !== 0 ): ?>
						<?php
						$productTagTermSlug = sprintf( '.product_tag-%s', $tag->slug );
						$liveSearch->setProductTagTermSlug( $productTagTermSlug );
						?>
						<a href="#" class="filter-link" data-filter="<?php echo $liveSearch->getProductTagTermSlug(); ?>"><?php echo $tag->name; ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
</div>
