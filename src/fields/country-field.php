<?php
/**
 * Nomad Forms Country Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Country_Field' ) ) {

	/**
	 * Country Field.
	 *
	 * @since 1.0.0
	 */
	class Country_Field extends Select_Field {

		public $type = 'country';

		public function get_options() {

			switch ( $this->args['format'] ) {
				case 'full':
					return Nomad_Constants::CHOICES_COUNTRY_FULL;
				break;
				case 'short':
					return Nomad_Constants::CHOICES_COUNTRY;
				break;
				default:
					return Nomad_Constants::CHOICES_COUNTRY;
				break;
			}

		}

	}

}
