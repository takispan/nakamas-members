<?php
/**
 * Template Name: Dance School Overview
 *
 * Display information about Dance School.
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Overview for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3></br>
  <?php
  // IF $dance_school has dance_school_invites from dancers
  $dance_school_fields = $dance_school->nkms_dance_school_fields;
  $dance_school_invites = $dance_school_fields['dance_school_invites'];
  if ( ! empty( $dance_school_invites ) ) {
    echo '<h4>Pending School Memberships</h4>';
    foreach ( $dance_school_invites as $dancer_id ) {
      $dancer = get_user_by( 'id', $dancer_id ); ?>
      <div><p><?php echo $dancer->first_name . " " . $dancer->last_name . ' wants to join ' . $dance_school_fields['dance_school_name']; ?>.</p>
        <form method="post" class="invite-btn">
          <input type="hidden" name="dance_school_invite_dancer_id" value="<?php echo $dancer_id; ?>" />
          <input type="hidden" name="dance_school_invite_ds_id" value="<?php echo $dance_school->ID; ?>" />
          <input type="submit" name="dance_school_invite_accept" value="Accept" />
          <input type="submit" name="dance_school_invite_decline" value="Decline" />
        </form>
      </div>
      <?php
    }
  }
  else {
    echo '<h4>Pending School Memberships</h4>';
    echo '<p>You do not have any invites.</p>';
  }
  ?>
  <h4>Dance School Details</h4>
  <p>
    <span><strong>Name:</strong> <?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span><br/>
    <span><strong>Address:</strong> <?php echo $dance_school->nkms_dance_school_fields['dance_school_address']; ?></span><br/>
    <span><strong>Phone:</strong> <?php echo $dance_school->nkms_dance_school_fields['dance_school_phone_number']; ?></span><br/>
    <span><strong>Description</strong></br/>
      <?php echo $dance_school->nkms_dance_school_fields['dance_school_description']; ?>
    </span>
  </p>
  <br>
  <a data-toggle="tab" href="#ds-details" class="nkms-btn">Edit Details</a>
</div><!-- .nkms-tabs -->
