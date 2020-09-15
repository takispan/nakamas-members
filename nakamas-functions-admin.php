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
*/

// Add admin pages
function my_admin_menu() {
  add_menu_page( 'Nakamas Members', 'Soar', 'manage_options', 'nkms-members', 'my_admin_page_contents', 'dashicons-admin-users', 3 );
}

add_action( 'admin_menu', 'my_admin_menu' );
function my_admin_page_contents() {
  echo '<h1>Welcome to Nakamas Members</h1>';
  echo '<form method="post"><input type="submit" name="admin_update_user_fields" value="Display Soar fields" /></form>';
  nkms_fix_user_meta();
  // echo '<form action="nkms_fix_user_meta()" method="post">'
  //     . '<button type="submit">Show users</button>'
  //     . '</form>';
}

// Fix user meta
function nkms_fix_user_meta() {
  if ( isset( $_POST['admin_update_user_fields'] ) ) {
    $users_list = get_users();
    foreach ( $users_list as $user ) {
      if ( $user->ID > 1 ) {
        // Basic fields
        $user_fields = $user->nkms_fields;
        // if empty, initialize
        if ( empty( $user_fields['dob'] ) ) { $user_fields['dob'] = '17/12/1987'; }
        if ( empty ( $user_fields['country'] ) ) { $user_fields['country'] = 'GB'; }
        if ( empty ( $user_fields['city'] ) ) { $user_fields['city'] = ''; }
        if ( empty ( $user_fields['address'] ) ) { $user_fields['address'] = ''; }
        if ( empty ( $user_fields['postcode'] ) ) { $user_fields['postcode'] = ''; }
        if ( empty ( $user_fields['phone_number'] ) ) { $user_fields['phone_number'] = ''; }
        // save fields
        update_user_meta( $user->ID, 'nkms_fields', $user_fields );

        // Dancer
        if ( $user->roles[0] === 'dancer' ) {
          // Dancer fields
          $dancer_fields = $user->nkms_dancer_fields;
          // if empty, initialize
          if ( empty ( $dancer_fields['dancer_ds_name'] ) ) { $dancer_fields['dancer_ds_name'] = ''; }
          if ( empty ( $dancer_fields['dancer_ds_teacher_name'] ) ) { $dancer_fields['dancer_ds_teacher_name'] = ''; }
          if ( empty ( $dancer_fields['dancer_ds_teacher_email'] ) ) { $dancer_fields['dancer_ds_teacher_email'] = ''; }
          if ( empty ( $dancer_fields['dancer_level'] ) ) { $dancer_fields['dancer_level'] = 'Novice'; }
          if ( empty ( $dancer_fields['dancer_age_category'] ) ) { $dancer_fields['dancer_age_category'] = '17&o'; }
          if ( empty ( $dancer_fields['dancer_status'] ) ) { $dancer_fields['dancer_status'] = 'Inactive'; }
          if ( empty ( $dancer_fields['dancer_invites'] ) ) { $dancer_fields['dancer_invites'] = array( array(), array() ); }
          if ( empty ( $dancer_fields['dancer_guardian_list'] ) ) { $dancer_fields['dancer_guardian_list'] = array(); }
          if ( empty ( $dancer_fields['dancer_guardian_name'] ) ) { $dancer_fields['dancer_guardian_name'] = ''; }
          if ( empty ( $dancer_fields['dancer_guardian_email'] ) ) { $dancer_fields['dancer_guardian_email'] = ''; }
          if ( empty ( $dancer_fields['dancer_guardian_phone_number'] ) ) { $dancer_fields['dancer_guardian_phone_number'] = ''; }
          if ( empty ( $dancer_fields['dancer_teacher_of'] ) ) { $dancer_fields['dancer_teacher_of'] = array(); }
          if ( empty ( $dancer_fields['dancer_part_of'] ) ) { $dancer_fields['dancer_part_of'] = array(); }
          // save fields
          update_user_meta( $user->ID, 'nkms_dancer_fields', $dancer_fields );
        }
        // Dance school
        if ( $user->roles[0] === 'dance-school' ) {
          // Dance school fields
          $dance_school_fields = $user->nkms_dance_school_fields;
          // if empty, initialize
          if ( empty ( $dance_school_fields['dance_school_name'] ) ) { $dance_school_fields['dance_school_name'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_address'] ) ) { $dance_school_fields['dance_school_address'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_phone_number'] ) ) { $dance_school_fields['dance_school_phone_number'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_description'] ) ) { $dance_school_fields['dance_school_description'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_dancers_list'] ) ) { $dance_school_fields['dance_school_dancers_list'] = array(); }
          if ( empty ( $dance_school_fields['dance_school_groups_list'] ) ) { $dance_school_fields['dance_school_groups_list'] = array(); }
          if ( empty ( $dance_school_fields['dance_school_teachers_list'] ) ) { $dance_school_fields['dance_school_teachers_list'] = array(); }
          if ( empty ( $dance_school_fields['dance_school_invites'] ) ) { $dance_school_fields['dance_school_invites'] = array(); }
          if ( empty ( $dance_school_fields['dance_school_currently_viewing'] ) ) { $dance_school_fields['dance_school_currently_viewing'] = array( 'dancer' => 0, 'group' => 0 ); }
          // save fields
          update_user_meta( $user->ID, 'nkms_dance_school_fields', $dance_school_fields );
        }
        // Guardian
        if ( $user->roles[0] === 'guardian' ) {
          // Guardian fields
          $guardian_fields = $user->nkms_guardian_fields;
          // if empty, initialize
          if ( empty ( $guardian_fields['guardian_dancers_list'] ) ) { $guardian_fields['guardian_dancers_list'] = array(); }
          if ( empty ( $guardian_fields['guardian_invites'] ) ) { $guardian_fields['guardian_invites'] = array(); }
          // save fields
          update_user_meta( $user->ID, 'nkms_guardian_fields', $guardian_fields );
        }
      }
    }
    echo 'Process completed.<br>';
    nkms_display_all_user_meta();
  }
}

