<?php
/**
 * Includes the composer Autoloader used for packages and classes in the src/ directory.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The Autoloader class.
 */
class Autoloader {
	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Require the autoloader and return the result.
	 *
	 * If the autoloader is not present, let's log the failure and display a nice admin notice.
	 *
	 * @return boolean
	 */
	public static function init() {
		$autoloader = dirname( __DIR__ ) . '/vendor/autoload.php';

		if ( ! is_readable( $autoloader ) ) {
			self::missing_autoloader();
			return false;
		}

		$autoloader_result = require $autoloader;
		if ( ! $autoloader_result ) {
			return false;
		}

		return $autoloader_result;
	}

	/**
	 * If the autoloader is missing, add an admin notice.
	 */
	protected static function missing_autoloader() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log(  // phpcs:ignore
				esc_html__( 'Your installation is incomplete. If you installed from GitHub, please refer to this document to set up your development environment: https://github.com/ibndawood/woo-multichannel-product-attributes#installing', 'woo-mcpa' )
			);
		}
		add_action(
			'admin_notices',
			function() {
				?>
				<div class="notice notice-error">
					<p>
						<?php
						printf(
							/* translators: 1: is a link to a support document. 2: closing link */
							esc_html__( 'Your installation is incomplete. If you installed from GitHub, %1$splease refer to this document%2$s to set up your development environment.', 'woo-mcpa' ),
							'<a href="' . esc_url( 'https://github.com/ibndawood/woo-multichannel-product-attributes#installing' ) . '" target="_blank" rel="noopener noreferrer">',
							'</a>'
						);
						?>
					</p>
				</div>
				<?php
			}
		);
	}
}
