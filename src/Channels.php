<?php
/**
 * Loads and manages multi-channels.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA;

use WooCommerce\Grow\WMCPA\Traits\SingletonTrait;
use WooCommerce\Grow\WMCPA\Channels\AbstractChannel;
use WooCommerce\Grow\WMCPA\Channels\Facebook;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Channels class.
 */
class Channels {

	use SingletonTrait;

	/**
	 * Channel classes.
	 *
	 * @var array
	 */
	public $channels = array();

	/**
	 * Initialize channels.
	 */
	protected function __construct() {
		$this->init();
	}

	/**
	 * Load channels.
	 */
	public function init() {
		$load_channels = array(
			Facebook::class,
		);

		foreach ( $load_channels as $channel ) {
			if ( is_string( $channel ) && class_exists( $channel ) ) {
				$channel = new $channel();
			}

			// Channels need to be a valid AbstractChannel instance.
			if ( ! is_a( $channel, AbstractChannel::class ) ) {
				continue;
			}

			$this->channels[ $channel->get_id() ] = $channel;
		}
	}

	/**
	 * Get channels.
	 *
	 * @return array
	 */
	public function channels() {
		$channels = array();

		if ( count( $this->channels ) > 0 ) {
			foreach ( $this->channels as $channel ) {
				$channels[ $channel->id ] = $channel;
			}
		}

		return $channels;
	}

	/**
	 * Get channel.
	 *
	 * @param string $id The Channel ID.
	 * @return WooCommerce\Grow\WMCPA\Channels\AbstractChannel
	 * @throws \Exception When invalid channel ID is requested.
	 */
	public function get_channel( $id ) {
		if ( isset( $this->channels[ $id ] ) ) {
			return $this->channels[ $id ];
		} else {
			throw new \Exception( esc_html__( 'Invalid Channel.', 'woo-mcpa' ) );
		}
	}
}
