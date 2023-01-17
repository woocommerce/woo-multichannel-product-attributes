<?php
/**
 * Manages integration with Facebook for WooCommerce plugin.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Integrations;

use WooCommerce\Grow\WMCPA\Integrations\AbstractIntegration;
use WooCommerce\Grow\WMCPA\Utilities\GoogleProductCategories;

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
		'condition'               => array( 'woo-mcpa_facebook_condition', '_wc_pinterest_condition' ), // TODO: must be added through Pinterest for WooCommerce integration class.
		'google_product_category' => array( 'wc_facebook_google_product_category_id', '_wc_pinterest_google_product_category' ),
	);

	/**
	 * Keys to be overridden during meta value sync.
	 *
	 * @var array
	 */
	protected $keys_for_meta_value_overrides = array( 'wc_facebook_google_product_category_id', '_wc_pinterest_google_product_category' );

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

	/**
	 * Override meta value for a given key.
	 *
	 * @param mixed  $value The meta value.
	 * @param string $key   The meta key.
	 * @return string
	 */
	public function get_overridden_meta_value( $value, $key ) {
		$options = GoogleProductCategories::get_options();
		if ( 'wc_facebook_google_product_category_id' === $key ) {
			$category_id = array_search( $value, $options, true );
			if ( false !== $category_id ) {
				$value = $category_id;
			}
		}

		if ( '_wc_pinterest_google_product_category' === $key ) {
			
			if ( isset( $options[ $value ] ) ) {
				$value = $options[ $value ];
			}
		}

		return $value;
	}
}
