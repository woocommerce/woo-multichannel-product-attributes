<?php
/**
 * Manages rendering meta fields.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\MetaFields;

use \WooCommerce\Grow\WMCPA\MetaFields\Types\Select;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ProductMetaFields {

	/**
	 * Renders the meta fields.
	 *
	 * @param array $fields Array of fields to render.
	 */
	public static function render( $fields ) {
		global $product_object;
        
        // Filter out false results.
        $fields = array_filter( $fields );

        // Loop over and render fields.
        if ( $fields ) {
            foreach ( $fields as $field ) {
                $args = $field['args'];
                if ( ! isset( $args['value'] ) ){
                    $args['value'] = $product_object->get_meta( $args['id'] );
                }
                self::render_field( $args );
            }
        }
	}

    /**
     * Render a meta field.
     */
    public static function render_field( $field ) {
        if ( 'select' === $field['type'] ) {
            Select::render( $field );
        }
    }
}
