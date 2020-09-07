<?php
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
function nkms_hide_dancer_registration ( $tquery ) {
	$user = wp_get_current_user();
	$blocked_user_roles = array( 'spectator', 'customer', 'subscriber', 'contributor', 'author', 'editor'  );
  $hidden_categories = array( 'dancer-registration' );
  if ( is_shop() && ( ! is_user_logged_in() || is_user_logged_in() && count( array_intersect( $blocked_user_roles, $user->roles ) ) > 0 ) ) {
		$tquery[] = array(
      'taxonomy' => 'product_cat',
			'terms'    => $hidden_categories,
			'field'    => 'slug',
			'operator' => 'NOT IN'
		);
  }
  return $tquery;
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
      echo '<p><input type="checkbox" id="select-all-dancers"><span>Select / Deselect all</span></p>';
      // Solo dancers
      echo '<div id="register-dancers"><h3>Dancers List</h3>';
      $registered_dancers = array();
      $ds_dancers = $dance_school->nkms_dance_school_fields['dance_school_dancers_list'];
      foreach ( $ds_dancers as $dancer_id ) {
        $dancer = get_userdata( $dancer_id );
        if ( $dancer->nkms_dancer_fields['dancer_status'] == 'Active' ) {
          array_push( $registered_dancers, $dancer->ID );
          echo '<p class="register-group-dancers"><label>' . '<input type="checkbox" name="registered_dancers[]" value="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</label></p>';
        }
      }
      // Groups
      $ds_groups_list = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
      echo '<h3>Groups List</h3>';
      // Check whether dance school has groups
      if ( ! empty( $ds_groups_list ) ) {
        // Get active groups matching the event category (Duo, Team etc.)
        $print_active_groups_from_category = array();
        $registered_groups = array();
        foreach ( $ds_groups_list as $group_id => $group ) {
          //check if group category is within product categories, if group is active and has members
          if ( $group->getStatus() == 'Active' && $group->getSize() ) {
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
      echo '</div>';
      echo '<input type="hidden" name="register_dancers_dance_school_id" value="' . $dance_school_id . '"/>';
      $registered_groups = base64_encode( json_encode( $registered_groups ) );
      echo '<input type="hidden" name="register_groups_array" value="' . $registered_groups . '"/>';
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

// Custom cart item data: group & dancers
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
  if ( isset( $values['registered_dancers'] ) ) {
    $item->add_meta_data( __( 'Dancers', 'nkms' ), $values['registered_dancers'], true );
  }
  if ( isset( $values['registered_groups'] ) ) {
    $item->add_meta_data( __( 'Groups', 'nkms' ), $values['registered_groups'], true );
  }
}

/**
 * Add custom meta to order
 */
// add_action( 'woocommerce_checkout_create_order_line_item', 'nkms_checkout_create_order_line_item', 10, 4 );
// function nkms_checkout_create_order_line_item( $item, $cart_item_key, $values, $order ) {
//   if( isset( $values['registered_dancers'] ) ) {
//     $item->add_meta_data( __( 'Dancers', 'nkms' ), $values['registered_dancers'], true );
//   }
//   if( isset( $values['registered_groups'] ) ) {
//     $item->add_meta_data( __( 'Groups', 'nkms' ), $values['registered_groups'], true );
//   }
// }

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
