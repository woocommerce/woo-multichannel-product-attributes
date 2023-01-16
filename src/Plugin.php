<?php
/**
 * The Plugin manager.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA;

use WooCommerce\Grow\WMCPA\Channels;
use WooCommerce\Grow\WMCPA\Integrations;
use WooCommerce\Grow\WMCPA\Utilities\ProductMetaDataSynchronizer;
use WooCommerce\Grow\WMCPA\MetaFields\ProductMetaFields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Plugin class.
 */
class Plugin {

	/**
	 * The plugin instance.
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning instances of the class is forbidden.', 'woo-mcpa' ), '0.0.1' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances of this class is forbidden.', 'woo-mcpa' ), '0.0.1' );
	}

	/**
	 * Auto-load in-accessible properties on demand.
	 *
	 * @param mixed $key Key name.
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'channels', 'integrations', 'product_meta_data_synchronizer' ), true ) ) {
			return $this->$key();
		}
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		$this->init();
	}

	/**
	 * Initialize the plugin.
	 */
	private function init() {
		$this->channels();
		$this->integrations();
		$this->product_meta_data_synchronizer();
		ProductMetaFields::init();
	}

	/**
	 * The Plugin instance.
	 *
	 * @return WooCommerce\Grow\WMCPA\Plugin
	 */
	public static function woo_mcpa() {
		return self::$instance;
	}

	/**
	 * The Plugin instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get Channels instance.
	 *
	 * @return WooCommerce\Grow\WMCPA\Channels
	 */
	public function channels() {
		return Channels::instance();
	}

	/**
	 * Get Integrations instance.
	 *
	 * @return WooCommerce\Grow\WMCPA\Integrations
	 */
	public function integrations() {
		return Integrations::instance();
	}

	/**
	 * Get product meta data synchronizer instance.
	 *
	 * @return WooCommerce\Grow\WMCPA\Utilities\ProductMetaDataSynchronizer
	 */
	public function product_meta_data_synchronizer() {
		return ProductMetaDataSynchronizer::instance();
	}
}
