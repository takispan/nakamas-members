<?php
/**
 * Template Name: My Account
 *
 * Tabs with information & actions regarding user's account.
 */

// Set up variables
global $current_user, $wp_roles;
wp_get_current_user();
$dancer_id = 0;
$dancer;
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
$dance_school_id = 0;
$dance_school;
if ( nkms_has_role( $current_user, 'dance-school') ) {
  $dance_school_id = $current_user->ID;
  $dance_school = get_user_by( 'id', $dance_school_id );
  $dancer_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['dancer'];
  $dancer = get_userdata( $dancer_id );
  $group_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['group'];
}
if ( nkms_is_teacher( $current_user->ID ) ) {
  $dance_school_id = nkms_is_teacher( $current_user->ID );
  $dance_school = get_user_by( 'id', $dance_school_id );
}

get_header(); // Loads the header.php template. ?>

<section id="content">
  <div id="nkms-account">
    <?php if ( ! is_user_logged_in() ) : ?>
      <p class="warning">
        <?php _e('You must be logged in to access your account', 'profile'); ?>
      </p><!-- .warning -->
    <?php else : ?>
      <!-- Tab links -->
      <div id="top-tabs-wrapper">
        <ul class="nav nav-tabs" id="top-tabs">
          <li class="active"><a data-toggle="tab" href="#dashboard">Dashboard</a></li>
          <?php
          if ( nkms_has_role( $current_user, 'dance-school') || nkms_is_teacher( $current_user->ID ) ) : ?>
            <li><a data-toggle="tab" href="#dance-school">Dance School</a></li>
          <?php endif; ?>
          <li><a data-toggle="tab" href="#profile">Profile</a></li>
        </ul>
      </div>

      <!-- Tab content -->
      <div class="tab-content">
        <div id="dashboard" class="tab-pane fade in active">
          <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-dashboard.php'); ?>
        </div>
        <?php if ( nkms_has_role( $current_user, 'dance-school') || nkms_is_teacher( $current_user->ID ) ) : ?>
          <div id="dance-school" class="tab-pane fade">
            <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-danceschool.php'); ?>
          </div>
        <?php endif; ?>

        <div id="profile" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile.php'); ?>
        </div>
        <div id="profile-picture" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile-picture.php'); ?>
        </div>
      </div>
    <?php endif; ?>
  </div><!-- #nkms-account -->
</section><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>
