<?php
/**
 * Nomad Forms AM PM Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Am_Pm_Field' ) ) {

	/**
	 * AM PM Field.
	 *
	 * @since 1.0.0
	 */
	class Am_Pm_Field extends Select_Field {

		public $type = 'am-pm';

		public function get_options() {

			return Nomad_Constants::CHOICES_AMPM;

		}

	}

}
