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

// Add avatar support
add_action('init','nkms_avatar_filter');
function nkms_avatar_filter(){

  // Remove from show_user_profile hook
  remove_action('show_user_profile', array('wp_user_avatar', 'wpua_action_show_user_profile'));
  remove_action('show_user_profile', array('wp_user_avatar', 'wpua_media_upload_scripts'));

  // Remove from edit_user_profile hook
  remove_action('edit_user_profile', array('wp_user_avatar', 'wpua_action_show_user_profile'));
  remove_action('edit_user_profile', array('wp_user_avatar', 'wpua_media_upload_scripts'));

  // Add to edit_user_avatar hook
  add_action('edit_user_avatar', array('wp_user_avatar', 'wpua_action_show_user_profile'));
  add_action('edit_user_avatar', array('wp_user_avatar', 'wpua_media_upload_scripts'));
  // Loads only outside of administration panel
  if(!is_admin()) {
    add_action('init','my_avatar_filter');
  }
}

//Check if a user has a role
function nkms_has_role($user, $role) {
	// $roles = $user->roles;
	return in_array($role, (array) $user->roles);
}

// is Dance school or Teacher
function nkms_can_manage_dance_school($dance_school_id, $user_id) {
  if ( $dance_school_id === $user_id ) {
    return true;
  }
  else {
    $dance_school_teachers = get_user_meta( $dance_school_id, 'dance_school_teachers_list', true );
    if ( in_array( $user_id, $dance_school_teachers ) ) {
      return true;
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

function nkms_guardian_get_dancers( $guardian ) {
  return $guardian->nkms_guardian_fields['guardian_dancers_list'];
}

// Fix user meta
function nkms_fix_user_meta() {
  $users_list = get_users();
  foreach ( $users_list as $user ) {
    if ( $user->ID > 1 ) {
      echo '<strong>' . $user->ID . ': ' . $user->roles[0] . '</strong><br>';
      echo '<u>Basic fields</u><br>';
      // Name
      echo 'Full Name: ';
      echo $user->first_name . ' ' . $user->last_name . '<br>';
      echo 'DOB: ' . $user->nkms_fields['dob'] . '<br>';
      // Address
      echo 'Address: ' . $user->nkms_fields['address'] . '<br>';
      // Phone
      echo 'Phone number: ' . $user->nkms_fields['phone_number'] . '<br>';
      // XP
      echo 'Experience: ' . $user->nkms_fields['experience'] . '<br>';
      if ( $user->roles[0] === 'dancer' ) {
        echo '<u>Dancer fields</u><br>';
        // Age category
        echo 'Age category: ' . $user->nkms_dancer_fields['dancer_age_category'];
        echo '<br>';
        // Dance school name
        echo 'Dance School name: ' . $user->nkms_dancer_fields['dancer_ds_name'];
        echo '<br>';
        // Dance school teacher name
        echo 'Dance School teacher name: ' . $user->nkms_dancer_fields['dancer_ds_teacher_name'];
        echo '<br>';
        // Dance school teacher email
        echo 'Dance School teacher email: ' . $user->nkms_dancer_fields['dancer_ds_teacher_email'];
        echo '<br>';
        // Dancer status
        echo 'Dancer status: ' . $user->nkms_dancer_fields['dancer_status'];
        echo '<br>';
        // Dancer invites
        echo '<u>Dancer invites:</u><br>';
        // var_dump($user->nkms_dancer_fields['dancer_invites']['guardian']);
        echo 'Guardian: ' . implode( ', ', $user->nkms_dancer_fields['dancer_invites']['guardian'] ) . '<br>';
        echo 'Dance School: ' . implode( ', ', $user->nkms_dancer_fields['dancer_invites']['dance_school'] );
        // print_r( $user->dancer_invites );
        echo '<br>';
        // Dancer Guaridan
        echo 'Guardian ID: ' . implode( ', ', $user->nkms_dancer_fields['dancer_guardian_list']);
        echo '<br>';
        // Dancer Guaridan
        if ( ! empty( $user->nkms_dancer_fields['dancer_teacher_of'] ) ) {
          echo 'Teacher of: ' . implode( ', ', $user->nkms_dancer_fields['dancer_teacher_of']);
        }
        else { echo 'Teacher of: N/A'; }
        echo '<br>';
      }
      if ( $user->roles[0] === 'dance-school' ) {
        echo '<u>Dance School fields</u><br>';
        // DS name
        echo 'Name: ' . $user->nkms_dance_school_fields['dance_school_name'];
        echo '<br>';
        // Dance school address
        echo 'Address: ' . $user->nkms_dance_school_fields['dance_school_address'];
        echo '<br>';
        // Dance school phone number
        echo 'Phone number: ' . $user->nkms_dance_school_fields['dance_school_phone_number'];
        echo '<br>';
        // Dance school description
        echo 'Description: ' . $user->nkms_dance_school_fields['dance_school_description'];
        echo '<br>';
        // Dancers list
        echo 'Dancers list: ' . implode( ', ', $user->nkms_dance_school_fields['dance_school_dancers_list'] );
        echo '<br>';
        // Dance groups list
        echo 'Groups list: <br>';
        $groups_list = $user->nkms_dance_school_fields['dance_school_groups_list'];
        foreach ( $groups_list as $key=>$group ) {
          echo $key . '. ' . $group->getGroupName();
        }
        echo '<br>';
        // Dancer invites
        echo 'Dancers invites: ' . implode( ', ', $user->nkms_dance_school_fields['dance_school_invites'] );
        echo '<br>';
        // DS currently looking
        echo 'Currently looking: ';
        // . implode( ', ', $user->nkms_dance_school_fields['dance_school_currently_viewing'] );
        foreach ( $user->nkms_dance_school_fields['dance_school_currently_viewing'] as $key => $value ) {
          echo '<br>' . $key . ': ' . $value;
        }
        echo '<br>';
      }
      if ( $user->roles[0] === 'guardian' ) {
        echo '<u>Guardian fields</u><br>';
        // Age category
        echo 'Dancers list: ' . implode( ', ', $user->nkms_guardian_fields['guardian_dancers_list'] );
        echo '<br>';
      }
    }
  }
}

/*
 * Invite system
 */
function nkms_invitations() {
  // Dancer accepts invite from guardian
  if ( isset ( $_POST['guardian_dancer_invite_accept'] ) ) {
    $dancer_id = intval( $_POST['guardian_invite_dancer_id'] );
    $dancer = get_user_by( 'id', $dancer_id );
    $guardian_id = intval( $_POST['guardian_invite_guardian_id'] );
    $guardian = get_user_by( 'id', $guardian_id );
    $dancer_fields = $dancer->nkms_dancer_fields;

    // get dancer_invites['guardian'] array from dancer fields
    $guardian_invites = $dancer_fields['dancer_invites']['guardian'];
    // get dancer_guardian_list array from dancer fields
    $dancer_guardian_list = $dancer_fields['dancer_guardian_list'];
    // add guardian_id to dancer_guardian_list
    if ( ! in_array( $guardian_id, $dancer_guardian_list ) ) {
      array_push( $dancer_guardian_list, $guardian_id );
    }
    $dancer_fields['dancer_guardian_list'] = $dancer_guardian_list;
    //remove invite from array
    $guardian_invites = array_diff( $guardian_invites, [$guardian_id] );
    $dancer_fields['dancer_invites']['guardian'] = $guardian_invites;
    // save dancer_guardian_list
    update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
    // get guardian_dancers_list from guardian fields
    $guardian_fields = $guardian->nkms_guardian_fields;
    $guardian_dancers_list = $guardian_fields['guardian_dancers_list'];
    // add dancer_id to guardian_dancers_list
    if ( ! in_array( $dancer_id, $guardian_dancers_list ) ) {
      array_push( $guardian_dancers_list, $dancer_id );
    }
    $guardian_fields['guardian_dancers_list'] = $guardian_dancers_list;
    // save guardian_dancers_list
    update_user_meta( $guardian_id, 'nkms_guardian_fields', $guardian_fields );
  }
  // Dancer declines invite from guardian
  if ( isset ( $_POST['guardian_dancer_invite_decline'] ) ) {
    $dancer_id = intval( $_POST['guardian_invite_dancer_id'] );
    $dancer = get_user_by( 'id', $dancer_id );
    $guardian_id = intval( $_POST['guardian_invite_guardian_id'] );
    $guardian = get_user_by( 'id', $guardian_id );
    $dancer_fields = $dancer->nkms_dancer_fields;

    // get dancer_invites['guardian'] array from dancer fields
    $guardian_invites = $dancer_fields['dancer_invites']['guardian'];
    // get dancer_guardian_list array from dancer fields
    $guardian_invites = array_diff( $guardian_invites, [$guardian_id] );
    $dancer_fields['dancer_invites']['guardian'] = $guardian_invites;
    // save dancer_guardian_list
    update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
  }

  // Dancer requests to join a dance school
  if ( isset( $_POST['dancer_request_invite'] ) ) {
    $dance_school_id = intval( $_POST['request_dance_school_name'] );
    $dancer_id = intval( $_POST['request_invite_dancer_id'] );
    var_dump($dance_school_id);
    var_dump($dancer_id);

    if ( ! empty( $dance_school_id ) ) {
      $ds = get_user_by( 'id', $dance_school_id );
      $ds_invites_list = get_user_meta( $ds->ID, 'dance_school_invites', true );
      if ( ! in_array( $dancer_id, $ds_invites_list ) ) {
        // array_push( $ds_invites_list, $dancer_id );
        var_dump($ds_invites_list);
      }
    }
  }
  // Dancer accepts invite from dance school
  if ( isset( $_POST['dancer_invite_accept'] ) ) {
    // get dancer & dance school objects
    $dance_school_id = intval( $_POST['dancer_invite_dance_school_id'] );
    $dance_school = get_user_by( 'id', $dance_school_id );
    $dancer_id = intval( $_POST['dancer_invite_dancer_id'] );
    $dancer = get_user_by( 'id', $dancer_id );

    // add dancer to dance school list of dancers
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    if ( ! in_array( $dancer_id, $dance_school_fields['dance_school_dancers_list'] ) ) {
      array_push( $dance_school_fields['dance_school_dancers_list'], $dancer_id );
    }
    update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );

    // remove dance school id from dancer_invites
    $dancer_fields = $dancer->nkms_dancer_fields;
    $dancer_fields['dancer_invites']['dance_school'] = array_diff( $dancer_fields['dancer_invites']['dance_school'], [$dance_school_id] );
    update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
  }
}

/*
 * WOOCOMMERCE!
**/
// change woo required user fields
add_filter('woocommerce_save_account_details_required_fields', 'nkms_woo_required_fields');
function nkms_woo_required_fields( $account_fields ) {
  unset( $account_fields['account_last_name'] );
	// unset( $account_fields['account_first_name'] ); // First name
	// unset( $account_fields['account_display_name'] ); // Display name
	return $required_fields;
}



// Display a custom text field on product
add_action( 'woocommerce_product_options_general_product_data', 'nkms_custom_woo_field' );
function nkms_custom_woo_field() {
//  woocommerce_wp_text_input(
//    array(
//      'id' => 'event_date',
//      'label' => __( 'Event date', 'cfwc' ),
//      'class' => 'nkms-event-date',
//      'desc_tip' => true,
//      'description' => __( 'Enter the date of the event.', 'ctwc' ),
//    )
//  );
}
//
// Save the custom field
add_action( 'woocommerce_process_product_meta', 'nkms_save_woo_field' );
function nkms_save_woo_field( $post_id ) {
//   $product = wc_get_product( $post_id );
//   $dancer_reg_fee = isset( $_POST['event_date'] ) ? $_POST['event_date'] : '';
//   $product->update_meta_data( 'event_date', sanitize_text_field( $dancer_reg_fee ) );
//   $product->save();
}
//
// Display custom field on the front end
add_action( 'woocommerce_before_add_to_cart_button', 'nkms_display_woo_field' );
function nkms_display_woo_field() {
//   global $post;
//   // Check for the custom field value
//   $product = wc_get_product( $post->ID );
//   $title = $product->get_meta( 'event_date' );
//   if( $title ) {
//   // Only display our field if we've got a value for the field title
//   printf( '<div class="nkms-event-date"><span>Date</span><label for="nkms-event-date">%s</label></div>', esc_html( $title ) );
//   }
}

// Hide Dancer Registration category if not needed
add_filter( 'woocommerce_product_query_tax_query', 'nkms_hide_dancer_registration');
function nkms_hide_dancer_registration ( $tax_query ) {
  if ( nkms_has_role( wp_get_current_user(), 'Administrator' ) ) {
  	$user = wp_get_current_user();
  	// $blocked_user_roles = array( 'dancer', 'administrator' );
    // $hidden_categories = array( 'Dancer Registration' );
    if ( ! is_user_logged_in() || ! nkms_is_teacher( $user->ID ) ) {
  		$tax_query[] = array(
        'taxonomy' => 'product_cat',
  			'terms'    => 'Dancer Registration',
  			'field'    => 'slug',
  			'operator' => 'NOT IN'
  		);
    }
  }
  return $tax_query;
}

// List active dancers (if solo) or active groups to register to events
add_action('woocommerce_before_add_to_cart_button', 'nkms_register_dancers_to_events');
function nkms_register_dancers_to_events() {
  // if teacher
  $dance_school_id = nkms_is_teacher( get_current_user_id() );
  if ( is_user_logged_in() && $dance_school_id ) {
    $dance_school = get_userdata( $dance_school_id );
    // Get woo product categories
    global $product;
    $product_id = $product->get_id();
    $product_categories = wc_get_product_category_list( $product_id );
    // Check if event is for dancers so they can register
    if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
      // if category is Solo
      if ( strpos( $product_categories, 'Solo' ) !== false ) {
        echo '<h3>Dancers List</h3>';
        $registered_dancers = array();
        $ds_dancers = $dance_school->nkms_dance_school_fields['dance_school_dancers_list'];
        foreach ( $ds_dancers as $dancer_id ) {
          $dancer = get_userdata( $dancer_id );
          if ( $dancer->nkms_dancer_fields['dancer_status'] == 'Active' ) {
            array_push( $registered_dancers, $dancer->ID );
            echo '<p class="register-group-dancers"><label>' . '<input type="checkbox" name="registered_dancers[]" value="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</label></p>';
          }
        }
      }
      // otherwise category is Groups
      else {
        // Get groups
        $ds_groups_list = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
        echo '<h3>Groups List</h3>';
        // Check whether dance school has groups
        if ( ! empty( $ds_groups_list ) ) {
          // Get active groups matching the event category (Duo, Team etc.)
          $print_active_groups_from_category = array();
          $registered_groups = array();
          foreach ( $ds_groups_list as $group_id => $group ) {
            //check if group category is within product categories, if group is active and has members
            if ( strpos( $product_categories, $group->getType() ) !== false && $group->getStatus() == 'Active' && $group->getSize() ) {
              $print_active_groups_from_category[$group_id] = $group;
              array_push( $registered_groups, $group_id );
            }
          }
          if ( ! empty( $print_active_groups_from_category ) ) {
            foreach ( $print_active_groups_from_category as $group_id => $group ) {
              echo '<p class="register-group-names">' . $group->getGroupName() . '</p>';
              $group_dancers = $group->getDancers();
              // List active dancers within group
              foreach ( $group_dancers as $dancer_id ) {
                $dancer = get_userdata( $dancer_id );
                if ( $dancer->nkms_dancer_fields['dancer_status'] == 'Active' ) {
                  echo '<p class="register-group-dancers"><label>' . '<input type="checkbox" name="registered_dancers[]" value="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</label></p>';
                }
              }
            }
          }
          else {
            echo '<p>No active groups found.</p>';
          }
        }
        else {
          echo '<p>No groups available. Make sure to add from your dashboard!</p>';
        }

        echo '<input type="hidden" name="register_dancers_dance_school_id" value="' . $dance_school_id . '"/>';
        $registered_groups = base64_encode( json_encode( $registered_groups ) );
        echo '<input type="hidden" name="register_groups_array" value="' . $registered_groups . '"/>';
      }
    }
  }
}

// Validate data - warn user if no dancers selected
add_filter( 'woocommerce_add_to_cart_validation', 'nkms_add_to_cart_validation', 10, 4 );
function nkms_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {
  $product_categories = wc_get_product_category_list( $product_id );
  if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
    if ( empty( $_POST['registered_dancers'] ) ) {
      $passed = false;
      wc_add_notice( __( 'You must select at least one dancer to register.', 'nkms' ), 'error' );
    }
  }
  return $passed;
}

