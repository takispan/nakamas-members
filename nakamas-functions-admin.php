<?php

/*
 * Admin Pages
 *
 */

// Edit user fields (on default user page)
add_action( 'show_user_profile', 'nkms_user_profile_fields' );
add_action( 'edit_user_profile', 'nkms_user_profile_fields' );
function nkms_user_profile_fields( $user ) { ?>
  <h2>Soar Member Fields</h2>

  <table class="form-table">
  <tr>
    <th><label for="dob">Date of Birth</label></th>
    <td><input type="text" name="dob" id="dob" placeholder="DD/MM/YYYY" value="<?php echo $user->nkms_fields['dob']; ?>" class="regular-text" /></td>
  </tr>
  <?php if ( $user->roles[0] === 'dance-school' ) : ?>
  <tr>
    <th><label for="dsname">Dance School name</label></th>
    <td><input type="text" name="dsname" id="dsname" value="<?php echo $user->nkms_dance_school_fields['dance_school_name']; ?>" class="regular-text" /></td>
  </tr>
  <?php endif; ?>
  </table>
<?php }
// and save the fields
add_action( 'personal_options_update', 'nkms_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'nkms_save_user_profile_fields' );
function nkms_save_user_profile_fields( $user_id ) {
    if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'update-user_' . $user_id ) ) {
        return;
    }
    // if ( ! current_user_can( 'edit_user', $user_id ) ) {
    //     return false;
    // }

    $user = get_userdata( $user_id );
    $nkms_fields = $user->nkms_fields;
    $nkms_fields[dob] = $_POST['dob'];

    update_user_meta( $user_id, 'nkms_fields', $nkms_fields );

    // also update dance school name if account is dance school
    // if ( $user->roles[0] === 'dance-school' ) {
      $nkms_dance_school_fields = $user->nkms_dance_school_fields;
      $nkms_dance_school_fields[dance_school_name] = $_POST['dsname'];
      update_user_meta( $user_id, 'nkms_dance_school_fields', $nkms_dance_school_fields );
    // }
}

// Add admin pages
function nkms_admin_menu() {
  add_menu_page( 'Nakamas Members', 'Soar Members', 'manage_options', 'nkms-members', 'nkms_admin_contents', 'dashicons-admin-users', 3 );
  add_submenu_page( 'nkms-members', 'Groups', 'Groups', 'manage_options', 'nkms-groups', 'nkms_admin_groups' );
  add_submenu_page( 'nkms-members', 'Actions', 'Actions', 'manage_options', 'nkms-actions', 'nkms_admin_actions' );
  add_submenu_page( 'nkms-members', 'Registrations', 'Registrations', 'manage_options', 'nkms-registrations', 'nkms_admin_registrations' );
}

add_action( 'admin_menu', 'nkms_admin_menu' );
function nkms_admin_contents() { ?>
  <h1>Soar Members</h1>
  <form method="post">
    <p><label for="nkms_select2_users">Search for Users:</label><br>
    <select id="nkms_select2_users" name="nkms_select2_users[]" style="width: 100%;">
      <option>Select a user...</option>
    </select></p>
  </form>
  <div id="nkms_user_results"></div>
  <div id="all_custom_users" class="nkms-admin">
    <table cellspacing="0" cellpadding="0">
      <tr>
        <th>Soar ID</th>
        <th>Full Name</th>
        <th>Role</th>
      </tr>
    <?php
    $dancers_list = array();
    $users_list = get_users('orderby=ID');
    foreach ( $users_list as $user ) {
      if ( nkms_has_role( $user, 'dancer') || nkms_has_role( $user, 'dance-school') || nkms_has_role( $user, 'guardian') ) {
        echo '<tr><td>' . $user->ID . '</td><td>' . $user->first_name . ' ' . $user->last_name
        . '</td><td class="role">' . ( $user->roles[0] === "dance-school" ? 'Dance School' : $user->roles[0] )  . '</td></tr>';
      }
    }
    ?>
    </table>
  </div>
  <?php
}

