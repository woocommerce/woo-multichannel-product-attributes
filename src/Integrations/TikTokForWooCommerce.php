<?php
/**
 * Manages integration with TikTok for WooCommerce plugin.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Integrations;

use WooCommerce\Grow\WMCPA\Integrations\AbstractIntegration;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * TikTok for WooCommerce integration with Woo Multi-Channel Product Attributes class.
 */
class TikTokForWooCommerce extends AbstractIntegration {

	/**
	 * Class to check if the plugin is active.
	 *
	 * @var string
	 */
	public $plugin_class = 'Tiktokforbusiness';
}
