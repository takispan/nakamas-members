<?php
/**
 * Template Name: My Account
 *
 * Tabs with information & actions regarding user's account.
 */

//Get user info.
global $current_user, $wp_roles;
wp_get_current_user();

get_header(); // Loads the header.php template. ?>

<section id="content">
    <div id="nkms-account">
        <?php if ( !is_user_logged_in() ) : ?>
            <p class="warning">
                <?php _e('You must be logged in to access your account', 'profile'); ?>
            </p><!-- .warning -->
        <?php else : ?>
            <!-- Tab links -->
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a data-toggle="tab" href="#dashboard">Dashboard</a></li>
                <?php if ( nkms_has_role(wp_get_current_user(), 'dance-school') ) : ?>
                  <li><a data-toggle="tab" href="#dance-school">Dance School</a></li>
                <?php endif; ?>
                <li><a data-toggle="tab" href="#profile">Profile</a></li>
            </ul>

            <!-- <div class="tab">
                <button class="tablinks" onclick="openTab(event, 'dashboard')" id="defaultOpen">Dashboard</button>
                <?php //if ( nkms_has_role(wp_get_current_user(), 'dance-school') ) : ?>
                  <button class="tablinks" onclick="openTab(event, 'dance-school')" id="danceSchool">Dance School</button>
                <?php //endif; ?>
                <button class="tablinks" onclick="openTab(event, 'profile')" id="profileTab">Profile</button>
            </div> -->

            <!-- Tab content -->
            <div class="tab-content">
              <div id="dashboard" class="tab-pane fade in active">
                  <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-dashboard.php'); ?>
              </div>
              <?php if ( nkms_has_role(wp_get_current_user(), 'dance-school') ) : ?>
                <div id="dance-school" class="tab-pane fade">
                   <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-danceschool.php'); ?>
                </div>
              <?php endif; ?>

              <div id="profile" class="tab-pane fade">
                  <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile.php'); ?>
              </div>
            </div>
        <?php endif; ?>
    </div><!-- #nkms-account -->
</section><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>
