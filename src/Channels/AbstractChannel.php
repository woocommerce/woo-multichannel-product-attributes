<?php
/**
 * Abstract Channel.
 *
 * Handles generic channel functionality and are extended by individual channels.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Channels;

use WooCommerce\Grow\WMCPA\MetaFields\ProductMetaFields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * The Abstract Channel class.
 */
abstract class AbstractChannel {

	/**
	 * The Channel ID.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * The Channel name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The price format arguments.
	 *
	 * @var array
	 */
	protected $price_args = array(
		'currency_pos' => 'right_space',
		'thousand_sep' => ',',
		'decimal_sep'  => '.',
		'decimals'     => 2,
	);

	/**
	 * The product attributes.
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * Details about the product settings tab added by the plugin.
	 *
	 * @var array
	 */
	protected $tab_settings = array();

	/**
	 * Define the product attributes used in this channel.
	 */
	abstract protected function init_attributes();

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		$this->init_tab_settings();
		$this->init_attributes();
		$this->render_meta_fields();
	}

	/**
	 * Get the ID of the channel.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the ID of the channel.
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get meta prefix.
	 */
	protected function get_meta_prefix() {
		return 'woo-mcpa_' . $this->get_id() . '_';
	}

	/**
	 * Get meta fields.
	 *
	 * @param string $filter Filter meta fields by location.
	 * @return array
	 */
	protected function get_meta_fields( $filter = '' ) {
		$attributes   = $this->attributes;
		$meta_fields  = array();
		$field_prefix = $this->get_meta_prefix();
		foreach ( $attributes as $key => $attribute ) {
			if ( isset( $attribute['meta_field'] ) ) {
				// If a location filter is specified, check if the meta field is assigned to a given location.
				$meta_field_location = isset( $attribute['meta_field']['location'] ) ? $attribute['meta_field']['location'] : array();
				
				if ( ! empty( $filter ) && ! in_array( $filter, $meta_field_location, true) ) {
					continue;
				}

				$attribute['meta_field']['args']['id']   = $field_prefix . $key;
				$attribute['meta_field']['args']['name'] = 'woo_mcpa_meta[' . $field_prefix . $key . ']';
				$meta_fields[ $field_prefix . $key ]            = $attribute['meta_field'];
			}
		}

		return $meta_fields;
	}

	/**
	 * Call the method to get a product's attribute dynamically.
	 *
	 * @param string                $key The attribute key set by the channel.
	 * @param int|WC_Product|object $product_id Product to get the attributes for.
	 * @return mixed
	 * @throws \Exception When a method to get a given attribute is not implemented.
	 */
	public function get_product_attribute( $key, $product_id ) {
		$method_name = 'get_product_' . $key;
		if ( is_callable( array( $this, $method_name ) ) ) {
			return $this->$method_name( $product_id );
		} else {
			/* translators: %s The attribute key for which the get attribute method is not implemented */
			throw new \Exception( sprintf( esc_html__( 'Get attribute method not implemented for %s.', 'woo-mcpa' ), $key ) );
		}
	}

	/**
	 * Get the product attributes
	 *
	 * @param int|WC_Product|object $product_id Product to get the attributes for.
	 * @return array
	 */
	public function get_product_attributes( $product_id ) {
		$product    = wc_get_product( $product_id );
		$attributes = array();

		if ( $product ) {
			foreach ( $this->attributes as $key => $attribute ) {
				try {
					$attributes[ $key ] = $this->get_product_attribute( $key, $product );
				} catch ( \Exception $e ) {
					continue;
				}
			}
		}

		return $attributes;
	}

	/**
	 * Get the price format depending on the currency position.
	 *
	 * @param string $currency_pos The currency position.
	 * @return string
	 */
	protected function get_price_format( $currency_pos ) {
		$format = '%1$s%2$s';

		switch ( $currency_pos ) {
			case 'left':
				$format = '%1$s%2$s';
				break;
			case 'right':
				$format = '%2$s%1$s';
				break;
			case 'left_space':
				$format = '%1$s %2$s';
				break;
			case 'right_space':
				$format = '%2$s %1$s';
				break;
		}

		return $format;
	}

	/**
	 * Get formatted price.
	 *
	 * @param float $price Raw price.
	 * @return string
	 */
	public function get_formatted_price( $price ) {
		$args          = $this->price_args;
		$orignal_price = $price;
		$currency      = get_woocommerce_currency();

		// Convert to float to avoid issues on PHP 8.
		$price = (float) $price;

		$unformatted_price = $price;
		$negative          = $price < 0;

		$price = $negative ? $price * -1 : $price;
		$price = number_format( $price, $args['decimals'], $args['decimal_sep'], $args['thousand_sep'] );

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $this->get_price_format( $args['currency_pos'] ), $currency, $price );

		return $formatted_price;
	}

	/**
	 * Display meta fields.
	 */
	public function render_meta_fields() {
		$this->render_product_settings_tab_meta_fields();
	}

	/**
	 * Render meta fields in product settings tab.
	 */
	protected function render_product_settings_tab_meta_fields() {
		$meta_fields  = $this->get_meta_fields( 'product_settings_tab' );

		if ( empty( $meta_fields ) ) {
			return;
		}
		
		add_action( 'woocommerce_product_data_tabs', array( $this, 'product_data_tabs' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_panels_start' ), 0 );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_panels_end' ), 999 );
	}

	/**
	 * Buffer the product data panels output.
	 */
	public function product_data_panels_start() {
		\ob_start();
	}

	/**
	 * Output from the buffer.
	 */
	public function product_data_panels_end() {
		$panel_data = \ob_get_clean();
		$this->product_data_panels( $panel_data );
	}

	/**
	 * Set array of tabs to show.
	 *
	 * @param  array $tabs The product tabs.
	 * @return array
	 */
	public function product_data_tabs( $tabs ) {
		$tab_settings = $this->get_tab_settings();
		if ( ! empty( $tab_settings ) && ! isset( $tabs[ $tab_settings['id'] ] ) ) {
			$tabs[ $tab_settings['id'] ] = $tab_settings['args'];
		}

		return $tabs;
	}

	/**
	 * Render the meta fields in the product data panel.
	 *
	 * @param string $panel_data The panel data.
	 */
	public function product_data_panels( $panel_data ) {

		$tab_settings = $this->get_tab_settings();
		$meta_fields  = $this->get_meta_fields( 'product_settings_tab' );

		if ( empty( $tab_settings ) ) {
			return;
		}

		ob_start();
		ProductMetaFields::render( $meta_fields );
		$channel_data_panel = ob_get_clean();
		$panel_html_search  = isset( $tab_settings['panel_html_search'] ) ? $tab_settings['panel_html_search'] : '';
		$panel_html_replace = '<div id="' . esc_attr( $tab_settings['args']['target'] ) . '" class="panel woocommerce_options_panel hidden">' . $channel_data_panel;

		if ( ! empty( $panel_html_search ) && false !== strpos( $panel_data, $panel_html_search ) ) {
			$panel_data = str_replace( $panel_html_search, $panel_html_replace, $panel_data );
		} else {
			$panel_data = $panel_data . $panel_html_replace . '</div>';
		}

		echo $panel_data; // phpcs:ignore
	}

	/**
	 * Get tab settings for this channel.
	 *
	 * @return array
	 */
	protected function get_tab_settings() {
		/**
		 * Filters the tab channel tab settings.
		 *
		 * @since x.x.x
		 * @param array  $tab_settings The tab settings.
		 * @param string $id           The channel id.
		 */
		return apply_filters( 'woo_mcpa_channel_get_tab_settings', $this->tab_settings, $this->get_id() );
	}

	/**
	 * Validate the attributes.
	 *
	 * @param int|WC_Product|object $product_id Product to run the validations for.
	 * @return array
	 */
	public function get_attribute_validations( $product_id ) {
		$product    = wc_get_product( $product_id );
		$attributes = array();

		if ( $product ) {
			foreach ( $this->attributes as $key => $attribute ) {
				try {
					$attributes[ $key ] = true; // TODO: Run validation.
				} catch ( \Exception $e ) {
					continue;
				}
			}
		}

		return $attributes;
	}
}
