<?php
/**
 * Manages integration with Facebook for WooCommerce plugin.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Integrations;

use \WooCommerce\Grow\WMCPA\Integrations\AbstractIntegration;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Facebook for WooCommerce integration with Woo Multi-Channel Product Attributes class.
 */
class FacebookForWooCommerce extends AbstractIntegration {

	/**
	 * Class to check if the plugin is active.
	 *
	 * @var string
	 */
	public $plugin_class = 'WC_Facebook_Loader';

	/**
	 * Details about the product settings tab added by the plugin.
	 *
	 * @var array
	 */
	protected $tab_settings = array(
		'id'     => 'fb_commerce_tab',
		'target' => 'facebook_options',
	);

	/**
	 * Meta key maps to keep in sync.
	 *
	 * @var array
	 */
	protected $meta_key_map = array(
		'google_product_category' => '_wc_facebook_google_product_category',
		'gtin'                    => 'fbw_gtin',
	);
}