// Custom cart item data: display group & dancers
add_filter( 'woocommerce_add_cart_item_data', 'nkms_add_group_cart', 10, 3 );
function nkms_add_group_cart( $cart_item_data, $product_id, $variation_id ) {
  if ( isset ( $_POST['registered_dancers'] ) ) {
    $cart_item_data['registered_dancers_num'] = sizeof( $_POST['registered_dancers'] );
    $registered_dancers = $_POST['registered_dancers'];
    // if dance_school_id is not set, it's a solo event (no groups)
    if ( ! isset( $_POST['register_dancers_dance_school_id'] ) ){
      foreach ( $registered_dancers as $key => $dancer_id ) {
        $dancer = get_userdata( $dancer_id );
        $registered_dancers[$key] = $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name;
      }
      $cart_item_data['registered_dancers'] = $registered_dancers;
    }
    // get groups
    else {
      $possible_group_ids = json_decode( base64_decode( $_POST['register_groups_array'] ) );
      $dance_school = get_userdata( $_POST['register_dancers_dance_school_id']);
      $groups_list = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
      $possible_groups = array();
      foreach ( $possible_group_ids as $group_id ) {
        $possible_groups[$group_id] = $groups_list[$group_id];
      }
      $registered_groups = array();
      foreach ( $possible_groups as $group ) {
        $group_members = $group->getDancers();
        $group_members = array_intersect( $group_members, $registered_dancers );
        $registered_groups[$group->getGroupName()] = $group_members;
      }
      $cart_item_data['registered_groups'] = $registered_groups;
    }
  }
  return $cart_item_data;
}

