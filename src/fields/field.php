<?php
/**
 * Nomad Forms Base Field class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms\Fields;

use function Nomad\Helpers\nomad_format_attributes;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Field' ) ) {

	/**
	 * Base Field class.
	 *
	 * All form fields extend this class.
	 *
	 * @since 1.0.0
	 * @abstract
	 */
	abstract class Field {

		/**
		 * Field Key.
		 *
		 * Unique identifier for this field.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var string
		 */
		public $key;

		/**
		 * Field Type.
		 *
		 * The type of field.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var string
		 */
		public $type;

		/**
		 * Field arguments.
		 *
		 * Arguments provided for the field.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var array
		 */
		public $args = array();

		/**
		 * Field attributes.
		 *
		 * Attributes available for the field.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @var array
		 */
		public $attributes = array();

		/**
		 * Field render.
		 *
		 * Renders the field HTML.
		 *
		 * @since 1.0.0
		 * @abstract
		 */
		abstract function render();

		/**
		 * Get Type.
		 *
		 * Gets the field type.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return string
		 */
		final public function get_type() {

			return $this->type;

		}

		/**
		 * Get Argument.
		 *
		 * Gets a specific field argument. If it was not provided, returns null.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @param string $arg The argument key to get.
		 *
		 * @return null|mixed
		 */
		final public function get_arg( $arg ) {

			return ( array_key_exists( $arg, $this->args ) && ! is_null( $this->args[ $arg ] ) ) ? $this->args[ $arg ] : null;

		}

		/**
		 * Get Value.
		 *
		 * Get the field value. If there is no value, use the default. If there
		 * is no default, then its null.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return mixed
		 */
		final public function get_value() {

			if ( ! is_null( $this->args['value'] ) ) {
				$value = $this->args['value'];
			} else if ( ! is_null( $this->args['default'] ) ) {
				$value = $this->args['default'];
			} else {
				$value = null;
			}

			return $value;

		}

		/**
		 * Get Attributes.
		 *
		 * Takes the allowed attributes, loops through them and maps their
		 * values into an array.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return array
		 */
		final public function get_attributes() {

			$attributes = $this->attributes;

			// Everything can have a `style` attribute.
			$attributes[] = 'style';

			$mapped_attributes = array();

			foreach ( $attributes as $attribute ) {
				$mapped_attributes[ $attribute ] = $this->map_attribute( $attribute );
			}

			$mapped_attributes['id'] = $this->key;

			return $mapped_attributes;

		}

		/**
		 * Map attribute.
		 *
		 * Maps a field attribute key with its respective value.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param string $attribute The attribute key to be mapped.
		 *
		 * @return mixed
		 */
		private function map_attribute( $attribute ) {

			$mapped_attribute = '';

			$validate_rule_keys = $this->get_validate_rule_keys();

			switch ( $attribute ) {
				case 'value':
					$mapped_attribute = $this->get_value();
				break;
				case 'required':
					if ( array_key_exists( $attribute, $validate_rule_keys ) ) {
						$mapped_attribute = true;
					} else {
						$mapped_attribute = $this->get_arg( $attribute );
					}
				break;
				case 'min':
				case 'max':
				case 'minlength':
				case 'maxlength':
					if ( array_key_exists( $attribute, $validate_rule_keys ) ) {
						$mapped_attribute = $validate_rule_keys[ $attribute ];
					} else {
						$mapped_attribute = $this->get_arg( $attribute );
					}
				break;
				case 'checked':
					if ( $this->get_arg( $attribute ) ) {
						$mapped_attribute = true;
					} else {
						$mapped_attribute = null;
					}
				break;
				default:
					$mapped_attribute = $this->get_arg( $attribute );
				break;
			}

			return $mapped_attribute;

		}

		/**
		 * Get Validate Rule Keys.
		 *
		 * Converts the field validate ruleset to an array of rule key and rule
		 * argument value pairs.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @return array
		 */
		private function get_validate_rule_keys() {

			$validate_rule_keys = array();

			if ( ! empty( $this->args['validate'] ) && is_array( $this->args['validate'] ) ) {

				foreach ( $this->args['validate'] as $rule_key ) {

					$argument = null;

					if ( strpos( $rule_key, ':' ) ) {
						list( $rule_key, $argument ) = explode( ':', $rule_key, 2 );
					}

					$validate_rule_keys[ $rule_key ] = $argument;

				}

			}

			return $validate_rule_keys;

		}

		/**
		 * Field Label.
		 *
		 * Outputs the field label and description, if provided.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return false|void
		 */
		final public function label() {

			if ( 'hidden' === $this->get_type() ) {
				return false;
			}

			if ( is_null( $this->get_arg( 'label' ) ) ) {
				return false;
			}

			if ( ! $this->get_arg( 'show_field_label' ) ) {
				return false;
			}

			$label_attributes = array(
				'class' => array(
					'nomad-field-label',
				)
			);

			$attributes = nomad_format_attributes( $label_attributes );

			$description = '';

			if ( $this->get_arg( 'description' ) ) {
				$description = sprintf( '<div class="nomad-field-description">%s</div>', $this->get_arg( 'description' ) );
			}

			$label = sprintf( '<label for="%s">%s</label>%s', esc_attr( $this->key ), $this->get_arg( 'label' ), $description );

			echo sprintf( '<div %s>%s</div>', $attributes, $label );

		}

		/**
		 * Open Container.
		 *
		 * Outputs the field opening container.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return false|void
		 */
		final public function open_container() {

			if ( 'hidden' === $this->get_type() ) {
				return false;
			}

			$field_attributes = array(
				'class' => array(
					'nomad-field',
					$this->get_type() . '-field'
				)
			);

			if ( $this->args['dependencies'] && is_array( $this->args['dependencies'] ) ) {
				// Add base field class names.
				foreach ( $this->args['dependencies'] as $dependency ) {
					$field_attributes['class'][] = $dependency . '-field';
				}
			}

			if ( $this->get_arg( 'inline' ) ) {
				$field_attributes['class'][] = 'inline';
			}

			$attributes = nomad_format_attributes( $field_attributes );

			echo sprintf( '<div %s>', $attributes );

		}

		/**
		 * Field Before.
		 *
		 * Outputs the opening field container tag and text before the field, if provided.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return false|void
		 */
		final public function before() {

			if ( 'hidden' === $this->get_type() ) {
				return false;
			}

			echo '<div class="nomad-field-container">';

			if ( $this->get_arg( 'before' ) ) {
				echo $this->get_arg( 'before' );
			}

		}

		/**
		 * Field After.
		 *
		 * Outputs the closing field container tag and text after the field, if provided.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return false|void
		 */
		final public function after() {

			if ( 'hidden' === $this->get_type() ) {
				return false;
			}

			if ( $this->get_arg( 'after' ) ) {
				echo $this->get_arg( 'after' );
			}

			echo '</div>';

		}

		/**
		 * Close Container.
		 *
		 * Outputs the field closing container.
		 *
		 * @since 1.0.0
		 * @access public
		 * @final
		 *
		 * @return false|void
		 */
		final public function close_container() {

			if ( 'hidden' === $this->get_type() ) {
				return false;
			}

			echo '</div>';

		}

		/**
		 * Field Constructor.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $field Field arguments.
		 */
		public function __construct( $field ) {

			$this->key  = $field['name'];
			$this->args = $field;

		}

	}

}
