<?php
/**
 * Template Name: User Profile
 *
 * Allow users to update their profiles from Frontend.
 */
?>


<div class="nkms-tabs">
  <h3 style="font-weight:300;">Update Information for <span style="font-weight:600;"><?php echo $current_user->user_login ?></span></h3>
  <form method="post" id="update_profile" action="">
    <p>
      <label for="update_profile_first_name">First Name</label>
      <input name="update_profile_first_name" type="text" value="<?php echo $current_user->first_name; ?>" disabled />
    </p>
    <p>
      <label for="update_profile_last_name">Last Name</label>
      <input name="update_profile_last_name" type="text" value="<?php echo $current_user->last_name; ?>" disabled />
    </p>
    <p>
      <label for="update_profile_email">Email</label>
      <input name="update_profile_email" type="text" value="<?php echo $current_user->user_email; ?>" disabled />
    </p>
    <p>
      <label for="update_profile_phone_number">Phone Number</label>
      <input name="update_profile_phone_number" type="text" value="<?php echo $current_user->nkms_fields['phone_number']; ?>" />
    </p>
    <p>
      <label for="update_profile_dob">Date of Birth</label>
      <input name="update_profile_dob" type="text" value="<?php echo $current_user->nkms_fields['dob']; ?>" disabled />
    </p>
    <p>
      <label for="update_profile_city">City</label>
      <input name="update_profile_city" type="text" value="<?php echo $current_user->nkms_fields['city']; ?>" />
    </p>
    <p>
      <label for="update_profile_address">Address</label>
      <input name="update_profile_address" type="text" value="<?php echo $current_user->nkms_fields['address']; ?>" />
    </p>
    <p>
      <label for="update_profile_postcode">Postcode</label>
      <input name="update_profile_postcode" type="text" value="<?php echo $current_user->nkms_fields['postcode']; ?>" />
    </p>
    <?php
    $age = nkms_get_age( $current_user->nkms_fields['dob'] );
    if ( $age < 18) : ?>
      <h6>Guardian Details</h6>
      <p>
        <label for="update_profile_guardian_name">Guardian Full Name</label>
        <input type="text" name="update_profile_guardian_name" value="<?php echo $current_user->nkms_dancer_fields['dancer_guardian_name']; ?>">
      </p>
      <p>
        <label for="update_profile_guardian_phone_number">Guardian Phone Number</label>
        <input type="text" name="update_profile_guardian_phone_number" value="<?php echo $current_user->nkms_dancer_fields['dancer_guardian_phone_number']; ?>">
      </p>
      <p>
        <label for="update_profile_guardian_email">Guardian Email</label>
        <input type="text" name="update_profile_guardian_email" value="<?php echo $current_user->nkms_dancer_fields['dancer_guardian_email']; ?>">
      </p>
    <?php endif; ?>
    <?php
    if ( nkms_has_role( $current_user, 'dancer' ) ) : ?>
      <h6>Dancer Details</h6>
      <!-- <p>
        <label for="update_profile_dancer_level">Level category</label>
        <select id="dancer-level" name="update_profile_dancer_level">
          <option value="" selected disabled hidden>Select level category</option>
          <option value="Newcomer">Newcomer</option>
          <option value="Novice">Novice</option>
          <option value="Intermediate">Intermediate</option>
          <option value="Advanced">Advanced</option>
        </select>
      </p>
      <p>
        <label for="update_profile_dancer_age_category">Age category</label>
        <select id="dancer-age-category" name="update_profile_dancer_age_category">
          <option value="" selected disabled hidden>Select age category</option>
          <option value="6u">6 and Under</option>
          <option value="8u">8 and Under</option>
          <option value="10u">10 and Under</option>
          <option value="12u">12 and Under</option>
          <option value="14u">14 and Under</option>
          <option value="16u">16 and Under</option>
          <option value="17">17+</option>
        </select>
      </p> -->
      <p>
        <label for="update_profile_dancer_ds_name">Name of Dance School</label>
        <input type="text" name="update_profile_dancer_ds_name" value="<?php echo $current_user->nkms_dancer_fields['dancer_ds_name']; ?>">
      </p>
      <p>
        <label for="update_profile_dancer_ds_teacher_name">Name of Dance Teacher</label>
        <input type="text" name="update_profile_dancer_ds_teacher_name" value="<?php echo $current_user->nkms_dancer_fields['dancer_ds_teacher_name']; ?>">
      </p>
      <p>
        <label for="update_profile_dancer_ds_teacher_email">Teacher Email</label>
        <input type="text" name="update_profile_dancer_ds_teacher_email" value="<?php echo $current_user->nkms_dancer_fields['dancer_ds_teacher_email']; ?>">
      </p>
    <?php endif; ?>
    <?php
    if ( nkms_has_role( $current_user, 'dance-school' ) ) : ?>
      <h4>Dance School details</h4>
      <p>
        <label for="update_profile_dance_school_name">Dance School Name</label>
        <input type="text" name="update_profile_dance_school_name" value="<?php echo $current_user->nkms_dance_school_fields['dance_school_name']; ?>" disabled>
      </p>
      <p>
        <label for="update_profile_dance_school_address">Dance School Address</label>
        <input type="text" name="update_profile_dance_school_address" value="<?php echo $current_user->nkms_dance_school_fields['dance_school_address']; ?>">
      </p>
      <p>
        <label for="update_profile_dance_school_phone_number">Dance School Phone Number</label>
        <input type="text" name="update_profile_dance_school_phone_number" value="<?php echo $current_user->nkms_dance_school_fields['dance_school_phone_number']; ?>">
      </p>
      <p>
        <label for="update_profile_dance_school_description">Dance School Description</label>
        <textarea rows="5" name="update_profile_dance_school_description"><?php echo $current_user->nkms_dance_school_fields['dance_school_description']; ?></textarea>
      </p>
    <?php endif; ?>
    <p class="nkms-pfp">
      <?php
      if ( nkms_has_role( $current_user, 'dance-school' ) ) {
        echo '<label>Dance School logo</label>';
      }
      else {
        echo '<label>Profile picture</label>';
      }
      ?>
      <a class="pfp-link">
        <?php echo get_wp_user_avatar( $current_user->ID, '256', '' ); ?>
      </a>
    </p>
    <p class="ajax-response"></p>
    <p class="form-submit">
      <?php //echo $referer; ?>
      <input type="hidden" name="update_profile_user_id" value="<?php echo $current_user->ID; ?>" />
      <input name="update_profile_submit" type="submit" id="update_profile_submit" class="submit button" value="Update" />
    </p><!-- .form-submit -->
  </form>
</div><!-- .nkms-tabs -->
