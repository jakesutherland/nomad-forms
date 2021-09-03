<?php
/**
 * Nomad Forms Hidden Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Hidden_Field' ) ) {

	/**
	 * Hidden Field.
	 *
	 * @since 1.0.0
	 */
	class Hidden_Field extends Input_Field {

		public $type = 'hidden';

	}

}
