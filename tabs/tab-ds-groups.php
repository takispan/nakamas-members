<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$ds_groups_list_array = get_user_meta( get_current_user_id(), 'dance_school_groups_list', true );
if (!is_array($ds_groups_list_array)) { $ds_groups_list_array = []; }
?>


<div class="nkms-tabs">
  <h3 style="font-weight:300;">Dance Groups for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <?php
  if ( ! empty( $ds_groups_list_array ) ) { ?>
    <table>
      <tr>
        <th>Type</th>
        <th>Group Name</th>
        <th>Status</th>
      </tr>
    <?php
    foreach ($ds_groups_list_array as $key => $value) {
      echo '<tr><td>' . $value->getType() . '</td><td><a data-toggle="tab" href="#ds-group-single" class="single-group" data-group-id="' . $key . '">' . $value->getGroupName() . '</a></td><td>' . $value->getStatus() . '</td></tr>';
    }
    echo '</table>';
  } else {
    echo "<p>" . $ds_name . " does not have any groups.</p>";
    echo "<p>Create one by clicking the button below.</p>";
  }
  ?>
  <a data-toggle="tab" href="#ds-add-groups" class="nkms-btn">Add Group</a>
</div><!-- .nkms-tabs -->
