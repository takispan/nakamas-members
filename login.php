<?php
get_header(); // Loads the header.php template.

if($_POST)
{

    global $wpdb;

    //We shall SQL escape all inputs
    $username = esc_sql($_REQUEST['username']);
    $password = esc_sql($_REQUEST['password']);

    $login_data = array();
    $login_data['user_login'] = $username;
    $login_data['user_password'] = $password;

    $user_verify = wp_signon( $login_data, false );

    if ( is_wp_error($user_verify) )
    {
        echo "Invalid login details";
       // Note, I have created a page called "Error" that is a child of the login page to handle errors. This can be anything, but it seemed a good way to me to handle errors.
     } else
    {
       echo "<script type='text/javascript'>window.location.href='". home_url() ."'</script>";
       exit();
     }

} else
{

    // No login details entered - you should probably add some more user feedback here, but this does the bare minimum

    //echo "Invalid login details";

}
 ?>
<h2>Welcome</h2>
<form id="login-form" name="form" action="<?php echo home_url(); ?>/login/" method="post">

        <table class="table">
            <tr>
                <td><label for="username">Username</label></td>
                <td><input id="username" type="text" placeholder="Username" name="username"></td>
            </tr>
            <tr>
                <td><label for="password">Password</label></td>
                <td><input id="password" type="password" placeholder="Password" name="password"></td>
            </tr>
        </table>

        <input id="submit" type="submit" name="submit" value="Login" class="btn btn-primary btn-login">
</form>
<?php
function callback_for_setting_up_scripts() {
    wp_register_style( 'nkms-css', plugins_url( 'CSS/login.css', __FILE__ ), array(), '20200404', 'all' );
    wp_enqueue_style( 'nkms-css' );
    //wp_enqueue_script( 'nakamas-members-scripts', plugin_dir_path( __FILE__ ) . 'nakamas-members-script.js', array( 'jquery' ) );
}
add_action('wp_enqueue_scripts', 'callback_for_setting_up_scripts');
get_footer(); // Loads the footer.php template. ?>