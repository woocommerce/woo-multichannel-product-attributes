<?php
/**
 * General Multichannel Plugin Integration class.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Integrations;

use WooCommerce\Grow\WMCPA\Utilities\ProductMetaDataSynchronizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The abstract Integration class.
 */
abstract class AbstractIntegration {

	/**
	 * Class name to check if the plugin is active.
	 *
	 * @var string
	 */
	public $plugin_class = '';

	/**
	 * Function name to check if the plugin is active.
	 *
	 * @var string
	 */
	public $plugin_function = '';

	/**
	 * A map of meta keys that needs to be synced.
	 *
	 * @var array
	 */
	protected $meta_key_map = array();

	/**
	 * Specify if the plugin integration adds a tab or not.
	 *
	 * @var bool
	 */
	protected $has_tab = false;

	/**
	 * Load the integration methods here.
	 */
	public function integrate() {
		if ( ! empty( $this->meta_key_map ) ) {
			foreach ( $this->meta_key_map as $key => $value ) {
				ProductMetaDataSynchronizer::instance()->register_to_sync( $key, $value );
			}
		}

		if ( $this->has_tab ) {
			add_filter( 'woo_mcpa_channel_get_tab_settings', array( $this, 'override_tab_settings' ) );
		}

		add_filter( 'wmcpa_related_channel_meta_value', array( $this, 'override_meta_value' ), 10, 2 );
	}


	/**
	 * Initialize the integration.
	 */
	public function __construct() {
		if ( $this->is_plugin_activated() ) {
			$this->integrate();
		}
	}

	/**
	 * Checks if a plugin is active.
	 *
	 * @return bool
	 */
	public function is_plugin_activated() {
		$is_activated = false;

		if ( ! empty( $this->plugin_class ) && ! empty( $this->plugin_function ) ) {
			$is_activated = class_exists( $this->plugin_class ) && function_exists( $this->plugin_function );
		} elseif ( ! empty( $this->plugin_class ) ) {
			$is_activated = class_exists( $this->plugin_class );
		} elseif ( ! empty( $this->plugin_function ) ) {
			$is_activated = function_exists( $this->plugin_function );
		}

		return $is_activated;
	}

	/**
	 * Override meta value.
	 *
	 * @param mixed  $value The meta value to be overriden.
	 * @param string $key   The meta key.
	 * @return mixed
	 */
	public function override_meta_value( $value, $key ) {
		if ( isset( $this->keys_for_meta_value_overrides ) && in_array( $key, $this->keys_for_meta_value_overrides, true ) ) {
			if ( is_callable( array( $this, 'get_overridden_meta_value' ) ) ) {
				$value = $this->get_overridden_meta_value( $value, $key );
			}
		}

		return $value;
	}
}
