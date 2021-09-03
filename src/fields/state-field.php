<?php
/**
 * Nomad Forms State Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\State_Field' ) ) {

	/**
	 * State Field.
	 *
	 * @since 1.0.0
	 */
	class State_Field extends Select_Field {

		public $type = 'state';

		public function get_options() {

			switch ( $this->args['format'] ) {
				case 'full':
					return Nomad_Constants::CHOICES_STATE_FULL;
				break;
				case 'short':
					return Nomad_Constants::CHOICES_STATE;
				break;
				default:
					return Nomad_Constants::CHOICES_STATE;
				break;
			}

		}

	}

}
