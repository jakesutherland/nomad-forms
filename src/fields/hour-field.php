<?php
/**
 * Nomad Forms Hour Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use Nomad\Helpers\Nomad_Constants;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Hour_Field' ) ) {

	/**
	 * Hour Field.
	 *
	 * @since 1.0.0
	 */
	class Hour_Field extends Select_Field {

		public $type = 'hour';

		public function get_options() {

			$format = ( isset( $this->args['format'] ) ) ? intval( $this->args['format'] ) : 12;

			if ( 24 === $format ) {
				$options = Nomad_Constants::CHOICES_HOUR24;
			} else {
				$options = Nomad_Constants::CHOICES_HOUR12;
			}

			return $options;

		}

	}

}
