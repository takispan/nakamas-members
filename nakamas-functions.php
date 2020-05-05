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

	$js_values = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'the_issue_key' => '0422',
	);
	wp_localize_script( 'nkms-js', 'nkms_ajax', $js_values );
  }

/*
 * Ajax in WP
 *
 */
<<<<<<< HEAD
//Add dancer to dance school list of dancers
=======
>>>>>>> 4c0ef90ee475594a5e3973681e1c6d0078e66cbf
add_action( 'wp_ajax_ds_add_dancer', 'ds_add_dancer' );
function ds_add_dancer() {
	global $wpdb; // this is how you get access to the database

	$dancer_to_add_id = $_POST['dancer'];
	$dancer2add = get_user_by( 'id', $dancer_to_add_id );
	if ( nkms_has_role( $dancer2add, 'dancer' ) ) {
		$data_entry = get_user_meta($current_user->ID, 'dance_school_dancers_list', true);
		if (!is_array($data_entry)) {
			$data_entry = [];
		}
<<<<<<< HEAD
		$entry = sanitize_text_field($dancer2add);
=======
		$entry = sanitize_text_field($_POST['dance_school_add_dancers']);
>>>>>>> 4c0ef90ee475594a5e3973681e1c6d0078e66cbf
		if (!in_array($entry, $data_entry)) {
			array_push($data_entry, $entry);
		}
		update_user_meta($current_user->ID, 'dance_school_dancers_list', $data_entry);
		echo "Dancer added.";
<<<<<<< HEAD
=======
	}
	else {
		echo "Invalid Dancer ID";
		wp_send_json_error();
>>>>>>> 4c0ef90ee475594a5e3973681e1c6d0078e66cbf
	}
	else {
		echo "Invalid Dancer ID";
		wp_send_json_error();
	}
	wp_die();
}

<<<<<<< HEAD
//Pass dancer id to populate single dancer tab
add_action( 'wp_ajax_ds_single_dancer', 'ds_single_dancer' );
function ds_single_dancer() {
	global $wpdb; // this is how you get access to the database

	$dancer_id = $_POST['dancer_id'];
	$dancer_single = get_user_by( 'id', $dancer_id );
	echo $dancer_single;

	wp_die();
}

=======
	wp_die();
}

// add_action( 'wp_ajax_ds_single_dancer', 'ds_single_dancer' );
// function ds_single_dancer() {
// 	global $wpdb; // this is how you get access to the database
//
// 	$dancer_id = $_POST['dancer_id'];
// 	$dancer_single = get_user_by( 'id', $dancer_id );
// 	echo $dancer_single;
//
// 	wp_die();
// }

>>>>>>> 4c0ef90ee475594a5e3973681e1c6d0078e66cbf
// add_action( 'wp_ajax_my_action', 'my_action' );
// add_action( 'wp_ajax_nopriv_my_action','my_action' ); // should be nkms_action later
// function my_action() {
// 	global $wpdb; // this is how you get access to the database
//
// 	if (isset($_POST['the_issue_key'])) {
// 			if ( ! empty( $_POST['the_issue_key'] ) ) {
// 				$val = $_POST['the_issue_key'];
// 				$test = $_POST['test'];
// 				var_dump($test);
// 				var_dump($val);
// 				echo "Test 2";
// 				echo $_POST[''];
// 		} else {
// 				echo "Is not empty";
// 			}
// 	} else {
// 		echo "Is not set";
// 	}
// 	//Button to remove dancers from list
// 	if (isset($_POST['dance_school_remove_dancers_submit'])) {
// 		if ( ! empty( $_POST['dance_school_remove_dancers'] ) ) {
// 			$data_entry = get_user_meta($current_user->ID, 'dance_school_dancers_list', true);
// 			if (!is_array($data_entry)) {
// 				$data_entry = [];
// 			}
// 			$data_entry = array_diff($data_entry, [sanitize_text_field($_POST['dance_school_remove_dancers'])]);
// 		}
// 		update_user_meta($current_user->ID, 'dance_school_dancers_list', $data_entry);
// 	}
// 	//Create the array we send back to javascript here
// 	$array_we_send_back = array( 'test' => "Test" );
// 	//Make sure to json encode the output because that's what it is expecting
// 	//echo json_encode( $array_we_send_back );
// 	wp_die(); // this is required to terminate immediately and return a proper response
// }

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

//Check if a user has a role
function nkms_has_role($user, $role) {
	$roles = $user->roles;
	return in_array($role, (array) $user->roles);
}

/*
 * Register custom post type Groups
 *
 *
function nkms_custom_post_type() {
    register_post_type('nkms_groups',
        array(
            'labels'      => array(
                'name'          => __('Groups', 'nkms'),
                'singular_name' => __('Group', 'nkms'),
								'add_new_item'  => __( 'Add New Group', 'nkms' ),
				        'new_item'      => __( 'New Group', 'nkms' ),
				        'edit_item'     => __( 'Edit Group', 'nkms' ),
				        'view_item'     => __( 'View Group', 'nkms' ),
            ),
                'public'       => true,
                'has_archive'  => false,
								'hierarchical' => false,
								'menu_icon'		 => 'dashicons-buddicons-buddypress-logo',
        )
    );
}
add_action('init', 'nkms_custom_post_type'); */

