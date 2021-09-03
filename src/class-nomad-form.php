<?php
/**
 * Nomad Form class file.
 *
 * @since 1.0.0
 *
 * @package Nomad\Forms
 */

namespace Nomad\Forms;

use Nomad\Forms\Nomad_Form_Fields;
use Nomad\Helpers\Nomad_Constants;
use Nomad\Helpers\Nomad_Exception;
use Nomad\Validate\Nomad_Validate;

use function Nomad\Helpers\nomad_error;
use function Nomad\Helpers\nomad_format_attributes;
use function Nomad\Validate\nomad_validate;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Nomad_Form' ) ) {

	/**
	 * Nomad Form Class.
	 *
	 * Handles generating and displaying forms.
	 *
	 * @since 1.0.0
	 * @final
	 */
	final class Nomad_Form {

		/**
		 * Form ID.
		 *
		 * The unique ID for the form being built.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var string
		 */
		private $id = null;

		/**
		 * Form Arguments.
		 *
		 * The arguments that were provided.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var array
		 */
		private $args = array();

		/**
		 * Form Fields.
		 *
		 * The fields that were added to the form.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var array
		 */
		private $fields = array();

		/**
		 * Form Processed.
		 *
		 * Whether or not the form has been processed.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var boolean
		 */
		private $processed = false;

		/**
		 * Form Is Valid.
		 *
		 * Whether or not the form is valid.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var null|boolean
		 */
		private $is_valid = null;

		/**
		 * Form Is Upload Form.
		 *
		 * Whether or not the form is a file upload form. This determines
		 * whether or not the form tag should have the enctype="multipart/form-data"
		 * attribute added. This attribute will be automatically added if the
		 * form has an 'upload' field.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var boolean
		 */
		private $is_upload_form = false;

		/**
		 * Form Messages.
		 *
		 * Messages generated when validating the form.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var array
		 */
		private $messages = array();

		/**
		 * Nomad Form Constructor.
		 *
		 * Gets everything set up.
		 *
		 * View the `README.md` file for documentation.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string $id   Unique form ID.
		 * @param array  $args Form arguments.
		 */
		public function __construct( $id, $args ) {

			$defaults = array(
				'action'              => null,
				'allow_modifications' => true,
				'automate_validation' => true,
				'callback'            => null,
				'cancel_button'       => false,
				'cancel_href'         => home_url( '/' ),
				'cancel_text'         => 'Cancel',
				'error_message'       => 'Sorry, there was a problem submitting your form: ',
				'fields'              => array(),
				'form_tag'            => true,
				'labels_alignment'    => 'left',
				'labels_position'     => 'top',
				'method'              => 'POST',
				'nonce'               => true,
				'nonce_message'       => 'Invalid form submission. Please try again.',
				'reset_button'        => false,
				'reset_text'          => 'Reset',
				'submit_button'       => true,
				'submit_text'         => 'Submit',
				'success_message'     => 'Success! Your form has been submitted.',
			);

			if ( ! array_key_exists( 'allow_modifications', $args ) || ( array_key_exists( 'allow_modifications', $args ) && $args['allow_modifications'] ) ) {
				// Only allow the form defaults to be changed if we didn't set `allow_modifications` or if it is set, it is set to true.
				$defaults = apply_filters( 'nomad/forms/defaults', $defaults );
			}

			$args = wp_parse_args( $args, $defaults );

			// Unset the defaults to free up a bit of memory. We are no longer using this.
			unset( $defaults );

			$this->id = $id;

			/**
			 * Fires when first initializing a Nomad Form.
			 *
			 * @since 1.0.0
			 *
			 * @param string $form_id The form ID being initialized.
			 * @param array  $args    The form arguments.
			 */
			do_action( 'nomad/forms/init', $this->form_id(), $args );

			/**
			 * Fires when first initializing a specific Nomad Form.
			 *
			 * @since 1.0.0
			 *
			 * @param array $args The form arguments.
			 */
			do_action( "nomad/forms/{$this->form_id()}/init", $args );

			if ( $args['allow_modifications'] ) {

				/**
				 * Filter the form arguments.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array $args The form arguments.
				 */
				$args = apply_filters( "nomad/forms/{$this->form_id()}/args", $args );

				/**
				 * Filter the form fields.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array $fields The form fields.
				 */
				$args['fields'] = apply_filters( "nomad/forms/{$this->form_id()}/fields", $args['fields'] );

				// Only allow injections if we allow modifications.
				$args['fields'] = $this->parse_injections( $args['fields'] );

			}

			if ( ! empty( $args['callback'] ) && ! is_callable( $args['callback'] ) ) {
				throw new Nomad_Exception( sprintf( 'The <code>%s</code> form has a callback function <code>%s</code> that could not be called. Check your spelling and namespacing.', $this->form_id(), esc_html( $args['callback'] ) ) );
			}

			if ( empty( $args['fields'] ) ) {
				// If we don't have any fields, then we really can't do anything at all.
				throw new Nomad_Exception( sprintf( 'The <code>%s</code> form has no fields.', $this->form_id() ) );
			}

			$args['fields'] = $this->prepare_fields( $args['fields'] );

			$this->args   = $args;
			$this->fields = $args['fields'];

			// Unset the arguments to free up a bit of memory. We are no longer using this.
			unset( $args );

			$form_data = array();

			if ( $this->args['method'] === $_SERVER['REQUEST_METHOD'] ) {
				$form_data = $this->prepare_submitted_form_data();
			} else {
				$form_data = $this->prepare_initial_form_data();
			}

			$this->form_data = $form_data;

		}

		/**
		 * Parse Injections.
		 *
		 * This allows additional form fields to be added in before or after
		 * existing fields. The `allow_modifications` argument must be set to
		 * true in order for injections to work.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param array $fields The form fields that were passed in to the form arguments.
		 *
		 * @return array
		 */
		private function parse_injections( $fields ) {

			if ( ! $this->args['allow_modifications'] ) {
				// Just return the fields if modifications aren't allowed.
				return $fields;
			}

			/**
			 * Filter form injections.
			 *
			 * It's recommended to use the `nomad_form_injection()` function instead of the filter directly.
			 *
			 * Can only be used if the form allows modifications.
			 *
			 * @since 1.0.0
			 *
			 * @param array $injections Form injections.
			 */
			$injections = apply_filters( "nomad/forms/{$this->form_id()}/injections", array() );

			if ( empty( $injections ) ) {
				return $fields;
			}

			$field_injections = array();

			foreach ( $injections as $injection ) {

				if ( ! array_key_exists( 'fields', $injection ) ) {
					nomad_error( sprintf( 'Invalid <code>nomad_form_injection</code> provided for <code>%s</code> form. Missing required <code>fields</code> property.', $this->form_id() ) );
				}

				if ( array_key_exists( 'before', $injection ) ) {
					$location = 'before';
				} else if ( array_key_exists( 'after', $injection ) ) {
					$location = 'after';
				} else {
					nomad_error( sprintf( 'Invalid <code>nomad_form_injection</code> provided for <code>%s</code> form. Missing required <code>before</code> or <code>after</code> property.', $this->form_id() ) );
				}

				$field_injections[ $injection[ $location ] ][ $location ] = $injection['fields'];

			}

			$merged_fields = array();

			foreach ( $fields as $field ) {

				if ( array_key_exists( $field['name'], $field_injections ) ) {
					if ( array_key_exists( 'before', $field_injections[ $field['name'] ] ) ) {
						$merged_fields += $field_injections[ $field['name'] ]['before'];
					}
				}

				$merged_fields[ $field['name'] ] = $field;

				if ( array_key_exists( $field['name'], $field_injections ) ) {
					if ( array_key_exists( 'after', $field_injections[ $field['name'] ] ) ) {
						$merged_fields += $field_injections[ $field['name'] ]['after'];
					}
				}

			}

			return $merged_fields;

		}

		/**
		 * Prepare Fields.
		 *
		 * Loop through every field after we've processed various filters and
		 * injections and prepare them before being rendered.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param array $fields The form fields that were passed in to the form arguments.
		 *
		 * @return array
		 */
		private function prepare_fields( $fields ) {

			foreach ( $fields as $field ) {

				if ( 'upload' === $field['type'] ) {
					// The form has an upload field so flag it to add the enctype="multipart/form-data" attribute to the form tag.
					$this->is_upload_form = true;
				}

				$field = $this->prepare_field( $field );
				$field = $this->automate_validation( $field );

				$fields[ $field['name'] ] = $field;

			}

			return $fields;

		}

		/**
		 * Prepare Field.
		 *
		 * Makes sure that field types have specific arguments provided.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param array $field The form field.
		 *
		 * @return array
		 */
		private function prepare_field( $field ) {

			// Reserved.
			if ( 'nomad_form_id' === $field['name'] ) {
				throw new Nomad_Exception( 'The field name <code>nomad_form_id</code> is for internal use only. Please specify another name for your field.' );
			}

			switch ( $field['type'] ) {
				case 'button-group':
					if ( ! isset( $field['options'] ) ) {
						// We can't do anything with this field if options aren't provided.
						$this->nomad_error_field_missing_argument( 'options', 'button-group', $field['name'] );
					}
				break;
				case 'checkbox':
					if ( ! isset( $field['value'] ) ) {
						// A single checkbox is typically used in a boolean type of way.
						$field['value'] = 1;
					}
				break;
				case 'checkboxes':
					if ( ! isset( $field['options'] ) ) {
						// We can't do anything with this field if options aren't provided.
						$this->nomad_error_field_missing_argument( 'options', 'checkboxes', $field['name'] );
					}
				break;
				case 'hidden':
					if ( ! isset( $field['value'] ) ) {
						// Hidden fields should always have a value set.
						$this->nomad_error_field_missing_argument( 'value', 'hidden', $field['name'] );
					}
				break;
				case 'input':
					// Should not be used.
					throw new Nomad_Exception( 'The <code>input</code> field should not be used directly. Use the specific type needed instead.' );
				break;
				case 'radio':
					if ( ! isset( $field['value'] ) ) {
						// A single radio is typically used in a boolean type of way.
						$field['value'] = 1;
					}
				break;
				case 'radios':
					if ( ! isset( $field['options'] ) ) {
						// We can't do anything with this field if options aren't provided.
						$this->nomad_error_field_missing_argument( 'options', 'radios', $field['name'] );
					}
				break;
				case 'select':
					if ( ! isset( $field['options'] ) ) {
						// We can't do anything with this field if options aren't provided.
						$this->nomad_error_field_missing_argument( 'options', 'select', $field['name'] );
					}
				break;
				case 'toggle':
					if ( ! isset( $field['value'] ) ) {
						// A single toggle is typically used in a boolean type of way.
						$field['value'] = 1;
					}
				break;
				case 'toggles':
					if ( ! isset( $field['options'] ) ) {
						// We can't do anything with this field if options aren't provided.
						$this->nomad_error_field_missing_argument( 'options', 'toggles', $field['name'] );
					}
				break;
			}

			// Ensure that all the field has a value attribute.
			if ( ! array_key_exists( 'value', $field ) ) {
				$field['value'] = null;
			}

			return $field;

		}

		/**
		 * Nomad Error Field Missing Argument.
		 *
		 * Helper function for generating Nomad Errors for fields that are missing a required argument.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param string $argument   The argument that the field is missing.
		 * @param string $field_type The type of field.
		 * @param string $field_name The field name.
		 */
		private function nomad_error_field_missing_argument( $argument, $field_type, $field_name ) {

			nomad_error( sprintf( 'Missing <code>%s</code> for <code>%s</code> %s field.', $argument, $field_name, $field_type ) );

		}

		/**
		 * Automate Validation.
		 *
		 * Intelligently add choices, email, and numeric validation rules.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param array $field The individual field to automate validation for.
		 *
		 * @return array
		 */
		private function automate_validation( $field ) {

			$automated_field_types = array(
				'choices' => array(
					'am-pm',
					'button-group',
					'checkbox',
					'checkboxes',
					'country',
					'day',
					'enable-disable-button-group',
					'enabled-disabled-button-group',
					'hour',
					'minute',
					'month',
					'on-off-button-group',
					'radio',
					'radios',
					'select',
					'state',
					'toggle',
					'toggles',
					'weekday',
					'yes-no-button-group',
				),
				'email' => array(
					'email',
				),
				'numeric' => array(
					'number',
					'percentage',
					'year',
				),
			);

			// Automatically generate choices validation for select, radio and checkbox types of fields.
			if ( in_array( $field['type'], $automated_field_types['choices'] ) ) {

				// Check if the field validate array doesn't already have a 'choices' rule specified.
				if ( ! $this->field_validate_has_rule_key( 'choices', $field['validate'] ) ) {

					$valid_choices = array();

					switch ( $field['type'] ) {
						case 'am-pm':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_AMPM );
						break;
						case 'button-group':
							$valid_choices = array_keys( $field['options'] );
						break;
						case 'checkbox':
							// Submitted value must match field value.
							$valid_choices = array( $field['value'] );
						break;
						case 'checkboxes':
							// Submitted values must all be in options.
							$valid_choices = array_keys( $field['options'] );
						break;
						case 'country':
							if ( 'full' === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_COUNTRY_FULL );
							} else {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_COUNTRY );
							}
						break;
						case 'day':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_DAY );
						break;
						case 'enable-disable-button-group':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_ENABLE_DISABLE );
						break;
						case 'enabled-disabled-button-group':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_ENABLED_DISABLED );
						break;
						case 'hour':
							if ( 24 === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_HOUR24 );
							} else {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_HOUR12 );
							}
						break;
						case 'minute':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_MINUTE );
						break;
						case 'month':
							if ( 'full' === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_MONTH_FULL );
							} else if ( 'short' === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_MONTH_SHORT );
							} else {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_MONTH );
							}
						break;
						case 'on-off-button-group':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_ON_OFF );
						break;
						case 'radio':
							// Submitted value must match field value.
							$valid_choices = array( $field['value'] );
						break;
						case 'radios':
							// Submitted value must be in options.
							$valid_choices = array_keys( $field['options'] );
						break;
						case 'select':
							$valid_choices = array_keys( $field['options'] );
						break;
						case 'state':
							if ( 'full' === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_STATE_FULL );
							} else {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_STATE );
							}
						break;
						case 'toggle':
							// Submitted value must match field value.
							$valid_choices = array( $field['value'] );
						break;
						case 'toggles':
							// Submitted values must all be in options.
							$valid_choices = array_keys( $field['options'] );
						break;
						case 'weekday':
							if ( 'full' === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_WEEKDAY_FULL );
							} else if ( 'short' === $field['format'] ) {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_WEEKDAY_SHORT );
							} else {
								$valid_choices = array_keys( Nomad_Constants::CHOICES_WEEKDAY );
							}
						break;
						case 'yes-no-button-group':
							$valid_choices = array_keys( Nomad_Constants::CHOICES_YES_NO );
						break;
					}

					$field['validate'][] = 'choices:' . implode( ',', $valid_choices );

				}

			}

			// Automatically generate email validation for email fields.
			if ( in_array( $field['type'], $automated_field_types['email'] ) ) {

				// Check if the field validate array doesn't already have an 'email' rule specified.
				if ( ! $this->field_validate_has_rule_key( 'email', $field['validate'] ) ) {
					$field['validate'][] = 'email';
				}

			}

			// Automatically generate numeric validation for numeric fields.
			if ( in_array( $field['type'], $automated_field_types['numeric'] ) ) {

				// Check if the field validate array doesn't already have a 'numeric' rule specified.
				if ( ! $this->field_validate_has_rule_key( 'numeric', $field['validate'] ) ) {
					$field['validate'][] = 'numeric';
				}

			}

			return $field;

		}

		/**
		 * Prepare Submitted Form Data.
		 *
		 * Gets the submitted form data and prepares it for processing.
		 *
		 * Makes sure that if there are errors that the values of fields are
		 * populated with the submitted data.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @return array
		 */
		private function prepare_submitted_form_data() {

			switch ( $this->args['method'] ) {
				case 'POST':
					$submitted_data = $_POST;
				break;
				case 'GET':
					$submitted_data = $_GET;
				break;
				default:
					$submitted_data = $_REQUEST;
				break;
			}

			$form_data['nomad_form_id'] = $submitted_data['nomad_form_id'];

			foreach ( $this->fields as $field ) {

				switch ( $field['type'] ) {
					case 'button-group':
						if ( $field['multiple'] ) {

							if ( array_key_exists( $field['name'], $submitted_data ) ) {
								$form_data[ $field['name'] ] = $submitted_data[ $field['name'] ];
								$this->fields[ $field['name'] ]['value'] = $submitted_data[ $field['name'] ];
							} else {
								$form_data[ $field['name'] ] = array();
								$this->fields[ $field['name'] ]['value'] = array();
							}

							// Check if there are any disabled options that are initially checked and keep them checked.
							if ( array_key_exists( 'disabled_options', $field ) && ! empty( $field['disabled_options'] ) ) {
								if ( array_key_exists( 'checked_options', $field ) ) {
									foreach ( $field['disabled_options'] as $disabled_option ) {
										if ( in_array( $disabled_option, $field['checked_options'] ) ) {
											$form_data[ $field['name'] ][] = $disabled_option;
											$this->fields[ $field['name'] ]['value'][] = $disabled_option;
										}
									}
								}
							}

						} else {

							$form_data[ $field['name'] ] = $submitted_data[ $field['name'] ];

							// Check if there are any disabled options that are initially checked and keep them checked, if no value was passed.
							if ( is_null( $form_data[ $field['name'] ] ) && array_key_exists( 'disabled_options', $field ) && ! empty( $field['disabled_options'] ) ) {
								foreach ( $field['disabled_options'] as $disabled_option ) {
									if ( $disabled_option === $field['value'] ) {
										$form_data[ $field['name'] ] = $disabled_option;
									}
								}
							}

						}
					break;
					case 'checkbox':
					case 'radio':
					case 'toggle':
						if ( array_key_exists( $field['name'], $submitted_data ) ) {
							$form_data[ $field['name'] ] = $submitted_data[ $field['name'] ];
							$this->fields[ $field['name'] ]['checked'] = true;
						} else {
							$this->fields[ $field['name'] ]['checked'] = null;
						}
					break;
					case 'checkboxes':
					case 'toggles':
						if ( array_key_exists( $field['name'], $submitted_data ) ) {
							$form_data[ $field['name'] ] = $submitted_data[ $field['name'] ];
							$this->fields[ $field['name'] ]['value'] = $submitted_data[ $field['name'] ];
						} else {
							$this->fields[ $field['name'] ]['value'] = array();
						}

						// Check if there are any disabled options that are initially checked and keep them checked.
						if ( array_key_exists( 'disabled_options', $field ) && ! empty( $field['disabled_options'] ) ) {
							if ( array_key_exists( 'checked_options', $field ) ) {
								foreach ( $field['disabled_options'] as $disabled_option ) {
									if ( in_array( $disabled_option, $field['checked_options'] ) ) {
										$form_data[ $field['name'] ][] = $disabled_option;
										$this->fields[ $field['name'] ]['value'][] = $disabled_option;
									}
								}
							}
						}
					break;
					case 'radios':
						$form_data[ $field['name'] ] = $submitted_data[ $field['name'] ];

						// Check if there are any disabled options that are initially checked and keep them checked, if no value was passed.
						if ( is_null( $form_data[ $field['name'] ] ) && array_key_exists( 'disabled_options', $field ) && ! empty( $field['disabled_options'] ) ) {
							foreach ( $field['disabled_options'] as $disabled_option ) {
								if ( $disabled_option === $field['value'] ) {
									$form_data[ $field['name'] ] = $disabled_option;
								}
							}
						}
					break;
					default:
						$form_data[ $field['name'] ] = $submitted_data[ $field['name'] ];
					break;
				}

			}

			return $form_data;

		}

		/**
		 * Prepare Initial Form Data.
		 *
		 * Generate an array of form field keys and their initial values.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @return array
		 */
		private function prepare_initial_form_data() {

			$form_data = array();

			foreach ( $this->fields as $field ) {
				$form_data[ $field['name'] ] = $field['value'];
			}

			return $form_data;

		}

		/**
		 * Form ID.
		 *
		 * Returns the unique form ID.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return string
		 */
		public function form_id() {

			return $this->id;

		}

		/**
		 * Render the form.
		 *
		 * Renders the various parts of the form.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function render() {

			$this->form_open();

			$this->form_nonce();

			$this->form_fields();

			$this->form_submit_button();

			$this->form_close();

		}

		/**
		 * Form Open.
		 *
		 * Generates and outputs the opening form tag.
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function form_open() {

			$form_attributes = array(
				'id'    => $this->form_id(),
				'class' => array(
					'nomad-form',
				),
			);

			if ( $this->args['form_tag'] ) {

				if ( ! empty( $this->args['method'] ) ) {
					$form_attributes['method'] = $this->args['method'];
				}

				if ( ! empty( $this->args['action'] ) ) {
					$form_attributes['action'] = $this->args['action'];
				}

			}

			if ( ! empty( $this->args['labels_position'] ) && in_array( $this->args['labels_position'], array( 'inline', 'top' ), true ) ) {
				$form_attributes['class'][] = 'labels-position-' . $this->args['labels_position'];

				if ( 'inline' === $this->args['labels_position'] && ! empty( $this->args['labels_same_width'] ) ) {
					$form_attributes['class'][] = 'labels-same-width';
				}
			}

			if ( ! empty( $this->args['labels_alignment'] ) && in_array( $this->args['labels_alignment'], array( 'left', 'center', 'right' ), true )  ) {
				$form_attributes['class'][] = 'labels-text-' . $this->args['labels_alignment'];
			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Filter the opening form tag attributes.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $form_attributes The opening form tag attributes.
				 * @param Nomad_Form $this            The form instance.
				 */
				$form_attributes = apply_filters( 'nomad/forms/form_open/attributes', $form_attributes, $this );

				/**
				 * Filter the opening form tag attributes for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $form_attributes The opening form tag attributes.
				 * @param Nomad_Form $this            The form instance.
				 */
				$form_attributes = apply_filters( "nomad/forms/{$this->form_id()}/form_open/attributes", $form_attributes, $this );

			}

			// Class attribute is required. Make sure it didn't get filtered out.
			if ( ! array_key_exists( 'class', $form_attributes ) ) {
				$form_attributes['class'] = array();
			}

			// The `nomad-form` class is required. Make sure it didn't get filtered out.
			if ( ! in_array( 'nomad-form', $form_attributes['class'], true ) ) {
				$form_attributes['class'][] = 'nomad-form';
			}

			if ( $this->is_upload_form ) {
				$form_attributes['enctype'] = 'multipart/form-data';
			}

			$formatted_attributes = nomad_format_attributes( $form_attributes );

			if ( $this->args['form_tag'] ) {
				$output = sprintf( '<form %s>', trim( $formatted_attributes ) );
			} else {
				$output = sprintf( '<div %s>', trim( $formatted_attributes ) );
			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires before the opening form tag.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/before_form_open', $this );

				/**
				 * Fires before the opening form tag for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/before_form_open", $this );

				/**
				 * Filters the opening form tag output.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param string     $output The opening form tag output.
				 * @param Nomad_Form $this   The form instance.
				 */
				$output = apply_filters( 'nomad/forms/form_open', $output, $this );

				/**
				 * Filters the opening form tag output for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param string     $output The opening form tag output.
				 * @param Nomad_Form $this   The form instance.
				 */
				$output = apply_filters( "nomad/forms/{$this->form_id()}/form_open", $output, $this );

			}

			echo $output;

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires after the opening form tag for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/after_open", $this );

				/**
				 * Fires after the opening form tag.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/after_open', $this );

			}

		}

		/**
		 * Form Nonce.
		 *
		 * Generates the `nomad_form_nonce` nonce to make sure that the form
		 * that was displayed on the page is the one that was submitted
		 * and being processed.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function form_nonce() {

			if ( $this->args['nonce'] ) {
				wp_nonce_field( sprintf( 'nomad_form_%s', $this->form_id() ), 'nomad_form_nonce' );
			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires just after the `nomad_form_nonce` field in order to output
				 * additional hidden fields.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/hidden_fields', $this );

				/**
				 * Fires just after the `nomad_form_nonce` field in order to output
				 * additional hidden fields of a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/hidden_fields", $this );

			}

		}

		/**
		 * Form Fields.
		 *
		 * Renders the form fields.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function form_fields() {

			// Output the `nomad_form_id` hidden field so we know which form is being submitted.
			$nomad_form_id_field = array(
				'name'  => 'nomad_form_id',
				'type'  => Nomad_Form_Fields::HIDDEN,
				'value' => $this->form_id(),
			);

			Nomad_Form_Fields::render_field( $nomad_form_id_field );

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires before the form fields container.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/before_fields_container', $this );

				/**
				 * Fires before the form fields container for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/before_fields_container", $this );

			}

			echo '<div class="nomad-form-fields">';

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires before the form fields.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/before_fields', $this );

				/**
				 * Fires before the form fields for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/before_fields", $this );

			}

			foreach ( $this->fields as $field ) {

				if ( 'checkbox' === $field['type'] || 'radio' === $field['type'] || 'toggle' === $field['type'] ) {
					// If a checkbox or radio field isn't checked when the form is submitted it won't provide a value.
					// This resets the value and makes sure that the fields value attribute is always provided.
					// Whether or not it is checked is up to the 'checked' argument.
					$field['value'] = $this->fields[ $field['name'] ]['value'];
				} else {
					$field['value'] = $this->form_data[ $field['name'] ];
				}

				if ( $this->args['allow_modifications'] ) {

					/**
					 * Filters a specific form fields arguments.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param array      $field The form field.
					 * @param Nomad_Form $this  The form instance.
					 */
					$field = apply_filters( "nomad/forms/{$this->form_id()}/field/{$field['name']}/args", $field, $this );

				}

				if ( array_key_exists( 'separator', $field ) && 'before' === $field['separator'] ) {
					$this->separator();
				}

				if ( $this->args['allow_modifications'] ) {

					/**
					 * Fires before a fields output.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param Nomad_Form $this  The form instance.
					 * @param mixed      $field The field being output.
					 */
					do_action( "nomad/forms/{$this->form_id()}/field/before", $this, $field );

					/**
					 * Fires before a fields output for a specific field.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param Nomad_Form $this  The form instance.
					 * @param mixed      $field The field being output.
					 */
					do_action( "nomad/forms/{$this->form_id()}/field/{$field['name']}/before", $this, $field );

				}

				Nomad_Form_Fields::render_field( $field );

				if ( $this->args['allow_modifications'] ) {

					/**
					 * Fires after a fields output for a specific field.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param Nomad_Form $this  The form instance.
					 * @param mixed      $field The field being output.
					 */
					do_action( "nomad/forms/{$this->form_id()}/field/{$field['name']}/after", $this, $field );

					/**
					 * Fires after a fields output.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param Nomad_Form $this  The form instance.
					 * @param mixed      $field The field being output.
					 */
					do_action( "nomad/forms/{$this->form_id()}/field/after", $this, $field );

				}

				if ( array_key_exists( 'separator', $field ) && 'after' === $field['separator'] ) {
					$this->separator();
				}

			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires after the form fields for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/after_fields", $this );

				/**
				 * Fires after the form fields.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/after_fields', $this );

			}

			echo '</div>';

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires after the form fields container for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/after_fields_container", $this );

				/**
				 * Fires before the form fields container.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/after_fields_container', $this );

			}

		}

		/**
		 * Separator.
		 *
		 * Displays a field separator.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function separator() {

			echo '<div class="nomad-field-separator"><hr /></div>';

		}

		/**
		 * Form Submit Button.
		 *
		 * Displays the form submit button.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function form_submit_button() {

			if ( ! $this->args['submit_button'] ) {
				return false;
			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires before the form actions.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/before_form_actions', $this );

				/**
				 * Fires before the form actions for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/before_form_actions", $this );

			}

			echo '<div class="nomad-form-actions">';

			$submit_attributes = array(
				'type' => 'submit',
			);

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Filters the submit button attributes.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $submit_attributes The submit button attributes.
				 * @param Nomad_Form $this              The form instance.
				 */
				$submit_attributes = apply_filters( 'nomad/forms/submit_attributes', $submit_attributes, $this );

				/**
				 * Filters the submit button attributes for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $submit_attributes The submit button attributes.
				 * @param Nomad_Form $this              The form instance.
				 */
				$submit_attributes = apply_filters( "nomad/forms/{$this->form_id()}/submit_attributes", $submit_attributes, $this );

			}

			$submit_attributes['class'][] = 'nomad-button';
			$submit_attributes['class'][] = 'primary';

			$formatted_attributes = nomad_format_attributes( $submit_attributes );

			$output = sprintf( '<button %s>%s</button>', trim( $formatted_attributes ), $this->args['submit_text'] );

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires before the submit button.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/before_submit_button', $this );

				/**
				 * Fires before the submit button for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/before_submit_button", $this );

			}

			echo $output;

			if ( $this->args['reset_button'] ) {
				$this->form_reset_button();
			}

			if ( $this->args['cancel_button'] ) {
				$this->form_cancel_button();
			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires after the submit button for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/after_submit_button", $this );

				/**
				 * Fires after the submit button.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/after_submit_button', $this );

			}

			echo '</div>';

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires after the form actions for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/after_form_actions", $this );

				/**
				 * Fires after the form actions.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/after_form_actions', $this );

			}

		}

		/**
		 * Form Reset Button.
		 *
		 * Displays the form reset button.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function form_reset_button() {

			$reset_attributes = array(
				'type' => 'reset',
			);

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Filters the reset button attributes.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $reset_attributes The reset button attributes.
				 * @param Nomad_Form $this             The form instance.
				 */
				$reset_attributes = apply_filters( 'nomad/forms/reset_attributes', $reset_attributes, $this );

				/**
				 * Filters the reset button attributes for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $reset_attributes The reset button attributes.
				 * @param Nomad_Form $this             The form instance.
				 */
				$reset_attributes = apply_filters( "nomad/forms/{$this->form_id()}/reset_attributes", $reset_attributes, $this );

			}

			$reset_attributes['class'][] = 'nomad-button';
			$reset_attributes['class'][] = 'secondary';

			$formatted_attributes = nomad_format_attributes( $reset_attributes );

			$output = sprintf( '<button %s>%s</button>', trim( $formatted_attributes ), $this->args['reset_text'] );

			echo $output;

		}

		/**
		 * Form Cancel Button.
		 *
		 * Displays the form cancel button.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function form_cancel_button() {

			$cancel_attributes = array(
				'href' => $this->args['cancel_href'],
			);

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Filters the cancel button attributes.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $cancel_attributes The cancel button attributes.
				 * @param Nomad_Form $this              The form instance.
				 */
				$cancel_attributes = apply_filters( 'nomad/forms/cancel_attributes', $cancel_attributes, $this );

				/**
				 * Filters the cancel button attributes for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param array      $cancel_attributes The cancel button attributes.
				 * @param Nomad_Form $this              The form instance.
				 */
				$cancel_attributes = apply_filters( "nomad/forms/{$this->form_id()}/cancel_attributes", $cancel_attributes, $this );

			}

			$cancel_attributes['class'][] = 'nomad-button';
			$cancel_attributes['class'][] = 'transparent';

			$formatted_attributes = nomad_format_attributes( $cancel_attributes );

			$output = sprintf( '<a %s>%s</a>', trim( $formatted_attributes ), $this->args['cancel_text'] );

			echo $output;

		}

		/**
		 * Form Close.
		 *
		 * Generates and outputs the closing form tag.
		 *
		 * @since 1.0.0
		 * @access private
		 */
		public function form_close() {

			if ( $this->args['form_tag'] ) {
				$output = '</form>';
			} else {
				$output = '</div>';
			}

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Filters the closing form tag output.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param string     $output The closing form tag output.
				 * @param Nomad_Form $this   The form instance.
				 */
				$output = apply_filters( 'nomad/forms/form_close', $output, $this );

				/**
				 * Filters the closing form tag output for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param string     $output The closing form tag output.
				 * @param Nomad_Form $this   The form instance.
				 */
				$output = apply_filters( "nomad/forms/{$this->form_id()}/form_close", $output, $this );

				/**
				 * Fires before the closing form tag for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/before_form_close", $this );

				/**
				 * Fires before the closing form tag.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/before_form_close', $this );

			}

			echo $output;

			if ( $this->args['allow_modifications'] ) {

				/**
				 * Fires after the closing form tag for a specific form.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/after_form_close", $this );

				/**
				 * Fires after the closing form tag.
				 *
				 * Can only be used if the form allows modifications.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/after_form_close', $this );

			}

		}

		/**
		 * Process the form.
		 *
		 * Process the form fields and validate them. If successfully validated,
		 * trigger the callback function and set the form status to valid. If
		 * there was an error, get the error messages and set the form status to
		 * invalid.
		 *
		 * @since 1.0.0
		 * @access public
		 */
		public function process() {

			if ( $this->args['method'] === $_SERVER['REQUEST_METHOD'] ) {

				if ( $this->form_id() !== $this->form_data['nomad_form_id'] ) {
					// Don't do anything if this form isn't the one that should be processed.
					return false;
				}

				$this->processed = true;

				$validated = $this->validate();

				if ( $validated instanceof Nomad_Validate ) { // Can return false if nonce validation fails.

					$is_valid = $validated->is_valid();

					if ( $this->args['allow_modifications'] ) {

						/**
						 * Filters whether or not the form submission is valid.
						 *
						 * Can only be used if the form allows modifications.
						 *
						 * @since 1.0.0
						 *
						 * @param boolean    $is_valid Whether or not the form submission is valid.
						 * @param Nomad_Form $this     The form instance.
						 */
						$is_valid = apply_filters( "nomad/forms/{$this->form_id()}/is_valid", $is_valid, $this );

					}

					if ( $is_valid ) {

						if ( is_callable( $this->args['callback'] ) ) {
							call_user_func_array( $this->args['callback'], array( $this->form_data ) );
						}

						$this->is_valid = true;

						/**
						 * Fires when a specific form is submitted successfully.
						 *
						 * @since 1.0.0
						 *
						 * @param Nomad_Form $this The form instance.
						 */
						do_action( "nomad/forms/{$this->form_id()}/success", $this );

					} else {

						$this->is_valid = false;
						$this->messages = $validated->get_all_error_messages();

						/**
						 * Fires when a specific form submission encounters an error.
						 *
						 * @since 1.0.0
						 *
						 * @param Nomad_Form $this The form instance.
						 */
						do_action( "nomad/forms/{$this->form_id()}/error", $this );

					}

				}

				/**
				 * Fires every time a form is submitted, regardless of whether
				 * or not it was valid.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( 'nomad/forms/process', $this );

				/**
				 * Fires every time a specific form is submitted regardless of
				 * whether or not it was valid.
				 *
				 * @since 1.0.0
				 *
				 * @param Nomad_Form $this The form instance.
				 */
				do_action( "nomad/forms/{$this->form_id()}/process", $this );

			}

		}

		/**
		 * Validate.
		 *
		 * Loops through the form fields and validates them. If a field is
		 * required it will be validated. If a field is not required, then it
		 * will only be validated if a value was provided.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @return Nomad_Validate
		 */
		private function validate() {

			$nonce_validated = $this->validate_nonce();

			if ( ! $nonce_validated ) {
				return false;
			}

			$validate_array = array();

			foreach ( $this->fields as $field ) {

				$field_required = false;

				if ( $this->field_validate_has_rule_key( 'required', $field['validate'] ) ) {
					$field_required = true;
				}

				// Only validate if the field is required, or if the field isn't required but it was provided (not empty).
				if ( $field_required || ( ! $field_required && ! empty( $this->form_data[ $field['name'] ] ) ) ) {
					$validate_array[ $field['name'] ] = array(
						'key'   => $field['name'],
						'label' => $field['label'],
						'value' => $this->form_data[ $field['name'] ],
						'rules' => $field['validate'],
					);

					if ( isset( $field['error_messages'] ) ) {
						$validate_array[ $field['name'] ]['error_messages'] = $field['error_messages'];
					}
				}

			}

			$validated = nomad_validate( $validate_array );

			return $validated;

		}

		/**
		 * Field Validate Has Rule Key.
		 *
		 * Whether or not the provided validate ruleset has a specific rule key.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param string $key   The rule key to check.
		 * @param array  $rules The ruleset to look in for the rule key.
		 *
		 * @return boolean
		 */
		private function field_validate_has_rule_key( $key, $rules ) {

			if ( empty( $rules ) ) {
				return false;
			}

			foreach ( $rules as $rule_key ) {

				// Some rules have arguments that are provided after ':' in the $rule_key.
				if ( strpos( $rule_key, ':' ) ) {
					// We set the explode limit to 1 so that we only split the string by the first occurrence, allowing possible regex rules to contain anything.
					list( $rule_key, $argument ) = explode( ':', $rule_key, 2 );
				}

				if ( $key === $rule_key ) {
					return true;
				}

			}

			return false;

		}

		/**
		 * Validate Nonce.
		 *
		 * If configured, validate the `nomad_form_nonce` nonce to make sure that
		 * the form that was displayed on the page is the one that was submitted
		 * and being processed.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @return boolean
		 */
		private function validate_nonce() {

			if ( $this->args['nonce'] ) {

				switch ( $this->args['method'] ) {
					case 'POST':
						$nomad_form_nonce = $_POST['nomad_form_nonce'];
					break;
					case 'GET':
						$nomad_form_nonce = $_GET['nomad_form_nonce'];
					break;
					default:
						$nomad_form_nonce = $_REQUEST['nomad_form_nonce'];
					break;
				}

				if ( ! wp_verify_nonce( $nomad_form_nonce, sprintf( 'nomad_form_%s', $this->form_id() ) ) ) {
					$this->is_valid = false;
					$this->messages = array( $this->args['nonce_message'] );
					return false;
				}

			}

			return true;

		}

		/**
		 * Is Processed.
		 *
		 * Whether or not the form has been submitted and processed.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return boolean
		 */
		public function is_processed() {

			if ( $this->form_id() !== $this->form_data['nomad_form_id'] ) {
				// Don't do anything if this form isn't the one that should be processed.
				return false;
			}

			return $this->processed;

		}

		/**
		 * Messages.
		 *
		 * Outputs the form success or error messages.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return false
		 */
		public function messages() {

			if ( $this->form_id() !== $this->form_data['nomad_form_id'] ) {
				// Don't do anything if this form isn't the one that should be processed.
				return false;
			}

			if ( ! $this->is_processed() ) {
				// Don't do anything if we haven't processed the form yet.
				return false;
			}

			if ( $this->is_valid ) {

				$success_message = $this->args['success_message'];

				if ( $this->args['allow_modifications'] ) {

					/**
					 * Filter the form submission success message for a specific form.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param string $success_message The success message for the form.
					 */
					$success_message = apply_filters( "nomad/forms/{$this->form_id()}/success_message", $success_message );

				}

				echo sprintf( '<div class="nomad-success">%s</div>', $success_message );

			} else {

				$error_message = $this->args['error_message'];

				if ( $this->args['allow_modifications'] ) {

					/**
					 * Filter the form submission error message for a specific form.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param string $error_message The error message for the form.
					 */
					$error_message = apply_filters( "nomad/forms/{$this->form_id()}/error_message", $error_message );

				}

				echo '<div class="nomad-error">';

				if ( ! empty( $error_message ) ) {
					echo sprintf( '<p>%s</p>', $error_message );
				}

				if ( $this->args['allow_modifications'] ) {

					/**
					 * Filter the form submission error messages for a specific form.
					 *
					 * Can only be used if the form allows modifications.
					 *
					 * @since 1.0.0
					 *
					 * @param array $error_messages The error messages generated when validating form fields.
					 */
					$error_messages = apply_filters( "nomad/forms/{$this->form_id()}/error_messages", $this->messages );

				}

				echo '<ul class="nomad-error-list">';

				foreach ( $error_messages as $message ) {
					echo sprintf( '<li>%s</li>', $message );
				}

				echo '</ul>';
				echo '</div>';

			}

		}

	}

}
