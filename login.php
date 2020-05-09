<?php
if ($current_user->ID) {
 // They're already logged in, so we bounce them back to the profile page.
 wp_redirect(home_url().'/profile');
 exit;
}

if( $_POST ) {
  global $wpdb;

  //We shall SQL escape all inputs
  $username = esc_sql($_REQUEST['login_username']);
  $password = esc_sql($_REQUEST['login_password']);

  $login_data = array(
    'user_login'    => $username,
    'user_password' => $password,
  );

  $user_verify = wp_signon( $login_data, false );
}

get_header(); ?>
<div id="nkms-account">
  <h2>Login</h2>
<?php

if( $_POST ) {
  if ( is_wp_error($user_verify) ) {
    echo "<p style='color:red;'><strong>Invalid login details</strong></p>";
  }
  else {
    echo "<h4>Login successful! If you're not redirected automatically click <a href='" . home_url().'/profile' . "'>here</a>.</h4>";
    wp_safe_redirect(home_url().'/profile');
    exit();
  }
}
?>

  <form id="login-form" name="form" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
    <p>
      <label for="username">Username</label>
      <input id="username" type="text" name="login_username">
    </p>
    <p>
      <label for="password">Password</label>
      <input id="password" type="password" name="login_password">
    </p>
    <p>
      <input type="submit" id="login_submit" name="login_submit" value="Login" class="btn btn-primary btn-login">
    </p>
  </form>
</div><!-- #nkms-account -->

<?php get_footer(); // Loads the footer.php template. ?>
