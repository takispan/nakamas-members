<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

//FIELDS NOT UPDATING ON FRONTEND - NEED TO FIX
 if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
   do_action( 'personal_options_update', $current_user );
}

?>
<div class="nkms-tabs">
    <h3 style="font-weight:300;">Details for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>

    <form method="post" id="edit-dance-school-details" action="<?php the_permalink(); ?>">
        <div class="nkms-extra-fields">
          <?php
          //display custom fields
          do_action( 'edit_user_profile', $current_user ); ?>


        <p class="form-submit">
            <input name="updateuser" type="submit" id="updateuser" class="submit button" value="<?php _e('Update', 'dance-school-update'); ?>" />
            <?php wp_nonce_field( 'update-user_'. $current_user->ID ) ?>

        </p><!-- .form-submit -->
        </div>
    </form>
</div><!-- .nkms-tabs -->
