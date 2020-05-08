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
        <button class="ds-tablinks" onclick="dsOpenTab(event, 'ds-dancers')" id="dancers">Dancers</button>
        <button class="ds-tablinks" onclick="dsOpenTab(event, 'ds-dance-groups')" id="danceGroups">Dance Groups</button>
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

    <!-- HIDDEN TABS -->
    <!-- Tab content - Dance School Details -->
    <div id="ds-details" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-details.php'); ?>
    </div>

    <!-- Tab content - Add Dancers -->
    <div id="ds-add-dancers" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-dancers.php'); ?>
    </div>

    <!-- Tab content - Dancer Single (Change status & remove)-->
    <div id="ds-dancer-single" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-dancer-single.php'); ?>
    </div>

    <!-- Tab content - Add Group -->
    <div id="ds-add-groups" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-groups.php'); ?>
    </div>

    <!-- Tab content - Group Single (Change status & remove)-->
    <div id="ds-group-single" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-single.php'); ?>
    </div>

    <!-- Tab content - Add dancers to group-->
    <div id="ds-group-add-dancers" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-add-dancers.php'); ?>
    </div>

    <!-- Tab content - Remove dancers from group -->
    <div id="ds-group-remove-dancers" class="ds-tabcontent">
        <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-remove-dancers.php'); ?>
    </div>

</div><!-- .nkms-tabs -->
