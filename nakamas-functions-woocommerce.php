<?php
/*
 * WOOCOMMERCE!
**/
/* Woo Account links & content */
add_filter ( 'woocommerce_account_menu_items', 'misha_remove_my_account_links' );
function misha_remove_my_account_links( $menu_links ){
  // $menu_links['TAB ID HERE'] = 'NEW TAB NAME HERE';
	$menu_links['downloads'] = 'My Files';

	unset( $menu_links['dashboard'] ); // Remove Dashboard
	//unset( $menu_links['orders'] ); // Remove Orders
	//unset( $menu_links['edit-address'] ); // Addresses
	//unset( $menu_links['payment-methods'] ); // Remove Payment Methods
	unset( $menu_links['downloads'] ); // Disable Downloads
	unset( $menu_links['edit-account'] ); // Remove Account details tab
	unset( $menu_links['customer-logout'] ); // Remove Logout link

	return $menu_links;
}

// change woo required user fields
add_filter('woocommerce_checkout_fields', 'nkms_woo_required_fields');
function nkms_woo_required_fields( $fields ) {
  unset( $fields['billing']['billing_address_2'] );
  unset( $fields['billing']['billing_state'] );
  if ( is_user_logged_in() ) {
    $user = wp_get_current_user();

    // $fields['billing']['billing_first_name'] = $user->first_name;
  }
	return $fields;
}

// define the woocommerce_save_account_details callback
// add_action( 'woocommerce_save_account_details', 'action_woocommerce_save_account_details', 10, 1 );
// function action_woocommerce_save_account_details( $user_id ) {
//     // make action magic happen here...
//     $user = get_userdata( $user_id );
//
// };

// Display a custom text field on product
add_action( 'woocommerce_product_options_general_product_data', 'nkms_custom_woo_field' );
function nkms_custom_woo_field() {
	woocommerce_wp_text_input(
    array(
      'id' => 'event_date',
      'label' => 'Event date',
      'class' => 'nkms-event-date',
      'desc_tip' => true,
      'description' => 'Enter the date of the event.',
    )
  );
}
// Save the custom text field on product
add_action( 'woocommerce_process_product_meta', 'nkms_save_event_date' );
function nkms_save_event_date( $post_id ) {
	$product = wc_get_product( $post_id );
	$event_date = isset( $_POST['event_date'] ) ? $_POST['event_date'] : '';
	$product->update_meta_data( 'event_date', $event_date );
	$product->save();
}

/**
 * Display custom field on the front end
 */
add_action( 'woocommerce_single_product_summary', 'cfwc_display_custom_field' );
function cfwc_display_custom_field() {
	global $post;
	// Check for the custom field value
	$product = wc_get_product( $post->ID );
	$event_date = $product->get_meta( 'event_date' );
	if( $event_date ) {
		// Only display our field if we've got a value for the field title
		echo '<div class="nkms-event-date"><p>' . $event_date . '</p></div>';
	}
}

// Hide Dancer Registration category if not needed
add_filter( 'woocommerce_product_query_tax_query', 'nkms_hide_dancer_registration');
function nkms_hide_dancer_registration ( $tquery ) {
	$hidden_categories = array( 'dancer-registration' );
	if ( is_shop() ) {
		$tquery[] = array(
			'taxonomy' => 'product_cat',
			'terms'    => $hidden_categories,
			'field'    => 'slug',
			'operator' => 'NOT IN'
		);
	}
	return $tquery;
}

// Change add to cart button
add_filter('woocommerce_product_add_to_cart_text', 'woo_custom_product_add_to_cart_text');
function woo_custom_product_add_to_cart_text() {
	global $product;
	$product_id = $product->get_id();
	$product_categories = wc_get_product_category_list( $product_id );
	// Check if event is for dancers so they can register
	if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
		return __('Register', 'woocommerce');
	}
	return __('Book', 'woocommerce');
}

add_filter('woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text');
function woo_custom_cart_button_text() {
	global $product;
	$product_id = $product->get_id();
	$product_categories = wc_get_product_category_list( $product_id );
	// Check if event is for dancers so they can register
	if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
		return __('Register', 'woocommerce');
	}
	return __('Book', 'woocommerce');
}

