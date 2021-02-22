<?php

/*
 * Flushing Rewrite on plugin Activation
 *
 * To get permalinks to work when you activate the plugin use the following example,
 * paying attention to how my_cpt_init() is called in the register_activation_hook callback:
 *
 * Also register custom post types
 *
add_action( 'init', 'nkms_dance_groups_init' );
function nkms_dance_groups_init() {
  register_post_type( 'dance-group', array(
    'label' => 'Dance Group',
    'labels' => array(
      'add_new' => 'New Dance Group',
      'add_new_item' => 'Add New Dance Group',
      'edit_item' => 'Edit Dance Group',
      'new_item' => 'New Dance Group',
      'view_item' => 'View Dance Groups',
      'search_items' => 'Search Dance Groups',
      'not_found' => 'No Dance Groups found',
      'singular_name' => 'Dance Group',
    ),
    'description' => 'Soar Dance Groups',
    'show_ui' => true,
    'support' => array( 'custom-fields' ),
    'taxonomies' => array( 'category' ),
    'rewrite' => array( 'slug' => 'dance-group' ),
    // 'show_in_rest' => true
  ) );
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
  wp_register_script( 'nkms-js', plugins_url( '/assets/nakamas-members.js', __FILE__ ), array( 'jquery' ), '20200406', true );

  /* Register the style like this for a plugin
   * Parameters
   * Required: handle (name), src (source)
   * Optional: deps (dependencies), ver (version, used date for this), media (The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.)
   */
  wp_register_style( 'nkms-css', plugins_url( '/assets/nakamas-members.css', __FILE__ ), array(), '20200404', 'all' );

  // For either a plugin or a theme, you can then enqueue the script/style
  wp_enqueue_script( 'nkms-js' );
  // Load the datepicker script (pre-registered in WordPress)
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_style( 'nkms-css' );

	$js_values = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'nkms-js', 'nkms_ajax', $js_values );
}

