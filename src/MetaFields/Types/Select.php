<?php
/**
 * The Select meta field.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\MetaFields\Types;

use \WooCommerce\Grow\WMCPA\Utilities\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The select meta field class.
 */
class Select extends AbstractField {

	/**
	 * Setup Field type data.
	 */
	public function initialize() {
		$this->name     = 'select';
		$this->defaults = array(
			'options'       => array(),
			'default_value' => '',
		);
	}

	/**
	 * Create the HTML interface for the meta field.
	 *
	 * @param array $field An array holding all the field's data.
	 */
	public static function render( $field ) {
		$value   = Helper::get_array( $field['value'] );
		$options = Helper::get_array( $field['options'] );

		if ( empty( $value ) ) {
			$value = array( '' );
		}

		$select = array(
			'id'      => $field['id'],
			'name'    => $field['name'],
			'label'   => $field['label'],
			'value'   => $value,
			'options' => $options,
		);

		woocommerce_wp_select( $select );
	}
}
