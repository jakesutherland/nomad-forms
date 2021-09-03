# Nomad Forms

A WordPress PHP Composer Package that provides you with an easy way to display and process forms, render and validate fields, and display error/success messages.

## Installation

You can install Nomad Forms in your project by using composer.

```
$ composer require jakesutherland/nomad-forms
```

## Dependencies

Nomad Forms depends on the following packages:

- [Nomad Helpers](https://github.com/jakesutherland/nomad-helpers) for various functions, constants, and utilities.
- [Nomad Validate](https://github.com/jakesutherland/nomad-validate) for validating form submissions and generating error messages.

If for some reason you didn't install Nomad Forms via Composer as a required package in your project, you will still need to run `composer install` to install it's dependencies as they are not included in the repository.

## Documentation

Nomad Forms is a full-featured form generator for processing, validating and displaying forms on your website. You can use Nomad Forms on the front-end or in the admin.

### Example Usage
The `nomad_form()` function is used to get your form configured and set up.

The first parameter is your form ID. In our example below, `my_form_id` is used as a unique identifer for this form.

The second parameter is your form arguments. This is where you can configure your form and define your form fields.

```
$my_form = nomad_form( 'my_form_id', array(
	'method'   => 'POST',
	'callback' => 'my_form_callback',
	'fields'   => array(
		// Add your form fields here...
	),
) );
```

Alternatively, you can use the `nomad_fields()` function if you want to output just form fields (and still utilize Nomad Forms Theme styles). Basically is a helper function that sets various form arguments to what would be needed in order to just output form fields. See below:

```
function nomad_fields( $id, $args ) {

	$defaults = array(
		'action'        => null,
		'method'        => null,
		'nonce'         => true,
		'form_tag'      => false,
		'submit_button' => false,
		'reset_button'  => false,
		'cancel_button' => false,
		'callback'      => null,
	);
	$args = wp_parse_args( $args, $defaults );

	return nomad_form( $id, $args );

}
```

When using this by itself, you would be expected to handle all form processing and validation manually.

Common use case would be adding custom fields to a post type in the WordPress admin and then processing those fields on `save_post`.

## Available Form Arguments

You can customize how a form behaves with many different form arguments available.

*Note: The only required form argument is `fields`. All other form arguments are optional and have defaults set.*

### `action`
Type: `string`

Default: `null`

Sets the form action if you want the form to be submitted to a specific URL.

*This is not recommended unless you plan on handling the form submission yourself. Note that the variable you assign `nomad_form()` to is only available on the page it is defined, so the other functions likely won't be available somewhere else in your codebase. It is highly recommended that you handle everything on the same page that the form is being displayed on.*

### `allow_modifications`

Type: `boolean`

Default: `true`

Whether or not you want to allow the potential for other plugins or pieces of code to modify the form.

When set to `true`, the form can be modified through various hooks, filters and fields added through injections. If set to `false`, the majority of hooks and filters won't be triggered and form injections will not add any fields.

### `automate_validation`

Type: `boolean`

Default: `true`

Whether or not you want to allow automatic validation of form fields.

When set to `true`, automatically validates values for "choice" type fields (such as button groups, checkboxes, radios, selects, etc) and makes sure that only the options that were available can be used, email fields are valid emails, and numeric fields only contain numbers.

If you already specify any of these rule keys in your field validation, automatic validation will not override them.

### `callback`

Type: `callable`

Default: `null`

The function to be called to process the form when it is successful. Must be a valid callback function.

### `cancel_button`

Type: `boolean`

Default: `false`

Whether or not to display a "Cancel" button at the bottom of the form.

### `cancel_href`

Type: `string`

Default: `home_url( '/' );` *(your WordPress installs homepage)*

The URL to go to when the Cancel button is clicked.

### `cancel_text`

Type: `string`

Default: `Cancel`

The Cancel button text.

### `error_message`

Type: `string`

Default: `Sorry, there was a problem submitting your form:`

When the form is submitted and there is an error, this is the text to be displayed above the list of error messages.

### `fields`

Type: `array`

Required

The list of form fields to be displayed, submitted validated, and processed.

### `form_tag`

Type: `boolean`

Default: `true`

Whether or not the form fields should be rendered inside a `<form>...</form>` tag. If set to `false`, the form fields will be rendered inside a `<div>...</div>` instead.

### `labels_alignment`

Type: `string`

Default: `left`

Possible Values: `left` `center` `right`

Determines the text alignment of the field labels.

### `labels_position`

Type: `string`

Default: `top`

Possible Values: `inline` `top`

Determines where the field labels are displayed in relation to its field.

### `method`

Type: `string`

Default: `POST`

Possible Values: `POST` `GET`

Determines the form method that is used when the fields are submitted.

### `nonce`

Type: `boolean`

Default: `true`

Whether or not to create and use a `nomad_form_nonce` to make sure that the form being processed is the one that was just submitted.

### `nonce_message`

Type: `string`

Default: `Invalid form submission. Please try again.`

The text to be displayed if the nonce verification fails and the form submission was invalid.

### `reset_button`

Type: `boolean`

Default: `false`

Whether or not to display a "Reset" button at the bottom of the form.

### `reset_text`

Type: `string`

Default: `Reset`

The Reset button text.

### `submit_button`

Type: `boolean`

Default: `true`

Whether or not to display a "Submit" button at the bottom of the form.

### `submit_text`

Type: `string`

Default: `Submit`

The Submit button text.

### `success_message`

Type: `string`

Default: `Success! Your form has been submitted.`

When the form is submitted and there is an error, this is the text to be displayed above the list of error messages.

## Available Fields

Each field is defined as a constant in the `Nomad_Form_Fields` class. Below is a list of all available fields and an example of how it would be registered.

Alternatively, you can take a look at [example.php](https://github.com/jakesutherland/nomad-forms/blob/master/example.php) to test out a Nomad Form that contains one of every field type available.

### AM PM Field

Usage: `Nomad_Form_Fields::AM_PM`

Select dropdown menu with AM and PM options.

```
'example_am_pm' => array(
	'name'  => 'example_am_pm',
	'label' => 'AM PM Field',
	'type'  => Nomad_Form_Fields::AM_PM,
),
```

### Button Group Field

Usage: `Nomad_Form_Fields::BUTTON_GROUP`

Styled radio buttons. If the `multiple` argument is provided and set to `true` then the styled buttons will behave like checkboxes.

```
'example_button_group' => array(
	'name'     => 'example_button_group',
	'label'    => 'Button Group Field',
	'type'     => Nomad_Form_Fields::BUTTON_GROUP,
	'multiple' => true,
	'options'  => array(
		'a' => 'Choice A',
		'b' => 'Choice B',
		'c' => 'Choice C',
	),
),
```

### Checkbox Field

Usage: `Nomad_Form_Fields::CHECKBOX`

A single checkbox.

```
'example_checkbox' => array(
	'name'          => 'example_checkbox',
	'label'         => 'Checkbox Field',
	'type'          => Nomad_Form_Fields::CHECKBOX,
	'checkbox_text' => 'Checkbox text.',
),
```

### Checkboxes Field

Usage: `Nomad_Form_Fields::CHECKBOXES`

Multiple checkboxes.

```
'example_checkboxes' => array(
	'name'    => 'example_checkboxes',
	'label'   => 'Checkboxes Field',
	'type'    => Nomad_Form_Fields::CHECKBOXES,
	'options' => array(
		'a' => 'Choice A is the longest option<br>it has three lines<br>how about that?',
		'b' => 'Choice B is much longer<br>spans a couple lines',
		'c' => 'Choice C is the shortest',
	),
),
```

### Country Field

Usage: `Nomad_Form_Fields::COUNTRY`

Select dropdown menu with list of all countries as options.

```
'example_country' => array(
	'name'  => 'example_country',
	'label' => 'Country Field',
	'type'  => Nomad_Form_Fields::COUNTRY,
),
```

### Date Field

Usage: `Nomad_Form_Fields::DATE`

Date input field.

```
'example_date' => array(
	'name'  => 'example_date',
	'label' => 'Date Field',
	'type'  => Nomad_Form_Fields::DATE,
),
```

### Day Field

Usage: `Nomad_Form_Fields::DAY`

Select dropdown menu with days of the month (01-31) as options.

```
'example_day' => array(
	'name'  => 'example_day',
	'label' => 'Day Field',
	'type'  => Nomad_Form_Fields::DAY,
),
```

### Email Field

Usage: `Nomad_Form_Fields::EMAIL`

Email input field.

```
'example_email' => array(
	'name'  => 'example_email',
	'label' => 'Email Field',
	'type'  => Nomad_Form_Fields::EMAIL,
),
```

### Enable/Disable Button Group

Usage: `Nomad_Form_Fields::ENABLE_DISABLE_BUTTON_GROUP`

Button group with predefined Enable and Disable options.

```
'example_enable_disable_button_group' => array(
	'name'  => 'example_enable_disable_button_group',
	'label' => 'Enable Disable Button Group',
	'type'  => Nomad_Form_Fields::ENABLE_DISABLE_BUTTON_GROUP,
),
```

### Enabled/Disabled Button Group

Usage: `Nomad_Form_Fields::ENABLED_DISABLED_BUTTON_GROUP`

Button group with predefined Enabled and Disabled options.

```
'example_enabled_disabled_button_group' => array(
	'name'  => 'example_enabled_disabled_button_group',
	'label' => 'Enabled Disabled Button Group',
	'type'  => Nomad_Form_Fields::ENABLED_DISABLED_BUTTON_GROUP,
),
```

### Hidden Field

Usage: `Nomad_Form_Fields::HIDDEN`

Hidden input field.

```
'example_hidden' => array(
	'name'  => 'example_hidden',
	'label' => 'Hidden Field',
	'type'  => Nomad_Form_Fields::HIDDEN,
	'value' => 'hidden_value',
),
```

### Hour Field

Usage: `Nomad_Form_Fields::HOUR`

Select dropdown menu with hours of the day as options. You can specify `format` with `12` or `24` to have 1-12 or 0-23 hour format options. Default is 12 hour format.

```
'example_hour' => array(
	'name'  => 'example_hour',
	'label' => 'Hour Field',
	'type'  => Nomad_Form_Fields::HOUR,
),
```

### Minute Field

Usage: `Nomad_Form_Fields::MINUTE`

Select dropdown menu with `00-60` minute options.

```
'example_minute' => array(
	'name'  => 'example_minute',
	'label' => 'Minute Field',
	'type'  => Nomad_Form_Fields::MINUTE,
),
```

### Month Field

Usage: `Nomad_Form_Fields::MONTH`

Select dropdown menu with months of the year as options. You can specify `format` with `full` (January-December), `short` (Jan-Dec), or `number` (1-12) format options. Default format: `number`.

```
'example_month' => array(
	'name'  => 'example_month',
	'label' => 'Month Field',
	'type'  => Nomad_Form_Fields::MONTH,
),
```

### Number Field

Usage: `Nomad_Form_Fields::NUMBER`

Number input field.

```
'example_number' => array(
	'name'  => 'example_number',
	'label' => 'Number Field',
	'type'  => Nomad_Form_Fields::NUMBER,
),
```

### On/Off Button Group

Usage: `Nomad_Form_Fields::ON_OFF_BUTTON_GROUP`

Button group with predefined On and Off options.

```
'example_on_off_button_group' => array(
	'name'  => 'example_on_off_button_group',
	'label' => 'On Off Button Group',
	'type'  => Nomad_Form_Fields::ON_OFF_BUTTON_GROUP,
),
```

### Password Field

Usage: `Nomad_Form_Fields::PASSWORD`

Password input field.

```
'example_password' => array(
	'name'  => 'example_password',
	'label' => 'Password Field',
	'type'  => Nomad_Form_Fields::PASSWORD,
),
```

### Percentage Field

Usage: `Nomad_Form_Fields::PERCENTAGE`

Number input field with a minimum value of 0 and maximum value of 100.

```
'example_percentage' => array(
	'name'  => 'example_percentage',
	'label' => 'Percentage Field',
	'type'  => Nomad_Form_Fields::PERCENTAGE,
),
```

### Phone Field

Usage: `Nomad_Form_Fields::PHONE`

Phone input field.

```
'example_phone' => array(
	'name'  => 'example_phone',
	'label' => 'Phone Field',
	'type'  => Nomad_Form_Fields::PHONE,
),
```

### Radio Field

Usage: `Nomad_Form_Fields::RADIO`

A single radio button.

```
'example_radio' => array(
	'name'       => 'example_radio',
	'label'      => 'Radio Field',
	'type'       => Nomad_Form_Fields::RADIO,
	'radio_text' => 'Radio text.',
),
```

### Radios Field

Usage: `Nomad_Form_Fields::RADIOS`

Multiple radio buttons.

```
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
```

### Select Field

Usage: `Nomad_Form_Fields::SELECT`

Select dropdown menu.

```
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
```

### State Field

Usage: `Nomad_Form_Fields::STATE`

Select dropdown menu with US States as options. You can specify `format` with `full` (Alabama-Wyoming) or `short` (AL-WY) format options. Default format: `short`.

```
'example_state' => array(
	'name'  => 'example_state',
	'label' => 'State Field',
	'type'  => Nomad_Form_Fields::STATE,
),
```

### Text Field

Usage: `Nomad_Form_Fields::TEXT`

Text input field

```
'example_text' => array(
	'name'  => 'example_text',
	'label' => 'Text Field',
	'type'  => Nomad_Form_Fields::TEXT,
),
```

### Textarea Field

Usage: `Nomad_Form_Fields::TEXTAREA`

Textarea field.

```
'example_textarea' => array(
	'name'  => 'example_textarea',
	'label' => 'Textarea Field',
	'type'  => Nomad_Form_Fields::TEXTAREA,
),
```

### Time Field

Usage: `Nomad_Form_Fields::TIME`

Time input field.

```
'example_time' => array(
	'name'  => 'example_time',
	'label' => 'Time Field',
	'type'  => Nomad_Form_Fields::TIME,
),
```

### Toggle Field

Usage: `Nomad_Form_Fields::TOGGLE`

Styled checkbox toggle switch field.

```
'example_toggle' => array(
	'name'  => 'example_toggle',
	'label' => 'Toggle Field',
	'type'  => Nomad_Form_Fields::TOGGLE,
),
```

### Toggles Field

Usage: `Nomad_Form_Fields::TOGGLES`

Styled checkboxes toggle switch fields.

```
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
```

### Upload Field

Usage: `Nomad_Form_Fields::UPLOAD`

File input field.

```
'example_upload' => array(
	'name'  => 'example_upload',
	'label' => 'Upload Field',
	'type'  => Nomad_Form_Fields::UPLOAD,
),
```

### URL Field

Usage: `Nomad_Form_Fields::URL`

URL input field.

```
'example_url' => array(
	'name'  => 'example_url',
	'label' => 'Url Field',
	'type'  => Nomad_Form_Fields::URL,
),
```

### Weekday Field

Usage: `Nomad_Form_Fields::WEEKDAY`

Select dropdown menu with days of the week as options. You can specify `format` with `full` (Monday-Sunday), `short` (Mon-Sun), or `lower` (monday-sunday) format options. Default format: `lower`.

```
'example_weekday' => array(
	'name'  => 'example_weekday',
	'label' => 'Weekday Field',
	'type'  => Nomad_Form_Fields::WEEKDAY,
),
```

### Year Field

Usage: `Nomad_Form_Fields::YEAR`

Select dropdown menu with the years 1900-current as options. You can specify the `min` and `max` to determine what years are available as options.

```
'example_year' => array(
	'name'  => 'example_year',
	'label' => 'Year Field',
	'type'  => Nomad_Form_Fields::YEAR,
),
```

### Yes/No Button Group

Usage: `Nomad_Form_Fields::YES_NO_BUTTON_GROUP`

Button group with predefined Yes and No options.

```
'example_yes_no_button_group' => array(
	'name'  => 'example_yes_no_button_group',
	'label' => 'Yes No Button Group',
	'type'  => Nomad_Form_Fields::YES_NO_BUTTON_GROUP,
),
```

## Reserved Field Names

The field name `nomad_form_id` is reserved because it is used to determine the form that is being submitted. This is how multiple Nomad Forms are allowed to be used on the same page and be able to distinguish between which one was submitted.

## Available Hooks and Filters

### `nomad/forms/init`

```
/**
 * Fires when first initializing a Nomad Form.
 *
 * @since 1.0.0
 *
 * @param string $form_id The form ID being initialized.
 * @param array  $args    The form arguments.
 */
do_action( 'nomad/forms/init', $this->form_id(), $args );
```

### `nomad/forms/{$this->form_id()}/init`

```
/**
 * Fires when first initializing a specific Nomad Form.
 *
 * @since 1.0.0
 *
 * @param array $args The form arguments.
 */
do_action( "nomad/forms/{$this->form_id()}/init", $args );
```

### `nomad/forms/{$this->form_id()}/args`

```
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
```

### `nomad/forms/{$this->form_id()}/fields`

```
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
```

### `nomad/forms/{$this->form_id()}/injections`

```
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
```

### `nomad/forms/before_form_open`

```
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
```

### `nomad/forms/{$this->form_id()}/before_form_open`

```
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
```

### `nomad/forms/form_open`

```
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
```

### `nomad/forms/{$this->form_id()}/form_open`

```
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
```

### `nomad/forms/{$this->form_id()}/after_open`

```
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
```

### `nomad/forms/after_open`

```
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

```

### `nomad/forms/hidden_fields`

```
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
```

### `nomad/forms/{$this->form_id()}/hidden_fields`

```
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
```

### `nomad/forms/before_fields_container`

```
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
```

### `nomad/forms/{$this->form_id()}/before_fields_container`

```
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
```

### `nomad/forms/before_fields`

```
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
```

### `nomad/forms/{$this->form_id()}/before_fields`

```
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
```

### `nomad/forms/{$this->form_id()}/field/{$field['name']}/args`

```
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
```

### `nomad/forms/{$this->form_id()}/field/before`

```
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
```

### `nomad/forms/{$this->form_id()}/field/{$field['name']}/before`

```
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
```

### `nomad/forms/{$this->form_id()}/field/{$field['name']}/after`

```
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
```

### `nomad/forms/{$this->form_id()}/field/after`

```
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
```

### `nomad/forms/{$this->form_id()}/after_fields`

```
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
```

### `nomad/forms/after_fields`

```
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
```

### `nomad/forms/{$this->form_id()}/after_fields_container`

```
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
```

### `nomad/forms/after_fields_container`

```
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
```

### `nomad/forms/before_form_actions`

```
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
```

### `nomad/forms/{$this->form_id()}/before_form_actions`

```
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
```

### `nomad/forms/submit_attributes`

```
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
```

### `nomad/forms/{$this->form_id()}/submit_attributes`

```
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
```

### `nomad/forms/before_submit_button`

```
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
```

### `nomad/forms/{$this->form_id()}/before_submit_button`

```
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
```

### `nomad/forms/{$this->form_id()}/after_submit_button`

```
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
```

### `nomad/forms/after_submit_button`

```
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
```

### `nomad/forms/{$this->form_id()}/after_form_actions`

```
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
```

### `nomad/forms/after_form_actions`

```
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
```

### `nomad/forms/reset_attributes`

```
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
```

### `nomad/forms/{$this->form_id()}/reset_attributes`

```
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
```

### `nomad/forms/cancel_attributes`

```
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
```

### `nomad/forms/{$this->form_id()}/cancel_attributes`

```
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
```

### `nomad/forms/form_close`

```
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
```

### `nomad/forms/{$this->form_id()}/form_close`

```
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
```

### `nomad/forms/{$this->form_id()}/before_form_close`

```
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
```

### `nomad/forms/before_form_close`

```
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
```

### `nomad/forms/{$this->form_id()}/after_form_close`

```
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
```

### `nomad/forms/after_form_close`

```
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
```

### `nomad/forms/{$this->form_id()}/is_valid`

```
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
```

### `nomad/forms/{$this->form_id()}/success`

```
/**
 * Fires when a specific form is submitted successfully.
 *
 * @since 1.0.0
 *
 * @param Nomad_Form $this The form instance.
 */
do_action( "nomad/forms/{$this->form_id()}/success", $this );
```

### `nomad/forms/{$this->form_id()}/error`

```
/**
 * Fires when a specific form submission encounters an error.
 *
 * @since 1.0.0
 *
 * @param Nomad_Form $this The form instance.
 */
do_action( "nomad/forms/{$this->form_id()}/error", $this );
```

### `nomad/forms/process`

```
/**
 * Fires every time a form is submitted, regardless of whether
 * or not it was valid.
 *
 * @since 1.0.0
 *
 * @param Nomad_Form $this The form instance.
 */
do_action( 'nomad/forms/process', $this );
```

### `nomad/forms/{$this->form_id()}/process`

```
/**
 * Fires every time a specific form is submitted regardless of
 * whether or not it was valid.
 *
 * @since 1.0.0
 *
 * @param Nomad_Form $this The form instance.
 */
do_action( "nomad/forms/{$this->form_id()}/process", $this );
```

### `nomad/forms/{$this->form_id()}/success_message`

```
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
```

### `nomad/forms/{$this->form_id()}/error_message`

```
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
```

### `nomad/forms/{$this->form_id()}/error_messages`

```
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
```

## Changelog

### v1.0.0
* Initial Release

## License

The MIT License (MIT). Please see [License File](https://github.com/jakesutherland/nomad-forms/blob/master/LICENSE) for more information.

## Copyright

Copyright (c) 2021 Jake Sutherland
