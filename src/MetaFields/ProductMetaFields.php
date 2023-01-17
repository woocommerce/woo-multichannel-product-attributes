<?php
/**
 * Manages rendering meta fields.
 *
 * @package woo-mcpa
 */

namespace WooCommerce\Grow\WMCPA\MetaFields;

use WooCommerce\Grow\WMCPA\MetaFields\Types\Select;
use WooCommerce\Grow\WMCPA\Traits\SingletonTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * The product meta fields class.
 */
class ProductMetaFields {

	use SingletonTrait;

	/**
	 * Initialzie the product meta fields.
	 */
	public static function init() {
		add_action( 'woocommerce_process_product_meta_simple', array( get_called_class(), 'save' ) );
	}

	/**
	 * Save the meta fields.
	 *
	 * @param int $product_id The ID of the product.
	 */
	public static function save( $product_id ) {
		$product     = wc_get_product( $product_id );
		$meta_fields = $_POST['woo_mcpa_meta']; //phpcs:ignore

		if ( ! $product ) {
			return;
		}

		if ( ! empty( $meta_fields ) ) {
			$has_changes = false;

			foreach ( $meta_fields as $meta => $value ) {
				if ( $value['before'] !== $value['after'] ) {
					$product->update_meta_data( $meta, $value['after'] );
					$has_changes = true;
				}
			}

			if ( $has_changes ) {
				$product->save_meta_data();
			}
		}
	}

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
				if ( ! isset( $args['value'] ) ) {
					$args['value'] = $product_object->get_meta( $args['id'] );
				}
				self::render_field( $args );
			}
		}
	}

	/**
	 * Render a meta field.
	 *
	 * @param array $field The field arguments.
	 */
	public static function render_field( $field ) {
		$field_name    = $field['name'];
		$field['name'] = $field_name . '[after]';
		if ( 'select' === $field['type'] ) {
			Select::render( $field );
		}
		?>
		<input type="hidden" name="<?php echo esc_attr( $field_name ); ?>[before]" value="<?php echo esc_attr( $field['value'] ); ?>">
		<?php
	}
}
