<?php
/**
 * Nomad Forms Number Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Number_Field' ) ) {

	/**
	 * Number Field.
	 *
	 * @since 1.0.0
	 */
	class Number_Field extends Input_Field {

		public $type = 'number';

		public $attributes = array(
			'autofocus',
			'disabled',
			'maxlength',
			'minlength',
			'placeholder',
			'readonly',
			'required',
			'size',
			'min',
			'max',
		);

	}

}
