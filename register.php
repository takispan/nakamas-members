<?php
/**
 * Template Name: Nakamas Register
 *
 * Allow users to regiser a profile from Frontend.
 */
if ($current_user->ID) {
 // They're already logged in, so we bounce them back to the profile page.
 wp_redirect(home_url().'/profile');
 exit;
}
get_header(); ?>
<div id="nkms-account">
  <h2>Register</h2>
<?php nkms_custom_registration(); ?>
</div>

<?php get_footer(); // Loads the footer.php template. ?>
