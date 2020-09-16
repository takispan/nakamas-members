<?php
/**
 * Template Name: Dance School
 *
 * Dance School tab to manage dance school.
 */
$dance_school_dancers_list = $dance_school->nkms_dance_school_fields['dance_school_dancers_list'];
$dance_school_groups_list = $dance_school->nkms_dance_school_fields['dance_school_groups_list'];
$dance_school_teachers_list = $dance_school->nkms_dance_school_fields['dance_school_teachers_list'];
?>

<h3 class="dance-school-h3">Dance School Information for <span><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
<div class="ds-tabs">
  <!-- Tab content - Overview -->
  <input type="radio" name="ds-tabs" id="ds-overview" checked="checked">
  <label for="ds-overview">Overview</label>
  <div class="ds-tab">
     <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-overview.php'); ?>
  </div>

  <!-- Tab content - Dancers -->
  <input type="radio" name="ds-tabs" id="ds-dancers" checked="checked">
  <label for="ds-dancers">Dancers</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-dancers.php'); ?>
  </div>

  <!-- Tab content - Groups -->
  <input type="radio" name="ds-tabs" id="ds-dance-groups" checked="checked">
  <label for="ds-dance-groups">Groups</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-groups.php'); ?>
  </div>

  <!-- Tab content - Registrations -->
  <input type="radio" name="ds-tabs" id="ds-registrations" checked="checked">
  <label for="ds-registrations">Registrations</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-register-groups.php'); ?>
  </div>

  <!-- Tab content - Teachers -->
  <input type="radio" name="ds-tabs" id="ds-teachers" checked="checked">
  <label for="ds-teachers">Teachers</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-teachers.php'); ?>
  </div>

  <!-- HIDDEN TABS -->
  <!-- Tab content - Dance School Details -->
  <input type="radio" name="ds-tabs" id="ds-details" checked="checked">
  <label for="ds-details" class="tab-hidden">Details</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-details.php'); ?>
  </div>

  <!-- Tab content - Add Dancers -->
  <input type="radio" name="ds-tabs" id="ds-add-dancers" checked="checked">
  <label for="ds-add-dancers" class="tab-hidden">Add dancers</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-dancers.php'); ?>
  </div>

  <!-- Tab content - Dancer Single (Change status & remove)-->
  <input type="radio" name="ds-tabs" id="ds-dancer-single" checked="checked">
  <label for="ds-dancer-single" class="tab-hidden">Dancer single</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-dancer-single.php'); ?>
  </div>

  <!-- Tab content - Add Group -->
  <input type="radio" name="ds-tabs" id="ds-add-groups" checked="checked">
  <label for="ds-add-groups" class="tab-hidden">Add group</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-groups.php'); ?>
  </div>

  <!-- Tab content - Group Single (Change status & remove)-->
  <input type="radio" name="ds-tabs" id="ds-group-single" checked="checked">
  <label for="ds-group-single" class="tab-hidden">Group single</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-single.php'); ?>
  </div>

  <!-- Tab content - Add dancers to group-->
  <input type="radio" name="ds-tabs" id="ds-group-add-dancers" checked="checked">
  <label for="ds-group-add-dancers" class="tab-hidden">Add dancers to group</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-add-dancers.php'); ?>
  </div>

  <!-- Tab content - Remove dancers from group -->
  <input type="radio" name="ds-tabs" id="ds-group-remove-dancers" checked="checked">
  <label for="ds-group-remove-dancers" class="tab-hidden">Remove dancers from group</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-group-remove-dancers.php'); ?>
  </div>

  <!-- Tab content - Add Dancers -->
  <input type="radio" name="ds-tabs" id="ds-add-teachers" checked="checked">
  <label for="ds-add-teachers" class="tab-hidden">Add teachers</label>
  <div class="ds-tab">
    <?php include( plugin_dir_path( __FILE__ ) . 'tab-ds-add-teachers.php'); ?>
  </div>

  <!-- Tab content - Teacher Single (Change status & remove)-->
  <input type="radio" name="ds-tabs" id="ds-teacher-single" checked="checked">
  <label for="ds-teacher-single" class="tab-hidden">Single teacher</label>
  <div class="ds-tab">
    <?php
    //include( plugin_dir_path( __FILE__ ) . 'tab-ds-teacher-single.php'); ?>
  </div>
</div><!-- .tabs -->
