<?php
/**
 * Nomad Forms On Off Button Group Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\On_Off_Button_Group_Field' ) ) {

	/**
	 * On Off Button Group Field.
	 *
	 * @since 1.0.0
	 */
	class On_Off_Button_Group_Field extends Button_Group_Field {

		public $type = 'on-off-button-group';

		public function get_options() {

			return Nomad_Constants::CHOICES_ON_OFF;

		}

	}

}
