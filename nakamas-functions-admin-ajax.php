<?php
/*
 * Ajax in WP
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

 /*
  * INVITE SYSTEM
 **/
// Dancer requests to join a dance school
add_action( 'wp_ajax_dancer_requests_to_join_dance_school', 'dancer_requests_to_join_dance_school' );
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
