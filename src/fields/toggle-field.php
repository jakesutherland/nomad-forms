<?php
/**
 * Nomad Forms Toggle Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Toggle_Field' ) ) {

	/**
	 * Toggle Field.
	 *
	 * @since 1.0.0
	 */
	class Toggle_Field extends Field {

		public $type = 'toggle';

		public $attributes = array(
			'autofocus',
			'disabled',
			'required',
			'checked',
		);

		public function render() {

			$attributes = $this->get_attributes();

			$attributes['class'][] = 'toggle';

			$formatted_attributes = nomad_format_attributes( $attributes );

			$label = '';

			if ( $this->get_arg( 'toggle_text' ) ) {
				$label = sprintf( '<label for="%s">%s</label>', esc_attr( $this->key ), $this->get_arg( 'toggle_text' ) );
			}

			echo '<div class="toggle-container">';
			echo sprintf( '<input type="checkbox" id="%s" name="%s" value="%s"%s />%s', esc_attr( $this->key ), esc_attr( $this->key ), esc_attr( $this->get_value() ), $formatted_attributes, $label );
			echo '</div>';

		}

	}

}
