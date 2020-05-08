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
//Add dancer to dance school list of dancers
add_action( 'wp_ajax_ds_add_dancer', 'ds_add_dancer' );
function ds_add_dancer() {
	global $wpdb; // this is how you get access to the database

  $user_id = get_current_user_id();
  echo $user_id;
	$dancer_to_add_id = intval($_POST['dancer']);
	$dancer2add = get_user_by( 'id', $dancer_to_add_id );
	if ( nkms_has_role( $dancer2add, 'dancer' ) ) {
		$data_entry = get_user_meta(get_current_user_id(), 'dance_school_dancers_list', true);
		if (!is_array($data_entry)) {
			$data_entry = [];
		}
		$entry = $dancer_to_add_id;
		if (!in_array($entry, $data_entry)) {
			array_push($data_entry, $entry);
		}
		update_user_meta($user_id, 'dance_school_dancers_list', $data_entry);
		echo "Dancer added.";
	}
	else {
		echo "Invalid Dancer ID";
		//wp_send_json_error();
	}
	wp_die();
}

//Pass dancer id to populate single dancer tab
add_action( 'wp_ajax_ds_single_dancer', 'ds_single_dancer' );
function ds_single_dancer() {
	global $wpdb; // this is how you get access to the database

  $currview = get_user_meta(get_current_user_id(), 'currently_viewing', true);
  if (!is_array($currview)) {
    $currview = [0,0];
  }
  if (sizeof($currview) < 2) {
    array_push($currview, 0);
  }

  $currview[0] = intval($_POST['single_dancer_id']);
  update_user_meta(get_current_user_id(), 'currently_viewing', $currview );
	wp_die();
}

//Remove dancer from dance school list of dancers
add_action( 'wp_ajax_ds_remove_dancer', 'ds_remove_dancer' );
function ds_remove_dancer() {
	global $wpdb; // this is how you get access to the database

  $user_id = get_current_user_id();
  echo $user_id;
	$dancer_to_remove_id = intval($_POST['single_dancer_id']);
  echo $dancer_to_remove_id;
  $dancers_list = get_user_meta(get_current_user_id(), 'dance_school_dancers_list', true);
  if ( in_array( $dancer_to_remove_id, $dancers_list) ) {
    $data_entry = array_diff( $dancer_list, [$dancer_to_remove_id] );
    update_user_meta(get_current_user_id(), 'dance_school_dancers_list', $data_entry);
  	echo "Dancer removed.";
  }
  else {
    echo "Error occured.";
    //wp_send_json_error();
	}
	wp_die();
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





}
//also add , $first_name, $last_name
function registration_validation( $username, $password, $email )  {
  global $reg_errors;
  $reg_errors = new WP_Error;

  //Check if fields are empty
  if ( empty( $username ) || empty( $password ) || empty( $email ) ) {
    $reg_errors->add('field', 'Required form field is missing');
  }

  //Check if username is more than 4 chars.
  if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters is required' );
  }

  //WP function. Checks if username exists.
  if ( username_exists( $username ) ) {
    $reg_errors->add('user_name', 'Sorry, that username already exists!');
  }

  //WP function. Checks if username is valid
  if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid' );
  }

  //Password more than 6 chars
  if ( 5 > strlen( $password ) ) {
        $reg_errors->add( 'password', 'Password length must be greater than 5' );
  }

  //Check if email is valid
  if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid' );
  }
  //Check if email is in use
  if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use' );
  }

  //Loop through errors & display them
  if ( is_wp_error( $reg_errors ) ) {
    foreach ( $reg_errors->get_error_messages() as $error ) {
      echo '<div>';
      echo '<strong>ERROR</strong>:';
      echo $error . '<br/>';
      echo '</div>';
    }
  }
}

//Complete registration. Should add , $first_name, $last_name
function complete_registration() {
  global $reg_errors, $username, $password, $email;
  if ( 1 > count( $reg_errors->get_error_messages() ) ) {
    $userdata = array(
    'user_login'    =>   $username,
    'user_email'    =>   $email,
    'user_pass'     =>   $password,
    //'first_name'    =>   $first_name,
    //'last_name'     =>   $last_name,
    );
    $user = wp_insert_user( $userdata );
    echo 'Registration complete. Goto <a href="' . get_site_url() . '/login">login page</a>.';
  }
}
