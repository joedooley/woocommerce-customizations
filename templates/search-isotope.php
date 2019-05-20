<?php
/**
 * The template for displaying the Isotope quick search bar on product archives.
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
<div class="wc-isotope-search">
	<a href="#" id="filter-toggle" class="toggle-filters">
		<i id="menu-icon" class="fa fa-bars"></i>
		<i id="arrow-icon" class="fa fa-caret-down"></i>
	</a>
	<label for="wc-isotope-search">
		<input id="wc-isotope-search" class="search-input quicksearch" placeholder="Live Search ..." type="search" />
		<span class="clear" title="Click to clear input"></span>
	</label>
</div>
