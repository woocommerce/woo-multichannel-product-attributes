<?php
/**
 * Manages rendering meta fields.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\MetaFields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class MetaFields {

	/**
	 * Renders the meta fields.
	 *
	 * @param array $fields Array of fields to render.
	 */
	public static function render( $fields ) {
		echo 'Hello World';
	}
}
