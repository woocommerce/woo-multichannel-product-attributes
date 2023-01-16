<?php
/**
 * Plugin Name: Woo Multi-Channel Product Attributes
 * Plugin URI: https://github.com/ibndawood/woo-multichannel-product-attributes
 * Description: Manage Product Attributes to be used across multi-channels.
 * Version: 0.0.1
 * Author: Ventures at Automattic
 * Author URI: https://woocommerce.com/
 * Text Domain: woo-mcpa
 * Requires at least: 5.9
 * Requires PHP: 7.2
 *
 * @package woo-mcpa
 */

// Load the autoloader.
require __DIR__ . '/src/Autoloader.php';

if ( ! WooCommerce\Grow\WMCPA\Autoloader::init() ) {
	return;
}

/**
 * Return the main instance of the plugin.
 *
 * @return WooCommerce\Grow\WMCPA\Plugin
 */
function woo_mcpa() {
	return WooCommerce\Grow\WMCPA\Plugin::instance();
}

woo_mcpa();
