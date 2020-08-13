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
  wp_register_script( 'nkms-js', plugins_url( '/assets/nakamas-members.js', __FILE__ ), array( 'jquery' ), '20200406', true );
  wp_register_script( 'nkms-bootstrap', plugins_url( '/assets/bootstrap.min.js', __FILE__ ), array( 'jquery' ), '4.4.1', true );

  /* Register the style like this for a plugin
   * Parameters
   * Required: handle (name), src (source)
   * Optional: deps (dependencies), ver (version, used date for this), media (The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.)
   */
  wp_register_style( 'nkms-css', plugins_url( '/assets/nakamas-members.css', __FILE__ ), array(), '20200404', 'all' );
  wp_register_style( 'nkms-bootstrap', plugins_url( '/assets/bootstrap.min.css', __FILE__ ), array(), '4.4.1', 'all' );

  // For either a plugin or a theme, you can then enqueue the script/style
  wp_enqueue_script( 'nkms-js' );
  wp_enqueue_script( 'nkms-bootstrap' );
  // Load the datepicker script (pre-registered in WordPress)
  wp_enqueue_script( 'jquery-ui-datepicker' );
  wp_enqueue_style( 'nkms-css' );
  wp_enqueue_style( 'nkms-bootstrap' );

	$js_values = array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
	);
	wp_localize_script( 'nkms-js', 'nkms_ajax', $js_values );
  }

/*
 * Ajax in WP
 *
 */
 include('nakamas-functions-ajax.php');

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

//Guardian
add_role('spectator', __(
	'Spectator'),
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
 * WooCommerce
 */

/**
* Display a custom text field on product
*/
// function nkms_custom_woo_field() {
//  woocommerce_wp_text_input(
//    array(
//      'id' => 'dancer_registration_fee',
//      'label' => __( 'Dancer Registration Fee', 'cfwc' ),
//      'class' => 'nkms-registration-fee',
//      'desc_tip' => true,
//      'description' => __( 'Enter the event registration price for a dancer.', 'ctwc' ),
//    )
//  );
// }
// add_action( 'woocommerce_product_options_general_product_data', 'nkms_custom_woo_field' );

/**
* Save the custom field
*/
function nkms_save_woo_field( $post_id ) {
  $product = wc_get_product( $post_id );
  $dancer_reg_fee = isset( $_POST['dancer_registration_fee'] ) ? $_POST['dancer_registration_fee'] : '';
  $product->update_meta_data( 'dancer_registration_fee', sanitize_text_field( $dancer_reg_fee ) );
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'nkms_save_woo_field' );

//Change event price based on registered dancers
add_filter('woocommerce_product_get_price', 'nkms_change_price_dancers_fee', 10, 2);
function nkms_change_price_dancers_fee($price, $product) {
   //global post object & post id
   // global $post;
   // $product = wc_get_product( $post->ID );
   // $_POST['registered_dancers_num'] = 0;
   if ( isset( $_POST['registered_dancers_num'] ) ) {
     // var_dump($_POST['registered_dancers_num']);
     $registed_dancers_num = $_POST['registered_dancers_num'];
     $dancer_fee = $product->get_meta( 'dancer_registration_fee' );
     $price += ($registed_dancers_num * $dancer_fee);
   }

   //return the new price
   // var_dump($_POST['registered_dancers_num']);
   return $price;
}

add_action('woocommerce_before_add_to_cart_button', 'nkms_register_dancers_to_events');
function nkms_register_dancers_to_events(){
  // Check if user is logged in
  if ( is_user_logged_in() ) {
    $user = get_user_by( 'id', get_current_user_id() );
    // Check if user is Dance School type
    if ( nkms_has_role($user, 'dance-school') ) {
      echo '<div id="product-register-groups">';
      // Get groups
      $ds_groups_list_array = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);
      // $registered_dancers_arr = [];
      // Check whether dance school has groups
      if (is_array($ds_groups_list_array) || is_object($ds_groups_list_array)) {
        echo '<h3>Groups List</h3><div>';
        // List active groups
        // echo '<form action="" method="post">';
        foreach ($ds_groups_list_array as $key => $id) {
          if ( $id->getStatus() == 'Active' ) {
            echo '<p class="register-group-names">' . $id->getGroupName() . '</p>';
            $group = $ds_groups_list_array[$key];
            $group_dancers = $group->getDancers();
            // List active dancers within group
            foreach ($group_dancers as $key => $id) {
              $dncr = get_user_by( 'id', $id );
              ( $dncr->active ==  1 ) ? $status = "Active" : $status = "Inactive";
              if ( $status == 'Active' ) {
                echo '<p class="register-group-dancers"><label>' . '<input type="checkbox" name="registered_dancer_' . $id . '">' . $dncr->first_name . ' ' . $dncr->last_name . '</label></p>';
              }
            }
          }
        }
        echo '</div>';
      }
      else {
        echo '<p>No groups or dancers available. Make sure you add from your dashboard!</p>';
      }
      echo '</div>';
      // var_dump($_GET['registered_dancers_arr']);
    }
  }
}

