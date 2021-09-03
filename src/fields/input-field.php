<?php
/**
 * Nomad Forms Input Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Input_Field' ) ) {

	/**
	 * Input Field.
	 *
	 * @since 1.0.0
	 * @abstract
	 */
	abstract class Input_Field extends Field {

		public $type = 'hidden';

		public $attributes = array(
			'autofocus',
			'disabled',
			'maxlength',
			'minlength',
			'pattern',
			'placeholder',
			'readonly',
			'required',
			'size',
		);

		public function render() {

			$attributes = nomad_format_attributes( $this->get_attributes() );

			$value = $this->get_value();

			if ( 'password' === $this->get_type() ) {
				// We should never output a value for a password field.
				$value = null;
			}

			echo sprintf( '<input type="%s" id="%s" name="%s" value="%s"%s />', esc_attr( $this->get_type() ), esc_attr( $this->key ), esc_attr( $this->key ), esc_attr( $value ), $attributes );

		}

	}

}
