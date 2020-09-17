<?php
/**
 * Template Name: My Account
 *
 * Tabs with information & actions regarding user's account.
 */

// Set up variables
global $current_user, $wp_roles, $dancer_id, $dancer, $dance_school_id, $dance_school;
wp_get_current_user();
$dancer_id = 0;
$dance_school_id = 0;
if ( nkms_has_role( $current_user, 'dancer' ) ) {
  $dancer_id = get_current_user_id();
  $dancer = get_user_by( 'id', $dancer_id );
}
if ( nkms_has_role( $current_user, 'guardian' ) ) {
  $guardian_dancers_list = $current_user->nkms_guardian_fields['guardian_dancers_list'];
  if ( ! empty( $guardian_dancers_list ) ) { $dancer_id = $guardian_dancers_list[0]; }
  if ( $dancer_id ) {
    $dancer = get_user_by( 'id', $dancer_id );
  }
}

if ( nkms_has_role( $current_user, 'dance-school') ) {
  $dance_school_id = $current_user->ID;
  $dance_school = get_userdata( $dance_school_id );
  $dancer_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['dancer'];
  if ( $dancer_id ) {
    $dancer = get_userdata( $dancer_id );
  }
  $group_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['group'];
}
if ( nkms_is_teacher( $current_user->ID ) ) {
  $dance_school_id = nkms_is_teacher( $current_user->ID );
  $dance_school = get_userdata( $dance_school_id );
}

get_header(); // Loads the header.php template. ?>

<section id="content">
  <div id="nkms-account" class="x-container max width">
    <?php if ( ! is_user_logged_in() ) : ?>
      <p class="warning">
        <?php _e('You must be logged in to access your account', 'profile'); ?>
      </p><!-- .warning -->
    <?php else : ?>
      <!-- Tab links -->


      <!-- Tab content -->
      <div class="tabs">
        <input type="radio" name="tabs" id="dashboard" checked="checked">
        <label for="dashboard">Dashboard</label>
        <div class="tab">
          <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-dashboard.php'); ?>
        </div>
        <?php if ( nkms_has_role( $current_user, 'dance-school') || nkms_is_teacher( $current_user->ID ) ) : ?>
          <input type="radio" name="tabs" id="dance-school">
          <label for="dance-school">Dance School</label>
          <div class="tab">
            <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-danceschool.php'); ?>
          </div>
        <?php endif; ?>

        <input type="radio" name="tabs" id="profile">
        <label for="profile">Profile</label>
        <div class="tab">
          <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile.php'); ?>
        </div>
        <input type="radio" name="tabs" id="pfp">
        <label for="pfp" class="tab-hidden">Profile Picture</label>
        <div class="tab">
          <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile-picture.php'); ?>
        </div>
      </div>
    <?php endif; ?>
  </div><!-- #nkms-account -->
</section><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>
