<?php
/**
 * Manages integration between various individual multichannel marketing plugins.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA;

use WooCommerce\Grow\WMCPA\Traits\SingletonTrait;
use WooCommerce\Grow\WMCPA\Integrations\FacebookForWooCommerce;
use WooCommerce\Grow\WMCPA\Integrations\PinterestForWooCommerce;
use WooCommerce\Grow\WMCPA\Integrations\TikTokForWooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Integrations class.
 */
class Integrations {

	use SingletonTrait;

	/**
	 * Initialize integrations.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Load integrations.
	 */
	public function init() {
		$integrations = array(
			FacebookForWooCommerce::class,
		);
		foreach ( $integrations as $integration ) {
			new $integration();
		}
	}
}
