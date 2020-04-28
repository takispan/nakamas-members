<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$ds_name = get_the_author_meta( 'dance_school_name', $current_user->ID );

?>

<div class="nkms-tabs">
    <h3 style="font-weight:300;">Dance School Information for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <div class="tab">
        <button class="ds-tablinks" onclick="dsOpenTab(event, 'ds-overview')" id="dsDefaultOpen">Overview</button>
        <button class="ds-tablinks" onclick="dsOpenTab(event, 'ds-dancers')">Dancers</button>
        <button class="ds-tablinks" onclick="dsOpenTab(event, 'ds-dance-groups')">Dance Groups</button>
        <!-- <button class="ds-tablinks" onclick="dsOpenTab(event, 'ds-details')" id="dsDetails">Dance School Details</button> -->
    </div>

    <!-- Tab content - Overview -->
    <div id="ds-overview" class="ds-tabcontent">
       <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-overview.php'); ?>
    </div>

    <!-- Tab content - Dancers -->
    <div id="ds-dancers" class="ds-tabcontent">
       <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-dancers.php'); ?>
    </div>

    <!-- Tab content - Dance Groups -->
    <div id="ds-dance-groups" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-groups.php'); ?>
    </div>

    <!-- Tab content - Dance School Details -->
    <div id="ds-details" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-details.php'); ?>
    </div>

    <!-- Tab content - Add / Remove Dancers -->
    <div id="ds-add-remove-dancers" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-remove-dancers.php'); ?>
    </div>

</div><!-- .nkms-tabs -->
