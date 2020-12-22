<?php
/*
 * Ajax in Admin WP
 *
 */
 // Update user profile
 // add_action( 'wp_ajax_user_update_profile', 'user_update_profile' );
 // function user_update_profile() {
 //   	$user_id = intval( $_POST['update_profile_user_id'] );
 //  	// $email = $_POST['update_profile_email'];
 //  	$phone_number = $_POST['update_profile_phone_number'];
 //  	$city = $_POST['update_profile_city'];
 //  	$address = $_POST['update_profile_address'];
 //  	$postcode = $_POST['update_profile_postcode'];
 //  	$guardian_name = $_POST['update_profile_guardian_name'];
 //  	$guardian_phone_number = $_POST['update_profile_guardian_phone_number'];
 //  	$guardian_email = $_POST['update_profile_guardian_email'];
 //  	$dancer_ds_name = $_POST['update_profile_dancer_ds_name'];
 //  	$dancer_ds_teacher_name = $_POST['update_profile_dancer_ds_teacher_name'];
 //  	$dancer_ds_teacher_email = $_POST['update_profile_dancer_ds_teacher_email'];
 //  	$dance_school_name = $_POST['update_profile_dance_school_name'];
 //  	$dance_school_address = $_POST['update_profile_dance_school_address'];
 //  	$dance_school_phone_number = $_POST['update_profile_dance_school_phone_number'];
 //  	$dance_school_description = $_POST['update_profile_dance_school_description'];
 //
 // 		if ( $user_id ) {
 //      $user = get_userdata( $user_id );
 //      $user_fields = $user->nkms_fields;
 //      // $user_fields['email'] = $email;
 //      $user_fields['phone_number'] = $phone_number;
 //      $user_fields['city'] = $city;
 //      $user_fields['address'] = $address;
 //      $user_fields['postcode'] = $postcode;
 //
 //      update_user_meta( $user_id, 'nkms_fields', $user_fields );
 //
 //      if ( nkms_has_role( $user, 'dancer' ) ) {
 //        $dancer_fields = $user->nkms_dancer_fields;
 //        $age = nkms_get_age( $user_fields['dob'] );
 //        if ( $age < 18 ) {
 //          $dancer_fields['dancer_guardian_name'] = $guardian_name;
 //          $dancer_fields['dancer_guardian_phone_number'] = $guardian_phone_number;
 //          $dancer_fields['dancer_guardian_email'] = $guardian_email;
 //        }
 //        $dancer_fields['dancer_ds_name'] = $dancer_ds_name;
 //        $dancer_fields['dancer_ds_teacher_name'] = $dancer_ds_teacher_name;
 //        $dancer_fields['dancer_ds_teacher_email'] = $dancer_ds_teacher_email;
 //
 //        update_user_meta( $user_id, 'nkms_dancer_fields', $dancer_fields );
 //      }
 //      if ( nkms_has_role( $user, 'dance-school' ) ) {
 //        $dance_school_fields = $user->nkms_dance_school_fields;
 //        $dance_school_fields['dance_school_name'] = $dance_school_name;
 //        $dance_school_fields['dance_school_address'] = $dance_school_address;
 //        $dance_school_fields['dance_school_phone_number'] = $dance_school_phone_number;
 //        $dance_school_fields['dance_school_description'] = $dance_school_description;
 //
 //        update_user_meta( $user_id, 'nkms_dance_school_fields', $dance_school_fields );
 //      }
 //      wp_send_json_success( '<p class="text-info">Profile updated.</p>' );
 // 		}
 //    else {
 //      wp_send_json_success( '<p class="text-danger">Invalid user ID.</p>' );
 //    }
 // }

