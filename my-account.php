<?php
/**
 * Template Name: My Account
 *
 * Tabs with information & actions regarding user's account.
 */

get_header(); // Loads the header.php template. ?>

<section id="content">
    <div id="nkms-account">
        <?php if ( !is_user_logged_in() ) : ?>
            <p class="warning">
                <?php _e('You must be logged to access your account', 'profile'); ?>
            </p><!-- .warning -->
        <?php else : ?>
            <!-- Tab links -->
            <div class="tab">
                <button class="tablinks" onclick="openTab(event, 'dashboard')">Dashboard</button>
                <button class="tablinks" onclick="openTab(event, 'dance-school')">Dance School</button>
                <button class="tablinks" onclick="openTab(event, 'profile')">Profile</button>
            </div>

            <div id="dashboard" class="tabcontent">
             <h3>Dashboard</h3>
             <p>Dashboard stuff.</p>
            </div>

            <div id="dance-school" class="tabcontent">
             <h3>Dance School</h3>
             <p>Dance School stuff.</p>
            </div>

            <!-- Tab content -->
            <div id="profile" class="tabcontent">
             <h3>Profile</h3>
             <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile.php'); ?>
            </div>
        <?php endif; ?>
    </div>

</section><!-- #content -->

<?php get_footer(); // Loads the footer.php template. ?>
