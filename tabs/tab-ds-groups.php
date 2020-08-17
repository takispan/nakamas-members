<?php
/**
 * List groups in dance school.
 */

$ds_groups_list_array = get_user_meta( get_current_user_id(), 'dance_school_groups_list', true );
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Groups for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <?php
  if ( ! empty( $ds_groups_list_array ) ) { ?>
    <table>
      <tr>
        <th>Type</th>
        <th>Group Name</th>
        <th>Status</th>
      </tr>
    <?php
    foreach ( $ds_groups_list_array as $key => $group ) {
      echo '<tr><td>' . $group->getType() . '</td><td><a data-toggle="tab" href="#ds-group-single" class="single-group" data-group-id="' . $key . '">' . $group->getGroupName() . '</a></td><td>' . $group->getStatus() . '</td></tr>';
    }
    echo '</table>';
  } else {
    echo "<p>" . $ds_name . " does not have any groups.</p>";
    echo "<p>Create one by clicking the button below.</p>";
  }
  ?>
  <a data-toggle="tab" href="#ds-add-groups" class="nkms-btn">Add Group</a>
</div><!-- .nkms-tabs -->
