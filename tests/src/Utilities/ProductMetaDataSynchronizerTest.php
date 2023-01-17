<?php

namespace WooCommerce\Grow\WMCPA\Tests\Utilities;

use \WP_UnitTestCase;
use WooCommerce\Grow\WMCPA\Tests\Helpers\ProductHelper;
use WooCommerce\Grow\WMCPA\Utilities\ProductMetaDataSynchronizer;
use WooCommerce\Grow\WMCPA\Integrations\FacebookForWooCommerce;

/**
 * Collection of tests to test the Product Meta Data Synchronizer.
 */
class ProductMetaDataSynchronizerTest extends WP_UnitTestCase {

	/**
	 * The Product meta data synchronizer instance.
	 *
	 * @var WooCommerce\Grow\WMCPA\Utilities\ProductMetaDataSynchronizer
	 */
	protected static $synchronizer;

	/**
	 * The product for which the channel attributes are generated.
	 *
	 * @var \WC_Product
	 */
	protected static $product;

	/**
	 * Load the Integration.
	 *
	 * @var WooCommerce\Grow\WMCPA\Integrations\AbstractIntegration;
	 */
	protected $integration;

	/**
	 * Setup the instance and product.
	 */
	public function setUp(): void {
		parent::setUp();

		$this->synchronizer = ProductMetaDataSynchronizer::instance();
		$this->product      = ProductHelper::create_simple_product();
		$this->integration  = new FacebookForWooCommerce();
	}

	/**
	 * Test synchronizer.
	 */
	public function test_sync() {
		$synchronizer = $this->synchronizer;
		$product      = $this->product;

		$synchronizer->register_to_sync( 'gtin', array( 'fbw_gtin', 'pin_gtin' ) );

		$product->update_meta_data( 'gtin', '123' );
		$product->save_meta_data();

		$this->display_meta( $product->get_id() );

		$product->update_meta_data( 'fbw_gtin', '456' );
		$product->save_meta_data();

		$this->display_meta( $product->get_id() );

		$product->update_meta_data( 'pin_gtin', '789' );
		$product->save_meta_data();

		$this->display_meta( $product->get_id() );

		$this->assertTrue( false, 'Sync product meta data failed.' );
	}

	/**
	 * Test meta value override.
	 */
	public function test_meta_value_override() {
		$synchronizer = $this->synchronizer;
		$product      = $this->product;

		$this->integration->integrate();

		$product->update_meta_data( 'google_product_category', '3237' );
		$product->save_meta_data();

		$updated_product = wc_get_product( $product->get_id() );

		echo "\n";
		echo 'P: ' . $updated_product->get_meta( 'google_product_category' ) . "\n";
		echo 'FB: ' . $updated_product->get_meta( 'wc_facebook_google_product_category_id' ) . "\n";
		echo 'PIN: ' . $updated_product->get_meta( '_wc_pinterest_google_product_category' ) . "\n";

		$product->update_meta_data( 'google_product_category', 'Animals & Pet Supplies > Live Animals' );
		$product->save_meta_data();

		$updated_product = wc_get_product( $product->get_id() );

		echo "\n";
		echo 'P: ' . $updated_product->get_meta( 'google_product_category' ) . "\n";
		echo 'FB: ' . $updated_product->get_meta( 'wc_facebook_google_product_category_id' ) . "\n";
		echo 'PIN: ' . $updated_product->get_meta( '_wc_pinterest_google_product_category' ) . "\n";

		$this->assertTrue( false, 'Meta value override failed.' );
	}

	private function display_meta( $product_id ) {
		$product = wc_get_product( $product_id );

		// $gtin_meta_data = get_post_meta( $product->get_id(), 'gtin', true );
		// $fbw_meta_data  = get_post_meta( $product->get_id(), 'fbw_gtin', true );
		// $pin_meta_data  = get_post_meta( $product->get_id(), 'pin_gtin', true );

		$gtin_meta_data = $product->get_meta( 'gtin' );
		$fbw_meta_data  = $product->get_meta( 'fbw_gtin' );
		$pin_meta_data  = $product->get_meta( 'pin_gtin' );

		echo "\n";
		echo 'GTIN: ' . $gtin_meta_data . "\n";
		echo 'FBW GTIN: ' . $fbw_meta_data . "\n";
		echo 'PIN GTIN: ' . $pin_meta_data . "\n";
	}
}
