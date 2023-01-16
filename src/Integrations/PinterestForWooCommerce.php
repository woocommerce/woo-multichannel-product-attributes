<?php
/**
 * Manages integration with Pinterest for WooCommerce plugin.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Integrations;

use WooCommerce\Grow\WMCPA\Integrations\AbstractIntegration;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Pinterest for WooCommerce integration with Woo Multi-Channel Product Attributes class.
 */
class PinterestForWooCommerce extends AbstractIntegration {

	/**
	 * Class to check if the plugin is active.
	 *
	 * @var string
	 */
	public $plugin_class = 'Pinterest_For_Woocommerce';
}
