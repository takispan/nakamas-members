<?php
/*
 * Ajax in WP
 *
 */
 // Update user profile
 add_action( 'wp_ajax_user_update_profile', 'user_update_profile' );
 function user_update_profile() {
   	$user_id = intval( $_POST['update_profile_user_id'] );
  	// $email = $_POST['update_profile_email'];
  	$phone_number = $_POST['update_profile_phone_number'];
  	$city = $_POST['update_profile_city'];
  	$address = $_POST['update_profile_address'];
  	$postcode = $_POST['update_profile_postcode'];
  	$guardian_name = $_POST['update_profile_guardian_name'];
  	$guardian_phone_number = $_POST['update_profile_guardian_phone_number'];
  	$guardian_email = $_POST['update_profile_guardian_email'];
  	$dancer_ds_name = $_POST['update_profile_dancer_ds_name'];
  	$dancer_ds_teacher_name = $_POST['update_profile_dancer_ds_teacher_name'];
  	$dancer_ds_teacher_email = $_POST['update_profile_dancer_ds_teacher_email'];
  	$dance_school_name = $_POST['update_profile_dance_school_name'];
  	$dance_school_address = $_POST['update_profile_dance_school_address'];
  	$dance_school_phone_number = $_POST['update_profile_dance_school_phone_number'];
  	$dance_school_description = $_POST['update_profile_dance_school_description'];

 		if ( $user_id ) {
      $user = get_userdata( $user_id );
      $user_fields = $user->nkms_fields;
      // $user_fields['email'] = $email;
      $user_fields['phone_number'] = $phone_number;
      $user_fields['city'] = $city;
      $user_fields['address'] = $address;
      $user_fields['postcode'] = $postcode;

      update_user_meta( $user_id, 'nkms_fields', $user_fields );

      if ( nkms_has_role( $user, 'dancer' ) ) {
        $dancer_fields = $user->nkms_dancer_fields;
        $age = nkms_get_age( $user_fields['dob'] );
        if ( $age < 18 ) {
          $dancer_fields['dancer_guardian_name'] = $guardian_name;
          $dancer_fields['dancer_guardian_phone_number'] = $guardian_phone_number;
          $dancer_fields['dancer_guardian_email'] = $guardian_email;
        }
        $dancer_fields['dancer_ds_name'] = $dancer_ds_name;
        $dancer_fields['dancer_ds_teacher_name'] = $dancer_ds_teacher_name;
        $dancer_fields['dancer_ds_teacher_email'] = $dancer_ds_teacher_email;

        update_user_meta( $user_id, 'nkms_dancer_fields', $dancer_fields );
      }
      if ( nkms_has_role( $user, 'dance-school' ) ) {
        $dance_school_fields = $user->nkms_dance_school_fields;
        $dance_school_fields['dance_school_name'] = $dance_school_name;
        $dance_school_fields['dance_school_address'] = $dance_school_address;
        $dance_school_fields['dance_school_phone_number'] = $dance_school_phone_number;
        $dance_school_fields['dance_school_description'] = $dance_school_description;

        update_user_meta( $user_id, 'nkms_dance_school_fields', $dance_school_fields );
      }
      wp_send_json_success( '<p class="text-info">Profile updated.</p>' );
 		}
    else {
      wp_send_json_success( '<p class="text-danger">Invalid user ID.</p>' );
    }
 }

