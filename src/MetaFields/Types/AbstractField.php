<?php
/**
 * The Abstract meta field.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\MetaFields\Types;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The abstract meta field class.
 */
abstract class AbstractField {

	/**
	 * The name of the field.
	 *
	 * @var string
	 */
	public $name = '';

	/**
	 * The default values for the field
	 *
	 * @var array
	 */
	public $defaults = array();
}
