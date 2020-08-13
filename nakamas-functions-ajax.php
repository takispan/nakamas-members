<?php
/*
 * Ajax in WP
 *
 */

 //Add dancer to dance school list of dancers
 add_action( 'wp_ajax_ds_add_dancer', 'ds_add_dancer' );
 function ds_add_dancer() {
 	global $wpdb; // this is how you get access to the database

 	$dancer2add_id = intval($_POST['dancer']);
 	$dancer2add = get_user_by( 'id', $dancer2add_id );
 	if ( nkms_has_role( $dancer2add, 'dancer' ) ) {
 		$dancers_list = get_user_meta(get_current_user_id(), 'dance_school_dancers_list', true);
 		if ( ! is_array( $dancers_list ) ) { $dancers_list = [];}
 		if ( ! in_array( $dancer2add_id, $dancers_list ) ) {
      array_push($dancers_list, $dancer2add_id);
   		update_user_meta(get_current_user_id(), 'dance_school_dancers_list', $dancers_list);
   		wp_send_json_success("<p class='text-info'>Dancer added.</p>");
    }
    else {
      wp_send_json_error("<p class='text-danger'>Dancer is already part of the School.</p>");
    }
 	}
 	else {
 		wp_send_json_error("<p class='text-danger'>Invalid dancer ID.</p>");
 	}
 }

 //Pass dancer id to populate single dancer tab
 add_action( 'wp_ajax_ds_single_dancer', 'ds_single_dancer' );
 function ds_single_dancer() {
 	global $wpdb; // this is how you get access to the database
   $currview = get_user_meta(get_current_user_id(), 'currently_viewing', true);
   $ds_name = get_user_meta( 'dance_school_name', get_current_user_id() );
   if ( ! is_array( $currview ) ) { $currview = [0,0]; }
   if ( sizeof($currview) < 2 ) { array_push($currview, 0); }

   $dancer_id = intval($_POST['single_dancer_id']);
   $currview[0] = $dancer_id;
   update_user_meta(get_current_user_id(), 'currently_viewing', $currview );
   $dancer = get_user_by( 'id', $dancer_id );
   (!get_user_meta($dancer_id, 'active', true)) ? $active_status = "Inactive" : $active_status = "Active";
   $single_dancer_data = '
   <h3 style="font-weight:300;">Dancer <span style="font-weight:600;">' . $dancer->first_name . ' ' . $dancer->last_name . '</span> for <span style="font-weight:600;">' . $ds_name . '</span></h3></br>
   <table>
     <tr>
       <th>ID</th>
       <th>Name</th>
       <th>Status</th>
     </tr>
     <tr>
       <td>' . $dancer_id . '</td>
       <td>' . $dancer->first_name . ' ' . $dancer->last_name . '</td>
       <td>' . $active_status . '</td>
     </tr>
   </table>
   <button class="change-dancer-status" data-dancer-id="' . $dancer_id . '">Change Status</button>
   <button class="remove-dancer" data-dancer-id="' . $dancer_id . '">Remove Dancer</button>';
   wp_send_json_success($single_dancer_data);
 	wp_die();
 }

 //Change dancer status
 add_action( 'wp_ajax_ds_change_status', 'ds_change_status');
 function ds_change_status() {
   global $wpdb;

   $dancer_id = $_POST['dancer_id'];
   $status = get_user_meta($dancer_id, 'active', true);
   if ($status != 1 && $status != 0) { $status = 1; }
   $status = abs($status - 1);
   update_user_meta($dancer_id, 'active', $status);
   wp_die();
 }

 //Remove dancer from dance school list of dancers
 add_action( 'wp_ajax_ds_remove_dancer', 'ds_remove_dancer' );
 function ds_remove_dancer() {
 	global $wpdb; // this is how you get access to the database

   $user_id = get_current_user_id();
 	$dancer_to_remove_id = intval($_POST['single_dancer_id']);
   $dancers_list = get_user_meta(get_current_user_id(), 'dance_school_dancers_list', true);
   if ( in_array( $dancer_to_remove_id, $dancers_list) ) {
     $data_entry = array_diff( $dancers_list, [$dancer_to_remove_id] );
     update_user_meta(get_current_user_id(), 'dance_school_dancers_list', $data_entry);
   	echo "Dancer removed.";
   }
   else {
     echo "Error occured.";
     wp_send_json_error();
 	}
 	wp_die();
 }

 //Add group to dance school list of groups
 add_action( 'wp_ajax_ds_add_group', 'ds_add_group' );
 function ds_add_group() {
   global $wpdb; // this is how you get access to the database
   $group_name = $_POST['group_name'];
   if ( empty($group_name) ) {wp_send_json_error();}
   $group_type = $_POST['group_type'];

   $ds_groups_list_array = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);
   if (!is_array($ds_groups_list_array)) {
     $ds_groups_list_array = [];
     $last = 0;
   }
   else {
     end($ds_groups_list_array);
     $last = key($ds_groups_list_array);
     $last++;
   }
   echo $last;
   $ds_group2add = new DanceGroup(get_current_user_id(), $last, $group_name, $group_type );
   $ds_groups_list_array[$last] = $ds_group2add;
   update_user_meta(get_current_user_id(), 'dance_school_groups_list', $ds_groups_list_array);
   echo "Group added.";
   wp_die();
 }

 //Pass group id to populate single group tab
 add_action( 'wp_ajax_ds_single_group', 'ds_single_group' );
 function ds_single_group() {
   global $wpdb; // this is how you get access to the database

   $currview = get_user_meta(get_current_user_id(), 'currently_viewing', true);
   if (!is_array($currview)) {
     $currview = [0,0];
   }
   if (sizeof($currview) < 2) {
     array_push($currview, 0);
   }

   $currview[1] = intval($_POST['single_group_id']);
   echo $currview[1];
   update_user_meta(get_current_user_id(), 'currently_viewing', $currview );
 	 wp_die();
 }

 //Add dancer to group
 add_action( 'wp_ajax_ds_add_group_dancer', 'ds_add_group_dancer' );
 function ds_add_group_dancer() {
   	global $wpdb; // this is how you get access to the database
    $currview = get_user_meta(get_current_user_id(), 'currently_viewing', true);
    $groups_list = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);
    $group_id = $currview[1];
    $group = $groups_list[$group_id];
   	$dancer_to_add_id = intval($_POST['dancer']);
   	$dancer2add = get_user_by( 'id', $dancer_to_add_id );
   	if ( nkms_has_role( $dancer2add, 'dancer' ) ) {
   		$data_entry = $group->addDancer($dancer_to_add_id);
   		if ( $data_entry ) {
         $groups_list[$group_id] = $group;
         update_user_meta(get_current_user_id(), 'dance_school_groups_list', $groups_list);
   			echo 'Dancer added.';
   		}
       else {
         echo 'An error occured, dancer not added.';
       }
   	}
   	else {
   		echo "Invalid Dancer ID";
   		wp_send_json_error();
   	}
   	wp_die();
 }

 //Remove dancer from group
 add_action( 'wp_ajax_ds_remove_group_dancer', 'ds_remove_group_dancer' );
 function ds_remove_group_dancer() {
 	 global $wpdb; // this is how you get access to the database
   $currview = get_user_meta(get_current_user_id(), 'currently_viewing', true);
   $groups_list = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);

   $group_id = $currview[1];
   $group = $groups_list[$group_id];
   var_dump($group);
   echo $group->getGroupName();

 	 $dancer_to_remove_id = intval($_POST['dancer']);
   $data_entry = $group->removeDancer($dancer_to_remove_id);
 	 if ( $data_entry ) {
     $groups_list[$group_id] = $group;
     update_user_meta(get_current_user_id(), 'dance_school_groups_list', $groups_list);
     echo 'Dancer removed.';
 	 }
   else {
     echo 'An error occured, dancer was not removed.';
   }
 	 wp_die();
 }

 //Change dancer status
 add_action( 'wp_ajax_ds_group_change_status', 'ds_group_change_status');
 function ds_group_change_status() {
   global $wpdb;
   $groups_list = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);

   $group_id = $_POST['group_id'];
   $group = $groups_list[$group_id];
   $status = $group->getStatus();
   ( $status == 'Active' ) ? $status = 'Inactive' : $status = 'Active';
   $group->setStatus($status);

   $groups_list[$group_id] = $group;
   update_user_meta(get_current_user_id(), 'dance_school_groups_list', $groups_list);
   echo "Group status set to " . $status;
   wp_die();
 }

 //Registered dancers
 add_action( 'wp_ajax_registered_dancers', 'registered_dancers');
 function registered_dancers() {
   $reg_dancers = $_POST['registered_dancers_num'];
   // var_dump($reg_dancers);
   echo "I got this: " . $reg_dancers . " wut ";

   wp_die();
 }