function nkms_display_all_user_meta() {
    $users_list = get_users();
    foreach ( $users_list as $user ) {
      if ( $user->ID > 1 ) {
        echo '<p><strong>' . $user->ID . ': ' . $user->roles[0] . '</strong></p>';
        // Full Name
        $fullname = $user->first_name . ' ' . $user->last_name;
        // Basic fields
        $user_fields = $user->nkms_fields;
        echo '<u>Basic fields</u><br>';
        // Display Basic fields
        echo 'Full Name: ' . $fullname . '<br>';
        echo 'DOB: ' . $user_fields['dob'] . '<br>';
        echo 'Country: ' . $user_fields['country'] . '<br>';
        echo 'City: ' . $user_fields['city'] . '<br>';
        echo 'Address: ' . $user_fields['address'] . '<br>';
        echo 'Postcode: ' . $user_fields['postcode'] . '<br>';
        echo 'Phone number: ' . $user_fields['phone_number'] . '<br>';

        // Dancer
        if ( $user->roles[0] === 'dancer' ) {
          // Dancer fields
          $dancer_fields = $user->nkms_dancer_fields;
          echo '<br><u>Dancer fields</u><br>';
          // Display Dancer fields
          echo 'Dance School name: ' . $dancer_fields['dancer_ds_name'] . '<br>';
          echo 'Dance School teacher name: ' . $dancer_fields['dancer_ds_teacher_name'] . '<br>';
          echo 'Dance School teacher email: ' . $dancer_fields['dancer_ds_teacher_email'] . '<br>';
          echo 'Level: ' . $dancer_fields['dancer_level'] . '<br>';
          echo 'Age category: ' . $dancer_fields['dancer_age_category'] . '<br>';
          echo 'Dancer status: ' . $dancer_fields['dancer_status'] . '<br>';
          echo 'Guardian ID: ' . ( empty ( $dancer_fields['dancer_guardian_list'] ) ? '-' : implode( ', ', $dancer_fields['dancer_guardian_list'] ) ) . '<br>';
          echo 'Guardian Name: ' . $dancer_fields['dancer_guardian_name'] . '<br>';
          echo 'Guardian Email: ' . $dancer_fields['dancer_guardian_email'] . '<br>';
          echo 'Guardian Phone number: ' . $dancer_fields['dancer_guardian_phone_number'] . '<br>';
          echo 'Teacher of: ' . implode( ', ', $dancer_fields['dancer_teacher_of']) . '<br>';
          echo 'Part of: ' . implode( ', ', $dancer_fields['dancer_part_of']) . '<br>';
          // Dancer invites
          echo '<u>Dancer invites:</u><br>';
          echo 'Guardian: ' . implode( ', ', $dancer_fields['dancer_invites']['guardian'] ) . '<br>';
          echo 'Dance School: ' . implode( ', ', $dancer_fields['dancer_invites']['dance_school'] ) . '<br>';
        }
        // Dance school
        if ( $user->roles[0] === 'dance-school' ) {
          // Dance school fields
          $dance_school_fields = $user->nkms_dance_school_fields;
          echo '<br><u>Dance School fields</u><br>';
          // Display Dance school fields
          echo 'Name: ' . $dance_school_fields['dance_school_name'] . '<br>';
          echo 'Address: ' . $dance_school_fields['dance_school_address'] . '<br>';
          echo 'Phone number: ' . $dance_school_fields['dance_school_phone_number'] . '<br>';
          echo 'Description: ' . $dance_school_fields['dance_school_description'] . '<br>';
          echo 'Dancers list: ' . implode( ', ', $dance_school_fields['dance_school_dancers_list'] ) . '<br>';
          echo '<u>Groups list:</u><br>';
          $groups_list = $dance_school_fields['dance_school_groups_list'];
          foreach ( $groups_list as $key => $group ) {
            echo $key . '. ' . $group->getGroupName() . '<br>';
          }
          echo 'Dancers invites: ' . implode( ', ', $dance_school_fields['dance_school_invites'] ) . '<br>';
          echo '<u>Currently looking:</u>';
          foreach ( $dance_school_fields['dance_school_currently_viewing'] as $key => $value ) {
            echo '<br>' . $key . ': ' . $value;
          }
          echo '<br>';
        }
        // Guardian
        if ( $user->roles[0] === 'guardian' ) {
          // Guardian fields
          $guardian_fields = $user->nkms_guardian_fields;
          echo '<u>Guardian fields</u><br>';
          echo 'Dancers list: ' . implode( ', ', $guardian_fields['guardian_dancers_list'] ) . '<br>';
          echo 'Invites list: ' . implode( ', ', $guardian_fields['guardian_invites'] ) . '<br>';
        }
      }
    }
}
