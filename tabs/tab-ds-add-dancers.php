<?php
/**
 * Add / Remove Dancers from Dance School
 *
 * Allow users manage dance school's dancer list.
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Add a dancer for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <form id="add-dancers" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_add_dancers"><?php esc_html_e( 'Add a dancer by ID', 'nkms' ); ?></label>
			<input id="add_dancer_to_ds" type="text" name="dance_school_add_dancers" value="" class="regular-text" />
    </p>
    <!-- info messages -->
    <div class="ajax-response"></div>
    <p>
      <input type="submit" name="dance_school_add_dancers_submit" value="Add" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
