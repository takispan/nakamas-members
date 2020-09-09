<?php
/**
 * Template Name: Dance School Overview
 *
 * Display information about Dance School.
 */
?>

<div>
  <h3 style="font-weight:300;">Overview for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <?php
  // IF $dance_school has dance_school_invites from dancers
  $dance_school_fields = $dance_school->nkms_dance_school_fields;
  $dance_school_invites = $dance_school_fields['dance_school_invites'];
  if ( ! empty( $dance_school_invites ) ) {
    echo '<h4>Pending School Memberships</h4>';
    foreach ( $dance_school_invites as $dancer_id ) {
      $dancer = get_userdata( $dancer_id ); ?>
      <div class="dancer-invites"><p><?php echo $dancer->first_name . ' ' . $dancer->last_name . ' wants to join ' . $dance_school_fields['dance_school_name']; ?>.</p>
        <form method="post" id="dance_school_pending_memberships" class="invite-btn">
          <input type="hidden" name="dance_school_request_to_join_dancer_id" value="<?php echo $dancer_id; ?>" />
          <input type="hidden" name="dance_school_request_to_join_ds_id" value="<?php echo $dance_school->ID; ?>" />
          <input type="submit" name="dance_school_request_to_join_accept" value="Accept" />
          <input type="submit" name="dance_school_request_to_join_decline" value="Decline" />
          <div class="ajax-response"></div>
        </form>
      </div>
      <?php
    }
  }
  else {
    echo '<h4>Pending School Memberships</h4>';
    echo '<p>You do not have any requests.</p>';
  }
  ?>
  <h4>Dance School Details</h4>
  <p class="nkms-pfp">
    <!-- <label>Dance School logo</label> -->
    <?php echo get_wp_user_avatar( $dance_school_id, '256', '' ); ?>
  </p>
  <p>
    <span><strong>Name:</strong> <?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span><br/>
    <span><strong>Address:</strong> <?php echo $dance_school->nkms_dance_school_fields['dance_school_address']; ?></span><br/>
    <span><strong>Phone:</strong> <?php echo $dance_school->nkms_dance_school_fields['dance_school_phone_number']; ?></span><br/>
    <span><strong>Description</strong></br/>
      <?php echo $dance_school->nkms_dance_school_fields['dance_school_description']; ?>
    </span>
  </p>
  <!-- <a data-toggle="tab" href="#ds-details" class="nkms-btn">Edit Details</a> -->
</div><!-- .nkms-tabs -->