add_action( 'woocommerce_before_calculate_totals', 'add_custom_price', 20, 1);
function add_custom_price( $cart ) {

    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Avoiding hook repetition (when using price calculations for example)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // Loop through cart items
    foreach ( $cart->get_cart() as $item ) {
        $prod_id = $item['product_id'];
        $product = wc_get_product( $prod_id );
        $dancer_fee = $product->get_meta( 'dancer_registration_fee' );
        // $item['data']->set_price( $dancer_fee );
    }

    // $cart->set_quantity($cart_item_key, $reg_dancers );
}

add_filter( 'woocommerce_add_cart_item_data', 'nkms_add_group_cart', 10, 3 );
function nkms_add_group_cart( $cart_item_data, $product_id, $variation_id ) {

  $user = get_user_by( 'id', get_current_user_id() );
  // Check if user is Dance School type
  if ( is_user_logged_in() && nkms_has_role($user, 'dance-school') ) {
    // Get groups
    $ds_groups_list_array = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);
    // $registered_dancers_arr = [];
    // Check whether dance school has groups
    if (is_array($ds_groups_list_array) || is_object($ds_groups_list_array)) {
      // List active groups
      $active_dancers = [];
      foreach ($ds_groups_list_array as $key => $id) {
        if ( $id->getStatus() == 'Active' ) {
          $group_name = $id->getGroupName();
          $group = $ds_groups_list_array[$key];
          $group_dancers = $group->getDancers();
          // List active dancers within group
          foreach ($group_dancers as $key => $id) {
            $dncr = get_user_by( 'id', $id );
            ( $dncr->active ==  1 ) ? $status = "Active" : $status = "Inactive";
            if ( $status == 'Active' ) {
              $active_dancers[$id] = $dncr->first_name . ' ' . $dncr->last_name;
            }
          }
        }
      }
    }
    else {
      echo '<p>No groups or dancers registered.</p>';
    }
    // var_dump($_GET['registered_dancers_arr']);
    $reg_dancers = [];
    foreach ( $active_dancers as $id => $name ) {
      if ( isset( $_POST['registered_dancer_'.$id] ) ) {
        if ( $_POST['registered_dancer_'.$id] === 'on' ) {
          array_push($reg_dancers, $name);
        }
      }
    }
    $cart_item_data['suki'] = $reg_dancers;
  }
  return $cart_item_data;

}

//* Display dancers in cart items.
add_filter( 'woocommerce_get_item_data', 'iconic_display_engraving_text_cart', 10, 2 );
function iconic_display_engraving_text_cart( $item_data, $cart_item ) {
	if ( empty( $cart_item['suki'] ) ) {
		return $item_data;
	}
  // Check if user is Dance School type
  if ( is_user_logged_in() ) {
    $user = get_user_by( 'id', get_current_user_id() );
    if (nkms_has_role($user, 'dance-school') ) {
      $dancers_str_tmp = implode( "<br>", $cart_item['suki'] );
      $dancers_str = '<p class="registered-dancers">' . $dancers_str_tmp;

    	$item_data[] = array(
    		'key'     => 'Dancers',
    		'value'   => $dancers_str,
    		'display' => '',
    	);

      $prod_id = $cart_item['product_id'];
      $product = wc_get_product( $prod_id );
      $dancer_fee = $product->get_price();
      // var_dump($dancer_fee);
      $cart_item['data']->set_price( sizeof($cart_item['suki']) * $dancer_fee );
      return $item_data;
    }
  }
}

/**
 * Display custom field on the front end
 */
