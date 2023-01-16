<?php
/**
 * PHPUnit Bootstrap file.
 *
 * @package woo-mcpa
 */

use Automattic\WooCommerce\Internal\Admin\FeaturePlugin;

 // Require composer dependencies.
require_once dirname( __DIR__ ) . '/vendor/autoload.php';

$_tests_dir = getenv( 'WP_TESTS_DIR' );

// Try the `wp-phpunit` composer package.
if ( ! $_tests_dir ) {
	$_tests_dir = getenv( 'WP_PHPUNIT__DIR' );
}

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php" . PHP_EOL; // phpcs:ignore
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

// Load the PHPUnit Polyfills library.
$_phpunit_polyfills_lib = dirname( dirname( __FILE__ ) ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
if ( ! file_exists( $_phpunit_polyfills_lib ) ) {
	echo "Could not find $_phpunit_polyfills_lib, have you run `docker-compose up` in order to install Composer packages?" . PHP_EOL; // phpcs:ignore
	exit( 1 );
}
require_once $_phpunit_polyfills_lib;

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {
	require dirname( dirname( __FILE__ ) ) . '/woo-multichannel-product-attributes.php';
	_load_wc();
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/**
 * Load WooCommerce.
 */
function _load_wc() {
	require '/var/www/html/wp-content/plugins/woocommerce/woocommerce.php';
	FeaturePlugin::instance()->init();
}

/**
 * Install WC.
 */
function _install_wc() {
	// Clean existing install first.
	define( 'WP_UNINSTALL_PLUGIN', true );
	define( 'WC_REMOVE_ALL_DATA', true );
	include '/var/www/html/wp-content/plugins/woocommerce/uninstall.php';

	WC_Install::install();

	// Reload capabilities after install, see https://core.trac.wordpress.org/ticket/28374.
	if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
		$GLOBALS['wp_roles']->reinit();
	} else {
		$GLOBALS['wp_roles'] = null; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		wp_roles();
	}

	echo esc_html( 'Installing WooCommerce...' . PHP_EOL );
}
// install WC.
tests_add_filter( 'setup_theme', '_install_wc' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';

error_reporting( error_reporting() & ~E_DEPRECATED );
