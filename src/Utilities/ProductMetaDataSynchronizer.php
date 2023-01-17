<?php
/**
 * Handles synchronization between meta data of WC_Product.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\Utilities;

use WooCommerce\Grow\WMCPA\Traits\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Product meta data synchronizer class.
 */
class ProductMetaDataSynchronizer {

	use SingletonTrait;

	/**
	 * Meta keys sync data.
	 *
	 * @var array
	 */
	private $sync_data = array();

	/**
	 * Subscriptions status of the synchronizer to the add and update post meta hooks.
	 *
	 * @var bool
	 */
	private $is_subscribed = false;

	/**
	 * Register a meta key and its related keys that needs to be synced.
	 *
	 * @param string       $key     The meta key that relates to the product attribute.
	 * @param string|array $related The related keys that needs to be updated.
	 */
	public function register_to_sync( $key, $related ) {

		if ( ! isset( $this->sync_data[ $key ] ) ) {
			$this->sync_data[ $key ] = array();
		}

		if ( is_array( $related ) ) {
			$this->sync_data[ $key ] = array_unique( array_merge( $this->sync_data[ $key ], $related ) );
		} else {
			$this->sync_data[ $key ][] = $related;
		}

		if ( ! empty( $this->sync_data ) ) {
			$this->subscribe();
		}
	}

	/**
	 * Subscribe to the updated and added post meta events.
	 */
	private function subscribe() {
		if ( $this->is_subscribed ) {
			return;
		}

		add_action( 'updated_postmeta', array( $this, 'sync_update' ), 10, 4 );
		add_action( 'added_post_meta', array( $this, 'sync_update' ), 10, 4 );
		$this->is_subscribed = true;
	}

	/**
	 * Unsubscribe to the updated and added post meta events.
	 */
	private function unsubscribe() {
		if ( ! $this->is_subscribed ) {
			return;
		}

		remove_action( 'updated_postmeta', array( $this, 'sync_update' ), 10, 4 );
		remove_action( 'added_post_meta', array( $this, 'sync_update' ), 10, 4 );
		$this->is_subscribed = false;
	}

	/**
	 * Sync the update made to one of the keys across all related keys.
	 *
	 * @param int    $meta_id    ID of metadata entry to update.
	 * @param int    $object_id  Post ID.
	 * @param string $meta_key   Metadata key.
	 * @param mixed  $meta_value Metadata value.
	 */
	public function sync_update( $meta_id, $object_id, $meta_key, $meta_value ) {
		$post_type = get_post_type( $object_id );

		if ( 'product' !== $post_type ) {
			return;
		}

		foreach ( $this->sync_data as $key => $related_keys ) {
			$meta_keys_to_sync = array_merge( array( $key ), $related_keys );
			if ( in_array( $meta_key, $meta_keys_to_sync, true ) ) {
				$this->update_related_meta_keys( $object_id, $meta_keys_to_sync, $meta_value );
				break;
			}
		}
	}

	/**
	 * Update related product meta data.
	 *
	 * @param int   $product_id The ID of the product.
	 * @param array $keys       The meta data keys that needs to be updated.
	 * @param mixed $value      Meta data value.
	 */
	private function update_related_meta_keys( $product_id, $keys, $value ) {
		$this->unsubscribe(); // Unsubscribe from the added/updated post meta hooks to prevent an infinite loop.

		$product = wc_get_product( $product_id );
		foreach ( $keys as $key ) {
			$value = apply_filters( 'wmcpa_related_channel_meta_value', $value, $key );
			$product->update_meta_data( $key, $value );
		}
		$product->save_meta_data();

		$this->subscribe();
	}
}
