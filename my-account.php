<?php
/**
 * Template Name: My Account
 *
 * Tabs with information & actions regarding user's account.
 */

get_header(); // Loads the header.php template. ?>

<section id="content">
    <div id="nkms-account">
        <?php if ( !is_user_logged_in() ) : ?>
            <p class="warning">
                <?php _e('You must be logged to access your account', 'profile'); ?>
            </p><!-- .warning -->
        <?php else : ?>
            <!-- Tab links -->
            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'dashboard')">Dashboard</button>
                <button class="tablinks" onclick="openCity(event, 'dance-school')">Dance School</button>
                <button class="tablinks" onclick="openCity(event, 'profile')">Profile</button>
            </div>

            <div id="dashboard" class="tabcontent">
             <h3>Dashboard</h3>
             <p>Dashboard stuff.</p>
            </div>

            <div id="dance-school" class="tabcontent">
             <h3>Dance School</h3>
             <p>Dance School stuff.</p>
            </div>

            <!-- Tab content -->
            <div id="profile" class="tabcontent">
             <h3>Profile</h3>
             <?php include( plugin_dir_path( __FILE__ ) . 'tabs/tab-user-profile.php'); ?>
            </div>
        <?php endif; ?>
    </div>

</section><!-- #content -->

<?php
/* add_action to... well add our action to wp
 * Parameters
 * Required: tag (wp specific, don't change), function_to_add (name of our function)
 * Optional: priority (defaults to 10. Lower = more priority), accepted_args (accepted arguments, defaults to 1)
 */
add_action('wp_enqueue_scripts', 'nkms_assets');
function nkms_assets() {
    /* Register the script like this for a plugin
     * Parameters
     * Required: handle (name), src (source)
     * Optional: deps (dependencies), ver (version, used date for this), in_footer (load on footer or not. By default off an loads on header)
     */
    wp_register_script( 'nkms-js', plugins_url( '/assets/js/nakamas-members.js', __FILE__ ), array( 'jquery' ), '20200406', true );

    /* Register the style like this for a plugin
     * Parameters
     * Required: handle (name), src (source)
     * Optional: deps (dependencies), ver (version, used date for this), media (The media for which this stylesheet has been defined. Accepts media types like 'all', 'print' and 'screen', or media queries like '(orientation: portrait)' and '(max-width: 640px)'.)
     */
    wp_register_style( 'nkms-css', plugins_url( '/assets/css/nakamas-members.css', __FILE__ ), array(), '20200404', 'all' );

    // For either a plugin or a theme, you can then enqueue the script/style
    wp_enqueue_script( 'nkms-js' );
    wp_enqueue_style( 'nkms-css' );
    //wp_enqueue_script( 'nakamas-members-scripts', plugin_dir_path( __FILE__ ) . 'nakamas-members-script.js', array( 'jquery' ) );
}

get_footer(); // Loads the footer.php template. ?>