// Remove quantity on Dancer Registration category
add_filter( 'woocommerce_is_sold_individually', 'nkms_remove_quantity_field', 10, 2 );
function nkms_remove_quantity_field( $return, $product ) {
  // Get woo product categories
  $product_id = $product->get_id();
  $product_categories = wc_get_product_category_list( $product_id );
  if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
    return true;
  }
}

//* Display dancers in cart items.
add_filter( 'woocommerce_get_item_data', 'nkms_display_dancers_in_cart', 10, 2 );
function nkms_display_dancers_in_cart( $item_data, $cart_item ) {
  $prod_id = $cart_item['product_id'];
  $product = wc_get_product( $prod_id );
  // Solo dancers
  if ( ! empty( $cart_item['registered_dancers'] ) ) {
    $dancers_str_tmp = implode( "<br>", $cart_item['registered_dancers'] );
    $dancers_str = '<p class="registered-dancers">' . $dancers_str_tmp . '</p>';

    $item_data[] = array(
      'key'     => 'Dancers',
      'value'   => $dancers_str,
      'display' => '',
    );
    $cart_item['data']->set_price( sizeof( $cart_item['registered_dancers'] ) * $product->get_price() );
	}
  // Groups
  if ( ! empty( $cart_item['registered_groups'] ) ) {
    $groups_str_tmp = '';
    $registered_groups = $cart_item['registered_groups'];
    $total_dancers = 0;
    foreach ( $registered_groups as $group_name => $group_members ) {
      if ( ! empty ( $group_members ) ) {
        $groups_str_tmp .= '<br><span class="group-title">' . $group_name . '</span><br>';
        foreach ( $group_members as $dancer_id ) {
          $total_dancers++;
          $dancer = get_userdata( $dancer_id );
          $groups_str_tmp .= '' . $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '<br>';
        }
      }
    }
    $groups_str = '<div class="registered-groups">' . $groups_str_tmp . '</div>';

    $item_data[] = array(
      'key'     => 'Groups',
      'value'   => $groups_str,
      'display' => '',
    );
    $cart_item['data']->set_price( $cart_item['registered_dancers_num'] * $product->get_price() );
	}

  return $item_data;
}

