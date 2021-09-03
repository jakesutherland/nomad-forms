<?php
/**
 * Nomad Forms Day Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Day_Field' ) ) {

	/**
	 * Day Field.
	 *
	 * @since 1.0.0
	 */
	class Day_Field extends Select_Field {

		public $type = 'day';

		public function get_options() {

			return Nomad_Constants::CHOICES_DAY;

		}

	}

}
