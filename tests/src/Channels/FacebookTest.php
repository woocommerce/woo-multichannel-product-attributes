<?php

namespace WooCommerce\Grow\WMCPA\Tests\Channels;

use \WP_UnitTestCase;
use WooCommerce\Grow\WMCPA\Tests\Helpers\ProductHelper;

/**
 * A collection of tests for the Facebook channel class.
 */
class FacebookTest extends WP_UnitTestCase {

	/**
	 * The Facebook Channel instance.
	 *
	 * @var WooCommerce\Grow\WMCPA\Channels\Facebook
	 */
	protected static $channel;

	/**
	 * The product for which the channel attributes are generated.
	 *
	 * @var \WC_Product
	 */
	protected static $product;

	/**
	 * Setup our Facebook channel instance.
	 */
	public function setUp(): void {
		parent::setUp();

		$this->channel = \woo_mcpa()->channels->get_channel( 'facebook' );
		$this->product = ProductHelper::create_simple_product();
	}

	public function test_get_id() {
		$channel_id = $this->channel->get_id();
		$this->assertEquals( 'facebook', $channel_id, 'Channel ID does not match' );
	}

	public function test_get_product_price() {
		$channel_price = $this->channel->get_product_price( $this->product );
		$this->assertEquals( '10.00 USD', $channel_price, 'Channel Price is incorrect' );
	}

	public function test_get_product_attributes() {
		$attributes = $this->channel->get_product_attributes( $this->product );
		print_r( $attributes );
	}
}
