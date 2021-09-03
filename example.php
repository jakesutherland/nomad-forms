<?php

use Nomad\Forms\Nomad_Form_Fields;

use function Nomad\Forms\nomad_form;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

function my_callback( $form_data ) {

	var_dump( $form_data );

}

echo '<h1>Example Form</h1>';

$form_fields = array(
	'example_am_pm' => array(
		'name'  => 'example_am_pm',
		'label' => 'AM PM Field',
		'type'  => Nomad_Form_Fields::AM_PM,
	),
	'example_button_group' => array(
		'name'    => 'example_button_group',
		'label'   => 'Button Group Field',
		'type'    => Nomad_Form_Fields::BUTTON_GROUP,
		'options' => array(
			'a' => 'Choice A',
			'b' => 'Choice B',
			'c' => 'Choice C',
		),
	),
	'example_checkbox' => array(
		'name'          => 'example_checkbox',
		'label'         => 'Checkbox Field',
		'type'          => Nomad_Form_Fields::CHECKBOX,
		'checkbox_text' => 'Checkbox text.',
	),
	'example_checkboxes' => array(
		'name'    => 'example_checkboxes',
		'label'   => 'Checkboxes Field',
		'type'    => Nomad_Form_Fields::CHECKBOXES,
		'options' => array(
			'a' => 'Choice A',
			'b' => 'Choice B',
			'c' => 'Choice C',
		),
	),
	'example_country' => array(
		'name'  => 'example_country',
		'label' => 'Country Field',
		'type'  => Nomad_Form_Fields::COUNTRY,
	),
	'example_date' => array(
		'name'  => 'example_date',
		'label' => 'Date Field',
		'type'  => Nomad_Form_Fields::DATE,
	),
	'example_day' => array(
		'name'  => 'example_day',
		'label' => 'Day Field',
		'type'  => Nomad_Form_Fields::DAY,
	),
	'example_email' => array(
		'name'  => 'example_email',
		'label' => 'Email Field',
		'type'  => Nomad_Form_Fields::EMAIL,
	),
	'example_enable_disable_button_group' => array(
		'name'  => 'example_enable_disable_button_group',
		'label' => 'Enable Disable Button Group',
		'type'  => Nomad_Form_Fields::ENABLE_DISABLE_BUTTON_GROUP,
	),
	'example_enabled_disabled_button_group' => array(
		'name'  => 'example_enabled_disabled_button_group',
		'label' => 'Enabled Disabled Button Group',
		'type'  => Nomad_Form_Fields::ENABLED_DISABLED_BUTTON_GROUP,
	),
	'example_hidden' => array(
		'name'  => 'example_hidden',
		'label' => 'Hidden Field',
		'type'  => Nomad_Form_Fields::HIDDEN,
		'value' => 'hidden_value',
	),
	'example_hour' => array(
		'name'  => 'example_hour',
		'label' => 'Hour Field',
		'type'  => Nomad_Form_Fields::HOUR,
	),
	'example_minute' => array(
		'name'  => 'example_minute',
		'label' => 'Minute Field',
		'type'  => Nomad_Form_Fields::MINUTE,
	),
	'example_month' => array(
		'name'  => 'example_month',
		'label' => 'Month Field',
		'type'  => Nomad_Form_Fields::MONTH,
	),
	'example_number' => array(
		'name'  => 'example_number',
		'label' => 'Number Field',
		'type'  => Nomad_Form_Fields::NUMBER,
	),
	'example_on_off_button_group' => array(
		'name'  => 'example_on_off_button_group',
		'label' => 'On Off Button Group',
		'type'  => Nomad_Form_Fields::ON_OFF_BUTTON_GROUP,
	),
	'example_password' => array(
		'name'  => 'example_password',
		'label' => 'Password Field',
		'type'  => Nomad_Form_Fields::PASSWORD,
	),
	'example_percentage' => array(
		'name'  => 'example_percentage',
		'label' => 'Percentage Field',
		'type'  => Nomad_Form_Fields::PERCENTAGE,
	),
	'example_phone' => array(
		'name'  => 'example_phone',
		'label' => 'Phone Field',
		'type'  => Nomad_Form_Fields::PHONE,
	),
	'example_radio' => array(
		'name'       => 'example_radio',
		'label'      => 'Radio Field',
		'type'       => Nomad_Form_Fields::RADIO,
		'radio_text' => 'Radio text.',
	),
	'example_radios' => array(
		'name'    => 'example_radios',
		'label'   => 'Radios Field',
		'type'    => Nomad_Form_Fields::RADIOS,
		'options' => array(
			'a' => 'Choice A',
			'b' => 'Choice B',
			'c' => 'Choice C',
		),
	),
	'example_select' => array(
		'name'    => 'example_select',
		'label'   => 'Select Field',
		'type'    => Nomad_Form_Fields::SELECT,
		'options' => array(
			'a' => 'Choice A',
			'b' => 'Choice B',
			'c' => 'Choice C',
		),
	),
	'example_state' => array(
		'name'  => 'example_state',
		'label' => 'State Field',
		'type'  => Nomad_Form_Fields::STATE,
	),
	'example_text' => array(
		'name'  => 'example_text',
		'label' => 'Text Field',
		'type'  => Nomad_Form_Fields::TEXT,
	),
	'example_textarea' => array(
		'name'  => 'example_textarea',
		'label' => 'Textarea Field',
		'type'  => Nomad_Form_Fields::TEXTAREA,
	),
	'example_time' => array(
		'name'  => 'example_time',
		'label' => 'Time Field',
		'type'  => Nomad_Form_Fields::TIME,
	),
	'example_toggle' => array(
		'name'  => 'example_toggle',
		'label' => 'Toggle Field',
		'type'  => Nomad_Form_Fields::TOGGLE,
	),
	'example_toggles' => array(
		'name'    => 'example_toggles',
		'label'   => 'Toggles Field',
		'type'    => Nomad_Form_Fields::TOGGLES,
		'options' => array(
			'a' => 'Choice A',
			'b' => 'Choice B',
			'c' => 'Choice C',
		),
	),
	'example_upload' => array(
		'name'  => 'example_upload',
		'label' => 'Upload Field',
		'type'  => Nomad_Form_Fields::UPLOAD,
	),
	'example_url' => array(
		'name'  => 'example_url',
		'label' => 'Url Field',
		'type'  => Nomad_Form_Fields::URL,
	),
	'example_weekday' => array(
		'name'  => 'example_weekday',
		'label' => 'Weekday Field',
		'type'  => Nomad_Form_Fields::WEEKDAY,
	),
	'example_year' => array(
		'name'  => 'example_year',
		'label' => 'Year Field',
		'type'  => Nomad_Form_Fields::YEAR,
	),
	'example_yes_no_button_group' => array(
		'name'  => 'example_yes_no_button_group',
		'label' => 'Yes No Button Group',
		'type'  => Nomad_Form_Fields::YES_NO_BUTTON_GROUP,
	),
);

$form_args = array(
	'callback' => 'my_callback',
	'fields'   => $form_fields,
);

$example_form = nomad_form( 'nomad-example', $form_args );

$example_form->process();

if ( $example_form->is_processed() ) {
	$example_form->messages();
}

$example_form->render();