// function nkms_display_woo_field() {
//   global $post;
//   // Check for the custom field value
//   $product = wc_get_product( $post->ID );
//   $title = $product->get_meta( 'dancer_registration_fee' );
//   if( $title ) {
//   // Only display our field if we've got a value for the field title
//   printf( '<div class="nkms-dancer-registration-fee"><label for="nkms-registration-fee">%s</label><input type="text" id="nkms-registration-fee" name="nkms-registration-fee" value=""></div>', esc_html( $title ) );
//   }
// }
// add_action( 'woocommerce_before_add_to_cart_button', 'nkms_display_woo_field' );

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

  if ( isset($_POST['update_ds_info']) ) {
    $ds_name = $_POST['dance_school_name'];
    $ds_address = $_POST['dance_school_address'];
    $ds_phone_number = $_POST['dance_school_phone_number'];
    $ds_description = $_POST['dance_school_description'];

    update_user_meta( $user->ID, 'dance_school_name', $ds_name );
    update_user_meta( $user->ID, 'dance_school_address', $ds_address );
    update_user_meta( $user->ID, 'dance_school_phone_number', $ds_phone_number );
    update_user_meta( $user->ID, 'dance_school_description', $ds_description );
  }

	// Dancer
	?>

	<!-- Dance School -->
	<h3>Dance School</h3>

	<p>
    <label for="dance_school_name"><?php esc_html_e( 'Dance School Name', 'nkms' ); ?></label>
    <input type="text" name="dance_school_name" value="<?php echo esc_attr( $ds_name ); ?>" class="regular-text" />
	</p>
  <p>
    <label for="dance_school_address"><?php esc_html_e( 'Dance School Address', 'nkms' ); ?></label>
    <input type="text" name="dance_school_address" value="<?php echo esc_attr( $ds_address ); ?>" class="regular-text" />
	</p>
	<p>
    <label for="dance_school_phone_number"><?php esc_html_e( 'Dance School Phone Number', 'nkms' ); ?></label>
    <input type="text" name="dance_school_phone_number" value="<?php echo esc_attr( $ds_phone_number ); ?>" class="regular-text" />
	</p>
  <p>
    <label for="dance_school_description"><?php esc_html_e( 'Dance School Description', 'nkms' ); ?></label>
    <textarea rows="5" name="dance_school_description" class="regular-text"><?php echo esc_html( $ds_description ); ?></textarea>
	</p>
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

/*
 * REGISTRATION
 *
 */
function registration_validation( $username, $password, $email, $first_name, $last_name )  {
  global $reg_errors;
  $reg_errors = new WP_Error;

  //Check if fields are empty
  if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $first_name ) || empty( $last_name ) ) {
    $reg_errors->add('field', 'All fields are equired.');
  }

  //Check if username is more than 4 chars.
  if ( 4 > strlen( $username ) ) {
    $reg_errors->add( 'username_length', 'Username too short. At least 4 characters required.' );
  }

  //WP function. Checks if username exists.
  if ( username_exists( $username ) ) {
    $reg_errors->add('user_name', 'Sorry, that username already exists!');
  }

  //WP function. Checks if username is valid
  if ( ! validate_username( $username ) ) {
    $reg_errors->add( 'username_invalid', 'Sorry, the username you entered is not valid.' );
  }

  //Password more than 6 chars
  if ( 5 > strlen( $password ) ) {
    $reg_errors->add( 'password', 'Password length must be greater than 5.' );
  }

  //Check if email is valid
  if ( !is_email( $email ) ) {
    $reg_errors->add( 'email_invalid', 'Email is not valid.' );
  }
  //Check if email is in use
  if ( email_exists( $email ) ) {
    $reg_errors->add( 'email', 'Email Already in use!' );
  }

  //Loop through errors & display them
  if ( is_wp_error( $reg_errors ) ) {
    echo '<div id="registration-errors">';
    foreach ( $reg_errors->get_error_messages() as $error ) {
      echo '<strong style="color:red;">' . $error . '</strong><br/>';
    }
    echo '<p></p></div>';
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
    $user = wp_insert_user( $userdata );
    echo '<h4>Registration complete. You may login <a href="' . get_site_url() . '/login">here</a>.</h4>';
  }
}
// Validate & Sanitize
function nkms_custom_registration() {
  if ( isset($_POST['registration_submit'] ) ) {
    registration_validation(
      $_POST['username'],
      $_POST['password'],
      $_POST['email'],
      $_POST['first_name'],
      $_POST['last_name'],
      $_POST['dob']
    );

    // sanitize user form input.
    global $username, $password, $email, $first_name, $last_name, $role, $dob, $xp;
    $username   = sanitize_user( $_POST['username'] );
    $password   = esc_attr( $_POST['password'] );
    $email      = sanitize_email( $_POST['email'] );
    $first_name = sanitize_text_field( $_POST['first_name'] );
    $last_name  = sanitize_text_field( $_POST['last_name'] );
    $dob        = sanitize_text_field( $_POST['dob'] );
    $xp         = $_POST['dancer_experience'];
    $role       = $_POST['sel_role'];

    // call @function complete_registration to create the user
    // only when no WP_error is found
    complete_registration();
  }
}
