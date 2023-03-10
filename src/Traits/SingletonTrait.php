<?php
/**
 * Singleton class trait.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Traits;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Singleton trait.
 */
trait SingletonTrait {
	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function __construct() {}

	/**
	 * Get class instance.
	 *
	 * @return object Instance.
	 */
	final public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Prevent cloning.
	 */
	private function __clone() {}

	/**
	 * Prevent unserializing.
	 */
	private function __wakeup() {}
}
