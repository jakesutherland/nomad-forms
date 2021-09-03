<?php
/**
 * Nomad Forms Enabled Disabled Button Group Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Enabled_Disabled_Button_Group_Field' ) ) {

	/**
	 * Enabled Disabled Button Group Field.
	 *
	 * @since 1.0.0
	 */
	class Enabled_Disabled_Button_Group_Field extends Button_Group_Field {

		public $type = 'enabled-disabled-button-group';

		public function get_options() {

			return Nomad_Constants::CHOICES_ENABLED_DISABLED;

		}

	}

}
