<?php
/**
 * Nomad Forms Select Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Select_Field' ) ) {

	/**
	 * Select Field.
	 *
	 * @since 1.0.0
	 */
	class Select_Field extends Field {

		public $type = 'select';

		public $attributes = array(
			'autocomplete',
			'autofocus',
			'disabled',
			'multiple',
			'readonly',
			'required',
			'size',
		);

		public function get_options() {

			return $this->get_arg( 'options' );

		}

		public function render() {

			$attributes = nomad_format_attributes( $this->get_attributes() );

			$options = '<option></option>';

			$selected_option = $this->get_value();
			$options_array   = $this->get_options();

			if ( ! empty( $options_array ) && is_array( $options_array ) ) {

				foreach ( $options_array as $value => $label ) {
					$selected = '';

					if ( ! is_null( $selected_option ) ) {
						if ( is_int( $value ) ) {
							if ( $value === intval( $selected_option ) ) {
								$selected = ' selected="selected"';
							}
						} else {
							if ( $value === $selected_option ) {
								$selected = ' selected="selected"';
							}
						}
					}

					$options .= sprintf( '<option value="%s"%s>%s</option>', esc_attr( $value ), $selected, esc_html( $label ) );
				}

			}

			echo sprintf( '<select id="%s" name="%s" %s>%s</select>', esc_attr( $this->key ), esc_attr( $this->key ), $attributes, $options );

		}

	}

}
