<?php
/**
 * Nomad Forms Composer Package Autoload file.
 *
 * Nomad Forms provides you with an easy way to display and process forms,
 * render and validate fields, and display error/success messages.
 *
 * @since 1.0.0
 *
 * @package Nomad/Forms
 */

namespace Nomad\Forms;

use function Nomad\Helpers\register_nomad_package;

if ( ! defined( 'ABSPATH' ) ) exit; // Prevent direct access.

// Composer Autoload.
require_once dirname( __FILE__ ) . '/vendor/autoload.php';

/**
 * Nomad Forms Version.
 *
 * @since 1.0.0
 */
if ( ! defined( 'NOMAD_FORMS_VERSION' ) ) {
	define( 'NOMAD_FORMS_VERSION', '1.0.0' );
}

/**
 * Nomad Forms Path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'NOMAD_FORMS_PATH' ) ) {
	define( 'NOMAD_FORMS_PATH', dirname( __FILE__ ) . '/' );

	register_nomad_package( 'nomad-forms', NOMAD_FORMS_PATH );
}

/**
 * Nomad Forms URL.
 *
 * @since 1.0.0
 */
if ( ! defined( 'NOMAD_FORMS_URL' ) ) {
	define( 'NOMAD_FORMS_URL', home_url( '/' ) . str_replace( ABSPATH, '', dirname( __FILE__ ) ) . '/' );
}

/**
 * Nomad Forms Dist Path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'NOMAD_FORMS_DIST_PATH' ) ) {
	define( 'NOMAD_FORMS_DIST_PATH', NOMAD_FORMS_PATH . 'dist/' );
}

/**
 * Nomad Forms Source Path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'NOMAD_FORMS_SRC_PATH' ) ) {
	define( 'NOMAD_FORMS_SRC_PATH', NOMAD_FORMS_PATH . 'src/' );
}

/**
 * Nomad Forms Fields Path.
 *
 * @since 1.0.0
 */
if ( ! defined( 'NOMAD_FORMS_FIELDS_PATH' ) ) {
	define( 'NOMAD_FORMS_FIELDS_PATH', NOMAD_FORMS_SRC_PATH . 'fields/' );
}

// Include the Nomad Form class.
if ( ! class_exists( __NAMESPACE__ . '\\Nomad_Form' ) ) {
	require_once NOMAD_FORMS_SRC_PATH . 'class-nomad-form.php';
}

// Include the Nomad Form Fields class.
if ( ! class_exists( __NAMESPACE__ . '\\Nomad_Form_Fields' ) ) {
	require_once NOMAD_FORMS_SRC_PATH . 'class-nomad-form-fields.php';
}

// Include the base Field class.
if ( ! class_exists( __NAMESPACE__ . '\\Fields\\Field' ) ) {
	require_once NOMAD_FORMS_FIELDS_PATH . 'field.php';
}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_forms_enqueue_assets' ) ) {

	/**
	 * Nomad Forms Enqueue Assets.
	 *
	 * Register and enqueue the Nomad Forms and Nomad Forms Themes stylesheets.
	 *
	 * @since 1.0.0
	 */
	function nomad_forms_enqueue_assets() {

		// Nomad Forms styles contains the bare minimum styles needed in order to keep the structural layout of forms.
		wp_register_style( 'nomad-forms', NOMAD_FORMS_URL . 'dist/css/nomad-forms.min.css', array(), NOMAD_FORMS_VERSION, 'all' );

		// Nomad Forms Theme styles contain more custom styles including styles for more visual fields (button group, checkboxes, radios, toggles).
		wp_register_style( 'nomad-forms-theme', NOMAD_FORMS_URL . 'dist/css/nomad-forms-theme.min.css', array( 'nomad-forms' ), NOMAD_FORMS_VERSION, 'all' );

		wp_enqueue_style( 'nomad-forms' );

		/**
		 * Filters whether or not to load the Nomad Forms Theme stylesheet.
		 *
		 * @since 1.0.0
		 *
		 * @param boolean Whether or not to load the Nomad Forms Theme stylesheet.
		 */
		$load_theme_styles = apply_filters( 'nomad/forms/load_theme_styles', true );

		if ( $load_theme_styles ) {
			wp_enqueue_style( 'nomad-forms-theme' );
		}

	}

	// Enqueue Nomad Forms styles in both the frontend and the admin.
	add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\nomad_forms_enqueue_assets' );
	add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\nomad_forms_enqueue_assets' );

}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_form' ) ) {

	/**
	 * Nomad Form.
	 *
	 * Creates a Nomad Form.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   The unique ID for the form.
	 * @param array  $args Array of form arguments.
	 *
	 * @return Nomad_Form
	 */
	function nomad_form( $id, $args ) {

		return new Nomad_Form( $id, $args );

	}

}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_fields' ) ) {

	/**
	 * Nomad Fields.
	 *
	 * Create a Nomad Form for when you just want to output fields and not have
	 * a form tag. Basically sets various form arguments to what would be needed
	 * in order to just output form fields.
	 *
	 * When using this by itself, you would be expected to handle all form
	 * processing and validation manually.
	 *
	 * Common use case would be adding custom fields to a post type in the
	 * WordPress admin and then processing those fields on `save_post`.
	 *
	 * @since 1.0.0
	 *
	 * @param string $id   The unique ID for the form.
	 * @param array  $args Array of form arguments.
	 *
	 * @return Nomad_Form
	 */
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

}

if ( ! function_exists( __NAMESPACE__ . '\\nomad_form_injection' ) ) {

	/**
	 * Nomad Form Injection.
	 *
	 * Used to inject additional fields before or after existing form fields.
	 *
	 * Injection arguments must include an array of `fields` and either a
	 * `before` or `after` argument that specifies the field name to inject the
	 * fields into.
	 *
	 * @since 1.0.0
	 *
	 * @param string $form_id The unique ID of the form to inject fields into.
	 * @param array  $args    Injection arguments.
	 */
	function nomad_form_injection( $form_id, $args ) {

		add_filter( "nomad/forms/{$form_id}/injections", function( $injections ) use ( $args ) {

			$injections[] = $args;

			return $injections;

		} );

	}

}
