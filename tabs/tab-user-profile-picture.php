<?php
/**
 * Template Name: User Profile Picture
 *
 * Allow users to update their profile picture from Frontend.
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Update Profile picture for <span style="font-weight:600;"><?php echo $current_user->user_login ?></span></h3>
  <p>
    <?php echo do_shortcode( '[avatar_upload id='. $current_user->ID . ']' ); ?>
  </p>
</div><!-- .nkms-tabs -->
