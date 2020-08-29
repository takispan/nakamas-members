<?php
/**
 * Add Teachers to Dance School
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Add a teacher for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3></br>
  <form id="add-teachers" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_add_teachers"><?php esc_html_e( 'Add a teacher by ID', 'nkms' ); ?></label>
			<input id="add_teacher_to_ds" type="text" name="dance_school_add_teachers" value="" class="regular-text" />
    </p>
    <!-- info messages -->
    <div class="ajax-response"></div>
    <p>
      <input type="hidden" name="dance_school_add_teachers_ds_id" value="<?php echo $dance_school->ID; ?>" />
      <input type="submit" name="dance_school_add_teachers_submit" value="Add" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