// Admin scripts
add_action( 'admin_enqueue_scripts', 'nkms_select2_enq' );
function nkms_select2_enq() {

  wp_register_style( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
	wp_register_script( 'nkms-admin-js', plugins_url( '/assets/nakamas-members-admin.js', __FILE__ ), array( 'jquery', 'select2' ), '20201021', true );
  wp_register_style( 'nkms-admin-css', plugins_url( '/assets/nakamas-members-admin.css', __FILE__ ), array(), '20200404', 'all' );

  wp_enqueue_style( 'select2' );
  wp_enqueue_script( 'select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
	wp_enqueue_script( 'nkms-admin-js' );
  wp_enqueue_style( 'nkms-admin-css' );


  $js_values = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'nkms-admin-js', 'nkms_ajax', $js_values );
}

/*
 * Ajax in WP
 *
**/
include( 'nakamas-functions-ajax.php' );

/*
 * WooCommerce
 *
**/
include( 'nakamas-functions-woocommerce.php' );

/*
 * Admin
 *
**/
include( 'nakamas-functions-admin.php' );

/*
 * Admin Ajax
 *
**/
include( 'nakamas-functions-admin-ajax.php' );

/*
 * Helper
 *
**/
include( 'nakamas-functions-helper.php' );

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

// Add avatar support
add_action('init','nkms_avatar_filter');
function nkms_avatar_filter() {

  // Remove from show_user_profile hook
  remove_action( 'show_user_profile', array( 'wp_user_avatar', 'wpua_action_show_user_profile' ) );
  remove_action( 'show_user_profile', array( 'wp_user_avatar', 'wpua_media_upload_scripts' ) );

  // Remove from edit_user_profile hook
  remove_action( 'edit_user_profile', array( 'wp_user_avatar', 'wpua_action_show_user_profile' ) );
  remove_action( 'edit_user_profile', array( 'wp_user_avatar', 'wpua_media_upload_scripts' ) );

  // Add to edit_user_avatar hook
  add_action( 'edit_user_avatar', array( 'wp_user_avatar', 'wpua_action_show_user_profile' ) );
  add_action( 'edit_user_avatar', array( 'wp_user_avatar', 'wpua_media_upload_scripts' ) );
  // Loads only outside of administration panel
  if( ! is_admin() ) {
    add_action( 'init', 'my_avatar_filter' );
  }
}

// Add menu items
add_filter( 'wp_nav_menu_items', 'nkms_add_loginout_link', 10, 2 );
function nkms_add_loginout_link( $items, $args ) {
    // If menu primary menu is set & user is logged in.
    if ( is_user_logged_in() && $args->theme_location == 'primary' ) {
      $items .= '<li id="nkms-acc-menu" class="menu-item menu-item-has-children"><a href="'. site_url('login/') .'"><img src="'. plugins_url('assets/images/nkms-account.png', __FILE__ ) . '" width="25" height="25" /></a>'
              . '<ul class="sub-menu"><li  class="menu-item"><a href="'. site_url('login/') .'">Soar Account</a></li>'
              . '<li  class="menu-item"><a href="'. wp_logout_url( home_url() ) .'">Log Out</a></li></ul></li>';
    }
    // Else display login menu item.
    elseif ( ! is_user_logged_in() && $args->theme_location == 'primary' ) {
      $items .= '<li id="nkms-acc-menu" class="menu-item menu-item-has-children"><a href="'. site_url('login/') .'"><img src="'. plugins_url('assets/images/nkms-account.png', __FILE__ ) . '" width="25" height="25" /></a>'
              . '<ul class="sub-menu"><li  class="menu-item"><a href="'. site_url('login/') .'">Login</a></li>'
              . '<li  class="menu-item"><a href="'. site_url('register/') .'">Sign Up</a></li></ul></li>';
    }
    return $items;
}

/*
 * Create a column. And maybe remove some of the default ones
 * @param array $columns Array of all user table columns {column ID} => {column Name}
 */
add_filter( 'manage_users_columns', 'rudr_modify_user_table' );
function rudr_modify_user_table( $columns ) {
  // unset( $columns['posts'] ); // maybe you would like to remove default columns
	$columns['registration_date'] = 'Registration date'; // add new

	return $columns;
}

/*
 * Fill our new column with the registration dates of the users
 * @param string $row_output text/HTML output of a table cell
 * @param string $column_id_attr column ID
 * @param int $user user ID (in fact - table row ID)
 */
add_filter( 'manage_users_custom_column', 'rudr_modify_user_table_row', 10, 3 );
function rudr_modify_user_table_row( $row_output, $column_id_attr, $user ) {
  $date_format = 'j M, Y H:i';
	switch ( $column_id_attr ) {
		case 'registration_date' :
			return date( $date_format, strtotime( get_the_author_meta( 'registered', $user ) ) );
			break;
		default:
	}
	return $row_output;
}

/*
 * Make our "Registration date" column sortable
 * @param array $columns Array of all user sortable columns {column ID} => {orderby GET-param}
 */
add_filter( 'manage_users_sortable_columns', 'rudr_make_registered_column_sortable' );
function rudr_make_registered_column_sortable( $columns ) {
  return wp_parse_args( array( 'registration_date' => 'registered' ), $columns );
}

//Check if a user has a role
function nkms_has_role($user, $role) {
	// $roles = $user->roles;
	return in_array($role, (array) $user->roles);
}

// is Dance school or Teacher
function nkms_can_manage_dance_school( $dance_school_id, $user_id ) {
  if ( $dance_school_id == $user_id ) {
    return true;
  }
  else {
    if ( $dance_school_id ) {
      $dance_school = get_userdata( $dance_school_id );
      $dance_school_teachers = $dance_school->nkms_dance_school_fields['dance_school_teachers_list'];
      if ( in_array( $user_id, $dance_school_teachers ) ) {
        return true;
      }
    }
    return false;
  }
}

// is Dancer or Guardian
function nkms_can_manage_dancer( $dancer_id, $user_id ) {
  if ( $dancer_id == $user_id ) {
    return true;
  }
  else {
    if ( $dancer_id ) {
      $dancer = get_userdata( $dancer_id );
      $dancer_guardian_list = $dancer->nkms_dancer_fields['dancer_guardian_list'];
      if ( is_array( $dancer_guardian_list ) ) {
        if ( in_array( $user_id, $dancer_guardian_list ) ) {
          return true;
        }
      }
    }
    return false;
  }
}

// returns ID of dance school if teacher can manage
function nkms_is_teacher( $user_id ) {
  $dance_school_id = 0;
  if ( is_user_logged_in() ) {
    $dancer = get_userdata( $user_id );
    if ( nkms_has_role( $dancer, 'dancer' ) ) {
      $teacher_of = $dancer->nkms_dancer_fields['dancer_teacher_of'];
      if ( ! empty( $teacher_of ) ) {
        $dance_school_id = $teacher_of[0];
      }
    }
    if ( nkms_has_role( $dancer, 'dance-school' ) ) {
      $dance_school_id = get_current_user_id();
    }
  }
  return $dance_school_id;
}

// function nkms_guardian_get_dancers( $guardian ) {
//   return $guardian->nkms_guardian_fields['guardian_dancers_list'];
// }

/*
 * Invite system
 */
function nkms_invitations() {
  // Dancer accepts invite from dance school
  // if ( isset( $_POST['dancer_invite_accept'] ) ) {
  //   // get dancer & dance school objects
  //   $dance_school_id = intval( $_POST['dancer_invite_dance_school_id'] );
  //   $dance_school = get_user_by( 'id', $dance_school_id );
  //   $dancer_id = intval( $_POST['dancer_invite_dancer_id'] );
  //   $dancer = get_user_by( 'id', $dancer_id );
  //
  //   // add dancer to dance school list of dancers
  //   $dance_school_fields = $dance_school->nkms_dance_school_fields;
  //   if ( ! in_array( $dancer_id, $dance_school_fields['dance_school_dancers_list'] ) ) {
  //     array_push( $dance_school_fields['dance_school_dancers_list'], $dancer_id );
  //   }
  //   update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
  //
  //   // remove dance school id from dancer_invites
  //   $dancer_fields = $dancer->nkms_dancer_fields;
  //   $dancer_fields['dancer_invites']['dance_school'] = array_diff( $dancer_fields['dancer_invites']['dance_school'], [$dance_school_id] );
  //   update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
  // }
}

/*
 * USER PROFILE
 *
 * show_user_profile: show on frontend when user editing their own profile
 * edit_user_profile: show on backend when admin edits other users
 */

// Update Dance school details
function nkms_update_ds_details() {
  // if ( isset( $_POST['update_ds_details'] ) ) {
  //   $dance_school_id = intval( $_POST['update_ds_details_ds_id'] );
  //   $dance_school = get_userdata( $dance_school_id );
  //   $dance_school_fields = $dance_school->nkms_dance_school_fields;
  //
  //   $dance_school_fields['dance_school_name'] = $_POST['ds_details_dance_school_name'];
  //   $dance_school_fields['dance_school_address'] = $_POST['ds_details_dance_school_address'];
  //   $dance_school_fields['dance_school_phone_number'] = $_POST['ds_details_dance_school_phone_number'];
  //   $dance_school_fields['dance_school_description'] = $_POST['ds_details_dance_school_description'];
  //
  //   $save_fields = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
  //   if ( $save_fields ) {
  //     echo '<p class="text-info">Details have been saved.</p>';
  //   }
  //   else {
  //     echo '<p class="text-danger">Something went wrong, details were not saved.</p>';
  //   }
  // }
}

/*
 * REGISTRATION
 *
 */
function registration_validation( $username, $password, $first_name, $last_name, $email, $phone_number, $dob, $country, $city, $address, $postcode,
$dancer_guardian_name, $dancer_guardian_phone_number, $dancer_guardian_email, $role,
$dancer_level, $dancer_ds_name, $dancer_ds_teacher_name, $dancer_ds_teacher_email,
$dance_school_name, $dance_school_address, $dance_school_phone_number, $dance_school_description )  {
  global $reg_errors;
  $reg_errors = new WP_Error;

  // Check if fields are empty
  if ( empty( $username ) || empty( $password ) || empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $phone_number ) || empty( $dob ) || empty( $country ) || empty( $city ) || empty( $address ) || empty( $postcode ) || empty( $role ) ) {
    $reg_errors->add('field', 'All fields are required.' );
  }
  // Check if username is more than 4 chars.
  if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters required.' );
  }
  // WP function. Checks if username exists.
  if ( username_exists( $username ) ) {
    $reg_errors->add('user_name', 'The username already exists!');
  }
  // WP function. Checks if username is valid
  if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'The username you entered is not valid.' );
  }
  // Password more than 6 chars
  if ( 5 > strlen( $password ) ) {
    $reg_errors->add( 'password', 'Password length must be greater than 5.' );
  }
  // Check if email is valid
  if ( ! is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid.' );
  }
  //Check if email is in use
  if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use!' );
  }
  $age = nkms_get_age( $dob );
  if ( $role === 'guardian' && $age < 18 ) {
    $reg_errors->add( 'guardian', 'You have to be an adult in order to own a guardian account.' );
  }
  if ( $age < 18 ) {
    if ( empty( $dancer_guardian_name ) || empty( $dancer_guardian_phone_number ) ) {
      $reg_errors->add( 'dancer_guardian', 'Guardian details are required if you are underage.' );
    }
    if ( ! is_email( $dancer_guardian_email ) && empty( $reg_errors->errors['email_invalid'] ) ) {
      $reg_errors->add( 'teacher_email_invalid', 'Guardian email is not valid.' );
    }
  }
  if ( $role === 'dancer' && empty( $reg_errors->errors['field'] ) ) {
    if ( empty( $dancer_level ) || empty( $dancer_ds_name ) || empty( $dancer_ds_teacher_name ) || empty( $dancer_ds_teacher_email ) ) {
      $reg_errors->add('field', 'All fields are required.' );
    }
    if ( ! is_email( $dancer_ds_teacher_email ) && empty( $reg_errors->errors['email_invalid'] ) ) {
      $reg_errors->add( 'teacher_email_invalid', 'Teacher email is not valid.' );
    }
  }
  if ( $role === 'dance-school' && empty( $reg_errors->errors['field'] ) ) {
    if ( empty( $dance_school_name ) || empty( $dance_school_address ) || empty( $dance_school_phone_number ) || empty( $dance_school_description ) ) {
      $reg_errors->add('field', 'All fields are required.' );
    }
  }
  //Loop through errors & display them
  if ( is_wp_error( $reg_errors ) ) {
    echo '<div id="registration-errors">';
    foreach ( $reg_errors->get_error_messages() as $error ) {
      echo '<span class="text-danger">' . $error . '</span><br/>';
    }
    echo '<p></p></div>';
  }
}

