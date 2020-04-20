<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

?>


<div class="nkms-tabs">
    <?php if ( !is_user_logged_in() ) : ?>
        <p class="warning">
            <?php _e('You must be logged in to view this section.', 'profile'); ?>
        </p><!-- .warning -->
    <?php else : ?>
    <h3 style="font-weight:300;">Add / Remove dancers for <span style="font-weight:600;"><?php echo $current_user->user_login ?></span></h3></br>

    <?php endif; ?>
</div><!-- .nkms-tabs -->