// List active dancers (if solo) or active groups to register to events
add_action('woocommerce_before_add_to_cart_button', 'nkms_register_dancers_to_events');
function nkms_register_dancers_to_events() {
  // if teacher
  $dance_school_id = nkms_is_teacher( get_current_user_id() );
	// Get woo product categories
	global $product;
	$product_id = $product->get_id();
	$product_categories = wc_get_product_category_list( $product_id );
  if ( is_user_logged_in() && nkms_can_manage_dance_school( $dance_school_id, get_current_user_id() ) ) {
    $dance_school = get_userdata( $dance_school_id );
    // Check if event is for dancers so they can register
    if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
			echo '<div class="x-accordion"><div class="x-accordion-group"><div class="x-accordion-heading"><a id="tab-nkms_registration" class="x-accordion-toggle collapsed" role="tab" data-x-toggle="collapse-b" data-x-toggleable="nkms_registration" aria-selected="false" aria-expanded="false" aria-controls="panel-nkms_registration">REGISTER DANCERS & GROUPS</a></div><div id="panel-nkms_registration" class="x-accordion-body x-collapsed" role="tabpanel" data-x-toggle-collapse="1" data-x-toggleable="nkms_registration" aria-hidden="true" aria-labelledby="tab-nkms_registration" style=""><div class="x-accordion-inner"><div class="et_pb_toggle_content clearfix">';
			echo '<h4>Active dancers & groups.</h4>';
			echo '<p>Only active dancers & groups are shown here. If you cannot find a dancer or group, make sure they are active before registering them to an event.</p>';
      echo '<p id="register-all-dancers"><label><input type="checkbox" id="select-all-dancers">Select / Unselect all dancers</label></p>';
      // Solo dancers
      echo '<div id="register-dancers"><h6>Dancers List</h6>';
      $registered_dancers = array();
      $ds_dancers = $dance_school->nkms_dance_school_fields['dance_school_dancers_list'];
      foreach ( $ds_dancers as $dancer_id ) {
        $dancer = get_userdata( $dancer_id );
        if ( $dancer->nkms_dancer_fields['dancer_status'] == 'Active' ) {
          array_push( $registered_dancers, $dancer->ID );
          echo '<p class="register-solo-dancers"><label><input type="checkbox" name="registered_dancers[]" value="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</label></p>';
        }
      }
      // Groups
      $ds_groups_list = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
      echo '<h6>Groups List</h6>';
      $possible_groups = array();
      // Check whether dance school has groups
      if ( ! empty( $ds_groups_list ) ) {
        // Get active groups
        $active_groups = array();
        foreach ( $ds_groups_list as $group_id => $group ) {
          //check if group is active and has members
          if ( $group->getStatus() == 'Active' && $group->getSize() ) {
            $active_groups[$group_id] = $group;
            array_push( $possible_groups, $group_id );
          }
        }
        if ( ! empty( $active_groups ) ) {
          foreach ( $active_groups as $group ) {
            echo '<p class="register-group-names">' . $group->getGroupName() . '</p>';
            $group_dancers = $group->getDancers();
            // List active dancers within group
						if ( $group_dancers ) {
							$active_dancers = 0;
	            foreach ( $group_dancers as $dancer_id ) {
	              $dancer = get_userdata( $dancer_id );
	              if ( $dancer->nkms_dancer_fields['dancer_status'] == 'Active' ) {
									$active_dancers++;
	                echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_groups[]" value="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</label></p>';
	              }
	            }
							if ( $active_dancers == 0 ) {
								echo '<p>No active dancers in group.</p>';
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
      echo '</div></div></div></div></div></div>';
      echo '<input type="hidden" name="register_dancers_dance_school_id" value="' . $dance_school_id . '"/>';
      $possible_groups = base64_encode( json_encode( $possible_groups ) );
      echo '<input type="hidden" name="register_groups_array" value="' . $possible_groups . '"/>';
    }
  }
  elseif ( is_user_logged_in() && nkms_has_role( wp_get_current_user(), 'guardian' ) && strpos( $product_categories, 'Dancer Registration' ) !== false ) {
		$guardian_dancers_list = wp_get_current_user()->nkms_guardian_fields['guardian_dancers_list'];
		if ( ! empty( $guardian_dancers_list ) ) {
			echo '<div class="x-accordion"><div class="x-accordion-group"><div class="x-accordion-heading"><a id="tab-nkms_registration" class="x-accordion-toggle collapsed" role="tab" data-x-toggle="collapse-b" data-x-toggleable="nkms_registration" aria-selected="false" aria-expanded="false" aria-controls="panel-nkms_registration">CATEGORIES</a></div><div id="panel-nkms_registration" class="x-accordion-body x-collapsed" role="tabpanel" data-x-toggle-collapse="1" data-x-toggleable="nkms_registration" aria-hidden="true" aria-labelledby="tab-nkms_registration" style=""><div class="x-accordion-inner"><div class="et_pb_toggle_content clearfix">';
			echo '<h3>Registering for</h3>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Solo">Solo</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Duo">Duo</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Parent-Child">Parent / Child</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Trio-Quad">Trio / Quad</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Team">Team</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Parent Team">Parent Team</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Mega Crew">Mega Crew</label></p>';
			echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Battle">Battle</label></p>';
			echo '</div></div></div></div></div>';
			$dancer_id = $guardian_dancers_list[0];
			echo '<p style="display:none;"><label><input type="checkbox" name="registered_dancers[]" value="' . $dancer_id . '" checked="true">' . $dancer_id . '</label></p>';
			echo '<input type="hidden" name="register_dancer_dancer_id" value="' . $dancer_id . '"/>';
		}
  }
  elseif ( is_user_logged_in() && nkms_has_role( wp_get_current_user(), 'dancer' ) && strpos( $product_categories, 'Dancer Registration' ) !== false ) {
		echo '<div class="x-accordion"><div class="x-accordion-group"><div class="x-accordion-heading"><a id="tab-nkms_registration" class="x-accordion-toggle collapsed" role="tab" data-x-toggle="collapse-b" data-x-toggleable="nkms_registration" aria-selected="false" aria-expanded="false" aria-controls="panel-nkms_registration">CATEGORIES</a></div><div id="panel-nkms_registration" class="x-accordion-body x-collapsed" role="tabpanel" data-x-toggle-collapse="1" data-x-toggleable="nkms_registration" aria-hidden="true" aria-labelledby="tab-nkms_registration" style=""><div class="x-accordion-inner"><div class="et_pb_toggle_content clearfix">';
		echo '<h3>Registering for</h3>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Solo">Solo</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Duo">Duo</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Parent-Child">Parent / Child</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Trio-Quad">Trio / Quad</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Team">Team</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Parent Team">Parent Team</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Mega Crew">Mega Crew</label></p>';
		echo '<p class="register-group-dancers"><label><input type="checkbox" name="registered_types[]" value="Battle">Battle</label></p>';
		echo '</div></div></div></div></div>';
		$dancer_id = get_current_user_id();
    echo '<input type="hidden" name="register_dancer_dancer_id" value="' . $dancer_id . '"/>';
    echo '<p style="display:none;"><label><input type="checkbox" name="registered_dancers[]" value="' . $dancer_id . '" checked="true">' . $dancer_id . '</label></p>';

  }
}

// Validate data - warn user if no dancers selected
add_filter( 'woocommerce_add_to_cart_validation', 'nkms_add_to_cart_validation', 10, 4 );
function nkms_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {
  $product_categories = wc_get_product_category_list( $product_id );
  if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
    if ( empty( $_POST['registered_dancers'] ) ) {
      $passed = false;
			if ( nkms_has_role( wp_get_current_user(), 'guardian' ) ) {
				wc_add_notice( 'You are not managing any dancers to register.', 'error' );
			}
			else {
				wc_add_notice( 'You must select at least one dancer to register.', 'error' );
			}
    }
		if ( nkms_has_role( wp_get_current_user(), 'guardian' ) || nkms_has_role( wp_get_current_user(), 'dancer' ) ) {
			if ( empty( $_POST['registered_types'] ) ) {
	      $passed = false;
				wc_add_notice( 'You must select at least one type to register for.', 'error' );
	    }
		}

  }
  return $passed;
}

// Custom cart item data: group & dancers
add_filter( 'woocommerce_add_cart_item_data', 'nkms_add_group_cart', 10, 3 );
function nkms_add_group_cart( $cart_item_data, $product_id, $variation_id ) {
  if ( isset ( $_POST['registered_dancers'] ) ) {
    $registered_dancers = $_POST['registered_dancers'];
    // if dance_school_id is not set, it's a solo event (no groups)
    if ( isset( $_POST['registered_dancers'] ) ){
      foreach ( $registered_dancers as $key => $dancer_id ) {
        $dancer = get_userdata( $dancer_id );
        $registered_dancers[$key] = $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name;
      }
      $cart_item_data['registered_dancers'] = $registered_dancers;
			$total_dancers = sizeof( $_POST['registered_dancers'] );
    }
    // get groups
    if ( isset( $_POST['register_groups_array'] ) ) {
      $possible_dancers_in_group = isset( $_POST['registered_groups'] ) ? $_POST['registered_groups'] : array() ;
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
        $group_members = array_intersect( $group_members, $possible_dancers_in_group );
        $registered_groups[$group->getGroupName()] = $group_members;
      }
      $cart_item_data['registered_groups'] = $registered_groups;
			if ( isset( $_POST['registered_groups']  ) ) {
				$total_dancers = sizeof( array_unique( array_merge( $_POST['registered_dancers'], $_POST['registered_groups'] ) ) );
			}
			else {
				$total_dancers = sizeof( array_unique( $_POST['registered_dancers'] ) );
			}
		}
		// get type
		if ( isset( $_POST['registered_types'] ) ) {
			$types = $_POST['registered_types'];
			$cart_item_data['registered_types'] = $types;
		}
    $cart_item_data['registered_dancers_num'] = $total_dancers;
  }
  return $cart_item_data;
}

// Remove quantity on Dancer Registration category
// add_filter( 'woocommerce_is_sold_individually', 'nkms_remove_quantity_field', 10, 2 );
// function nkms_remove_quantity_field( $return, $product ) {
//   // Get woo product categories
//   $product_id = $product->get_id();
//   $product_categories = wc_get_product_category_list( $product_id );
//   if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
//     return true;
//   }
// }

// Change product price.
add_action( 'woocommerce_before_calculate_totals', 'nkms_add_custom_price', 20, 1);
function nkms_add_custom_price( $cart ) {
    // This is necessary for WC 3.0+
    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // // Avoiding hook repetition (when using price calculations for example)
    // if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 )
    //     return;

    // Loop through cart items
		// echo '<pre>'; print_r($cart->get_cart()); echo '</pre>';
    foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
      $reg_dancers = 1;
      if ( isset( $cart_item['registered_dancers_num'] ) ) {
        $reg_dancers = $cart_item['registered_dancers_num'];
      }
			$cart->set_quantity( $cart_item_key, $reg_dancers, false );
    }
}

//* Display dancers in cart items.
add_filter( 'woocommerce_get_item_data', 'nkms_display_dancers_in_cart', 10, 2 );
function nkms_display_dancers_in_cart( $item_data, $cart_item ) {
	// Types
	if ( ! empty( $cart_item['registered_types'] ) ) {
		$types = implode( ', ', $cart_item['registered_types'] );
		$item_data[] = array(
			'key'     => 'Type',
			'value'   => $types,
			'display' => '',
		);
	}
  // Solo dancers
  if ( ! empty( $cart_item['registered_dancers'] ) ) {
		// echo '<pre>'; var_dump( $cart_item['registered_dancers'] ); echo '</pre>';

    $dancers_str_tmp = implode( "<br>", $cart_item['registered_dancers'] );
    $dancers_str = '<p class="registered-dancers">' . $dancers_str_tmp . '</p>';
    $registered_dancers_value_to_save = maybe_serialize( $cart_item['registered_dancers'] );

    $item_data[] = array(
      'key'     => 'Dancers',
      'value'   => $registered_dancers_value_to_save,
      'display' => $dancers_str,
    );
	}
  // Groups
  if ( ! empty( $cart_item['registered_groups'] ) ) {
    $groups_str_tmp = '';
    $registered_groups = $cart_item['registered_groups'];
    foreach ( $registered_groups as $group_name => $group_members ) {
      if ( ! empty ( $group_members ) ) {
        $groups_str_tmp .= '<br><span class="group-title">' . $group_name . '</span><br>';
        foreach ( $group_members as $dancer_id ) {
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
	}
  return $item_data;
}

/**
 * Add custom meta to order
**/
// Save to order
add_action( 'woocommerce_checkout_create_order_line_item', 'nkms_save_registered_dancers', 10, 4 );
function nkms_save_registered_dancers( $item, $cart_item_key, $values, $order ) {
  foreach( $item as $something ) {
		// Types
		if ( isset( $values['registered_types'] ) ) {
			$types = implode( ", ", $values['registered_types'] );
			$item->add_meta_data( 'Type', $types, true );
		}
    if ( isset( $values['registered_dancers'] ) ) {
      $dancers_str_tmp = '<br>';
      $dancers_str_tmp .= implode( "<br>", $values['registered_dancers'] );
      $item->add_meta_data( 'Dancers (' . $values['registered_dancers_num'] . ')', $dancers_str_tmp, true );

		  $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();

			foreach( $values['registered_dancers'] as $dancer_string ) {
				$dancer_id_string = explode( ':', $dancer_string );
				$dancer_id = intval( $dancer_id_string[0] );
				$dancer = get_userdata( $dancer_id );
				$dancer_fields = $dancer->nkms_dancer_fields;
				$registered_to = $dancer_fields['dancer_registered_to'];
				array_push( $registered_to, $product_id );
				$dancer_fields['dancer_registered_to'] = array_unique( $registered_to );
				update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
			}
    }
    if ( isset( $values['registered_groups'] ) ) {
      $groups_str_tmp = '<br>';
      foreach ( $values['registered_groups'] as $group_name => $group_members ) {
        if ( ! empty ( $group_members ) ) {
          $groups_str_tmp .= '<br><span class="group-title">' . $group_name . '</span><br>';
          foreach ( $group_members as $dancer_id ) {
            $dancer = get_userdata( $dancer_id );
            $groups_str_tmp .= '' . $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '<br>';
          }
        }
      }
      $item->add_meta_data( 'Groups (' . sizeof( $values['registered_groups'] ) . ')', $groups_str_tmp, true );
    }
  }
}
