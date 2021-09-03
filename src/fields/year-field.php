<?php
/**
 * Nomad Forms Year Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Year_Field' ) ) {

	/**
	 * Year Field.
	 *
	 * @since 1.0.0
	 */
	class Year_Field extends Select_Field {

		public $type = 'year';

		public function get_options() {

			$options = array();

			$min_year = ( isset( $this->args['min'] ) ) ? $this->args['min'] : 1900;
			$max_year = ( isset( $this->args['max'] ) ) ? $this->args['max'] : intval( date( 'Y' ) );

			for ( $year = $max_year; $year >= $min_year; $year-- ) {
				$options[ $year ] = $year;
			}

			return $options;

		}

	}

}
