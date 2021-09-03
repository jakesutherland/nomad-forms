<?php
/**
 * Nomad Forms Button Group Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Button_Group_Field' ) ) {

	/**
	 * Button Group Field
	 *
	 * @since 1.0.0
	 */
	class Button_Group_Field extends Field {

		public $type = 'button-group';

		public function get_options() {

			return $this->get_arg( 'options' );

		}

		public function render() {

			$options_html = '';

			$options_array = $this->get_options();

			if ( ! empty( $options_array ) && is_array( $options_array ) ) {

				$multiple = $this->get_arg( 'multiple' );

				$checked_options  = $this->get_value();
				$disabled_options = $this->get_arg( 'disabled_options' );

				foreach ( $options_array as $value => $label ) {

					$label_attributes       = array();
					$button_item_attributes = array();

					$id = sprintf( '%s_%s', $this->key, $value );

					if ( ! is_null( $checked_options ) ) {
						if ( $multiple ) {
							if ( in_array( $value, $checked_options, true ) ) {
								$button_item_attributes['checked'] = true;
							}
						} else {
							if ( is_int( $value ) ) {
								if ( $value === intval( $checked_options ) ) {
									$button_item_attributes['checked'] = true;
								}
							} else {
								if ( $value === $checked_options ) {
									$button_item_attributes['checked'] = true;
								}
							}
						}
					}

					if ( ! empty( $disabled_options ) && in_array( $value, $disabled_options, true ) ) {
						$button_item_attributes['disabled'] = true;
						$label_attributes['class'][] = 'disabled';
					}

					$button_item_attributes['class'][] = 'button-group-item';

					$label_formatted_attributes = nomad_format_attributes( $label_attributes );

					$button_item_formatted_attributes = nomad_format_attributes( $button_item_attributes );

					$options_html .= '<div class="button-group-item-container">';

					if ( $multiple ) {
						$options_html .= sprintf( '<input type="checkbox" id="%s" name="%s[]" value="%s"%s />', esc_attr( $id ), esc_attr( $this->key ), esc_attr( $value ), $button_item_formatted_attributes );
					} else {
						$options_html .= sprintf( '<input type="radio" id="%s" name="%s" value="%s"%s />', esc_attr( $id ), esc_attr( $this->key ), esc_attr( $value ), $button_item_formatted_attributes );
					}

					$options_html .= sprintf( '<label for="%s"%s>%s</label>', esc_attr( $id ), $label_formatted_attributes, $label );
					$options_html .= '</div>';

				}

			}

			echo $options_html;

		}

	}

}
