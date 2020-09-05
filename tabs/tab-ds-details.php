<?php
/**
 * View & edit dance school details.
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Details for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>

  <form method="post" id="edit-dance-school-details" action="">
    <p>
      <label for="ds_details_dance_school_name">Dance School Name</label>
      <input type="text" name="ds_details_dance_school_name" value="<?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?>">
    </p>
    <p>
      <label for="ds_details_dance_school_address">Dance School Address</label>
      <input type="text" name="ds_details_dance_school_address" value="<?php echo $dance_school->nkms_dance_school_fields['dance_school_address']; ?>">
    </p>
    <p>
      <label for="ds_details_dance_school_phone_number">Dance School Phone Number</label>
      <input type="text" name="ds_details_dance_school_phone_number" value="<?php echo $dance_school->nkms_dance_school_fields['dance_school_phone_number']; ?>">
    </p>
    <p>
      <label for="ds_details_dance_school_description">Dance School Description</label>
      <textarea rows="5" name="ds_details_dance_school_description"><?php echo $dance_school->nkms_dance_school_fields['dance_school_description']; ?></textarea>
    </p>
    <p>
      <?php nkms_update_ds_details(); ?>
    </p>
    <p class="form-submit">
      <input type="hidden" name="update_ds_details_ds_id" value="<?php echo $dance_school->ID; ?>" />
      <input name="update_ds_details" type="submit" id="update_ds_details" class="submit button" value="Update" />
    </p><!-- .form-submit -->
  </form>
</div><!-- .nkms-tabs -->
