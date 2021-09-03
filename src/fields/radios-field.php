<?php
/**
 * Nomad Forms Radios Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Radios_Field' ) ) {

	/**
	 * Radios Field.
	 *
	 * @since 1.0.0
	 */
	class Radios_Field extends Field {

		public $type = 'radios';

		public $attributes = array(
			'autofocus',
			'disabled',
			'id',
			'name',
			'maxlength',
			'minlength',
			'pattern',
			'placeholder',
			'readonly',
			'required',
			'size',
			'value',
			'checked',
		);

		public function get_options() {

			return $this->get_arg( 'options' );

		}

		public function render() {

			$options_html = '';

			$options = $this->get_options();

			if ( ! empty( $options ) && is_array( $options ) ) {

				$checked_options  = $this->get_value();
				$disabled_options = $this->get_arg( 'disabled_options' );

				if ( is_string( $checked_options ) ) {
					$checked_options = array( $checked_options );
				}

				foreach ( $options as $value => $label ) {

					$label_attributes = array();
					$radio_attributes = array();

					$id = sprintf( '%s_%s', $this->key, $value );

					if ( ! is_null( $checked_options ) ) {
						if ( in_array( $value, $checked_options, true ) ) {
							$radio_attributes['checked'] = true;
						}
					}

					if ( ! empty( $disabled_options ) && in_array( $value, $disabled_options, true ) ) {
						$radio_attributes['disabled'] = true;
						$label_attributes['class'][] = 'disabled';
					}

					$label_formatted_attributes = nomad_format_attributes( $label_attributes );

					$radio_formatted_attributes = nomad_format_attributes( $radio_attributes );

					$options_html .= '<div class="radio-container">';
					$options_html .= sprintf( '<input type="radio" id="%s" name="%s" value="%s"%s />', esc_attr( $id ), esc_attr( $this->key ), esc_attr( $value ), $radio_formatted_attributes );
					$options_html .= sprintf( '<label for="%s"%s>%s</label>', esc_attr( $id ), $label_formatted_attributes, $label );
					$options_html .= '</div>';

				}

			}

			echo $options_html;

		}

	}

}
