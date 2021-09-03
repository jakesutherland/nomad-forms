<?php
/**
 * Nomad Forms Weekday Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Weekday_Field' ) ) {

	/**
	 * Weekday Field.
	 *
	 * @since 1.0.0
	 */
	class Weekday_Field extends Select_Field {

		public $type = 'weekday';

		public function get_options() {

			switch ( $this->args['format'] ) {
				case 'full':
					return Nomad_Constants::CHOICES_WEEKDAY_FULL;
				break;
				case 'short':
					return Nomad_Constants::CHOICES_WEEKDAY_SHORT;
				break;
				case 'lower':
					return Nomad_Constants::CHOICES_WEEKDAY;
				break;
				default:
					return Nomad_Constants::CHOICES_WEEKDAY;
				break;
			}

		}

	}

}