/*
 * GROUPS
**/
add_action( 'admin_menu', 'nkms_admin_menu' );
function nkms_admin_groups() { ?>
  <h1>Soar Groups</h1>
  <form method="post">
    <p><label for="nkms_select2_users">Search for Groups:</label><br>
    <select id="nkms_select2_groups" name="nkms_select2_groups[]" style="width: 100%;">
      <option>Select a group...</option>
    </select>
  </form>
  <div id="nkms_groups_results"></div>
  <div id="all_groups" class="nkms-admin">
    <table cellspacing="0" cellpadding="0">
      <tr>
        <th width="7%">Group ID</th>
        <th width="45%">Group Name</th>
        <th width="15%">Type</th>
        <th width="20%">Level Category</th>
        <th width="13%">Age Category</th>
      </tr>
    <?php
    $dance_school_list = array();
    $users_list = get_users('orderby=ID');
    foreach ( $users_list as $user ) {
      if ( nkms_has_role( $user, 'dance-school') ) {
        $dance_school_groups_list = $user->nkms_dance_school_fields['dance_school_groups_list'];
        foreach ($dance_school_groups_list as $group_id => $group ) {
          echo '<tr><td>' . $user->ID . '-' . $group_id . '</td><td>' . $group->getGroupName()
          . '</td><td class="role">' . $group->getType()  . '</td><td>' . $group->getLevelCategory() . '</td><td>' . $group->getAgeCategory() . '</td></tr>';
        }
      }
    }
    ?>
    </table>
  </div>
  <?php
}

// Select2 user results
add_action( 'wp_ajax_nkms_get_custom_users', 'nkms_get_custom_users' );
function nkms_get_custom_users(){
	// we will pass post IDs and titles to this array
	$return = array();
  $users_list = get_users('orderby=ID');

  foreach ( $users_list as $user ) {
    if ( nkms_has_role( $user, 'dancer') || nkms_has_role( $user, 'dance-school') || nkms_has_role( $user, 'guardian') ) {
      $full_name = $user->ID . ': ' . $user->first_name . ' ' . $user->last_name;
      if ( nkms_has_role( $user, 'dance-school') ) {
        $full_name .= ' (' . $user->nkms_dance_school_fields['dance_school_name'] . ')';
      }
      $query = $_GET['users_query'];
      if ( stripos( $full_name, $query ) !== false ) {
        // ( mb_strlen( $full_name ) > 50 ) ? mb_substr( $full_name, 0, 49 ) . '...' : $full_name;
        // array( User ID, User First & Last Name )
        $return[] = array( $user->ID, $full_name );
      }
    }
  }
	echo json_encode( $return );
	die;
}

// Select2 group results
add_action( 'wp_ajax_nkms_get_dance_groups', 'nkms_get_dance_groups' );
function nkms_get_dance_groups(){
	// we will pass post IDs and titles to this array
	$return = array();

  $users_list = get_users('orderby=ID');
  foreach ( $users_list as $user ) {
    if ( nkms_has_role( $user, 'dance-school') ) {
      $groups = $user->nkms_dance_school_fields['dance_school_groups_list'];
      $query = $_GET['groups_query'];
      if ( $groups ) {
        foreach ( $groups as $group_id => $group ) {
          $group_id_with_name = $user->ID . '-' . $group_id . ': ' . $group->getGroupName();
          if ( stripos( $group_id_with_name, $query ) !== false ) {
            // ( mb_strlen( $group->getGroupName() ) > 50 ) ? mb_substr( $group->getGroupName(), 0, 49 ) . '...' : $group->getGroupName();
            // array( User-Group ID, Group Name )
            $return[] = array( $user->ID . '-' . $group_id, $group_id_with_name );
          }
        }
      }
    }
  }
	echo json_encode( $return );
	die;
}

