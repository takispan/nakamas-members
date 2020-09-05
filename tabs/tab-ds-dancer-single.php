<?php
/**
 * Displays a single dancer from the Dance School's dance list.
 * Able to change status of dancers and remove from Dance School.
 */
?>
<?php $dancer = get_userdata($dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['dancer']); ?>
<div class="ds-single-dancer">
  <h3 style="font-weight:300;">Dancer <span style="font-weight:600;"><?php echo $dancer->first_name . ' ' . $dancer->last_name; ?></span> for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <div class="dancer-details">
    <p class="nkms-pfp"><?php echo get_wp_user_avatar( $dancer->ID, '256', '' ); ?></p>
    <p><span>Soar ID</span><?php echo $dancer->ID; ?></p>
    <p><span>Full Name</span><?php echo $dancer->first_name . " " . $dancer->last_name; ?></p>
    <p><span>Status</span><?php echo $dancer->nkms_dancer_fields['dancer_status']; ?></p>
    <p><span>Age category</span><?php echo $dancer->nkms_dancer_fields['dancer_age_category']; ?></p>
    <p><span>Level category</span><?php echo $dancer->nkms_dancer_fields['dancer_level']; ?></p>
  </div>
  <p class="ajax-response"></p>
  <button id="change-dancer-status" data-dancer-id="<?php echo $dancer->ID; ?>">Change Status</button>
  <button id="remove-dancer-from-dancers-list" data-ds-id="<?php echo $dance_school_id; ?>" data-dancer-id="<?php echo $dancer->ID; ?>">Remove Dancer</button>
</div><!-- .nkms-tabs -->
