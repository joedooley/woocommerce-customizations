<?php
/**
 * The template for displaying the Isotope sort dropdown.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/search-isotope.php.
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

defined( 'ABSPATH' ) || exit;

?>

<div class="wc-isotope-sort-container">
	<label for="wc-isotope-sort">
		<select name="wc-isotope-sort-select" id="wc-isotope-sort">
			<option value="" data-sort-by="name"><?php echo __( 'Sort by name: A to Z', 'woocommerce' ); ?></option>
			<option value="nameDesc" data-sort-by="nameDesc"><?php echo __( 'Sort by name: Z to A', 'woocommerce' ); ?></option>
			<option value="price" data-sort-by="price"><?php echo __( 'Sort by price: low to high', 'woocommerce' ); ?></option>
			<option value="priceDesc" data-sort-by="priceDesc"><?php echo __( 'Sort by price: high to low', 'woocommerce' ); ?></option>
		</select>
	</label>
</div>