/*
 * DANCER LEAVES DANCE SCHOOL
**/
add_action( 'wp_ajax_dancer_leaves_dance_school', 'dancer_leaves_dance_school' );
function dancer_leaves_dance_school() {
  $dance_school_id = intval( $_POST['dancer_leaves_dance_school_ds_id'] );
  $dancer_id = intval( $_POST['dancer_leaves_dance_school_dancer_id'] );

  if ( $dance_school_id && $dancer_id ) {
    $dancer = get_userdata( $dancer_id );
    $dancer_fields = $dancer->nkms_dancer_fields;
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;

    $ds_dancers_list = $dance_school_fields['dance_school_dancers_list'];
    if ( in_array( $dancer_id, $ds_dancers_list ) ) {
      $dance_school_fields['dance_school_dancers_list'] = array_diff( $ds_dancers_list, [$dancer_id] );

      $part_of_ds = $dancer_fields['dancer_part_of'];
      $dancer_fields['dancer_part_of'] = array_diff( $part_of_ds, [$dance_school_id] );
      update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
      update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
      wp_send_json_success( '<p class="text-info">Dancer left ' . $dance_school_fields['dance_school_name'] . '</p>' );
    }
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

 /*
  * INVITE SYSTEM
 **/
// Dancer requests to join a dance school
add_action( 'wp_ajax_dancer_requests_to_join_dance_school', 'dancer_requests_to_join_dance_school' );
function dancer_requests_to_join_dance_school() {
  $dance_school_id = intval( $_POST['dancer_requests_to_join_dance_school_ds_id'] );
  $dancer_id = intval( $_POST['dancer_requests_to_join_dance_school_dancer_id'] );

  if ( $dance_school_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $ds_invites_list = $dance_school_fields['dance_school_invites'];
    if ( ! in_array( $dancer_id, $ds_invites_list ) ) {
      array_push( $ds_invites_list, $dancer_id );
      $dance_school_fields['dance_school_invites'] = $ds_invites_list;
      $db_result = update_user_meta( $dance_school->ID, 'nkms_dance_school_fields', $dance_school_fields );
      if ( $db_result ) {
        wp_send_json_success( '<p class="text-info">Request sent to ' . $dance_school_fields['dance_school_name'] . '</p>' );
      }
      else {
        wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
      }
    }
  }
  else {
    wp_send_json_success( '<p class="text-danger">Please select a Dance school.</p>' );
  }
}

// Dancer accepts invite to join dance school
add_action( 'wp_ajax_dancer_accepts_dance_school_invite', 'dancer_accepts_dance_school_invite' );
function dancer_accepts_dance_school_invite() {
  $dancer_id = intval( $_POST['dancer_accepts_dance_school_invite_dancer_id'] );
  $dance_school_id = intval( $_POST['dancer_accepts_dance_school_invite_ds_id'] );

  $dance_school = get_userdata( $dance_school_id );
  $dancer = get_userdata( $dancer_id );

  if ( $dance_school_id && $dancer_id ) {
    // add dancer to dance school list of dancers
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    if ( ! in_array( $dancer_id, $dance_school_fields['dance_school_dancers_list'] ) ) {
      array_push( $dance_school_fields['dance_school_dancers_list'], $dancer_id );
    }
    update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );

    // remove dance school id from dancer_invites
    $dancer_fields = $dancer->nkms_dancer_fields;
    $dancer_fields['dancer_invites']['dance_school'] = array_diff( $dancer_fields['dancer_invites']['dance_school'], [$dance_school_id] );
    $part_of_ds = $dancer_fields['dancer_part_of'];
    if ( ! in_array( $dance_school_id, $part_of_ds ) ) {
      array_push( $part_of_ds, $dance_school_id );
      $dancer_fields['dancer_part_of'] = $part_of_ds;
    }
    update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
    wp_send_json_success( '<p class="text-info">Invite accepted.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Dancer declines invite to join dance school
add_action( 'wp_ajax_dancer_declines_dance_school_invite', 'dancer_declines_dance_school_invite' );
function dancer_declines_dance_school_invite() {
  $dancer_id = intval( $_POST['dancer_declines_dance_school_invite_dancer_id'] );
  $dance_school_id = intval( $_POST['dancer_declines_dance_school_invite_ds_id'] );

  $dance_school = get_userdata( $dance_school_id );
  $dancer = get_userdata( $dancer_id );

  if ( $dance_school_id && $dancer_id ) {
    // remove dance school id from dancer_invites
    $dancer_fields = $dancer->nkms_dancer_fields;
    $dancer_fields['dancer_invites']['dance_school'] = array_diff( $dancer_fields['dancer_invites']['dance_school'], [$dance_school_id] );
    update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
    wp_send_json_success( '<p class="text-info">Invite declined.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Dance School accepts dancer
add_action( 'wp_ajax_dance_school_accepts_dancer_invite', 'dance_school_accepts_dancer_invite' );
function dance_school_accepts_dancer_invite() {
  $dancer_id = intval( $_POST['dance_school_accepts_dancer_invite_dancer_id'] );
  $dance_school_id = intval( $_POST['dance_school_accepts_dancer_invite_ds_id'] );

  if ( $dance_school_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $ds_invites_list = $dance_school_fields['dance_school_invites'];
    if ( in_array( $dancer_id, $ds_invites_list ) ) {
      $ds_invites_list = array_diff( $ds_invites_list, [$dancer_id] );
      $dance_school_fields['dance_school_invites'] = $ds_invites_list;
      $dancers_list = $dance_school_fields['dance_school_dancers_list'];
      if ( ! in_array( $dancer_id, $dancers_list ) ) {
        array_push( $dancers_list, $dancer_id );
        $dance_school_fields['dance_school_dancers_list'] = $dancers_list;
      }
      $dancer = get_userdata( $dancer_id );
      $dancer_fields = $dancer->nkms_dancer_fields;
      $part_of_ds = $dancer_fields['dancer_part_of'];
      if ( ! in_array( $dance_school_id, $part_of_ds ) ) {
        array_push( $part_of_ds, $dance_school_id );
        $dancer_fields['dancer_part_of'] = $part_of_ds;
      }
      update_user_meta( $dancer->ID, 'nkms_dancer_fields', $dancer_fields );
      update_user_meta( $dance_school->ID, 'nkms_dance_school_fields', $dance_school_fields );
      wp_send_json_success( '<p class="text-info">Dancer added.</p>' );
    }
    else {
      wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
    }
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Dance School declines dancer
add_action( 'wp_ajax_dance_school_declines_dancer_invite', 'dance_school_declines_dancer_invite' );
function dance_school_declines_dancer_invite() {
  $dancer_id = intval( $_POST['dance_school_declines_dancer_invite_dancer_id'] );
  $dance_school_id = intval( $_POST['dance_school_declines_dancer_invite_ds_id'] );

  if ( $dance_school_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $ds_invites_list = $dance_school_fields['dance_school_invites'];
    if ( in_array( $dancer_id, $ds_invites_list ) ) {
      $ds_invites_list = array_diff( $ds_invites_list, [$dancer_id] );
      $dance_school_fields['dance_school_invites'] = $ds_invites_list;
      update_user_meta( $dance_school->ID, 'nkms_dance_school_fields', $dance_school_fields );
      wp_send_json_success( '<p class="text-info">Dancer declined.</p>' );
    }
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Guardian - Request to manage dancer
add_action( 'wp_ajax_guardian_invite_to_manage_dancer', 'guardian_invite_to_manage_dancer' );
function guardian_invite_to_manage_dancer() {
  // get dancer & guardian objects
  $dancer_id = intval( $_POST['guardian_manage_dancer_dancer_id'] );
  $dancer = get_user_by( 'id', $dancer_id );
  $guardian_id = intval( $_POST['guardian_manage_dancer_guardian_id'] );
  $guardian = get_user_by( 'id', $guardian_id );

  // check if input is dancer
  if ( nkms_has_role( $dancer, 'dancer' ) ) {
    // get dancer fields
    $dancer_fields = $dancer->nkms_dancer_fields;
    // check if guardian has already sent an invite
    if ( ! in_array( $guardian_id, $dancer_fields['dancer_invites']['guardian'] ) ) {
      // add guardian id to dancer invites(guardian) array
      array_push( $dancer_fields['dancer_invites']['guardian'], $guardian_id );
      // save dancer fields
      update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
      wp_send_json_success("<p class='text-info'>Dancer invite sent.</p>");
    }
    else {
      wp_send_json_success("<p class='text-danger'>Dancer already has a pending invite.</p>");
    }
  }
  else {
    wp_send_json_success("<p class='text-danger'>Invalid dancer ID.</p>");
  }
}

// Dancer accepts invite from guardian
add_action( 'wp_ajax_dancer_accepts_guardian_invite', 'dancer_accepts_guardian_invite' );
function dancer_accepts_guardian_invite() {
  $dancer_id = intval( $_POST['dancer_accepts_guardian_invite_dancer_id'] );
  $guardian_id = intval( $_POST['dancer_accepts_guardian_invite_guardian_id'] );

  if ( $dancer_id && $guardian_id ) {
    $guardian = get_userdata( $guardian_id );
    $dancer = get_userdata( $dancer_id );
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
    wp_send_json_success( '<p class="text-info">Invite accepted.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Dancer declines invite from guardian
add_action( 'wp_ajax_dancer_declines_guardian_invite', 'dancer_declines_guardian_invite' );
function dancer_declines_guardian_invite() {
  $dancer_id = intval( $_POST['dancer_declines_guardian_invite_dancer_id'] );
  $guardian_id = intval( $_POST['dancer_declines_guardian_invite_guardian_id'] );

  if ( $dancer_id && $guardian_id ) {
    $dancer = get_user_by( 'id', $dancer_id );
    $dancer_fields = $dancer->nkms_dancer_fields;

    // get dancer_invites['guardian'] array from dancer fields
    $guardian_invites = $dancer_fields['dancer_invites']['guardian'];
    // get dancer_guardian_list array from dancer fields
    $guardian_invites = array_diff( $guardian_invites, [$guardian_id] );
    $dancer_fields['dancer_invites']['guardian'] = $guardian_invites;
    // save dancer_guardian_list
    update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
    wp_send_json_success( '<p class="text-info">Invite declined.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}


// DANCE SCHOOL - send invite to dancer
add_action( 'wp_ajax_ds_add_dancer', 'ds_add_dancer' );
function ds_add_dancer() {
  // get dancer & dance school objects
  $dancer_id = intval( $_POST['ds_add_dancer_id'] );
  $dancer = get_user_by( 'id', $dancer_id );
  $dance_school_id = intval( $_POST['ds_add_dancer_dance_school_id'] );
  $dance_school = get_user_by( 'id', $dance_school_id );
  // check if input is dancer
  if ( nkms_has_role( $dancer, 'dancer' ) ) {
    // get dance school fields
  	$dance_school_fields = $dance_school->nkms_dance_school_fields;
    // check if dancer is already part of dance school
  	if ( ! in_array( $dancer_id, $dance_school_fields['dance_school_dancers_list'] ) ) {
      // get dancer fields
      $dancer_fields = $dancer->nkms_dancer_fields;
      // check if dancer already has an invite by the dance school
      if ( ! in_array( $dance_school_id, $dancer_fields['dancer_invites']['dance_school'] ) ) {
        // add dance school id to dancer invites(dance school) array
        array_push( $dancer_fields['dancer_invites']['dance_school'], $dance_school_id );
        // save dancer fields
        update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
        wp_send_json_success("<p class='text-info'>Dancer invite sent.</p>");
      }
      else {
        wp_send_json_success("<p class='text-danger'>Dancer already has a pending invite.</p>");
      }
    }
    else {
      wp_send_json_success("<p class='text-danger'>Dancer is already member of the School.</p>");
    }
  }
  else {
  	wp_send_json_success("<p class='text-danger'>Invalid dancer ID.</p>");
  }
}

 // Pass dancer id to populate single dancer tab
 add_action( 'wp_ajax_ds_single_dancer', 'ds_single_dancer' );
 function ds_single_dancer() {
   // get dancer & dance school objects
   $dancer_id = intval( $_POST['single_dancer_id'] );
   $dancer = get_userdata( $dancer_id );
   $dance_school_id = intval( $_POST['dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );

   // get dance school fields
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   // add currently viewing dancer
   $dance_school_fields['dance_school_currently_viewing']['dancer'] = $dancer_id;
   // save dance school fields
   update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
   $single_dancer_data = '
   <div class="loader"><div class="lds-dual-ring"></div></div>
   <h3 style="font-weight:300;">Dancer <span style="font-weight:600;">' . $dancer->first_name . ' ' . $dancer->last_name . '</span> for <span style="font-weight:600;">' . $dance_school->nkms_dance_school_fields['dance_school_name'] . '</span></h3>
   <div class="dancer-details">
     <p class="nkms-pfp">' . get_wp_user_avatar( $dancer_id, '256', '' ) . '</p>
     <p><span>Soar ID</span>' . $dancer_id . '</p>
     <p><span>Full Name</span>' . $dancer->first_name . ' ' . $dancer->last_name . '</p>
     <p><span>Status</span>' . $dancer->nkms_dancer_fields['dancer_status'] . '</p>
     <p><span>Age category</span>' . $dancer->nkms_dancer_fields['dancer_age_category'] . '</p>
     <p><span>Level category</span>' . $dancer->nkms_dancer_fields['dancer_level'] . '</p>
   </div>
   <p class="ajax-response"></p>
   <button id="change-dancer-status" class="button" data-dancer-id="' .  $dancer_id . '">Change Status</button>
   <button id="remove-dancer-from-dancers-list" class="button" data-ds-id="' . $dance_school_id . '" data-dancer-id="' . $dancer_id . '">Remove Dancer</button>';
   wp_send_json_success( $single_dancer_data );
 }

 // Change dancer status
 add_action( 'wp_ajax_ds_change_status', 'ds_change_status');
 function ds_change_status() {
   // get dancer object
   $dancer_id = intval( $_POST['change_dancer_status_dancer_id'] );
   $dancer = get_userdata( $dancer_id );

   // get dancer fields
   $dancer_fields = $dancer->nkms_dancer_fields;

   $status = $dancer->nkms_dancer_fields['dancer_status'];
   ( $status === 'Active' ) ? $status = 'Inactive' : $status = 'Active';
   $dancer_fields['dancer_status'] = $status;
   update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
   wp_send_json_success('<p class="text-info">Dancer status changed.</p>');
 }

 // Remove dancer from dance school list of dancers
 add_action( 'wp_ajax_ds_remove_dancer', 'ds_remove_dancer' );
 function ds_remove_dancer() {
   // get dancer & dance school objects
   $dancer_id = intval( $_POST['remove_dancer_single_dancer_id'] );
   $dancer = get_userdata( $dancer_id );
   $dance_school_id = intval( $_POST['remove_dancer_dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );

   // get dance school fields
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   // get dancers list
   $dancers_list = $dance_school_fields['dance_school_dancers_list'];
   if ( in_array( $dancer_id, $dancers_list) ) {
     $new_dancers_list = array_diff( $dancers_list, [$dancer_id] );
     $dance_school_fields['dance_school_dancers_list'] = $new_dancers_list;
     $dancer = get_userdata( $dancer_id );
     $status = $dancer->nkms_dancer_fields['dancer_status'];
     if ( $status == 'Inactive' ) {
       $dancer_fields = $dancer->nkms_dancer_fields;
       $part_of_ds = $dancer_fields['dancer_part_of'];
       if ( in_array( $dance_school_id, $part_of_ds ) ) {
         $part_of_ds = array_diff( $part_of_ds, [$dance_school_id] );
         $dancer_fields['dancer_part_of'] = $part_of_ds;
         update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
       }
       update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
       wp_send_json_success('<p class="text-info">Dancer was removed.</p>');
     }
     else {
       wp_send_json_success('<p class="text-danger">Dancer must be inactive in order to be removed.</p>');
     }
   }
   wp_send_json_success('<p class="text-danger">An error occured. Please try again.</p>');
 }

 // Add group to dance school list of groups
 add_action( 'wp_ajax_ds_add_group', 'ds_add_group' );
 function ds_add_group() {
   $group_name = $_POST['add_group_group_name'];
   if ( ! $group_name ) { wp_send_json_success( "<p class='text-danger'>Group name cannot be empty.</p>" ); }
   $group_type = $_POST['add_group_group_type'];
   if ( ! $group_type ) { wp_send_json_success( "<p class='text-danger'>Group type must be selected.</p>" ); }
   $dance_school_id = intval( $_POST['add_group_dance_school_id'] );
   $group_level = $_POST['add_group_group_level'];
   if ( ! $group_level ) { wp_send_json_success( "<p class='text-danger'>Group leve category must be selected.</p>" ); }

   $dance_school = get_userdata( $dance_school_id );
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   end( $dance_school_fields['dance_school_groups_list'] );
   $last = key( $dance_school_fields['dance_school_groups_list'] );
   $last++;

   $ds_new_group = new DanceGroup( $dance_school_id, $last, $group_name, $group_type, $group_level );
   $dance_school_fields['dance_school_groups_list'][$last] = $ds_new_group;
   $success = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
   if ( $success ) {
     wp_send_json_success("<p class='text-info'>Group added successfully.</p>");
   }
   else {
     wp_send_json_success("<p class='text-danger'>An error occured, please try again.</p>");
   }
 }

 // Pass group id to populate single group tab
 add_action( 'wp_ajax_ds_single_group', 'ds_single_group' );
 function ds_single_group() {
   // get group id & dance school object
   $group_id = intval( $_POST['single_group_id'] );
   $dance_school_id = intval( $_POST['dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );

   // get dance school fields
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   // add currently viewing group
   $dance_school_fields['dance_school_currently_viewing']['group'] = $group_id;
   // save dance school fields
   update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
   $group = $dance_school->nkms_dance_school_fields['dance_school_groups_list'][$group_id];
   $group_dancers = $group->getDancers();
   $print_dancers;
   if ( empty( $group_dancers ) ) {
     $print_dancers = '<p>' . $group->getGroupName() . ' does not have any dancers.<br>You may add by clicking the button below.</p>';
   }
   else {
     $print_dancers .= '<div class="group-dancers">';
     foreach ($group_dancers as $group_dancer_id ) {
       $group_dancer = get_user_by( 'id', $group_dancer_id );
       $print_dancers .= '<p>' . $group_dancer_id . ': ' . $group_dancer->first_name . ' ' . $group_dancer->last_name . '<br><span>Status</span>' . $group_dancer->nkms_dancer_fields['dancer_status'] . '</p>';
     }
     $print_dancers .= '</div>';
   }
   $single_group_data = '
   <div class="loader"><div class="lds-dual-ring"></div></div>
   <h3 style="font-weight:300;">Dance Group <span style="font-weight:600;">' . $group->getGroupName() . '</span> in <span style="font-weight:600;">' . $dance_school->nkms_dance_school_fields['dance_school_name'] . '</span></h3>
   <div class="group-details">
     <p><span>Type</span>' . $group->getType() . '</p>
     <p><span>Name</span>' . $group->getGroupName() . '</p>
     <p><span>Age category</span>' . $group->getAgeCategory() . '</p>
     <p><span>Level category</span>' . $group->getLevelCategory() . '</p>
     <p><span>Status</span>' . $group->getStatus() . '</p>
     <button class="change-group-status button" data-ds-id="' . $dance_school_id . '" data-dancer-id="' .  $group_id . '">Change Status</button>
     <a class="button ds-group-change-level-category-link">Change Level Category</a>
     <button class="remove-group button" data-ds-id="' . $dance_school_id . '" data-group-id="' .  $group_id . '">Remove Group</button>
     <h4 style="font-weight: 300;">Dancers of <span style="font-weight:600;">' . $group->getGroupName() . '</span></h4>
     <p>' . $print_dancers . '</p>
     <a class="button ds-group-add-dancers-link">Add Dancers</a>';

   if ( ! empty( $group_dancers ) ) {
     $single_group_data .= ' <a class="button ds-group-remove-dancers-link">Remove Dancers</a>';
   }
   $single_group_data .= '</div>';

   wp_send_json_success( $single_group_data );
 }

 // Change group status
 add_action( 'wp_ajax_ds_group_change_status', 'ds_group_change_status');
 function ds_group_change_status() {
   // get group_id and dance school object
   $group_id = intval( $_POST['ds_group_change_status_group_id'] );
   $dance_school_id = intval( $_POST['ds_group_change_status_dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   $group = $dance_school_fields['dance_school_groups_list'][$group_id];
   // var_dump( $group );
   $status = $group->getStatus();
   ( $status === 'Active' ) ? $status = 'Inactive' : $status = 'Active';
   $group->setStatus( $status );
   $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
   $update = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
   if ( $update ) {
     wp_send_json_success('<p class="text-info">Group status changed.</p>');
   }
   else {
     wp_send_json_error();
   }
 }

 // Remove group from dance school
 add_action( 'wp_ajax_ds_remove_group', 'ds_remove_group' );
 function ds_remove_group() {
   	$group_id = intval( $_POST['ds_remove_group_group_id'] );
    $dance_school_id = intval( $_POST['ds_remove_group_dance_school_id'] );
   	if ( ! empty( $dance_school_id ) ) {
      $dance_school = get_userdata( $dance_school_id );
      $dance_school_fields = $dance_school->nkms_dance_school_fields;
      $dance_school_groups_list = $dance_school_fields['dance_school_groups_list'];
      $group = $dance_school_groups_list[$group_id];
      $status = $group->getStatus();
      if ( $status == 'Inactive' ) {
        unset( $dance_school_groups_list[$group_id] );
        // change currently viewing group to either first group or none if groups list is empty
        $key = array_key_first( $dance_school_groups_list );
        if ( $key ) {
          $dance_school_fields['dance_school_currently_viewing']['group'] = $key;
        }
        else {
          $dance_school_fields['dance_school_currently_viewing']['group'] = 0;
        }
        $dance_school_fields['dance_school_groups_list'] = $dance_school_groups_list;
        $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
        if ( $db_result ) {
          wp_send_json_success( '<p class="text-info">Group was removed.</p>' );
        }
      }
      else {
        wp_send_json_success( '<p class="text-danger">Group must be inactive in order to be removed.</p>' );
      }
   	}
   	else {
      wp_send_json_success( '<p class="text-danger">An error occured. Group was not removed.</p>' );
   	}
 }

 // Change level category group
 add_action( 'wp_ajax_ds_group_change_level_category', 'ds_group_change_level_category' );
 function ds_group_change_level_category() {
   	$level_category = $_POST['group_change_level_category'];
    $dance_school_id = intval( $_POST['group_change_level_category_dance_school_id'] );
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $group_id = $dance_school_fields['dance_school_currently_viewing']['group'];
    $group = $dance_school_fields['dance_school_groups_list'][$group_id];

 		$success = $group->changeLevelCategory( $level_category );
 		if ( $success ) {
      $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
      $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
      if ( $db_result ) {
        wp_send_json_success( '<p class="text-info">Level category changed to ' . $group->getLevelCategory() . '.</p>' );
      }
      else {
        wp_send_json_success( '<p class="text-danger">An error occured, please try again.</p>' );
      }
 		}
    else {
      wp_send_json_success( '<p class="text-danger">An error occured, please try again.</p>' );
    }
 }

 // Add dancer to group
 add_action( 'wp_ajax_ds_add_group_dancer', 'ds_add_group_dancer' );
 function ds_add_group_dancer() {
   	$dancer_id = intval( $_POST['dancer_id'] );
    $dancer = get_userdata( $dancer_id );
    $dance_school_id = intval( $_POST['dance_school_id'] );
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $group_id = $dance_school_fields['dance_school_currently_viewing']['group'];
    $group = $dance_school_fields['dance_school_groups_list'][$group_id];
   	if ( ! empty( $dancer_id ) ) {
   		$success = $group->addDancer( $dancer_id );
      $age_cat = $group->changeAgeCategory();
   		if ( $success ) {
        $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
        $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
        if ( $db_result ) {
          wp_send_json_success( '<p class="text-info">' . $dancer->first_name . ' ' . $dancer->last_name . ' added in ' . $group->getGroupName() . '.</p>' );
        }
        else {
          wp_send_json_success( '<p class="text-danger">Dancer was not saved in database.</p>' );
        }
   		}
      else {
        wp_send_json_success( '<p class="text-danger">' . $dancer->first_name . ' ' . $dancer->last_name . ' is already member of ' . $group->getGroupName() . '.</p>' );
      }
   	}
   	else {
      wp_send_json_success( '<p class="text-danger">Invalid dancer.</p>' );
   	}
 }

 // Remove dancer from group
 add_action( 'wp_ajax_ds_remove_group_dancer', 'ds_remove_group_dancer' );
 function ds_remove_group_dancer() {
   $dancer_id = intval( $_POST['dancer_id'] );
   $dancer = get_userdata( $dancer_id );
   $dance_school_id = intval( $_POST['dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   $group_id = $dance_school_fields['dance_school_currently_viewing']['group'];
   $group = $dance_school_fields['dance_school_groups_list'][$group_id];
   $success = $group->removeDancer( $dancer_id );
   if ( $success ) {
     $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
     $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
     if ( $db_result ) {
       wp_send_json_success( '<p class="text-info">Dancer was removed.</p>' );
     }
     else {
       wp_send_json_success( '<p class="text-danger">Dancer was not saved in database.</p>' );
     }
   }
   else {
     wp_send_json_success( '<p class="text-danger">Dancer is not member of ' . $group->getGroupName() . '.</p>' );
   }
 }

 // TEACHER - add to dance school
 add_action( 'wp_ajax_ds_add_teacher', 'ds_add_teacher' );
 function ds_add_teacher() {
   // get dancer & dance school objects
   $teacher_id = intval( $_POST['teacher_id'] );
   $teacher = get_userdata( $teacher_id );
   $dance_school_id = intval( $_POST['dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );
   // check if input is dancer
   if ( nkms_has_role( $teacher, 'dancer' ) ) {
     // get dance school fields
   	 $dance_school_fields = $dance_school->nkms_dance_school_fields;
     // check if teacher is already part of dance school
   	if ( ! in_array( $teacher_id, $dance_school_fields['dance_school_teachers_list'] ) ) {
       // get teacher fields
       $teacher_fields = $teacher->nkms_dancer_fields;
       // check if teacher is already managing another dance school
       if ( empty( $teacher_fields['dancer_teacher_of'] ) ) {
         // add dance school id to teacher array
         array_push( $teacher_fields['dancer_teacher_of'], $dance_school_id );
         // add teacher id to dance school list of teachers
         array_push( $dance_school_fields['dance_school_teachers_list'], $teacher_id );
         // save teacher fields
         update_user_meta( $teacher_id, 'nkms_dancer_fields', $teacher_fields );
         // save dance school fields
         update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
         wp_send_json_success("<p class='text-info'>Teacher added.</p>");
       }
       else {
         wp_send_json_success("<p class='text-danger'>Teacher is already managing another dance school.</p>");
       }
     }
     else {
       wp_send_json_success("<p class='text-danger'>Teacher is already managing this School.</p>");
     }
   }
   else {
   	wp_send_json_success("<p class='text-danger'>Invalid teacher ID.</p>");
   }
 }

 //Registered dancers
 // add_action( 'wp_ajax_registered_dancers', 'registered_dancers');
 // function registered_dancers() {
 //   $reg_dancers = $_POST['registered_dancers_num'];
 //   // var_dump($reg_dancers);
 //   echo "I got this: " . $reg_dancers . " wut ";
 //
 //   wp_die();
 // }
