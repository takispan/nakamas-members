<?php
/**
 * Template Name: Dance School Overview
 *
 * Allow users to update their profiles from Frontend.
 */

?>


<div class="nkms-tabs">
    <h3 style="font-weight:300;">Overview for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <h4>Pending School Memberships</h4>
    <!-- Disabled for testing -->
    <p>Invites will show up here.</p>
    <h4>Dance School Details</h4>
    <p>
      <span><strong>Name:</strong> <?php echo get_the_author_meta( 'dance_school_name', $current_user->ID ); ?></span><br/>
      <span><strong>Address:</strong> <?php echo get_the_author_meta( 'dance_school_address', $current_user->ID ); ?></span><br/>
      <span><strong>Phone:</strong> <?php echo get_the_author_meta( 'dance_school_phone_number', $current_user->ID ); ?></span><br/>
      <span><strong>Description</strong></br/>
        <?php echo get_the_author_meta( 'dance_school_description', $current_user->ID ); ?>
      </span>
    </p>
    <br>
    <a data-toggle="tab" href="#ds-details" class="nkms-btn">Edit Details</a>
    <!-- <button onclick="dsOpenTab(event, 'ds-details')">Edit Details</button> -->
</div><!-- .nkms-tabs -->