/*
 * ACTIONS
**/
add_action( 'admin_menu', 'nkms_admin_menu' );
function nkms_admin_actions() { ?>
  <h1>Soar Actions</h1>
  <!-- <form method="post"><input type="submit" action="nkms_fix_user_meta()" value="Fix user meta" /></form>  -->

  <h2>Dance School</h2>
  <!-- Add Dancer to Dance School -->
  <div id="add_dancer_to_dance_school_toggle" class="nkms-toggle">Add dancer to Dance School</div>
  <div id="add_dancer_to_dance_school" class="nkms-toggle-content">
    <form method="post">
      <p><label for="add_dancer_to_dance_school_ds">Select dance school</label></p>
      <p><select id="add_dancer_to_dance_school_ds" name="add_dancer_to_dance_school_ds">
        <option value="" selected disabled hidden>Select a dance school</option>
        <?php
        // function to get Dance Schools only
        $dance_school_list = nkms_get_all_dance_schools();
        foreach ( $dance_school_list as $dance_school ) {
          echo '<option value="' . $dance_school->ID . '">' . $dance_school->ID . ': ' . $dance_school->nkms_dance_school_fields['dance_school_name'] . '</option>';
        }
        ?>
      </select></p>
      <p><label for="add_dancer_to_dance_school_dancer">Select dancer</label></p>
      <p><select id="add_dancer_to_dance_school_dancer" name="add_dancer_to_dance_school_dancer">
        <option value="" selected disabled hidden>Select a dancer</option>
        <?php
        // function to get Dance Schools only
        $dancers_list = nkms_get_all_dancers();
        foreach ( $dancers_list as $dancer ) {
          echo '<option value="' . $dancer->ID . '">' . $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
        }
        ?>
      </select></p>
      <div class="admin-ajax-response"></div>
      <p><input type="submit" value="Submit"/></p>
    </form>
  </div>
  <!-- Remove Dancer from Dance School -->
  <div id="remove_dancer_from_dance_school_toggle" class="nkms-toggle">Remove dancer from Dance School</div>
  <div id="remove_dancer_from_dance_school" class="nkms-toggle-content">
    <form method="post">
      <p><label for="remove_dancer_from_dance_school_ds">Select dance school</label></p>
      <p><select id="remove_dancer_from_dance_school_ds" name="remove_dancer_from_dance_school_ds">
        <option value="" selected disabled hidden>Select a dance school</option>
        <?php
        // function to get Dance Schools only
        $dance_school_list = nkms_get_all_dance_schools();
        foreach ( $dance_school_list as $dance_school ) {
          echo '<option value="' . $dance_school->ID . '">' . $dance_school->ID . ': ' . $dance_school->nkms_dance_school_fields['dance_school_name'] . '</option>';
        }
        ?>
      </select></p>
      <p><label for="remove_dancer_from_dance_school_dancer">Select dancer</label></p>
      <p><select id="remove_dancer_from_dance_school_dancer" name="remove_dancer_from_dance_school_dancer" disabled>
        <option value="" selected disabled hidden>Select a dance school first</option>
      </select></p>
      <p><input type="submit" value="Submit"/></p>
      <div class="admin-ajax-response"></div>
    </form>
  </div>

  <h2>Dance Groups</h2>
  <!-- Add Dancer to Dance Group -->
  <div id="add_dancer_to_dance_group_toggle" class="nkms-toggle">Add dancer to Dance Group</div>
  <div id="add_dancer_to_dance_group" class="nkms-toggle-content">
    <form method="post">
      <p><label for="add_dancer_to_dance_group_name">Select dance group</label></p>
      <p><select id="add_dancer_to_dance_group_name" name="add_dancer_to_dance_group_name">
        <option value="" selected disabled hidden>Select a dance group</option>
        <?php
        // function to get Dance groups only
        $dance_groups_list = nkms_get_all_groups();
        foreach ( $dance_groups_list as $group_id => $group ) {
          echo '<option value="' . $group_id . '">' . $group_id . ': ' . $group->getGroupName() . '</option>';
        }
        ?>
      </select></p>
      <p><label for="add_dancer_to_dance_group_dancer">Select dancer</label></p>
      <p><select id="add_dancer_to_dance_group_dancer" name="add_dancer_to_dance_group_dancer" disabled>
        <option value="" selected disabled hidden>Select a dance group first</option>
      </select></p>
      <p><input type="submit" value="Submit"/></p>
      <div class="admin-ajax-response"></div>
    </form>
  </div>
  <!-- Remove Dancer from Dance Group -->
  <div id="remove_dancer_from_dance_group_toggle" class="nkms-toggle">Remove dancer from Dance Group</div>
  <div id="remove_dancer_from_dance_group" class="nkms-toggle-content">
    <form method="post">
      <p><label for="remove_dancer_from_dance_group_name">Select dance group</label></p>
      <p><select id="remove_dancer_from_dance_group_name" name="remove_dancer_from_dance_group_name">
        <option value="" selected disabled hidden>Select a dance group</option>
        <?php
        // function to get Dance groups only
        $dance_groups_list = nkms_get_all_groups();
        foreach ( $dance_groups_list as $group_id => $group ) {
          echo '<option value="' . $group_id . '">' . $group_id . ': ' . $group->getGroupName() . '</option>';
        }
        ?>
      </select></p>
      <p><label for="remove_dancer_from_dance_group_dancer">Select dancer</label></p>
      <p><select id="remove_dancer_from_dance_group_dancer" name="remove_dancer_from_dance_group_dancer">
        <option value="" selected disabled hidden>Select a dance group first</option>
      </select></p>
      <p><input type="submit" value="Submit"/></p>
      <div class="admin-ajax-response"></div>
    </form>
  </div>

  <h2>Guardian</h2>
  <!-- Add Dancer to Guardian -->
  <div id="add_dancer_to_guardian_toggle" class="nkms-toggle">Add dancer to Guardian</div>
  <div id="add_dancer_to_guardian" class="nkms-toggle-content">
    <form method="post">
      <p><label for="add_dancer_to_guardian_guardian">Select guardian</label></p>
      <p><select id="add_dancer_to_guardian_guardian" name="add_dancer_to_guardian_guardian">
        <option value="" selected disabled hidden>Select a guardian</option>
        <?php
        $guardians_list = nkms_get_all_guardians();
        foreach ( $guardians_list as $guardian ) {
          echo '<option value="' . $guardian->ID . '">' . $guardian->ID . ': ' . $guardian->first_name . ' ' . $guardian->last_name . '</option>';
        }
        ?>
      </select></p>
      <p><label for="add_dancer_to_guardian_dancer">Select dancer</label></p>
      <p><select id="add_dancer_to_guardian_dancer" name="add_dancer_to_guardian_dancer">
        <option value="" selected disabled hidden>Select a dancer</option>
        <?php
        $dancers_list = nkms_get_all_dancers();
        foreach ( $dancers_list as $dancer ) {
          echo '<option value="' . $dancer->ID . '">' . $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
        }
        ?>
      </select></p>
      <p><input type="submit" value="Submit"/></p>
      <div class="admin-ajax-response"></div>
    </form>
  </div>
  <!-- Remove Dancer from Guardian -->
  <div id="remove_dancer_from_guardian_toggle" class="nkms-toggle">Remove dancer from Guardian</div>
  <div id="remove_dancer_from_guardian" class="nkms-toggle-content">
    <form method="post">
      <p><label for="remove_dancer_from_guardian_guardian">Select guardian</label></p>
      <p><select id="remove_dancer_from_guardian_guardian" name="remove_dancer_from_guardian_guardian">
        <option value="" selected disabled hidden>Select a guardian</option>
        <?php
        $guardians_list = nkms_get_all_guardians();
        foreach ( $guardians_list as $guardian ) {
          echo '<option value="' . $guardian->ID . '">' . $guardian->ID . ': ' . $guardian->first_name . ' ' . $guardian->last_name . '</option>';
        }
        ?>
      </select></p>
      <p><label for="remove_dancer_from_guardian_dancer">Select dancer</label></p>
      <p><select id="remove_dancer_from_guardian_dancer" name="remove_dancer_from_guardian_dancer">
        <option value="" selected disabled hidden>Select a guardian first</option>
      </select></p>
      <p><input type="submit" value="Submit"/></p>
      <div class="admin-ajax-response"></div>
    </form>
  </div>
<?php
}

