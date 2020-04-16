<?php

/*
 * Flushing Rewrite on plugin Activation
 *
 * To get permalinks to work when you activate the plugin use the following example,
 * paying attention to how my_cpt_init() is called in the register_activation_hook callback:
 *
 * Also register custom post types
 *
add_action( 'init', 'my_cpt_init' );
function my_cpt_init() {
    register_post_type( ... );
}

register_activation_hook( __FILE__, 'my_rewrite_flush' );
function my_rewrite_flush() {
    // First, we "add" the custom post type via the above written function.
    // Note: "add" is written with quotes, as CPTs don't get added to the DB,
    // They are only referenced in the post_type column with a post entry,
    // when you add a post of this CPT.
    my_cpt_init();

    // ATTENTION: This is *only* done during plugin activation hook in this example!
    // You should *NEVER EVER* do this on every page load!!
    flush_rewrite_rules();
}
*/

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

/*
 * Enqueue scripts & styles
 *
 * add_action to... well add our action to wp
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

/*
 * Create user roles & capabilities
 *
 * Parameters
 * Required: role, display_name,  capabilities
 */
//Dance School
add_role('dance-school', __(
	'Dance School'),
	array(
		'read'	=> true, //Allows a user to read
	)
);

//Dancer
add_role('dancer', __(
	'Dancer'),
	array(
		'read'	=> true, //Allows a user to read
	)
);

//Guardian
add_role('guardian', __(
	'Guardian'),
	array(
		'read'	=> true, //Allows a user to read
	)
);


/*
 * Add user profile fields
 *
 * show_user_profile: show on frontend when user editing their own profile
 * edit_user_profile: show on backend when admin edits other users
 */
add_action( 'show_user_profile', 'nkms_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'nkms_show_extra_profile_fields' );
function nkms_show_extra_profile_fields( $user) {
	/* Dancer
	 *
	 *
	 * Create custom fields
	 */
	$suki = get_the_author_meta( 'love_suki', $user->ID );
	$ds_name = get_the_author_meta( 'dance_school_name', $user->ID );
	$ds_address = get_the_author_meta( 'dance_school_address', $user->ID );
	$ds_phone_number = get_the_author_meta( 'dance_school_phone_number', $user->ID );
	$ds_add_dancers = get_the_author_meta( 'dance_school_add_dancers', $user->ID );
	$ds_dancers_list_array = get_user_meta($user->ID, 'dance_school_dancers_list', true);
	if (!is_array($ds_dancers_list_array)) {
		$ds_dancers_list_array = [];
	}
	// Dance School

	// Dancer
	?>
	<!-- Dancer -->
	<h3><?php esc_html_e( 'Dancer', 'nkms' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="love_suki"><?php esc_html_e( 'Who loves suki', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="love_suki" value="<?php echo esc_attr( $suki ); ?>" class="regular-text" />
					<!--<span class="description"><?php //_e("Please enter who loves dis good boi.") ?></span>-->
			</td>
		</tr>
	</table>

	<!-- Dance School -->
	<h3><?php esc_html_e( 'Dance School', 'nkms' ); ?></h3>

	<table class="form-table">
		<tr>
			<th><label for="love_suki"><?php esc_html_e( 'Who loves suki', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="love_suki" value="<?php echo esc_attr( $suki ); ?>" class="regular-text" />
					<!--<span class="description"><?php //_e("Please enter who loves dis good boi.") ?></span>-->
			</td>
		</tr>
		<tr>
			<th><label for="dance_school_name"><?php esc_html_e( 'Dance School Name', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="dance_school_name" value="<?php echo esc_attr( $ds_name ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="dance_school_address"><?php esc_html_e( 'Dance School Address', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="dance_school_address" value="<?php echo esc_attr( $ds_address ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="dance_school_phone_number"><?php esc_html_e( 'Dance School Phone Number', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="dance_school_phone_number" value="<?php echo esc_attr( $ds_phone_number ); ?>" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="dance_school_add_dancers"><?php esc_html_e( 'Add a dancer', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="dance_school_add_dancers" value="" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th><label for="dance_school_remove_dancers"><?php esc_html_e( 'Remove a dancer', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="dance_school_remove_dancers" value="" class="regular-text" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
					<?php

						if ( ! empty( $ds_dancers_list_array ) ) { ?>
							<table>
								<tr>
									<th>ID</th>
									<th>First Name</th>
									<th>Last Name</th>
								</tr>
							<?php
							foreach ($ds_dancers_list_array as $key => $value) {
								$user_info = get_userdata($value);
								echo "<tr><td>" . $value . "</td><td>" . $user_info->first_name . "</td><td>" . $user_info->last_name . "</td></tr>";
							}
						} else {
							echo "This Dance School does not have any registered dancers.";
						} ?>
						</table>

			</td>
		</tr>
	</table>
	<?php
}

// Saving the field
add_action( 'personal_options_update', 'nkms_update_profile_fields' );
add_action( 'edit_user_profile_update', 'nkms_update_profile_fields' );
function nkms_update_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! empty( $_POST['love_suki'] ) ) {
		update_user_meta( $user_id, 'love_suki', sanitize_text_field( $_POST['love_suki'] ) );
	}
	if ( ! empty( $_POST['dance_school_name'] ) ) {
		update_user_meta( $user_id, 'dance_school_name', sanitize_text_field( $_POST['dance_school_name'] ) );
	}
	if ( ! empty( $_POST['dance_school_address'] ) ) {
		update_user_meta( $user_id, 'dance_school_address', sanitize_text_field( $_POST['dance_school_address'] ) );
	}
	if ( ! empty( $_POST['dance_school_phone_number'] ) ) {
		update_user_meta( $user_id, 'dance_school_phone_number', sanitize_text_field( $_POST['dance_school_phone_number'] ) );
	}
	if ( ! empty( $_POST['dance_school_add_dancers'] ) ) {
		$data_entry = get_user_meta($user_id, 'dance_school_dancers_list', true);
		if (!is_array($data_entry)) {
			$data_entry = [];
		}
		array_push($data_entry, sanitize_text_field($_POST['dance_school_add_dancers']));
		update_user_meta($user_id, 'dance_school_dancers_list', $data_entry);
	}
	if ( ! empty( $_POST['dance_school_remove_dancers'] ) ) {
		$data_entry = get_user_meta($user_id, 'dance_school_dancers_list', true);
		if (!is_array($data_entry)) {
			$data_entry = [];
		}
		//if (!array_contains($data, value)) -> error message to user
		$data_entry = array_diff($data_entry, [sanitize_text_field($_POST['dance_school_remove_dancers'])]);
		update_user_meta($user_id, 'dance_school_dancers_list', $data_entry);
	}
}
