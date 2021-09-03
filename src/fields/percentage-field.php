<?php
/**
 * Nomad Forms Percentage Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Percentage_Field' ) ) {

	/**
	 * Percentage Field.
	 *
	 * @since 1.0.0
	 */
	class Percentage_Field extends Number_Field {

		public $type = 'percentage';

		public function render() {

			$attributes_array = $this->get_attributes();

			$attributes_array['min'] = ( isset( $attributes_array['min'] ) ) ? $attributes_array['min'] : 0;
			$attributes_array['max'] = ( isset( $attributes_array['max'] ) ) ? $attributes_array['max'] : 100;

			$attributes = nomad_format_attributes( $attributes_array );

			echo sprintf( '<input type="number" id="%s" name="%s" value="%s"%s />%%', esc_attr( $this->key ), esc_attr( $this->key ), esc_attr( $this->get_value() ), $attributes );

		}

	}

}
