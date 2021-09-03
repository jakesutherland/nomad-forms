<?php
/**
 * Nomad Forms Checkbox Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Checkbox_Field' ) ) {

	/**
	 * Checkbox Field.
	 *
	 * @since 1.0.0
	 */
	class Checkbox_Field extends Field {

		public $type = 'checkbox';

		public $attributes = array(
			'autofocus',
			'disabled',
			'required',
			'checked',
		);

		public function render() {

			$attributes = nomad_format_attributes( $this->get_attributes() );

			$label = sprintf( '<label for="%s">%s</label>', esc_attr( $this->key ), $this->get_arg( 'checkbox_text' ) );

			echo '<div class="checkbox-container">';
			echo sprintf( '<input type="checkbox" name="%s" id="%s" value="%s"%s />%s', esc_attr( $this->key ), esc_attr( $this->key ), esc_attr( $this->get_value() ), $attributes, $label );
			echo '</div>';

		}

	}

}
