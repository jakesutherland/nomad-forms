<?php
/**
 * Nomad Forms Email Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Email_Field' ) ) {

	/**
	 * Email Field.
	 *
	 * @since 1.0.0
	 */
	class Email_Field extends Input_Field {

		public $type = 'email';

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