// Something with price.
add_action( 'woocommerce_before_calculate_totals', 'nkms_add_custom_price', 20, 1);
function nkms_add_custom_price( $cart ) {
    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // Avoiding hook repetition (when using price calculations for example)
    if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
        return;

    // Loop through cart items
    foreach ( $cart->get_cart() as $item ) {
      $reg_dancers = 1;
      if ( isset( $item['registered_dancers_num'] ) ) {
        $reg_dancers = $item['registered_dancers_num'];
      }
      $item['data']->set_price( $item['data']->get_price() * $reg_dancers );
    }
}

// Save to order
add_action( 'woocommerce_checkout_create_order_line_item', 'nkms_save_registered_dancers', 10, 4 );
function nkms_save_registered_dancers( $item, $cart_item_key, $values, $order ) {
  if ( ! empty( $values['registered_dancers'] ) ) {
    $item->add_meta_data( __( 'Dancers', 'nkms' ), $values['registered_dancers'] );
  }
  if ( ! empty( $values['registered_groups'] ) ) {
    $item->add_meta_data( __( 'Groups', 'nkms' ), $values['registered_groups'] );
  }

  return;
}

//Change event price based on registered dancers
// add_filter('woocommerce_product_get_price', 'nkms_change_price_dancers_fee', 10, 2);
// function nkms_change_price_dancers_fee($price, $product) {
//    //global post object & post id
//    // global $post;
//    // $product = wc_get_product( $post->ID );
//    // $_POST['registered_dancers_num'] = 0;
//    if ( isset( $_POST['registered_dancers'] ) ) {
//      $registered_dancers = $_POST['registered_dancers'];
//      $num = sizeof( $registered_dancers );
//
//      // $dancer_fee = $product->get_meta( 'dancer_registration_fee' );
//      $price *= $num;
//    }
//    //return the new price
//    return $price;
// }

