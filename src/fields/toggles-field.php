<?php
/**
 * Nomad Forms Toggles Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Toggles_Field' ) ) {

	/**
	 * Toggles Field.
	 *
	 * @since 1.0.0
	 */
	class Toggles_Field extends Field {

		public $type = 'toggles';

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
					$toggle_attributes = array();

					$id = sprintf( '%s_%s', $this->key, $value );

					if ( ! is_null( $checked_options ) ) {
						if ( in_array( $value, $checked_options, true ) ) {
							$toggle_attributes['checked'] = true;
						}
					}

					if ( ! empty( $disabled_options ) && in_array( $value, $disabled_options, true ) ) {
						$toggle_attributes['disabled'] = true;
						$label_attributes['class'][] = 'disabled';
					}

					$toggle_attributes['class'][] = 'toggle';

					$label_formatted_attributes = nomad_format_attributes( $label_attributes );

					$toggle_formatted_attributes = nomad_format_attributes( $toggle_attributes );

					$options_html .= '<div class="toggle-container">';
					$options_html .= sprintf( '<input type="checkbox" id="%s" name="%s[]" value="%s"%s />', esc_attr( $id ), esc_attr( $this->key ), esc_attr( $value ), $toggle_formatted_attributes );
					$options_html .= sprintf( '<label for="%s"%s>%s</label>', esc_attr( $id ), $label_formatted_attributes, $label );
					$options_html .= '</div>';

				}

			}

			echo $options_html;

		}

	}

}
