<?php
/**
 * Nomad Forms Enable Disable Button Group Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Enable_Disable_Button_Group_Field' ) ) {

	/**
	 * Enable Disable Button Group Field.
	 *
	 * @since 1.0.0
	 */
	class Enable_Disable_Button_Group_Field extends Button_Group_Field {

		public $type = 'enable-disable-button-group';

		public function get_options() {

			return Nomad_Constants::CHOICES_ENABLE_DISABLE;

		}

	}

}
