<?php

// Get all Dance Schools
function nkms_get_all_dance_schools() {
  $users = get_users( [ 'role__in' => [ 'dance-school' ] ] );
  return $users;
}

// Get all Dancers
function nkms_get_all_dancers() {
  $users = get_users( [ 'role__in' => [ 'dancer' ] ] );
  return $users;
}

// Get all Guardians
function nkms_get_all_guardians() {
  $users = get_users( [ 'role__in' => [ 'guardian' ] ] );
  return $users;
}

// Get all Groups
function nkms_get_all_groups() {
  $dance_schools_list = nkms_get_all_dance_schools();
  $groups_list = array();
  foreach ( $dance_schools_list as $dance_school ) {
    $groups_list_of_dance_school = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
    foreach ( $groups_list_of_dance_school as $group_id => $group ) {
      $groups_list[$dance_school->ID . '-' . $group_id] = $group;
    }
  }
  if ( ! empty( $groups_list ) ) {
    return $groups_list;
  }
  else {
    return false;
  }
}

// Get Dancers list of Dance School
function nkms_get_dancers_of_dance_school( $dance_school_id ) {
  if ( $dance_school_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $dancers_list = $dance_school->nkms_dance_school_fields['dance_school_dancers_list'];
    return $dancers_list;
  }
  else {
    return false;
  }
}

// Get Groups list of Dance School
function nkms_get_groups_of_dance_school( $dance_school_id ) {
  if ( $dance_school_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $groups_list = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
    return $groups_list;
  }
  else {
    return false;
  }
}

// Add dancer to Dance School
function nkms_add_dancer_to_dance_school( $dance_school_id, $dancer_id ) {
  $dancer_request = nkms_dancer_requests_to_join_dance_school( $dance_school_id, $dancer_id );
  $dance_school_accepts = nkms_dance_school_accepts_dancer_invite( $dance_school_id, $dancer_id );
  if ( $dancer_request && $dance_school_accepts ) {
    return true;
  }
  else {
    return false;
  }
}

// Add Dancer to Guardian
function nkms_add_dancer_to_guardian( $dancer_id, $guardian_id ) {
  $invite = nkms_guardian_invite_to_manage_dancer( $dancer_id, $guardian_id );
  $accept_inv = nkms_dancer_accepts_guardian_invite( $dancer_id, $guardian_id );
  if ( $invite && $accept_inv ) {
    return true;
  }
  else {
    return false;
  }
}

// Remove dancer from Dance School
function nkms_remove_dancer_from_dance_school( $dance_school_id, $dancer_id ) {
  if ( $dance_school_id && $dancer_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $dancers_list = $dance_school_fields['dance_school_dancers_list'];
    if ( in_array( $dancer_id, $dancers_list) ) {
      $new_dancers_list = array_diff( $dancers_list, [$dancer_id] );
      $dance_school_fields['dance_school_dancers_list'] = $new_dancers_list;
      $dancer = get_userdata( $dancer_id );
      $dancer_fields = $dancer->nkms_dancer_fields;
      $part_of_ds = $dancer_fields['dancer_part_of'];
      if ( in_array( $dance_school_id, $part_of_ds ) ) {
        $part_of_ds = array_diff( $part_of_ds, [$dance_school_id] );
        $dancer_fields['dancer_part_of'] = $part_of_ds;
        $update_dancer = update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
      }
      $update_ds = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
      if ( $update_dancer && $update_ds ) {
        return $dance_school_id;
      }
      else {
        return false;
      }
    }
  }
  else {
    return false;
  }
}

// Dancer request to join Dance School
function nkms_dancer_requests_to_join_dance_school( $dance_school_id, $dancer_id ) {
  if ( $dance_school_id && $dancer_id ) {
    $dance_school = get_userdata( $dance_school_id );
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    $ds_invites_list = $dance_school_fields['dance_school_invites'];
    if ( ! in_array( $dancer_id, $ds_invites_list ) ) {
      array_push( $ds_invites_list, $dancer_id );
      $dance_school_fields['dance_school_invites'] = $ds_invites_list;
      $db_result = update_user_meta( $dance_school->ID, 'nkms_dance_school_fields', $dance_school_fields );
      if ( $db_result ) {
        return $dance_school_id;
      }
      else {
        return false;
      }
    }
  }
  else {
    return false;
  }
}

// Dancer accepts invite to join dance school
function nkms_dancer_accepts_dance_school_invite( $dance_school_id, $dancer_id ) {
  $dance_school = get_userdata( $dance_school_id );
  $dancer = get_userdata( $dancer_id );

  if ( $dance_school_id && $dancer_id ) {
    // add dancer to dance school list of dancers
    $dance_school_fields = $dance_school->nkms_dance_school_fields;
    if ( ! in_array( $dancer_id, $dance_school_fields['dance_school_dancers_list'] ) ) {
      array_push( $dance_school_fields['dance_school_dancers_list'], $dancer_id );
    }
    $update1 = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );

    // remove dance school id from dancer_invites
    $dancer_fields = $dancer->nkms_dancer_fields;
    $dancer_fields['dancer_invites']['dance_school'] = array_diff( $dancer_fields['dancer_invites']['dance_school'], [$dance_school_id] );
    $part_of_ds = $dancer_fields['dancer_part_of'];
    if ( ! in_array( $dance_school_id, $part_of_ds ) ) {
      array_push( $part_of_ds, $dance_school_id );
      $dancer_fields['dancer_part_of'] = $part_of_ds;
    }
    $update2 = update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
    if ( $update1 && $update2 ) {
      return $dance_school_id;
    }
  }
  else {
    return false;
  }
}

