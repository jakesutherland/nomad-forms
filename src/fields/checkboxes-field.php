<?php
/**
 * Nomad Forms Checkboxes Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Checkboxes_Field' ) ) {

	/**
	 * Checkboxes Field.
	 *
	 * @since 1.0.0
	 */
	class Checkboxes_Field extends Field {

		public $type = 'checkboxes';

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

					$label_attributes    = array();
					$checkbox_attributes = array();

					$id = sprintf( '%s_%s', $this->key, $value );

					if ( ! empty( $checked_options ) && in_array( $value, $checked_options, true ) ) {
						$checkbox_attributes['checked'] = true;
					}

					if ( ! empty( $disabled_options ) && in_array( $value, $disabled_options, true ) ) {
						$checkbox_attributes['disabled'] = true;
						$label_attributes['class'][] = 'disabled';
					}

					$label_formatted_attributes = nomad_format_attributes( $label_attributes );

					$checkbox_formatted_attributes = nomad_format_attributes( $checkbox_attributes );

					$options_html .= '<div class="checkbox-container">';
					$options_html .= sprintf( '<input type="checkbox" id="%s" name="%s[]" value="%s"%s />', esc_attr( $id ), esc_attr( $this->key ), esc_attr( $value ), $checkbox_formatted_attributes );
					$options_html .= sprintf( '<label for="%s"%s>%s</label>', esc_attr( $id ), $label_formatted_attributes, $label );
					$options_html .= '</div>';

				}

			}

			echo $options_html;

		}

	}

}