/*
 * Add user profile fields
 *
 * show_user_profile: show on frontend when user editing their own profile
 * edit_user_profile: show on backend when admin edits other users
 */
add_action( 'show_user_profile', 'nkms_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'nkms_show_extra_profile_fields' );
function nkms_show_extra_profile_fields( $user ) {
	/* Dancer
	 *
	 *
	 * Create custom fields
	 */
	$ds_name = get_the_author_meta( 'dance_school_name', $user->ID );
	$ds_address = get_the_author_meta( 'dance_school_address', $user->ID );
	$ds_phone_number = get_the_author_meta( 'dance_school_phone_number', $user->ID );
	$ds_description = get_the_author_meta( 'dance_school_description', $user->ID );
	$ds_dancers_list_array = get_user_meta($user->ID, 'dance_school_dancers_list', true);
	if (!is_array($ds_dancers_list_array)) {
		$ds_dancers_list_array = [];
	}
	// Dance School

	// Dancer
	?>

	<!-- Dance School -->
	<h3><?php esc_html_e( 'Dance School', 'nkms' ); ?></h3>

	<table class="form-table">
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
			<th><label for="dance_school_description"><?php esc_html_e( 'Dance School Description', 'nkms' ); ?></label></th>
			<td>
					<textarea rows="5" name="dance_school_description" class="regular-text"><?php echo esc_attr( $ds_description ); ?></textarea>
			</td>
		</tr>
		<!--<tr>
			<th><label for="dance_school_add_dancers"><?php //esc_html_e( 'Add a dancer', 'nkms' ); ?></label></th>
			<td>
					<input type="text" name="dance_school_add_dancers" value="" class="regular-text" />
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
					<input class="button button-primary add-dancer" type="submit" name="dance_school_add_dancers_submit" value="Add" />
			</td>
		</tr>
		<tr>
			<th><label for="dance_school_remove_dancers"><?php //esc_html_e( 'Remove a dancer', 'nkms' ); ?></label></th>
			<td>
					<select name="dance_school_remove_dancers">
						<?php
						// echo "<option>Select a dancer</option>";
						// foreach ($ds_dancers_list_array as $key => $value) {
						// 	$user_info = get_userdata($value);
						// 	echo "<option>" . $value . "</option>"; //. $user_info->first_name . "</td><td>" . $user_info->last_name . "</td></tr>";
						// }
						?>
					</select>
			</td>
		</tr>
		<tr>
			<th></th>
			<td>
					<input class="button button-primary remove-dancer" type="submit" name="dance_school_remove_dancers_submit" value="Remove" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
					<?php

						// if ( ! empty( $ds_dancers_list_array ) ) { ?>
							<table>
								<tr>
									<th>ID</th>
									<th>First Name</th>
									<th>Last Name</th>
								</tr>
							<?php
						// 	foreach ($ds_dancers_list_array as $key => $value) {
						// 		$user_info = get_userdata($value);
						// 		echo "<tr><td>" . $value . "</td><td>" . $user_info->first_name . "</td><td>" . $user_info->last_name . "</td></tr>";
						// 	}
						// } else {
						// 	echo "This Dance School does not have any registered dancers.";
						// }
						// if (!in_array($_POST['dance_school_remove_dancers'], $ds_dancers_list_array)) {
						// 	echo "The dancer was not part of the list.";
						// } ?>
						</table>

			</td>
		</tr>-->
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

	if ( ! empty( $_POST['dance_school_name'] ) ) {
		update_user_meta( $user_id, 'dance_school_name', sanitize_text_field( $_POST['dance_school_name'] ) );
	}
	if ( ! empty( $_POST['dance_school_address'] ) ) {
		update_user_meta( $user_id, 'dance_school_address', sanitize_text_field( $_POST['dance_school_address'] ) );
	}
	if ( ! empty( $_POST['dance_school_phone_number'] ) ) {
		update_user_meta( $user_id, 'dance_school_phone_number', sanitize_text_field( $_POST['dance_school_phone_number'] ) );
	}
	if ( ! empty( $_POST['dance_school_description'] ) ) {
		update_user_meta( $user_id, 'dance_school_description', sanitize_textarea_field( $_POST['dance_school_description'] ) );
	}

	//Button to add dancers from list
	if (isset($_POST['dance_school_add_dancers_submit'])) {
		if ( ! empty( $_POST['dance_school_add_dancers'] ) ) {
			$data_entry = get_user_meta($user_id, 'dance_school_dancers_list', true);
			if (!is_array($data_entry)) {
				$data_entry = [];
			}
			$entry = sanitize_text_field($_POST['dance_school_add_dancers']);
			if (!in_array($entry, $data_entry)) {
				array_push($data_entry, $entry);
			}
			update_user_meta($user_id, 'dance_school_dancers_list', $data_entry);
		}
	}

	//Button to remove dancers from list
	if (isset($_POST['dance_school_remove_dancers_submit'])) {
		if ( ! empty( $_POST['dance_school_remove_dancers'] ) ) {
			$data_entry = get_user_meta($user_id, 'dance_school_dancers_list', true);
			if (!is_array($data_entry)) {
				$data_entry = [];
			}
			$data_entry = array_diff($data_entry, [sanitize_text_field($_POST['dance_school_remove_dancers'])]);
		}
		update_user_meta($user_id, 'dance_school_dancers_list', $data_entry);
	}

}
