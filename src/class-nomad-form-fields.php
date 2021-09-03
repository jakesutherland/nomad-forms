<?php
/**
 * Nomad Form Fields class file.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms;

use function Nomad\Helpers\nomad_error;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

if ( ! class_exists( __NAMESPACE__ . '\\Nomad_Form_Fields' ) ) {

	/**
	 * Nomad Form Fields Class.
	 *
	 * Prepares and renders form fields, loads their respective class files.
	 *
	 * @since 1.0.0
	 * @final
	 */
	final class Nomad_Form_Fields {

		/**
		 * AM PM Field Key.
		 *
		 * @since 1.0.0
		 */
		const AM_PM = 'am-pm';

		/**
		 * Button Group Field Key.
		 *
		 * @since 1.0.0
		 */
		const BUTTON_GROUP = 'button-group';

		/**
		 * Checkbox Field Key.
		 *
		 * @since 1.0.0
		 */
		const CHECKBOX = 'checkbox';

		/**
		 * Checkboxes Field Key.
		 *
		 * @since 1.0.0
		 */
		const CHECKBOXES = 'checkboxes';

		/**
		 * Country Field Key.
		 *
		 * @since 1.0.0
		 */
		const COUNTRY = 'country';

		/**
		 * Date Field Key.
		 *
		 * @since 1.0.0
		 */
		const DATE = 'date';

		/**
		 * Day Field Key.
		 *
		 * @since 1.0.0
		 */
		const DAY = 'day';

		/**
		 * Email Field Key.
		 *
		 * @since 1.0.0
		 */
		const EMAIL = 'email';

		/**
		 * Enable Disable Button Group Field Key.
		 *
		 * @since 1.0.0
		 */
		const ENABLE_DISABLE_BUTTON_GROUP = 'enable-disable-button-group';

		/**
		 * Enabled Disabled Button Group Field Key.
		 *
		 * @since 1.0.0
		 */
		const ENABLED_DISABLED_BUTTON_GROUP = 'enabled-disabled-button-group';

		/**
		 * File Field Key.
		 *
		 * Alias for Upload Field.
		 *
		 * @since 1.0.0
		 */
		const FILE = 'upload';

		/**
		 * Hidden Field Key.
		 *
		 * @since 1.0.0
		 */
		const HIDDEN = 'hidden';

		/**
		 * Hour Field Key.
		 *
		 * @since 1.0.0
		 */
		const HOUR = 'hour';

		/**
		 * Input Field Key.
		 *
		 * @since 1.0.0
		 */
		const INPUT = 'input';

		/**
		 * Minute Field Key.
		 *
		 * @since 1.0.0
		 */
		const MINUTE = 'minute';

		/**
		 * Month Field Key.
		 *
		 * @since 1.0.0
		 */
		const MONTH = 'month';

		/**
		 * Number Field Key.
		 *
		 * @since 1.0.0
		 */
		const NUMBER = 'number';

		/**
		 * On Off Button Group Field Key.
		 *
		 * @since 1.0.0
		 */
		const ON_OFF_BUTTON_GROUP = 'on-off-button-group';

		/**
		 * Password Field Key.
		 *
		 * @since 1.0.0
		 */
		const PASSWORD = 'password';

		/**
		 * Percentage Field Key.
		 *
		 * @since 1.0.0
		 */
		const PERCENTAGE = 'percentage';

		/**
		 * Phone Field Key.
		 *
		 * @since 1.0.0
		 */
		const PHONE = 'phone';

		/**
		 * Radio Field Key.
		 *
		 * @since 1.0.0
		 */
		const RADIO = 'radio';

		/**
		 * Radios Field Key.
		 *
		 * @since 1.0.0
		 */
		const RADIOS = 'radios';

		/**
		 * Select Field Key.
		 *
		 * @since 1.0.0
		 */
		const SELECT = 'select';

		/**
		 * State Field Key.
		 *
		 * @since 1.0.0
		 */
		const STATE = 'state';

		/**
		 * Text Field Key.
		 *
		 * @since 1.0.0
		 */
		const TEXT = 'text';

		/**
		 * Textarea Field Key.
		 *
		 * @since 1.0.0
		 */
		const TEXTAREA = 'textarea';

		/**
		 * Time Field Key.
		 *
		 * @since 1.0.0
		 */
		const TIME = 'time';

		/**
		 * Toggle Field Key.
		 *
		 * @since 1.0.0
		 */
		const TOGGLE = 'toggle';

		/**
		 * Toggles Field Key.
		 *
		 * @since 1.0.0
		 */
		const TOGGLES = 'toggles';

		/**
		 * Upload Field Key.
		 *
		 * @since 1.0.0
		 */
		const UPLOAD = 'upload';

		/**
		 * URL Field Key.
		 *
		 * @since 1.0.0
		 */
		const URL = 'url';

		/**
		 * Weekday Field Key.
		 *
		 * @since 1.0.0
		 */
		const WEEKDAY = 'weekday';

		/**
		 * Year Field Key.
		 *
		 * @since 1.0.0
		 */
		const YEAR = 'year';

		/**
		 * Yes No Button Group Field Key.
		 *
		 * @since 1.0.0
		 */
		const YES_NO_BUTTON_GROUP = 'yes-no-button-group';

		/**
		 * Registered Fields.
		 *
		 * List of registered field keys and their respective class names.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var array
		 */
		private static $registered_fields = array(
			'am-pm'                         => 'Am_Pm_Field',
			'button-group'                  => 'Button_Group_Field',
			'checkbox'                      => 'Checkbox_Field',
			'checkboxes'                    => 'Checkboxes_Field',
			'country'                       => 'Country_Field',
			'date'                          => 'Date_Field',
			'day'                           => 'Day_Field',
			'email'                         => 'Email_Field',
			'enable-disable-button-group'   => 'Enable_Disable_Button_Group_Field',
			'enabled-disabled-button-group' => 'Enabled_Disabled_Button_Group_Field',
			'hidden'                        => 'Hidden_Field',
			'hour'                          => 'Hour_Field',
			'input'                         => 'Input_Field',
			'minute'                        => 'Minute_Field',
			'month'                         => 'Month_Field',
			'number'                        => 'Number_Field',
			'on-off-button-group'           => 'On_Off_Button_Group_Field',
			'password'                      => 'Password_Field',
			'percentage'                    => 'Percentage_Field',
			'phone'                         => 'Phone_Field',
			'radio'                         => 'Radio_Field',
			'radios'                        => 'Radios_Field',
			'select'                        => 'Select_Field',
			'state'                         => 'State_Field',
			'text'                          => 'Text_Field',
			'textarea'                      => 'Textarea_Field',
			'time'                          => 'Time_Field',
			'toggle'                        => 'Toggle_Field',
			'toggles'                       => 'Toggles_Field',
			'upload'                        => 'Upload_Field',
			'url'                           => 'Url_Field',
			'weekday'                       => 'Weekday_Field',
			'year'                          => 'Year_Field',
			'yes-no-button-group'           => 'Yes_No_Button_Group_Field',
		);

		/**
		 * Field Dependencies.
		 *
		 * List of field keys and their respective dependencies.
		 *
		 * When a field extends a more basic field (input, select, etc) we need
		 * to load the basic field first.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @var array
		 */
		private static $field_dependencies = array(
			// Select.
			'am-pm'   => array( 'select' ),
			'day'     => array( 'select' ),
			'country' => array( 'select' ),
			'hour'    => array( 'select' ),
			'minute'  => array( 'select' ),
			'month'   => array( 'select' ),
			'state'   => array( 'select' ),
			'weekday' => array( 'select' ),
			'year'    => array( 'select' ),

			// Input.
			'date'     => array( 'input' ),
			'email'    => array( 'input' ),
			'hidden'   => array( 'input' ),
			'number'   => array( 'input' ),
			'password' => array( 'input' ),
			'phone'    => array( 'input' ),
			'text'     => array( 'input' ),
			'time'     => array( 'input' ),
			'upload'   => array( 'input' ),
			'url'      => array( 'input' ),

			// Button Group.
			'enable-disable-button-group'   => array( 'button-group' ),
			'enabled-disabled-button-group' => array( 'button-group' ),
			'on-off-button-group'           => array( 'button-group' ),
			'yes-no-button-group'           => array( 'button-group' ),

			// Other.
			'percentage' => array( 'number', 'input' ),
		);

		/**
		 * Render Field.
		 *
		 * Loads the field class file (and any dependencies) and renders the field
		 * by calling the necessary methods.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $args Field arguments.
		 *
		 * @return false|void
		 */
		public static function render_field( $args ) {

			if ( ! self::is_valid_field_type( $args['type'] ) ) {
				nomad_error( sprintf( 'Invalid field type provided for <code>%s</code>.', $args['name'] ) );
				return false;
			}

			$defaults = array(
				'validate'         => array(),
				'show_field_label' => true,
				'value'            => null,
			);
			$args = wp_parse_args( $args, $defaults );

			$args['dependencies'] = self::load_field_dependencies( $args['type'] );

			$field_class_name = self::get_field_class_name( $args['type'] );

			if ( ! class_exists( $field_class_name ) ) {
				require_once NOMAD_FORMS_FIELDS_PATH . $args['type'] . '-field.php';
			}

			$field = new $field_class_name( $args );

			$field->open_container();

			$field->label();

			$field->before();
			$field->render();
			$field->after();

			$field->close_container();

		}

		/**
		 * Load Field Dependencies.
		 *
		 * Loads any field dependencies for the specified field type. Utilizes
		 * the `$field_dependencies` array to determine what other field types
		 * need to be loaded first.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param string $field_type The field type to load dependencies for.
		 *
		 * @return false|void
		 */
		private static function load_field_dependencies( $field_type ) {

			if ( ! array_key_exists( $field_type, self::$field_dependencies ) ) {
				return false;
			}

			$field_dependencies = self::$field_dependencies[ $field_type ];

			if ( ! empty( $field_dependencies ) && is_array( $field_dependencies ) ) {

				foreach ( $field_dependencies as $field_dependency ) {
					if ( ! class_exists( __NAMESPACE__ . '\\' . self::$registered_fields[ $field_dependency ] ) ) {
						require_once NOMAD_FORMS_FIELDS_PATH . $field_dependency . '-field.php';
					}
				}

			}

			return $field_dependencies;

		}

		/**
		 * Get Field Class Name.
		 *
		 * Generates the class name for the specified field type.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string $field_type The field type to generate the class name for.
		 *
		 * @return string
		 */
		private static function get_field_class_name( $field_type ) {

			return __NAMESPACE__ . '\\Fields\\' . self::$registered_fields[ $field_type ];

		}

		/**
		 * Is Valid Field Type.
		 *
		 * Determines whether or not the provided type is a valid field type.
		 * Utilizes the `$registered_fields` list.
		 *
		 * @since 1.0.0
		 * @access private
		 *
		 * @param string $type The field type to determine whether or not it is valid.
		 *
		 * @return boolean
		 */
		private static function is_valid_field_type( $type ) {

			return array_key_exists( $type, self::$registered_fields );

		}

	}

}
