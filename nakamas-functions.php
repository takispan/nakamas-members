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

//Enqueue scripts & styles
/* add_action to... well add our action to wp
 * Parameters
 * Required: tag (wp specific, don't change), function_to_add (name of our function)
 * Optional: priority (defaults to 10. Lower = more priority), accepted_args (accepted arguments, defaults to 1)
 */
add_action('wp_enqueue_scripts', 'nkms_assets');
function nkms_assets() {
    /* Register the script like this for a plugin
     * Parameters
     * Required: handle (name), src (source)
     * Optional: deps (dependencies), ver (version, used date for this), in_footer (load on footer or not. By default off an loads on header)
     */
    wp_register_script( 'nkms-js', plugins_url( '/assets/js/nakamas-members.js', __FILE__ ), array( 'jquery' ), '20200406', true );

    /* Register the style like this for a plugin
     * Parameters
     * Required: handle (name), src (source)
     * Optional: deps (dependencies), ver (version, used date for this), media (The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.)
     */
    wp_register_style( 'nkms-css', plugins_url( '/assets/css/nakamas-members.css', __FILE__ ), array(), '20200404', 'all' );

    // For either a plugin or a theme, you can then enqueue the script/style
    wp_enqueue_script( 'nkms-js' );
    wp_enqueue_style( 'nkms-css' );
    //wp_enqueue_script( 'nakamas-members-scripts', plugin_dir_path( __FILE__ ) . 'nakamas-members-script.js', array( 'jquery' ) );
}
