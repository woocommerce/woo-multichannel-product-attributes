<?php
/**
 * Manages the Facebook channel.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Channels;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Facebook channel class.
 */
class Facebook extends AbstractChannel {

	/**
	 * The Channel ID.
	 *
	 * @var string
	 */
	protected $id = 'facebook';

	/**
	 * The Channel name.
	 *
	 * @var string
	 */
	protected $name = 'Facebook';

	/**
	 * Initialize tab settings.
	 */
	protected function init_tab_settings() {
		$prefix             = $this->get_meta_prefix();
		$this->tab_settings = array(
			'id'                => $prefix . $this->get_id(),
			'args'              => array(
				'label'  => $this->name,
				'target' => $prefix . '_product_data',
			),
			'panel_html_search' => '<div id="marketplace_suggestions" class="panel woocommerce_options_panel hidden">',
		);
	}

	/**
	 * Initialize the attributes used in this channel.
	 */
	protected function init_attributes() {
		$meta_prefix      = $this->get_meta_prefix();
		$this->attributes = array(
			'id'                      => array(
				'description' => esc_html__( 'A unique content ID for the item. Use the item\'s SKU if possible.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'max:100' ),
			),
			'title'                   => array(
				'description' => esc_html__( 'A specific, relevant title for the item.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'max:200' ),
			),
			'description'             => array(
				'description' => esc_html__( 'A relevant description of the item. Include specific and unique product features, such as material or color.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'max:9999' ),
			),
			'availability'            => array(
				'description' => esc_html__( 'Availability of the item.', 'woo-mcpa' ),
				'type'        => 'string',
				'enum'        => array( 'in stock', 'out of stock' ),
				'validations' => array( 'required' ),
			),
			'condition'               => array(
				'description' => esc_html__( 'The condition of the item.', 'woo-mcpa' ),
				'type'        => 'string',
				'enum'        => array( 'new', 'refurbished', 'used' ),
				'validations' => array( 'required' ),
				'meta_field'  => array(
					'location' => array( 'product_setting_tab' ),
					'args'     => array(
						'label'         => esc_html__( 'Condition', 'woo-mcpa' ),
						'type'          => 'select',
						'options'       => array(
							'new'         => esc_html__( 'New', 'woo-mcpa' ),
							'refurbished' => esc_html__( 'Refurbished', 'woo-mcpa' ),
							'used'        => esc_html__( 'Used', 'woo-mcpa' ),
						),
						'default_value' => 'new',
					),
				),
			),
			'price'                   => array(
				'description' => esc_html__( 'The price of the item.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'regex:/^/i' ), // TODO: Add the correct regular expression to validate price.
			),
			'link'                    => array(
				'description' => esc_html__( 'The URL to the specific product page for the item on the business\'s website where people can learn more about or buy that exact item.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'url' ),
			),
			'image_link'              => array(
				'description' => esc_html__( 'The URL for the main image of the item.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'url', 'dimensions:min_width=500,min_height=500', 'mimes:jpg,png' ),
			),
			'brand'                   => array(
				'description' => esc_html__( 'The brand name, unique manufacturer part number (MPN) or Global Trade Item Number (GTIN) of the item.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'required', 'max:100' ),
			),
			'google_product_category' => array(
				'description' => esc_html__( 'The most specific and relevant Google product category.', 'woo-mcpa' ),
				'type'        => 'string',
				'validations' => array( 'min_depth:2' ),
			),
		);
	}

	/*
	|--------------------------------------------------------------------------
	| Attribute Getters
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the id attribute of the product.
	 *
	 * @param int|WC_Product|object $product_id Product to get the attributes for.
	 * @return string
	 */
	public function get_product_id( $product_id ) {
		$product = wc_get_product( $product_id );
		return $product->get_id();
	}

	/**
	 * Get the price attribute of the product.
	 *
	 * @param int|WC_Product|object $product_id Product to get the attributes for.
	 * @return string
	 */
	public function get_product_price( $product_id ) {
		$product = wc_get_product( $product_id );
		return $this->get_formatted_price( $product->get_regular_price() );
	}

	/**
	 * Get the google_product_category attribute of the product.
	 *
	 * @param int|WC_Product|object $product_id Product to get the attributes for.
	 * @return string
	 */
	public function get_product_google_product_category( $product_id ) {
		$product = wc_get_product( $product_id );
		return $product->get_meta( $this->get_meta_suffix() . 'google_product_category' );
	}
}
