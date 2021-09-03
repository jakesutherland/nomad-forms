<?php
/**
 * Nomad Forms Password Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Password_Field' ) ) {

	/**
	 * Password Field.
	 *
	 * @since 1.0.0
	 */
	class Password_Field extends Input_Field {

		public $type = 'password';

		public $attributes = array(
			'autofocus',
			'disabled',
			'maxlength',
			'minlength',
			'placeholder',
			'readonly',
			'required',
			'size',
		);

	}

}
