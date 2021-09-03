<?php
/**
 * Nomad Forms Phone Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Phone_Field' ) ) {

	/**
	 * Phone Field.
	 *
	 * @since 1.0.0
	 */
	class Phone_Field extends Input_Field {

		public $type = 'phone';

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