// Dance School accepts dancer
function nkms_dance_school_accepts_dancer_invite( $dance_school_id, $dancer_id ) {
  if ( $dance_school_id && $dancer_id ) {
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
      $update1 = update_user_meta( $dancer->ID, 'nkms_dancer_fields', $dancer_fields );
      $update2 = update_user_meta( $dance_school->ID, 'nkms_dance_school_fields', $dance_school_fields );
      if ( $update1 && $update2 ) {
        return $dance_school_id;
      }
      else {
        return false;
      }
    }
  }
  else {
    return false;
  }
}

// Add dancer to group
function nkms_add_dancer_to_group( $dance_school_id, $group_id, $dancer_id ) {
  $dancer = get_userdata( $dancer_id );
  $dance_school = get_userdata( $dance_school_id );
  $dance_school_fields = $dance_school->nkms_dance_school_fields;
  $group = $dance_school_fields['dance_school_groups_list'][$group_id];
  if ( ! empty( $dancer_id ) ) {
    $success = $group->addDancer( $dancer_id );
    $age_cat = $group->changeAgeCategory();
    if ( $success ) {
      $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
      $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
      if ( $db_result ) {
        return true;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }
}

// Remove dancer from group
function nkms_remove_dancer_from_group( $dance_school_id, $group_id, $dancer_id ) {
  $dancer = get_userdata( $dancer_id );
  $dance_school = get_userdata( $dance_school_id );
  $dance_school_fields = $dance_school->nkms_dance_school_fields;
  $group = $dance_school_fields['dance_school_groups_list'][$group_id];
  $success = $group->removeDancer( $dancer_id );
  if ( $success ) {
    $dance_school_fields['dance_school_groups_list'][$group_id] = $group;
    $db_result = update_user_meta( $dance_school_id, 'nkms_dance_school_fields', $dance_school_fields );
    if ( $db_result ) {
      return true;
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }
}

// Guardian - Request to manage dancer
function nkms_guardian_invite_to_manage_dancer( $dancer_id, $guardian_id ) {
  $dancer = get_userdata( $dancer_id );
  $guardian = get_userdata( $guardian_id );

  // check if input is dancer
  if ( nkms_has_role( $dancer, 'dancer' ) ) {
    // get dancer fields
    $dancer_fields = $dancer->nkms_dancer_fields;
    // check if guardian has already sent an invite
    if ( ! in_array( $guardian_id, $dancer_fields['dancer_invites']['guardian'] ) ) {
      // add guardian id to dancer invites(guardian) array
      array_push( $dancer_fields['dancer_invites']['guardian'], $guardian_id );
      // save dancer fields
      $update = update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
      if ( $update ) {
        return $dancer_id;
      }
      else {
        return false;
      }
    }
  }
  else {
    return false;
  }
}

// Dancer accepts invite from guardian
function nkms_dancer_accepts_guardian_invite( $dancer_id, $guardian_id ) {
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
    $update1 = update_user_meta( $dancer_id, 'nkms_dancer_fields', $dancer_fields );
    // get guardian_dancers_list from guardian fields
    $guardian_fields = $guardian->nkms_guardian_fields;
    $guardian_dancers_list = $guardian_fields['guardian_dancers_list'];
    // add dancer_id to guardian_dancers_list
    if ( ! in_array( $dancer_id, $guardian_dancers_list ) ) {
      array_push( $guardian_dancers_list, $dancer_id );
    }
    $guardian_fields['guardian_dancers_list'] = $guardian_dancers_list;
    // save guardian_dancers_list
    $update2 = update_user_meta( $guardian_id, 'nkms_guardian_fields', $guardian_fields );
    if ( $update1 && $update2 ) {
      return $dancer_id;
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }
}

// Remove dancer from guardian
function nkms_remove_dancer_from_guardian( $guardian_id, $dancer_id ) {
  $guardian = get_userdata( $guardian_id );
  $dancer = get_userdata( $dancer_id );
  $guardian_fields = $guardian->nkms_guardian_fields;
  $guardian_dancers_list = $guardian_fields['guardian_dancers_list'];

  if ( in_array( $dancer_id, $guardian_dancers_list ) ) {
    $new_guardian_dancers_list = array_diff( $guardian_dancers_list, [$dancer_id] );
    $guardian_fields['guardian_dancers_list'] = $new_guardian_dancers_list;
    $update = update_user_meta( $guardian_id, 'nkms_guardian_fields', $guardian_fields );

    if ( $update ) {
      return true;
    }
    else {
      return false;
    }
  }
  else {
    return false;
  }
}
