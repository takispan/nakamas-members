<?php
/*
 * Ajax in WP
 *
 */
 //Add dancer to dance school list of dancers
 add_action( 'wp_ajax_ds_add_dancer', 'ds_add_dancer' );
 function ds_add_dancer() {
 	global $wpdb; // this is how you get access to the database

 	$dancer_to_add_id = intval($_POST['dancer']);
 	$dancer2add = get_user_by( 'id', $dancer_to_add_id );
 	if ( nkms_has_role( $dancer2add, 'dancer' ) ) {
 		$data_entry = get_user_meta(get_current_user_id(), 'dance_school_dancers_list', true);
 		if (!is_array($data_entry)) {
 			$data_entry = [];
 		}
 		$entry = $dancer_to_add_id;
 		if (!in_array($entry, $data_entry)) {
 			array_push($data_entry, $entry);
 		}
 		update_user_meta(get_current_user_id(), 'dance_school_dancers_list', $data_entry);
 		echo "Dancer added.";
 	}
 	else {
 		echo "Invalid Dancer ID";
 		//wp_send_json_error();
 	}
 	wp_die();
 }

 //Change dancer status
 add_action( 'wp_ajax_ds_change_status', 'ds_change_status');
 function ds_change_status() {
   global $wpdb;

   $dancer_id = $_POST['single_dancer_id'];
   $status = get_user_meta($dancer_id, 'active', true);
   echo "Status is of value: " . $status;
   if ($status != 1 && $status != 0) {
     echo "Was not bool so changed";
     $status = 1;
   }
   $status = abs($status - 1);
   update_user_meta($dancer_id, 'active', $status);
   echo "Dancer ID: " . $dancer_id;
   echo "Type of status: " . gettype($status);
   echo "Set active to: " . $status;
   wp_die();
 }

 //Pass dancer id to populate single dancer tab
 add_action( 'wp_ajax_ds_single_dancer', 'ds_single_dancer' );
 function ds_single_dancer() {
 	global $wpdb; // this is how you get access to the database
   $currview = get_user_meta(get_current_user_id(), 'currently_viewing', true);
   if (!is_array($currview)) {
     $currview = [0,0];
   }
   if (sizeof($currview) < 2) {
     array_push($currview, 0);
   }

   $currview[0] = intval($_POST['single_dancer_id']);
   echo $currview[0];
   echo 'tiny';
   update_user_meta(get_current_user_id(), 'currently_viewing', $currview );
   header("Refresh:0");
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
   $group_type = $_POST['group_type'];

   $data_entry = get_user_meta(get_current_user_id(), 'dance_school_groups_list', true);
   echo $data_entry;
 	if (!is_array($data_entry)) {
 		$data_entry = [];
     $last = 0;
 	}
   else {
     end($data_entry);
     $last = key($data_entry);
     $last++;
   }
   echo $last;
   $ds_group2add = new DanceGroup(get_current_user_id(), $last, $group_name, $group_type );
   $data_entry[$last] = $ds_group2add;
 	update_user_meta(get_current_user_id(), 'dance_school_groups_list', $data_entry);
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
