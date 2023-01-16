<?php
/**
 * Manages integration with Facebook for WooCommerce plugin.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Integrations;

use WooCommerce\Grow\WMCPA\Integrations\AbstractIntegration;

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
	 * Specify if the plugin adds a tab.
	 *
	 * @var bool
	 */
	protected $has_tab = true;

	/**
	 * Meta key maps to keep in sync.
	 *
	 * @var array
	 */
	protected $meta_key_map = array(
		'condition' => array( 'woo-mcpa_facebook_condition', '_wc_pinterest_condition' ), // TODO: must be added through Pinterest for WooCommerce integration class.
	);

	/**
	 * Override tab settings.
	 *
	 * @param array $settings The tab settings to be overridden.
	 * @return array
	 */
	public function override_tab_settings( $settings ) {
		$new_settings = array(
			'id'                => 'fb_commerce_tab',
			'args'              => array(
				'label'  => 'Facebook',
				'target' => 'facebook_options',
			),
			'panel_html_search' => '<div id=\'facebook_options\' class=\'panel woocommerce_options_panel\'>',
		);
		return $new_settings;
	}
}
