<?php
/**
 * Nomad Forms Text Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Text_Field' ) ) {

	/**
	 * Text Field.
	 *
	 * @since 1.0.0
	 */
	class Text_Field extends Input_Field {

		public $type = 'text';

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

	}

}
