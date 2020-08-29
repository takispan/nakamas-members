<?php
/**
 * View & edit dance school details.
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Details for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>

  <form method="post" id="edit-dance-school-details" action="">
    <div class="nkms-extra-fields">
      <?php
      //display custom fields
      do_action( 'edit_user_profile', $current_user ); ?>

    <p class="form-submit">
      <input name="update_ds_info" type="submit" id="update_ds_info" class="submit button" value="Update" />
      <?php wp_nonce_field( 'update-user_'. $current_user->ID ) ?>
    </p><!-- .form-submit -->
    </div>
  </form>
</div><!-- .nkms-tabs -->
