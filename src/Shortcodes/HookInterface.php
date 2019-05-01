<?php
/**
 * Interface for WP actions and filters.
 *
 * @package     DevDesigns\WoocommerceCustomizations
 * @author      Developing Designs
 * @since       1.0.0
 */

namespace DevDesigns\WoocommerceCustomizations\src\Shortcodes;



interface HookInterface {

	/**
	 * Add actions and filters.
	 *
	 * @since  1.0.0
	 */
	public function addHooks(): void;
}