/*
 * USER PROFILE
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
function registration_validation( $username, $password, $email, $first_name, $last_name, $role, $dob )  {
  global $reg_errors;
  $reg_errors = new WP_Error;

  //Check if fields are empty
  if ( empty( $username ) || empty( $password ) || empty( $email ) || empty( $first_name ) || empty( $last_name ) || empty( $dob ) ) {
    $reg_errors->add('field', 'All fields are required.' );
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

  $age = nkms_get_age($dob);
  if ( $role === 'guardian' && $age < 18 ) {
    $reg_errors->add( 'guardian', 'You have to be an adult in order to own a guardian account.' );
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
    $user_id = wp_insert_user( $userdata );
    if ( $user_id ) {
      //initialize custom fields
      user_initialization($user_id);
      echo '<h4>Registration complete. You may login <a href="' . get_site_url() . '/login">here</a>.</h4>';
    }
    else {
      echo '<h4>Something went wrong. Please try again later!</h4>';
    }
  }
}
// Validate & Sanitize
function nkms_custom_registration() {
  if ( isset( $_POST['registration_submit'] ) ) {
    registration_validation(
      $_POST['username'],
      $_POST['password'],
      $_POST['email'],
      $_POST['first_name'],
      $_POST['last_name'],
      $_POST['sel_role'],
      $_POST['dob']
    );

    // sanitize user form input.
    global $username, $password, $email, $first_name, $last_name, $role,
    $dob, $address, $phone_number, $xp,
    $dancer_ds_name, $dancer_ds_teacher_name, $dancer_ds_teacher_email,
    $dancer_guardian_name, $dancer_guardian_phone_number,
    $dance_school_name, $dance_school_address, $dance_school_phone_number, $dance_school_description;

    $username     = sanitize_user( $_POST['username'] );
    $password     = esc_attr( $_POST['password'] );
    $email        = sanitize_email( $_POST['email'] );
    $first_name   = sanitize_text_field( $_POST['first_name'] );
    $last_name    = sanitize_text_field( $_POST['last_name'] );
    $role         = $_POST['sel_role'];
    $dob          = sanitize_text_field( $_POST['dob'] );
    $address      = sanitize_text_field( $_POST['address'] );
    $phone_number = sanitize_text_field( $_POST['phone_number'] );
    $xp           = $_POST['dancer_experience'];

    // dancer fields
    if ( $role === 'dancer' ) {
      $dancer_ds_name = sanitize_text_field( $_POST['dancer_ds_name'] );
      $dancer_ds_teacher_name = sanitize_text_field( $_POST['dancer_ds_teacher_name'] );
      $dancer_ds_teacher_email = sanitize_email( $_POST['dancer_ds_teacher_email'] );

      // dob calcs
      $is_adult = nkms_get_age($dob) >= 18;
      if ( $is_adult ) {
        // guardian details
        $dancer_guardian_name = sanitize_text_field( $_POST['dancer_guardian_name'] );
        $dancer_guardian_phone_number = sanitize_text_field( $_POST['dancer_guardian_phone_number'] );
      }
    }

    // dance school fields
    if ( $role === 'dance-school' ) {
      $dance_school_name = sanitize_text_field( $_POST['dance_school_name'] );
      $dance_school_address = sanitize_text_field( $_POST['dance_school_address'] );
      $dance_school_phone_number = sanitize_text_field( $_POST['dance_school_phone_number'] );
      $dance_school_description = sanitize_textarea_field( $_POST['dance_school_description'] );
    }

    // call @function complete_registration to create the user
    // only when no WP_error is found
    complete_registration();
  }
}

// Initialize user & custom fields
function user_initialization( $user_id ) {
  global $role, $dob, $address, $phone_number, $xp,
  $dancer_ds_name, $dancer_ds_teacher_name, $dancer_ds_teacher_email,
  $dancer_guardian_name, $dancer_guardian_phone_number,
  $dance_school_name, $dance_school_address, $dance_school_phone_number, $dance_school_description;

  // Custom fields not created with wp_insert_user (only available for basic fields)
  $nkms_fields = array(
    'dob' => $dob,
    'address' => $address,
    'phone_number' => $phone_number,
    'experience' => $xp,
  );
  update_user_meta( $user_id, 'nkms_fields', $nkms_fields );

  if ( $role === 'dancer' ) {

    $age = nkms_get_age($dob);
    // $guardian = array();
    // if ( $age < 18 ) {
    //   $guardian = array(
    //     'guardian_id' => 0,
    //     'guardian_name' => $dancer_guardian_name,
    //     'guardian_phone_number' => $dancer_guardian_phone_number
    //   );
    // }

    $age_category;
    // Age category
    if ( $age < 7 ) {
      $age_category = '6 and under';
    }
    elseif ( $age == 7 || $age == 8 ) {
      $age_category = '8 and under';
    }
    elseif ( $age == 9 || $age == 10 ) {
      $age_category = '10 and under';
    }
    elseif ( $age == 11 || $age == 12 ) {
      $age_category = '12 and under';
    }
    elseif ( $age == 13 || $age == 14 ) {
      $age_category = '14 and under';
    }
    elseif ( $age == 15 || $age == 16 ) {
      $age_category = '16 and under';
    }
    else {
      $age_category = '17+';
    }

    // create & save fields related to DANCER
    $nkms_dancer_fields = array(
      'dancer_ds_name' => $dancer_ds_name,
      'dancer_ds_teacher_name' => $dancer_ds_teacher_name,
      'dancer_ds_teacher_email' => $dancer_ds_teacher_email,
      'dancer_status' => 'Active',
      'dancer_invites' => array(
        'guardian' => array(),
        'dance_school' => array(),
      ),
      'dancer_age_category' => $age_category,
      'dancer_guardian_list' => array(),
      'dancer_teacher_of' => array(),
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
function nkms_get_age($dob) {
  // $birthday can be UNIX_TIMESTAMP or just a string-date.
  if( is_string($dob) ) {
      $dob = strtotime($dob);
  }

  // 31536000 is the number of seconds in a 365 days year.
  $age = ( time() - $dob ) * 31536000;
  // if( time() - $dob < $age * 31536000 )  { return false; }
  return $age;
}
