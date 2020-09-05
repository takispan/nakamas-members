<?php
/**
 * Template Name: Nakamas Register
 *
 * Allow users to regiser a profile from Frontend.
 */
if ( is_user_logged_in() ) {
 wp_redirect( home_url() . '/profile' );
 exit;
}
get_header(); ?>
<div id="nkms-account">
  <h2>Register</h2>
  <?php nkms_custom_registration(); ?>
  <form id="nkms_registration" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
    <p>
      <label for="reg_username">Username</label>
      <input type="text" name="reg_username" value="<?php echo ( isset( $_POST['reg_username'] ) ?  $_POST['reg_username'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_password">Password</label>
      <input type="password" name="reg_password" value="">
    </p>
    <p>
      <label for="reg_first_name">First Name</label>
      <input type="text" name="reg_first_name" value="<?php echo ( isset( $_POST['reg_first_name'] ) ?  $_POST['reg_first_name'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_last_name">Last Name</label>
      <input type="text" name="reg_last_name" value="<?php echo ( isset( $_POST['reg_last_name'] ) ?  $_POST['reg_last_name'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_email">Email</label>
      <input type="text" name="reg_email" value="<?php echo ( isset( $_POST['reg_email'] ) ?  $_POST['reg_email'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_phone_number">Phone Number</label>
      <input type="text" name="reg_phone_number" value="<?php echo ( isset( $_POST['reg_phone_number'] ) ?  $_POST['reg_phone_number'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_dob">Date of Birth</label>
      <input id="datepicker" type="text" name="reg_dob" value="">
    </p>
    <p>
      <?php
      // date field by WooCommerce. Better UX due to year selection.
      // woocommerce_form_field( 'user_dob', array(
      //   'type' => 'date',
      //   'label' => 'Date of Birth',
      // ) );
      woocommerce_form_field( 'billing_country', array(
        'type' => 'country',
        'label' => 'Country',
        // 'required' => true,
        'default' => 'GB'
      ) ); ?>

    </p>
    <p>
      <label for="reg_city">City</label>
      <input type="text" name="reg_city" value="<?php echo ( isset( $_POST['reg_city'] ) ?  $_POST['reg_city'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_address">Address</label>
      <input type="text" name="reg_address" value="<?php echo ( isset( $_POST['reg_address'] ) ?  $_POST['reg_address'] : ''  ); ?>">
    </p>
    <p>
      <label for="reg_postcode">Postcode</label>
      <input type="text" name="reg_postcode" value="<?php echo ( isset( $_POST['reg_postcode'] ) ?  $_POST['reg_postcode'] : ''  ); ?>">
    </p>
    <!-- Guardian fields if dancer is < 18 -->
    <div id="ds-reg-fields-dancer-guardian" style="display:none;">
      <h6>Guardian Details</h6>
      <p>
        <label for="reg_dancer_guardian_name">Guardian Full Name</label>
        <input type="text" name="reg_dancer_guardian_name" value="<?php echo ( isset( $_POST['reg_dancer_guardian_name'] ) ?  $_POST['reg_dancer_guardian_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dancer_guardian_phone_number">Guardian Phone Number</label>
        <input type="text" name="reg_dancer_guardian_phone_number" value="<?php echo ( isset( $_POST['reg_dancer_guardian_phone_number'] ) ?  $_POST['reg_dancer_guardian_phone_number'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dancer_guardian_email">Guardian Email</label>
        <input type="text" name="reg_dancer_guardian_email" value="<?php echo ( isset( $_POST['reg_dancer_guardian_email'] ) ?  $_POST['reg_dancer_guardian_email'] : ''  ); ?>">
      </p>
    </div>
    <p>
      <label for="reg_sel_role">Account type</label>
      <select id="select_role" name="reg_sel_role">
        <option value="" selected disabled hidden>Select account type</option>
        <option value="spectator">Spectator</option>
        <option value="dancer">Dancer</option>
        <option value="guardian">Guardian/Parent</option>
        <option value="dance-school">Dance School</option>
      </select>
    </p>
    <!-- Dancer fields -->
    <div id="ds-reg-fields-dancer" style="display:none;">
      <h6>Dancer Details</h6>
      <p>
        <label for="reg_dancer_level">Level category</label>
        <select id="dancer-level" name="reg_dancer_level">
          <option value="" selected disabled hidden>Select level category</option>
          <option value="Newcomer">Newcomer</option>
          <option value="Novice">Novice</option>
          <option value="Intermediate">Intermediate</option>
          <option value="Advanced">Advanced</option>
        </select>
      </p>
      <p>
        <label for="reg_dancer_ds_name">Name of Dance School</label>
        <input type="text" name="reg_dancer_ds_name" value="<?php echo ( isset( $_POST['reg_dancer_ds_name'] ) ?  $_POST['reg_dancer_ds_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dancer_ds_teacher_name">Name of Dance Teacher</label>
        <input type="text" name="reg_dancer_ds_teacher_name" value="<?php echo ( isset( $_POST['reg_dancer_ds_teacher_name'] ) ?  $_POST['reg_dancer_ds_teacher_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dancer_ds_teacher_email">Teacher Email</label>
        <input type="text" name="reg_dancer_ds_teacher_email" value="<?php echo ( isset( $_POST['reg_dancer_ds_teacher_email'] ) ?  $_POST['reg_dancer_ds_teacher_email'] : ''  ); ?>">
      </p>
    </div>
    <!-- Dance School fields -->
    <div id="ds-reg-fields-dance-school" style="display:none;">
      <h6>Dance School Details</h6>
      <p>
        <label for="reg_dance_school_name">Dance School Name</label>
        <input type="text" name="reg_dance_school_name" value="<?php echo ( isset( $_POST['reg_dance_school_name'] ) ?  $_POST['reg_dance_school_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dance_school_address">Dance School Address</label>
        <input type="text" name="reg_dance_school_address" value="<?php echo ( isset( $_POST['reg_dance_school_address'] ) ?  $_POST['reg_dance_school_address'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dance_school_phone_number">Dance School Phone Number</label>
        <input type="text" name="reg_dance_school_phone_number" value="<?php echo ( isset( $_POST['reg_dance_school_phone_number'] ) ?  $_POST['reg_dance_school_phone_number'] : ''  ); ?>">
      </p>
      <p>
        <label for="reg_dance_school_description">Dance School Description</label>
        <textarea rows="5" name="reg_dance_school_description" class="regular-text"><?php echo ( isset( $_POST['reg_dance_school_description'] ) ?  $_POST['reg_dance_school_description'] : ''  ); ?></textarea>
      </p>
    </div>
    <p>
      <input type="submit" name="registration_submit" value="Register"/>
    </p>
  </form>
</div>

<?php get_footer(); // Loads the footer.php template. ?>
