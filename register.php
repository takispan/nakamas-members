<?php
/**
 * Template Name: Nakamas Register
 *
 * Allow users to regiser a profile from Frontend.
 */
if ($current_user->ID) {
 // They're already logged in, so we bounce them back to the profile page.
 wp_redirect(home_url().'/profile');
 exit;
}
get_header(); ?>
<div id="nkms-account">
  <h2>Register</h2>
  <?php nkms_custom_registration(); ?>
  <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
    <p>
      <label for="username">Username</label>
      <input type="text" name="username" value="<?php echo ( isset( $_POST['username'] ) ?  $_POST['username'] : ''  ); ?>">
    </p>

    <p>
      <label for="password">Password</label>
      <input type="password" name="password" value="">
    </p>

    <p>
      <label for="email">Email</label>
      <input type="text" name="email" value="<?php echo ( isset( $_POST['email'] ) ?  $_POST['email'] : ''  ); ?>">
    </p>

    <p>
      <label for="first_name">First Name</label>
      <input type="text" name="first_name" value="<?php echo ( isset( $_POST['first_name'] ) ?  $_POST['first_name'] : ''  ); ?>">
    </p>

    <p>
      <label for="last_name">Last Name</label>
      <input type="text" name="last_name" value="<?php echo ( isset( $_POST['last_name'] ) ?  $_POST['last_name'] : ''  ); ?>">
    </p>
    <p>
      <label for="dob">Date of Birth</label>
      <input id="datepicker" type="text" name="dob" value="<?php echo ( isset( $_POST['dob'] ) ?  $_POST['dob'] : ''  ); ?>">
    </p>
    <p>
      <label for="address">Full Adress</label>
      <input type="text" name="address" value="<?php echo ( isset( $_POST['address'] ) ?  $_POST['address'] : ''  ); ?>">
    </p>
    <p>
      <label for="phone_number">Phone Number</label>
      <input type="text" name="phone_number" value="<?php echo ( isset( $_POST['phone_number'] ) ?  $_POST['phone_number'] : ''  ); ?>">
    </p>
    <p>
      <label for="dancer_experience">Dancing Experience</label>
      <select id="dancer_xp" name="dancer_experience">
        <option value="Newcomer">Newcomer</option>
        <option value="Novice">Novice</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
      </select>
    </p>
    <!-- Guardian fields if dancer is < 18 -->
    <div id="ds-reg-fields-dancer-guardian" style="display:none;">
      <h6>Guardian Details</h6>
      <p>
        <label for="dancer_guardian_name">Guardian Name</label>
        <input type="text" name="dancer_guardian_name" value="<?php echo ( isset( $_POST['dancer_guardian_name'] ) ?  $_POST['dancer_guardian_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="dancer_guardian_phone_number">Guardian Phone Number</label>
        <input type="text" name="dancer_guardian_phone_number" value="<?php echo ( isset( $_POST['dancer_guardian_phone_number'] ) ?  $_POST['dancer_guardian_phone_number'] : ''  ); ?>">
      </p>
      <p>
        <label for="dancer_guardian_email">Guardian Email</label>
        <input type="text" name="dancer_guardian_email" value="<?php echo ( isset( $_POST['dancer_guardian_email'] ) ?  $_POST['dancer_guardian_email'] : ''  ); ?>">
      </p>
    </div>
    <p>
      <label for="sel_role">Account type</label>
      <select id="select_role" name="sel_role">
        <option value="spectator">Spectator</option>
        <option value="dancer">Dancer</option>
        <option value="guardian">Guardian/Parent</option>
        <option value="dance-school">Dance School</option>
      </select>
    </p>
    <!-- Dancer fields -->
    <div id="ds-reg-fields-dancer" style="display:none;">
      <p>
        <label for="dancer_ds_name">Name of Dance School</label>
        <input type="text" name="dancer_ds_name" value="<?php echo ( isset( $_POST['dancer_ds_name'] ) ?  $_POST['dancer_ds_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="dancer_ds_teacher_name">Name of Dance Teacher</label>
        <input type="text" name="dancer_ds_teacher_name" value="<?php echo ( isset( $_POST['dancer_ds_teacher_name'] ) ?  $_POST['dancer_ds_teacher_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="dancer_ds_teacher_email">Teacher Email</label>
        <input type="text" name="dancer_ds_teacher_email" value="<?php echo ( isset( $_POST['dancer_ds_teacher_email'] ) ?  $_POST['dancer_ds_teacher_email'] : ''  ); ?>">
      </p>
    </div>
    <!-- Dance School fields -->
    <div id="ds-reg-fields-dance-school" style="display:none;">
      <p>
        <label for="dance_school_name">Dance School Name</label>
        <input type="text" name="dance_school_name" value="<?php echo ( isset( $_POST['ds_name'] ) ?  $_POST['ds_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="dance_school_address">Dance School Address</label>
        <input type="text" name="dance_school_address" value="<?php echo ( isset( $_POST['ds_name'] ) ?  $_POST['ds_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="dance_school_phone_number">Dance School Phone Number</label>
        <input type="text" name="dance_school_phone_number" value="<?php echo ( isset( $_POST['ds_name'] ) ?  $_POST['ds_name'] : ''  ); ?>">
      </p>
      <p>
        <label for="dance_school_description">Dance School Description</label>
        <textarea rows="5" name="dance_school_description" class="regular-text"><?php echo ( isset( $_POST['ds_name'] ) ?  $_POST['ds_name'] : ''  ); ?></textarea>
      </p>
    </div>
    <p>
      <input type="submit" name="registration_submit" value="Register"/>
    </p>
  </form>
</div>

<?php get_footer(); // Loads the footer.php template. ?>