/*
 * REGISTRATIONS
**/
add_action( 'admin_menu', 'nkms_admin_menu' );
function nkms_admin_registrations() { ?>
  <h1>Soar Registrations</h1>
  <!-- <div id="nkms_groups_results"></div> -->
  <div id="all_registrations" class="nkms-admin">
    <?php
    // Get products
    $args = array(
      'category' => array( 'dancer-registration' ),
      // 'orderby'  => 'name',
    );
    $products = wc_get_products( $args );

    // Get orders of each product
    foreach ( $products as $product ) {
      $reg_orders = array();
      $product_ids = array();
      echo '<div id="nkms_product_' . $product->get_id() . '_toggle" class="nkms-toggle">' . $product->get_name() . '</div>';
      echo '<div id="nkms_product_' . $product->get_id() . '" class="nkms-toggle-content">';
      echo '<h3>Event link: <a href="' . get_permalink( $product->get_id() ) . '">' . $product->get_name() . '</a></h5>';
      $registered_dancers_num = 0;

      $product_id = $product->get_id();
      $reg_orders_single = get_orders_ids_by_product_id( $product_id );
      array_push( $reg_orders, $reg_orders_single );
      $reg_orders = array_filter( $reg_orders );

      if( $reg_orders ) {
        foreach ( $reg_orders as $order_id_array ) {
          foreach ( $order_id_array as $order_id ) {
            if ( $order_id ) {
              $order = wc_get_order( $order_id );
              // count registered dancers
              $order_items = $order->get_items();
              foreach ( $order_items as $item ) {
                $formatted_meta_data = $item->get_formatted_meta_data( '_', true );
                foreach ( $formatted_meta_data as $meta_values ) {
                  if ( $meta_values->key === 'Number of Dancers' )
                    $registered_dancers_num += intval( $meta_values->value );
                }
              }
            }
          }
        }
      }
      echo '<h3>Dancers registered: ' . $registered_dancers_num . '</h5>';
      // echo '<br>';

      // Display meta data of dancers for each order
      if( $reg_orders ) {
        echo '<table><thead><tr><th>Member</th><th>Dancers</th><th>Groups</th></tr></thead><tbody>';
        foreach ( $reg_orders as $order_id_array ) {
          foreach ( $order_id_array as $order_id ) {
            if ( $order_id ) {
              echo '<tr>';
              $order = wc_get_order( $order_id );
              $member = get_userdata( $order->get_customer_id() );
              echo '<td>';
              if ( in_array( 'dance-school', (array) $member->roles ) )
                echo '<h4>' . $member->ID . ': ' . $member->nkms_dance_school_fields['dance_school_name'] . '</h4>';
              else
                echo '<h4>' . $member->ID . ': ' . $member->first_name . ' ' . $member->last_name . '</h4>';
              echo '</td>';
              $order_items = $order->get_items();
              foreach ( $order_items as $item ) {
                $formatted_meta_data = $item->get_formatted_meta_data( '_', true );
                foreach ( $formatted_meta_data as $meta_values ) {
                  if ( $meta_values->key === 'Dancers' || $meta_values->key === 'Groups' )
                    echo '<td>' . $meta_values->value . '</td>';
                }
              }
              echo '</tr>';
            }
          }
        }
        echo '</tbody></table>';
      }
      else
        echo '<tr><td><h4>No orders for this event.</h4></td></tr>';
    echo '</div>';
    }
    ?>
  </div>
  <?php
}

