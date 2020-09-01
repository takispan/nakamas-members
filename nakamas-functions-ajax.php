<?php
/*
 * Ajax in WP
 *
 */
 // Guardian - Request to manage dancer
add_action( 'wp_ajax_guardian_invite_to_manage_dancer', 'guardian_invite_to_manage_dancer' );
function guardian_invite_to_manage_dancer() {
  // get dancer & guardian objects
  $dancer_id = intval( $_POST['dancer_id'] );
  $dancer = get_user_by( 'id', $dancer_id );
  $guardian_id = intval( $_POST['guardian_id'] );
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
      wp_send_json_success("<p class='text-danger'>Dancer is already part of the School.</p>");
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
   <h3 style="font-weight:300;">Dancer <span style="font-weight:600;">' . $dancer->first_name . ' ' . $dancer->last_name . '</span> for <span style="font-weight:600;">' . $dance_school->nkms_dance_school_fields['dance_school_name'] . '</span></h3></br>
   <div class="dancer-details">
     <p class="nkms-pfp">' . get_wp_user_avatar( $dancer_id, '256', '' ) . '</p>
     <p><span>Soar ID</span>' . $dancer_id . '</p>
     <p><span>Full Name</span>' . $dancer->first_name . ' ' . $dancer->last_name . '</p>
     <p><span>Status</span>' . $dancer->nkms_dancer_fields['dancer_status'] . '</p>
     <p><span>Experience</span>' . $dancer->nkms_fields['experience'] . '</p>
     <p><span>Category</span>' . $dancer->nkms_dancer_fields['dancer_age_category'] . '</p>
   </div>
   <button class="change-dancer-status" data-ds-id="' . $dance_school_id . '" data-dancer-id="' .  $dancer_id . '">Change Status</button>
   <button class="remove-dancer" data-ds-id="' . $dance_school_id . '" data-dancer-id="' . $dancer_id . '">Remove Dancer</button>';
   wp_send_json_success( $single_dancer_data );
 }

 //Change dancer status
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

 //Remove dancer from dance school list of dancers
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
     update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
     wp_send_json_sucess('<p class="text-info">Dancer was removed.</p>');
   }
   wp_send_json_error();
 }

 //Add group to dance school list of groups
 add_action( 'wp_ajax_ds_add_group', 'ds_add_group' );
 function ds_add_group() {
   $group_name = $_POST['group_name'];
   if ( ! $group_name ) { wp_send_json_success( "<p class='text-danger'>Group name cannot be empty.</p>" ); }
   $group_type = $_POST['group_type'];
   if ( ! $group_type ) { wp_send_json_success( "<p class='text-danger'>Group type must be selected.</p>" ); }
   $dance_school_id = intval( $_POST['dance_school_id'] );

   $dance_school = get_userdata( $dance_school_id );
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   end( $dance_school_fields['dance_school_groups_list'] );
   $last = key( $dance_school_fields['dance_school_groups_list'] );
   $last++;

   $ds_new_group = new DanceGroup( $dance_school_id, $last, $group_name, $group_type );
   $dance_school_fields['dance_school_groups_list'][$last] = $ds_new_group;
   update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
   wp_send_json_success("<p class='text-info'>Group added successfully.</p>");
 }

 //Pass group id to populate single group tab
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
   <h3 style="font-weight:300;">Dance Group <span style="font-weight:600;">' . $group->getGroupName() . '</span> for <span style="font-weight:600;">' . $dance_school->nkms_dance_school_fields['dance_school_name'] . '</span></h3>
   <div class="group-details">
     <p><span>Type</span>' . $group->getType() . '</p>
     <p><span>Name</span>' . $group->getGroupName() . '</p>
     <p><span>Status</span>' . $group->getStatus() . '</p>
     <button class="change-group-status" data-ds-id="' . $dance_school_id . '" data-dancer-id="' .  $group_id . '">Change Status</button>
     <h4 style="font-weight: 300;">Dancers of <span style="font-weight:600;">' . $group->getGroupName() . '</span></h4>
     <p>' . $print_dancers . '</p>
     <a data-toggle="tab" href="#ds-group-add-dancers" class="nkms-btn">Add Dancers</a>';

   if ( ! empty( $group_dancers ) ) {
     $single_group_data .= '<a style="margin-left:5px;" data-toggle="tab" href="#ds-group-remove-dancers" class="nkms-btn">Remove Dancers</a>';
   }
   $single_group_data .= '</div>';

   wp_send_json_success( $single_group_data );
 }

 //Add dancer to group
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
   		if ( $success ) {
        $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
        $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
        if ( $db_result ) {
          wp_send_json_success( '<p class="text-info">Dancer added.</p>' );
        }
        else {
          wp_send_json_success( '<p class="text-danger">Dancer was not saved in database.</p>' );
        }
   		}
      else {
        wp_send_json_success( '<p class="text-danger">Dancer is already part of ' . $group->getGroupName() . '.</p>' );
      }
   	}
   	else {
      wp_send_json_success( '<p class="text-danger">Invalid dancer.</p>' );
   	}
 }

 //Remove dancer from group
 add_action( 'wp_ajax_ds_remove_group_dancer', 'ds_remove_group_dancer' );
 function ds_remove_group_dancer() {
   $dancer_id = intval( $_POST['dancer_id'] );
   $dancer = get_userdata( $dancer_id );
   $dance_school_id = intval( $_POST['dance_school_id'] );
   $dance_school = get_userdata( $dance_school_id );
   $dance_school_fields = $dance_school->nkms_dance_school_fields;
   $group_id = $dance_school_fields['dance_school_currently_viewing']['group'];
   $group = $dance_school_fields['dance_school_groups_list'][$group_id];
   // if ( ! empty( $dancer_id ) ) {
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
       wp_send_json_success( '<p class="text-danger">Dancer is not part of ' . $group->getGroupName() . '.</p>' );
     }
   // }
   // else {
   //   wp_send_json_success( '<p class="text-danger">Invalid dancer.</p>' );
   // }
 }

 //Change group status
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
         $teacher_save_to_db = update_user_meta( $teacher_id, 'nkms_dancer_fields', $teacher_fields );
         $teacher_saved = false;
         $ds_saved = false;
         if ( $teacher_save_to_db ) {
           $teacher_saved = true;
         }
         else {
           wp_send_json_success("<p class='text-info'>Problem saving teacher data.</p>");
         }
         // save dance school fields
         $ds_save_to_db = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
         if ( $ds_save_to_db ) {
           // wp_send_json_success("<p class='text-info'>Teacher added.</p>");
           $ds_saved = true;
         }
         else {
           wp_send_json_success("<p class='text-info'>Problem saving teacher data.</p>");
         }
         if ( $teacher_saved && $ds_saved ) {
           wp_send_json_success("<p class='text-info'>Teacher added.</p>");
         }
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
