<?php
/**
 * Nomad Forms Textarea Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Textarea_Field' ) ) {

	/**
	 * Textarea Field.
	 *
	 * @since 1.0.0
	 */
	class Textarea_Field extends Field {

		public $type = 'textarea';

		public $attributes = array(
			'autofocus',
			'cols',
			'disabled',
			'maxlength',
			'minlength',
			'placeholder',
			'readonly',
			'required',
			'rows',
			'spellcheck',
			'wrap',
		);

		public function render() {

			$attributes = nomad_format_attributes( $this->get_attributes() );

			echo sprintf( '<textarea id="%s" name="%s"%s>%s</textarea>', esc_attr( $this->key ), esc_attr( $this->key ), $attributes, esc_textarea( $this->get_value() ) );

		}

	}

}
