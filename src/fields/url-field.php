<?php
/**
 * Nomad Forms URL Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Url_Field' ) ) {

	/**
	 * URL Field.
	 *
	 * @since 1.0.0
	 */
	class Url_Field extends Input_Field {

		public $type = 'url';

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
