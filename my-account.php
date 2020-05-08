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
            <div class="tab">
                <button class="tablinks" onclick="openTab(event, 'dashboard')" id="defaultOpen">Dashboard</button>
                <button class="tablinks" onclick="openTab(event, 'dance-school')" id="danceSchool">Dance School</button>
                <button class="tablinks" onclick="openTab(event, 'profile')" id="profile">Profile</button>
            </div>

            <!-- Tab content -->
            <div id="dashboard" class="tabcontent">
                <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-dashboard.php'); ?>
            </div>

            <div id="dance-school" class="tabcontent">
               <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-danceschool.php'); ?>
            </div>

            <div id="profile" class="tabcontent">
                <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile.php'); ?>
            </div>
        <?php endif; ?>
    </div><!-- #nkms-account -->
</section><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>
