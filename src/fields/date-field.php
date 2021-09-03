<?php
/**
 * Nomad Forms Date Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Date_Field' ) ) {

	/**
	 * Date Field.
	 *
	 * @since 1.0.0
	 */
	class Date_Field extends Input_Field {

		public $type = 'date';

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
