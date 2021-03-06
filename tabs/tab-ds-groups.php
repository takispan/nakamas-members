<?php
/**
 * List groups in dance school.
 */
?>

<div class="groups-list">
  <h3 style="font-weight:300;">Groups for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <?php
  if ( ! empty( $dance_school_groups_list ) ) { ?>
    <table>
      <thead>
        <tr>
          <th>Type</th>
          <th>Group name</th>
          <th>Age category</th>
          <th>Level category</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach ( $dance_school_groups_list as $key => $group ) {
        echo '<tr><td>' . $group->getType() . '</td>
        <td><a class="single-group" data-ds-id="' . $dance_school->ID . '" data-group-id="' . $key . '">' . $group->getGroupName() . '</a></td>
        <td>' . $group->getAgeCategory() . '</td>
        <td>' . $group->getLevelCategory() . '</td>
        <td>' . $group->getStatus() . '</td></tr>';
      }
      echo '</tbody></table>';
  } else {
    echo "<p>" . $dance_school->nkms_dance_school_fields['dance_school_name'] . " does not have any groups.";
    echo "<br>Create one by clicking the button below.</p>";
  }
  ?>
  <a class="button ds-add-groups-link">Add Group</a>
</div><!-- .nkms-tabs -->
