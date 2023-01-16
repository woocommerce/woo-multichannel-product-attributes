<?php
/**
 * General Helper functions to be used across the codebase.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Utilities;

use WooCommerce\Grow\WMCPA\MetaFields\Types\Select;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Helper utility class.
 */
class Helper {

	/**
	 * This method will force a variable to become an array.
	 *
	 * @param  mixed  $var       The variable to be converted to array.
	 * @param  string $delimiter Delimiter for a string to explode.
	 * @return array
	 */
	public static function get_array( $var = false, $delimiter = '' ) {
		// Array.
		if ( is_array( $var ) ) {
			return $var;
		}

		// Bail early if empty.
		if ( self::is_empty( $var ) ) {
			return array();
		}

		// A string.
		if ( is_string( $var ) && $delimiter ) {
			return explode( $delimiter, $var );
		}

		// Place in array.
		return (array) $var;
	}

	/**
	 * Returns true if the value provided is considered "empty". Allows numbers such as 0.
	 *
	 * @param  mixed $var The value to check.
	 * @return bool
	 */
	public static function is_empty( $var ) {
		return ( ! $var && ! is_numeric( $var ) );
	}
}
