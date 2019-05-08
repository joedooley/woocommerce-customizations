/**
 * Override event.preventDefault() within WP Fly Menu when
 * clicking on top level dropdown menu item with children.
 *
 * @since 1.0.0
 */

jQuery(document).ready(function ($) {
	$('a.wpflym-dropdown-toggle').off('click')
})
