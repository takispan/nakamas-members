<?php

// add_action( 'admin_menu', 'add_nakamas_users_options_page' );
function add_nakamas_users_options_page() {

	add_options_page(
		'nakamas_users Options',
		'nakamas_users Options',
		'manage_options',
		'nakamas_users-options-page',
		'display_nakamas_users_options_page'
	);

}
add_action( 'admin_menu', 'add_nakamas_users_options_page' );
function display_nakamas_users_options_page() {

	echo '<h2>nakamas_users Options</h2>';

	echo '<form method="post" action="options.php">';

	do_settings_sections( 'nakamas_users-options-page' );
	settings_fields( 'nakamas_users-settings' );

	submit_button();

	echo '</form>';

}

add_action( 'admin_init', 'nakamas_users_admin_init_one' );
function nakamas_users_admin_init_one() {

	add_settings_section(
		'nakamas_users-settings-section-one',
		'nakamas_users Settings Part One',
		'display_nakamas_users_settings_message',
		'nakamas_users-options-page'
	);

	add_settings_field(
		'nakamas_users-input-field',
		'nakamas_users Input Field',
		'render_nakamas_users_input_field',
		'nakamas_users-options-page',
		'nakamas_users-settings-section-one'
	);

	register_setting(
		'nakamas_users-settings',
		'nakamas_users-input-field'
	);

}

function display_nakamas_users_settings_message() {
	echo "This displays the settings message.";
}

function render_nakamas_users_input_field() {

	$input = get_option( 'nakamas_users-input-field' );
	echo '<input type="text" id="nakamas_users-input-field" name="nakamas_users-input-field" value="' . $input . '" />';

}

add_action( 'admin_init', 'nakamas_users_admin_init_two' );
function nakamas_users_admin_init_two() {

	add_settings_section(
		'nakamas_users-settings-section-two',
		'nakamas_users Settings Part Two',
		'display_another_nakamas_users_settings_message',
		'nakamas_users-options-page'
	);

	add_settings_field(
		'nakamas_users-input-field-two',
		'nakamas_users Input Field Two',
		'render_nakamas_users_input_field_two',
		'nakamas_users-options-page',
		'nakamas_users-settings-section-two'
	);

	register_setting(
		'nakamas_users-settings',
		'nakamas_users-input-field-two'
	);

}

function display_another_nakamas_users_settings_message() {
	echo "This displays the second settings message.";
}

function render_nakamas_users_input_field_two() {

	$input = get_option( 'nakamas_users-input-field-two' );
	echo '<input type="text" id="nakamas_users-input-field-two" name="nakamas_users-input-field-two" value="' . $input . '" />';

}