// Validate & Sanitize
function nkms_custom_registration() {
  if ( isset( $_POST['registration_submit'] ) ) {
    registration_validation(
      $_POST['reg_username'],
      $_POST['reg_password'],
      $_POST['reg_first_name'],
      $_POST['reg_last_name'],
      $_POST['reg_email'],
      $_POST['reg_phone_number'],
      $_POST['reg_dob'],
      $_POST['billing_country'],
      $_POST['reg_city'],
      $_POST['reg_address'],
      $_POST['reg_postcode'],
      // if not adult
      $_POST['reg_dancer_guardian_name'],
      $_POST['reg_dancer_guardian_phone_number'],
      $_POST['reg_dancer_guardian_email'],
      ( isset( $_POST['reg_sel_role'] ) ? $_POST['reg_sel_role'] : '' ),
      // if dancer
      ( isset( $_POST['reg_dancer_level'] ) ? $_POST['reg_dancer_level'] : '' ),
      $_POST['reg_dancer_ds_name'],
      $_POST['reg_dancer_ds_teacher_name'],
      $_POST['reg_dancer_ds_teacher_email'],
      // if dance school
      $_POST['reg_dance_school_name'],
      $_POST['reg_dance_school_address'],
      $_POST['reg_dance_school_phone_number'],
      $_POST['reg_dance_school_description'],
    );

    // sanitize user form input.
    global $username, $password, $email, $first_name, $last_name, $role,
    $dob, $phone_number, $country, $city, $address, $postcode,
    $dancer_ds_name, $dancer_ds_teacher_name, $dancer_ds_teacher_email, $level,
    $dancer_guardian_name, $dancer_guardian_phone_number, $dancer_guardian_email,
    $dance_school_name, $dance_school_address, $dance_school_phone_number, $dance_school_description;

    $username     = sanitize_user( $_POST['reg_username'] );
    $password     = esc_attr( $_POST['reg_password'] );
    $first_name   = sanitize_text_field( $_POST['reg_first_name'] );
    $last_name    = sanitize_text_field( $_POST['reg_last_name'] );
    $email        = sanitize_email( $_POST['reg_email'] );
    $phone_number = sanitize_email( $_POST['reg_phone_number'] );
    $dob          = sanitize_text_field( $_POST['reg_dob'] );
    $country      = $_POST['billing_country'];
    $city         = sanitize_text_field( $_POST['reg_city'] );
    $address      = sanitize_text_field( $_POST['reg_address'] );
    $postcode     = sanitize_text_field( $_POST['reg_postcode'] );
    $role         = ( isset( $_POST['reg_sel_role'] ) ? $_POST['reg_sel_role'] : '' );
    // $address      = sanitize_text_field( $_POST['reg_address'] );
    $phone_number = sanitize_text_field( $_POST['reg_phone_number'] );
    $level        = ( isset( $_POST['reg_dancer_level'] ) ? $_POST['reg_dancer_level'] : '' );

    // dancer fields
    if ( $role === 'dancer' ) {
      $dancer_ds_name = sanitize_text_field( $_POST['reg_dancer_ds_name'] );
      $dancer_ds_teacher_name = sanitize_text_field( $_POST['reg_dancer_ds_teacher_name'] );
      $dancer_ds_teacher_email = sanitize_email( $_POST['reg_dancer_ds_teacher_email'] );

      // guardian details
      $dancer_guardian_name = ( isset( $_POST['reg_dancer_guardian_name'] ) ? sanitize_text_field( $_POST['reg_dancer_guardian_name'] ) : '' );
      $dancer_guardian_phone_number = ( isset( $_POST['reg_dancer_guardian_phone_number'] ) ? sanitize_text_field( $_POST['reg_dancer_guardian_phone_number'] ) : '' );
      $dancer_guardian_email = ( isset( $_POST['reg_dancer_guardian_email'] ) ? sanitize_email( $_POST['reg_dancer_guardian_email'] ) : '' );
    }

    // teacher fields
    if ( $role === 'teacher' ) {
      $role = 'dancer';
      $dancer_ds_name = sanitize_text_field( $_POST['reg_dancer_ds_name'] );
      $dancer_ds_teacher_name = $first_name . ' ' . $last_name;
      $dancer_ds_teacher_email = $email;
    }

    // dance school fields
    if ( $role === 'dance-school' ) {
      $dance_school_name = sanitize_text_field( $_POST['reg_dance_school_name'] );
      $dance_school_address = sanitize_text_field( $_POST['reg_dance_school_address'] );
      $dance_school_phone_number = sanitize_text_field( $_POST['reg_dance_school_phone_number'] );
      $dance_school_description = sanitize_textarea_field( $_POST['reg_dance_school_description'] );
    }

    // call @function complete_registration to create the user
    // only when no WP_error is found
    complete_registration();
  }
}