// Add Dancer to Dance School
add_action( 'wp_ajax_admin_add_dancer_to_dance_school', 'admin_add_dancer_to_dance_school' );
function admin_add_dancer_to_dance_school() {
  $dance_school_id = intval( $_POST['admin_add_dancer_to_dance_school_dance_school_id'] );
  $dancer_id = intval( $_POST['admin_add_dancer_to_dance_school_dancer_id'] );

  $update = nkms_add_dancer_to_dance_school( $dance_school_id, $dancer_id );
  if ( $update ) {
    wp_send_json_success( '<p class="text-info">Dancer added.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Remove Dancer from Dance School
// Populate 2nd select when Dance School is selected (to select Dancer from Dance School)
add_action( 'wp_ajax_admin_get_dancers_of_dance_school', 'admin_get_dancers_of_dance_school' );
function admin_get_dancers_of_dance_school() {
  $dance_school_id = intval( $_POST['admin_get_dancers_of_dance_school_dance_school_id'] );

  $dancers_list = nkms_get_dancers_of_dance_school( $dance_school_id );
  if ( $dancers_list ) {
    $return = '<option value="" selected disabled hidden>Select a dancer</option>';
    foreach ( $dancers_list as $dancer_id ) {
      $dancer = get_userdata( $dancer_id );
      $return .= '<option value="' . $dancer_id . '">' . $dancer_id . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
    }
    wp_send_json_success( $return );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}
// Submit form
add_action( 'wp_ajax_admin_remove_dancer_from_dance_school', 'admin_remove_dancer_from_dance_school' );
function admin_remove_dancer_from_dance_school() {
  $dance_school_id = intval( $_POST['admin_remove_dancer_from_dance_school_dance_school_id'] );
  $dancer_id = intval( $_POST['admin_remove_dancer_from_dance_school_dancer_id'] );

  $update = nkms_remove_dancer_from_dance_school( $dance_school_id, $dancer_id );
  if ( $update ) {
    wp_send_json_success( '<p class="text-info">Dancer removed.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Add Dancer to Dance Group
add_action( 'wp_ajax_admin_add_dancer_to_dance_group', 'admin_add_dancer_to_dance_group' );
function admin_add_dancer_to_dance_group() {
  $dance_school_id = intval( $_POST['admin_add_dancer_to_dance_group_dance_school_id'] );
  $group_id = intval( $_POST['admin_add_dancer_to_dance_group_group_id'] );
  $dancer_id = intval( $_POST['admin_add_dancer_to_dance_group_dancer_id'] );

  $update = nkms_add_dancer_to_group( $dance_school_id, $group_id, $dancer_id );
  if ( $update ) {
    wp_send_json_success( '<p class="text-info">Dancer added.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Remove Dancer from Dance Group
// Populate 2nd select when Dance School is selected (to select Dancer from Dance School)
add_action( 'wp_ajax_admin_get_dancers_of_dance_group', 'admin_get_dancers_of_dance_group' );
function admin_get_dancers_of_dance_group() {
  $dance_school_id = intval( $_POST['admin_get_dancers_of_dance_school_dance_school_id'] );
  $group_id = intval( $_POST['admin_get_dancers_of_dance_school_dance_group_id'] );

  $dance_school = get_userdata( $dance_school_id );
  $dance_school_fields = $dance_school->nkms_dance_school_fields;
  $group = $dance_school_fields['dance_school_groups_list'][$group_id];

  $dancers_list = $group->getDancers();
  if ( $dancers_list ) {
    $return = '<option value="" selected disabled hidden>Select a dancer</option>';
    foreach ( $dancers_list as $dancer_id ) {
      $dancer = get_userdata( $dancer_id );
      $return .= '<option value="' . $dancer_id . '">' . $dancer_id . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
    }
    wp_send_json_success( $return );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}
//submit form
add_action( 'wp_ajax_admin_remove_dancer_from_dance_group', 'admin_remove_dancer_from_dance_group' );
function admin_remove_dancer_from_dance_group() {
  $dance_school_id = intval( $_POST['admin_remove_dancer_from_dance_group_dance_school_id'] );
  $group_id = intval( $_POST['admin_remove_dancer_from_dance_group_group_id'] );
  $dancer_id = intval( $_POST['admin_remove_dancer_from_dance_group_dancer_id'] );

  $update = nkms_remove_dancer_from_group( $dance_school_id, $group_id, $dancer_id );
  if ( $update ) {
    wp_send_json_success( '<p class="text-info">Dancer removed.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Add dancer to guardian
add_action( 'wp_ajax_admin_add_dancer_to_guardian', 'admin_add_dancer_to_guardian' );
function admin_add_dancer_to_guardian() {
  $guardian_id = intval( $_POST['admin_add_dancer_to_guardian_guardian_id'] );
  $dancer_id = intval( $_POST['admin_add_dancer_to_guardian_dancer_id'] );

  $update = nkms_add_dancer_to_guardian( $dancer_id, $guardian_id );
  if ( $update ) {
    wp_send_json_success( '<p class="text-info">Dancer added.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

// Remove Dancer from Guardian
// Populate 2nd select when Guardian is selected (to select Dancer from Guardian)
add_action( 'wp_ajax_admin_get_dancers_of_guardian', 'admin_get_dancers_of_guardian' );
function admin_get_dancers_of_guardian() {
  $guardian_id = intval( $_POST['admin_get_dancers_of_guardian_guardian_id'] );
  $guardian = get_userdata( $guardian_id );
  $guardian_fields = $guardian->nkms_guardian_fields;
  $guardian_dancers_list = $guardian_fields['guardian_dancers_list'];

  if ( $guardian_dancers_list ) {
    $return = '<option value="" selected disabled hidden>Select a dancer</option>';
    foreach ( $guardian_dancers_list as $dancer_id ) {
      $dancer = get_userdata( $dancer_id );
      $return .= '<option value="' . $dancer_id . '">' . $dancer_id . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
    }
    wp_send_json_success( $return );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}
//submit form
add_action( 'wp_ajax_admin_remove_dancer_from_guardian', 'admin_remove_dancer_from_guardian' );
function admin_remove_dancer_from_guardian() {
  $guardian_id = intval( $_POST['admin_remove_dancer_from_guardian_guardian_id'] );
  $dancer_id = intval( $_POST['admin_remove_dancer_from_guardian_dancer_id'] );

  $update = nkms_remove_dancer_from_guardian( $guardian_id, $dancer_id );
  if ( $update ) {
    wp_send_json_success( '<p class="text-info">Dancer removed.</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}
