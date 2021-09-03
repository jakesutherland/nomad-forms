<?php
/**
 * Nomad Forms Upload Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Upload_Field' ) ) {

	/**
	 * Upload Field.
	 *
	 * @since 1.0.0
	 */
	class Upload_Field extends Input_Field {

		public $type = 'file';

		public $attributes = array(
			'autofocus',
			'disabled',
			'placeholder',
			'required',
			'size',
		);

	}

}
