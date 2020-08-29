<?php
/**
 * Displays a single dancer from the Dance School's dance list.
 * Able to change status of dancers and remove from Dance School.
 */
?>

<div class="nkms-tabs ds-single-dancer">
  <h3 style="font-weight:300;">Dancer <span style="font-weight:600;"><?php echo $dancer->first_name . ' ' . $dancer->last_name; ?></span> for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3></br>
  <div class="dancer-details">
    <p><span>Soar ID</span><?php echo $dancer_id; ?></p>
    <p><span>Full Name</span><?php echo $dancer->first_name . " " . $dancer->last_name; ?></p>
    <p><span>Status</span><?php echo $dancer->nkms_dancer_fields['dancer_status']; ?></p>
    <p><span>Experience</span><?php echo $dancer->nkms_fields['experience']; ?></p>
    <p><span>Category</span><?php echo $dancer->nkms_dancer_fields['dancer_age_category']; ?></p>
  </div>
  <button class="change-dancer-status" data-ds-id="<?php echo $dance_school_id; ?>" data-dancer-id="<?php echo $dancer_id; ?>">Change Status</button>
  <button class="remove-dancer" data-ds-id="<?php echo $dance_school_id; ?>" data-dancer-id="<?php echo $dancer_id; ?>">Remove Dancer</button>
</div><!-- .nkms-tabs -->
