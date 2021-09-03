<?php
/**
 * Nomad Forms Time Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Time_Field' ) ) {

	/**
	 * Time Field.
	 *
	 * @since 1.0.0
	 */
	class Time_Field extends Input_Field {

		public $type = 'time';

		public $attributes = array(
			'autofocus',
			'disabled',
			'placeholder',
			'readonly',
			'required',
			'size',
		);

	}

}
