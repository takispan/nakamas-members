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
    <h3 style="font-weight:300;">Dance School Information for <span style="font-weight:600;"><?php echo $current_user->user_login ?></span></h3></br>

    <form method="post" id="adduser" action="<?php the_permalink(); ?>">

    <?php
    //action hook for plugin and extra fields
    do_action('edit_user_profile',$current_user); ?>

        <p class="form-submit">
            <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'profile'); ?>" />
            <?php wp_nonce_field( 'update-user_'. $current_user->ID ) ?>

        </p><!-- .form-submit -->
    </form>
    <?php endif; ?>
</div><!-- .nkms-tabs -->