//Complete registration
function complete_registration() {
  global $reg_errors, $username, $password, $email, $first_name, $last_name, $role;
  //if dance school set custom fields to empty array.
  $empty_array = [];
  if ( 1 > count( $reg_errors->get_error_messages() ) ) {
    $userdata = array(
    'user_login'    =>   $username,
    'user_email'    =>   $email,
    'user_pass'     =>   $password,
    'first_name'    =>   $first_name,
    'last_name'     =>   $last_name,
    'role'          =>   $role
    );
    $user_id = wp_insert_user( $userdata );
    if ( $user_id ) {
      //initialize custom fields
      user_initialization( $user_id );
      echo '<h4>Registration complete. You may login <a href="' . get_site_url() . '/login">here</a>.</h4>';
    }
    else {
      echo '<h4>Something went wrong. Please try again later!</h4>';
    }
  }
}

// Initialize user & custom fields
function user_initialization( $user_id ) {
  global $role, $dob, $phone_number, $country, $city, $address, $postcode,
  $dancer_ds_name, $dancer_ds_teacher_name, $dancer_ds_teacher_email, $level,
  $dancer_guardian_name, $dancer_guardian_phone_number, $dancer_guardian_email,
  $dance_school_name, $dance_school_address, $dance_school_phone_number, $dance_school_description;

  // Custom fields not created with wp_insert_user (only available for basic fields)
  $nkms_fields = array(
    'dob' => $dob,
    'country' => $country,
    'city' => $city,
    'address' => $address,
    'postcode' => $postcode,
    'phone_number' => $phone_number,
  );
  $fields_save = update_user_meta( $user_id, 'nkms_fields', $nkms_fields );

  if ( $role === 'dancer' ) {
    $age = nkms_get_age( $dob );
    $age_category;
    // Age category
    if ( $age < 7 ) {
      $age_category = '6&u';
    }
    elseif ( $age == 7 || $age == 8 ) {
      $age_category = '8&u';
    }
    elseif ( $age == 9 || $age == 10 ) {
      $age_category = '10&u';
    }
    elseif ( $age == 11 || $age == 12 ) {
      $age_category = '12&u';
    }
    elseif ( $age == 13 || $age == 14 ) {
      $age_category = '14&u';
    }
    elseif ( $age == 15 || $age == 16 ) {
      $age_category = '16&u';
    }
    else {
      $age_category = '17&o';
    }

    // create & save fields related to DANCER
    $nkms_dancer_fields = array(
      'dancer_ds_name' => $dancer_ds_name,
      'dancer_ds_teacher_name' => $dancer_ds_teacher_name,
      'dancer_ds_teacher_email' => $dancer_ds_teacher_email,
      'dancer_level' => $level,
      'dancer_status' => 'Active',
      'dancer_invites' => array(
        'guardian' => array(),
        'dance_school' => array(),
      ),
      'dancer_age_category' => $age_category,
      'dancer_guardian_list' => array(),
      'dancer_guardian_name' => $dancer_guardian_name,
      'dancer_guardian_email' => $dancer_guardian_email,
      'dancer_guardian_phone_number' => $dancer_guardian_phone_number,
      'dancer_teacher_of' => array(),
      'dancer_part_of' => array(),
      'dancer_registered_to' => array(),
    );
    update_user_meta( $user_id, 'nkms_dancer_fields', $nkms_dancer_fields );
  }

  if ( $role === 'dance-school' ) {
    // create fields realated to DANCE SCHOOL
    $nkms_dance_school_fields = array(
      'dance_school_name' => $dance_school_name,
      'dance_school_address' => $dance_school_address,
      'dance_school_phone_number' => $dance_school_phone_number,
      'dance_school_description' => $dance_school_description,
      'dance_school_dancers_list' => array(),
      'dance_school_groups_list' => array(),
      'dance_school_teachers_list' => array(),
      'dance_school_invites' => array(),
      'dance_school_currently_viewing' => array(
        'dancer' => 0,
        'group' => 0
      )
    );
    update_user_meta( $user_id, 'nkms_dance_school_fields', $nkms_dance_school_fields );
  }

  if ( $role === 'guardian' ) {
    // create fields realated to DANCE SCHOOL
    $nkms_guardian_fields = array(
      'guardian_dancers_list' => array(),
      'guardian_invites' => array()
    );
    update_user_meta( $user_id, 'nkms_guardian_fields', $nkms_guardian_fields );
  }
}

// return age
function nkms_get_age( $dob ) {
  return intval( date( 'Y', time() - strtotime( $dob) ) ) - 1970;
}