/**
 * Get All orders IDs for a given product ID.
 *
 * @param  integer  $product_id (required)
 * @param  array    $order_status (optional) Default is 'wc-completed'
 *
 * @return array
 */
function get_orders_ids_by_product_id( $product_id, $order_status = array( 'wc-on-hold', 'wc-processing', 'wc-pending', 'wc-completed' ) ){
    global $wpdb;

    $results = $wpdb->get_col("
        SELECT order_items.order_id
        FROM {$wpdb->prefix}woocommerce_order_items as order_items
        LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
        LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
        WHERE posts.post_type = 'shop_order'
        AND posts.post_status IN ( '" . implode( "','", $order_status ) . "' )
        AND order_items.order_item_type = 'line_item'
        AND order_item_meta.meta_key = '_product_id'
        AND order_item_meta.meta_value = '$product_id'
    ");

    return $results;
}

/*
 * DISPLAY ALL CUSTOM USER METADATA
**/
add_action( 'wp_ajax_nkms_user_results', 'nkms_user_results' );
function nkms_user_results() {
  $user_id = intval( $_POST['nkms_select_users_user_id'] );

  if ( $user_id ) {
    $user = get_userdata( $user_id );
    // $fix_data = nkms_fix_user_meta();
    $userdata = nkms_get_all_user_meta( $user );
    wp_send_json_success( '<p class="text-danger">' . $userdata . '</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

/*
 * DISPLAY ALL GROUP METADATA
**/
add_action( 'wp_ajax_nkms_groups_results', 'nkms_groups_results' );
function nkms_groups_results() {
  $user_id = intval( $_POST['nkms_select_groups_user_id'] );
  $group_id = intval( $_POST['nkms_select_groups_group_id'] );

  if ( $user_id ) {
    $groupdata = nkms_get_all_group_data( $user_id, $group_id );
    wp_send_json_success( '<p class="text-danger">' . $groupdata . '</p>' );
  }
  else {
    wp_send_json_success( '<p class="text-danger">An error occured. Please try again.</p>' );
  }
}

/* Fix user meta
function nkms_fix_user_meta() {
  // if ( isset( $_POST['admin_update_user_fields'] ) ) {
    $users_list = get_users();
    foreach ( $users_list as $user ) {
      if ( $user->ID > 0 ) {
        // Basic fields
        $user_fields = $user->nkms_fields;
        if ( ! is_array( $user_fields ) )
          $user_fields = array();
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
          if ( ! is_array( $dancer_fields ) )
            $dancer_fields = array();
          // if empty, initialize
          if ( empty ( $dancer_fields['dancer_ds_name'] ) ) { $dancer_fields['dancer_ds_name'] = ''; }
          if ( empty ( $dancer_fields['dancer_ds_teacher_name'] ) ) { $dancer_fields['dancer_ds_teacher_name'] = ''; }
          if ( empty ( $dancer_fields['dancer_ds_teacher_email'] ) ) { $dancer_fields['dancer_ds_teacher_email'] = ''; }
          if ( empty ( $dancer_fields['dancer_level'] ) ) { $dancer_fields['dancer_level'] = 'Novice'; }
          if ( empty ( $dancer_fields['dancer_age_category'] ) ) { $dancer_fields['dancer_age_category'] = '17&o'; }
          if ( empty ( $dancer_fields['dancer_status'] ) ) { $dancer_fields['dancer_status'] = 'Inactive'; }
          if ( ! is_array ( $dancer_fields['dancer_invites'] ) ) { $dancer_fields['dancer_invites'] = array( 'dance_school' => array(), 'guardian' => array() ); }
          if ( ! isset( $dancer_fields['dancer_invites']['dance_school'] ) ) { $dancer_fields['dancer_invites']['dance_school'] = array(); }
          if ( ! isset( $dancer_fields['dancer_invites']['guardian'] ) ) { $dancer_fields['dancer_invites']['guardian'] = array(); }
          if ( ! is_array ( $dancer_fields['dancer_guardian_list'] ) ) { $dancer_fields['dancer_guardian_list'] = array(); }
          if ( empty ( $dancer_fields['dancer_guardian_name'] ) ) { $dancer_fields['dancer_guardian_name'] = ''; }
          if ( empty ( $dancer_fields['dancer_guardian_email'] ) ) { $dancer_fields['dancer_guardian_email'] = ''; }
          if ( empty ( $dancer_fields['dancer_guardian_phone_number'] ) ) { $dancer_fields['dancer_guardian_phone_number'] = ''; }
          if ( ! is_array ( $dancer_fields['dancer_teacher_of'] ) ) { $dancer_fields['dancer_teacher_of'] = array(); }
          if ( ! is_array ( $dancer_fields['dancer_part_of'] ) ) { $dancer_fields['dancer_part_of'] = array(); }
          if ( ! is_array ( $dancer_fields['dancer_registered_to'] ) ) { $dancer_fields['dancer_registered_to'] = array(); }
          // save fields
          update_user_meta( $user->ID, 'nkms_dancer_fields', $dancer_fields );
        }
        // Dance school
        if ( $user->roles[0] === 'dance-school' ) {
          // Dance school fields
          $dance_school_fields = $user->nkms_dance_school_fields;
          if ( ! is_array( $dance_school_fields ) )
            $dance_school_fields = array();
          // if empty, initialize
          if ( empty ( $dance_school_fields['dance_school_name'] ) ) { $dance_school_fields['dance_school_name'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_address'] ) ) { $dance_school_fields['dance_school_address'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_phone_number'] ) ) { $dance_school_fields['dance_school_phone_number'] = ''; }
          if ( empty ( $dance_school_fields['dance_school_description'] ) ) { $dance_school_fields['dance_school_description'] = ''; }
          if ( ! is_array ( $dance_school_fields['dance_school_dancers_list'] ) ) { $dance_school_fields['dance_school_dancers_list'] = array(); }
          if ( ! is_array ( $dance_school_fields['dance_school_groups_list'] ) ) { $dance_school_fields['dance_school_groups_list'] = array(); }
          if ( ! is_array ( $dance_school_fields['dance_school_teachers_list'] ) ) { $dance_school_fields['dance_school_teachers_list'] = array(); }
          if ( ! is_array ( $dance_school_fields['dance_school_invites'] ) ) { $dance_school_fields['dance_school_invites'] = array(); }
          if ( ! is_array ( $dance_school_fields['dance_school_currently_viewing'] ) ) { $dance_school_fields['dance_school_currently_viewing'] = array( 'dancer' => 0, 'group' => 0 ); }
          // save fields
          update_user_meta( $user->ID, 'nkms_dance_school_fields', $dance_school_fields );
        }
        // Guardian
        if ( $user->roles[0] === 'guardian' ) {
          // Guardian fields
          $guardian_fields = $user->nkms_guardian_fields;
          if ( ! is_array( $guardian_fields ) )
            $guardian_fields = array();
          // if empty, initialize
          if ( ! is_array ( $guardian_fields['guardian_dancers_list'] ) ) { $guardian_fields['guardian_dancers_list'] = array(); }
          if ( ! is_array ( $guardian_fields['guardian_invites'] ) ) { $guardian_fields['guardian_invites'] = array(); }
          // save fields
          update_user_meta( $user->ID, 'nkms_guardian_fields', $guardian_fields );
        }
      }
    }
    return 'Process completed.<br>';
  // }
}
/**/

// Display all user meta
function nkms_get_all_user_meta( $user ) {
  $return = '';
  if ( $user->ID > 1 ) {
    $return .= get_wp_user_avatar( $user->ID, '256', '' );
    $return .= '<h1><strong>' . $user->ID . ': ' . $user->first_name . ' ' . $user->last_name . '</strong></h1>';
    // $return .= '<h1><strong>' . $user->ID . ': ' . $user->roles[0] . '</strong></h1>';
    // Full Name
    $fullname = $user->first_name . ' ' . $user->last_name;
    // Basic fields
    $user_fields = $user->nkms_fields;
    $return .= '<h3>Basic fields</h3>';
    // Display Basic fields
    $return .= '<span class="basic-fields">Full Name</span>' . $fullname . '<br>';
    $return .= '<span class="basic-fields">Date of Birth</span>' . $user_fields['dob'] . '<br>';
    $return .= '<span class="basic-fields">Country</span>' . $user_fields['country'] . '<br>';
    $return .= '<span class="basic-fields">City</span>' . $user_fields['city'] . '<br>';
    $return .= '<span class="basic-fields">Address</span>' . $user_fields['address'] . '<br>';
    $return .= '<span class="basic-fields">Postcode</span>' . $user_fields['postcode'] . '<br>';
    $return .= '<span class="basic-fields">Phone number</span>' . $user_fields['phone_number'] . '<br>';

    // Dancer
    if ( nkms_has_role( $user, 'dancer' ) ) {
      // Dancer fields
      $dancer_fields = $user->nkms_dancer_fields;
      $return .= '<h3>Dancer fields</h3>';
      // Display Dancer fields
      $return .= '<span class="dancer-fields">Dancer status</span>' . $dancer_fields['dancer_status'] . '<br>';
      $return .= '<span class="dancer-fields">Level category</span>' . $dancer_fields['dancer_level'] . '<br>';
      $return .= '<span class="dancer-fields">Age category</span>' . $dancer_fields['dancer_age_category'] . '<br>';
      $return .= '<span class="dancer-fields">Dance School name</span>' . $dancer_fields['dancer_ds_name'] . '<br>';
      $return .= '<span class="dancer-fields">Dance School teacher name</span>' . $dancer_fields['dancer_ds_teacher_name'] . '<br>';
      $return .= '<span class="dancer-fields">Dance School teacher email</span>' . $dancer_fields['dancer_ds_teacher_email'] . '<br>';
      // Guardian
      if ( is_array( $dancer_fields['dancer_guardian_list'] ) && ! empty( $dancer_fields['dancer_guardian_list'] ) ) {
        $guardians = array();
        foreach ( $dancer_fields['dancer_guardian_list'] as $guardian_id) {
          $guardian = get_userdata( $guardian_id );
          $guardian_name = $guardian->first_name . ' ' . $guardian->last_name;
          array_push( $guardians, $guardian_name );
        }
        if ( ! empty( $guardians ) ) {
          $return .= '<span class="dancer-fields">Guardian</span>' . implode( ', ', $guardians ) . '<br>';
        }
      }
      // Guardian Name
      if ( ! empty( $dancer_fields['dancer_guardian_name'] ) ) {
        $return .= '<span class="dancer-fields">Guardian Name</span>' . $dancer_fields['dancer_guardian_name'] . '<br>';
      }
      // Guardian Email
      if ( ! empty( $dancer_fields['dancer_guardian_email'] ) ) {
        $return .= '<span class="dancer-fields">Guardian Email</span>' . $dancer_fields['dancer_guardian_email'] . '<br>';
      }
      // Guardian Phone Number
      if ( ! empty( $dancer_fields['dancer_guardian_phone_number'] ) ) {
        $return .= '<span class="dancer-fields">Guardian Phone number</span>' . $dancer_fields['dancer_guardian_phone_number'] . '<br>';
      }
      // Teacher of Dance School
      if ( is_array( $dancer_fields['dancer_teacher_of'] ) && ! empty( $dancer_fields['dancer_teacher_of'] ) ) {
        $teacher_part_of = array();
        foreach ( $dancer_fields['dancer_teacher_of'] as $ds_id ) {
          $part_of_ds_user = get_userdata( $ds_id );
          $part_of_ds_name = $part_of_ds_user->nkms_dance_school_fields['dance_school_name'];
          array_push( $teacher_part_of, $part_of_ds_name );
        }
        if ( ! empty( $teacher_part_of ) ) {
          $return .= '<span class="dancer-fields">Teacher of</span>' . implode( ', ', $teacher_part_of ) . '<br>';
        }
      }
      // Dancer part of Dance School
      if ( is_array( $dancer_fields['dancer_part_of'] ) && ! empty( $dancer_fields['dancer_part_of'] ) ) {
        $dancer_part_of = array();
        foreach ( $dancer_fields['dancer_part_of'] as $ds_id ) {
          $part_of_ds_user = get_userdata( $ds_id );
          $part_of_ds_name = $part_of_ds_user->nkms_dance_school_fields['dance_school_name'];
          array_push( $dancer_part_of, $part_of_ds_name );
        }
        if ( ! empty( $dancer_part_of ) ) {
          $return .= '<span class="dancer-fields">Member of</span>' . implode( ', ', $dancer_part_of ) . '<br>';
        }
      }
      // Dancer registered to Events
      if ( is_array( $dancer_fields['dancer_registered_to'] ) && ! empty( $dancer_fields['dancer_registered_to'] ) ) {
        $registered_to = array();
        foreach ( $dancer_fields['dancer_registered_to'] as $product_id ) {
          $product = wc_get_product( $product_id );
          $product_name = '<a href="' . get_permalink( $product->get_id() ) . '" target="_blank">' . $product->get_name() . '</a>';
          array_push( $registered_to, $product_name );
        }
        if ( ! empty( $registered_to ) ) {
          $return .= '<span class="dancer-fields">Registered to</span>' . implode( ', ', $registered_to ) . '<br>';
        }
      }

      // Dancer invites
      // $return .= '<h4>Dancer invites</h4>';
      // $return .= 'Guardian: ' . implode( ', ', $dancer_fields['dancer_invites']['guardian'] ) . '<br>';
      // $return .= 'Dance School: ' . implode( ', ', $dancer_fields['dancer_invites']['dance_school'] ) . '<br>';
    }
    // Dance school
    if ( nkms_has_role( $user, 'dance-school') ) {
      // Dance school fields
      $dance_school_fields = $user->nkms_dance_school_fields;
      $return .= '<h3>Dance School fields</h3>';
      // Display Dance school fields
      $return .= '<span class="dance-school-fields">Name</span>' . $dance_school_fields['dance_school_name'] . '<br>';
      $return .= '<span class="dance-school-fields">Address</span>' . $dance_school_fields['dance_school_address'] . '<br>';
      $return .= '<span class="dance-school-fields">Phone number</span>' . $dance_school_fields['dance_school_phone_number'] . '<br>';
      $return .= '<span class="dance-school-fields">Description</span>' . $dance_school_fields['dance_school_description'] . '<br>';
      $return .= '<h4>Dancers list</h4>';
      // . implode( ', ', $dance_school_fields['dance_school_dancers_list'] ) . '<br>';
      $dancers_list = $dance_school_fields['dance_school_dancers_list'];
      // asort( $dancers_list );
      foreach ( $dancers_list as $dancer_id ) {
        $dancer = get_userdata ( $dancer_id );
        $return .= $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '<br>';
      }
      $return .= '<h4>Groups list</h4>';
      $groups_list = $dance_school_fields['dance_school_groups_list'];
      foreach ( $groups_list as $key => $group ) {
        $return .= '<p><strong>' . $group->getGroupName() . '</strong><br>';
        $group_dancers = $group->getDancers();
        // asort( $group_dancers );
        foreach ( $group_dancers as $dancer_id ) {
          $dancer = get_userdata( $dancer_id );
          $return .= '<span style="padding-left: 0px;">' . $dancer_id . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '<span><br>';
        }
        $return .= '</p>';
      }
      $return .= '<br>';
    }
    // Guardian
    if ( nkms_has_role( $user, 'guardian') ) {
      // Guardian fields
      $guardian_fields = $user->nkms_guardian_fields;
      $return .= '<h3>Guardian fields</h3>';
      // Dancer part of Dance School
      if ( is_array( $guardian_fields['guardian_dancers_list'] ) && ! empty( $guardian_fields['guardian_dancers_list'] ) ) {
        $guardians_dancers = array();
        foreach ( $guardian_fields['guardian_dancers_list'] as $dancer_id) {
          $dancer = get_userdata( $dancer_id );
          $dancer_name = $dancer->first_name . ' ' . $dancer->last_name;
          array_push( $guardians_dancers, $dancer_name );
        }
        if ( ! empty( $guardians_dancers ) ) {
          $return .= '<span class="dancer-fields">Dancers list</span>' . implode( ', ', $guardians_dancers ) . '<br>';
        }
      }
      else {
        $return .= 'Guardian is not managing any dancers.';
      }
    }
    return $return;
  }
}

// Display all group data
function nkms_get_all_group_data( $user_id, $group_id ) {
  $return = '';
  $user = get_userdata( $user_id );

  // Dance school
  if ( nkms_has_role( $user, 'dance-school') ) {
    // Dance school fields
    $dance_school_fields = $user->nkms_dance_school_fields;
    $group = $dance_school_fields['dance_school_groups_list'][$group_id];
    $return .= '<h3>' . $user_id . '-' . $group_id . ': ' . $group->getGroupName() . '</h3>';
    // Display Dance school fields
    $return .= '<span class="group-fields">Type</span>' . $group->getType() . '<br>';
    $return .= '<span class="group-fields">Level category</span>' . $group->getLevelCategory() . '<br>';
    $return .= '<span class="group-fields">Age category</span>' . $group->getAgeCategory() . '<br>';
    $return .= '<span class="group-fields">Member of</span>' . $dance_school_fields['dance_school_name'] . '<br>';
    $return .= '<h4>Dancers list</h4>';
    // . implode( ', ', $dance_school_fields['dance_school_dancers_list'] ) . '<br>';
    $dancers_list = $group->getDancers();
    foreach ( $dancers_list as $dancer_id ) {
      $dancer = get_userdata ( $dancer_id );
      $return .= $dancer->ID . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '<br>';
    }
    $return .= '<br>';
  }
  return $return;
}
