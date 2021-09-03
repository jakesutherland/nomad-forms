<?php
/**
 * Nomad Forms Month Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Month_Field' ) ) {

	/**
	 * Month Field.
	 *
	 * @since 1.0.0
	 */
	class Month_Field extends Select_Field {

		public $type = 'month';

		public function get_options() {

			switch ( $this->args['format'] ) {
				case 'full':
					return Nomad_Constants::CHOICES_MONTH_FULL;
				break;
				case 'short':
					return Nomad_Constants::CHOICES_MONTH_SHORT;
				break;
				case 'number':
					return Nomad_Constants::CHOICES_MONTH;
				break;
				default:
					return Nomad_Constants::CHOICES_MONTH;
				break;
			}

		}

	}

}
