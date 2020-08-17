<?php
/**
 * Add Dancers to Dance School
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
    <div id="ajax-add-dancers"></div>
    <p>
      <input type="submit" name="dance_school_add_dancers_submit" value="Add" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
