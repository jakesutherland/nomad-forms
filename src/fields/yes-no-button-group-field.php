<?php
/**
 * Nomad Forms Yes No Button Group Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Yes_No_Button_Group_Field' ) ) {

	/**
	 * Yes No Button Group Field.
	 *
	 * @since 1.0.0
	 */
	class Yes_No_Button_Group_Field extends Button_Group_Field {

		public $type = 'yes-no-button-group';

		public function get_options() {

			return Nomad_Constants::CHOICES_YES_NO;

		}

	}

}
