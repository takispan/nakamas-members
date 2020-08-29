<?php
/**
 * Add Dancers to Dance School
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Add a dancer for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name'] ?></span></h3></br>
  <form id="add-dancers" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_add_dancers"><?php esc_html_e( 'Add a dancer by ID', 'nkms' ); ?></label>
			<input type="text" name="dance_school_add_dancers_dancer_id" value="" class="regular-text" />
    </p>
    <!-- info messages -->
    <p class="ajax-response"></p>
    <p>
      <input type="hidden" name="dance_school_add_dancers_ds_id" value="<?php echo $dance_school->ID; ?>" />
      <input type="submit" name="dance_school_add_dancers_submit" value="Add" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
