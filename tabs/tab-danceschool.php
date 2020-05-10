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
    <ul class="nav nav-tabs" id="myTab2">
        <li class="active"><a data-toggle="tab" href="#ds-overview">Overview</a></li>
        <li><a data-toggle="tab" href="#ds-dancers">Dancers</a></li>
        <li><a data-toggle="tab" href="#ds-dance-groups">Dance groups</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-details">Details</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-add-dancers">Add dancer</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-dancer-single">Single dancer</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-add-groups">Add group</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-group-single">Single group</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-group-add-dancers">Add dancer to group</a></li>
        <li style="display:none;"><a data-toggle="tab" href="#ds-group-remove-dancers">Remove dancer from group</a></li>
    </ul>

    <!-- Tab content - Overview -->
    <div class="tab-content">
      <div id="ds-overview" class="tab-pane fade in active">
         <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-overview.php'); ?>
      </div>

      <!-- Tab content - Dancers -->
      <div id="ds-dancers" class="tab-pane fade">
         <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-dancers.php'); ?>
      </div>

      <!-- Tab content - Dance Groups -->
      <div id="ds-dance-groups" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-groups.php'); ?>
      </div>

      <!-- HIDDEN TABS -->
      <!-- Tab content - Dance School Details -->
      <div id="ds-details" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-details.php'); ?>
      </div>

      <!-- Tab content - Add Dancers -->
      <div id="ds-add-dancers" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-dancers.php'); ?>
      </div>

      <!-- Tab content - Dancer Single (Change status & remove)-->
      <div id="ds-dancer-single" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-dancer-single.php'); ?>
      </div>

      <!-- Tab content - Add Group -->
      <div id="ds-add-groups" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-groups.php'); ?>
      </div>

      <!-- Tab content - Group Single (Change status & remove)-->
      <div id="ds-group-single" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-single.php'); ?>
      </div>

      <!-- Tab content - Add dancers to group-->
      <div id="ds-group-add-dancers" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-add-dancers.php'); ?>
      </div>

      <!-- Tab content - Remove dancers from group -->
      <div id="ds-group-remove-dancers" class="tab-pane fade">
          <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-remove-dancers.php'); ?>
      </div>
    </div>

</div><!-- .nkms-tabs -->
