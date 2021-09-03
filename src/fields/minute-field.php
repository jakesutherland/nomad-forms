<?php
/**
 * Nomad Forms Minute Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Minute_Field' ) ) {

	/**
	 * Minute Field.
	 *
	 * @since 1.0.0
	 */
	class Minute_Field extends Select_Field {

		public $type = 'minute';

		public function get_options() {

			return Nomad_Constants::CHOICES_MINUTE;

		}

	}

}
