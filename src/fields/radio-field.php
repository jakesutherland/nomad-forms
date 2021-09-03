<?php
/**
 * Nomad Forms Radio Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Radio_Field' ) ) {

	/**
	 * Radio Field.
	 *
	 * @since 1.0.0
	 */
	class Radio_Field extends Field {

		public $type = 'radio';

		public $attributes = array(
			'autofocus',
			'disabled',
			'required',
			'checked',
		);

		public function render() {

			$attributes = nomad_format_attributes( $this->get_attributes() );

			$label = sprintf( '<label for="%s">%s</label>', esc_attr( $this->key ), $this->get_arg( 'radio_text' ) );

			echo '<div class="radio-container">';
			echo sprintf( '<input type="radio" id="%s" name="%s" value="%s"%s />%s', esc_attr( $this->key ), esc_attr( $this->key ), esc_attr( $this->get_value() ), $attributes, $label );
			echo '</div>';

		}

	}

}
