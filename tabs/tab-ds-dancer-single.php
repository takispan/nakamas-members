<?php
/**
 * Displays a single dancer from the Dance School's dance list.
 * Able to change status of dancers and remove from Dance School.
 */

$currently_viewing = get_user_meta(get_current_user_id(), 'currently_viewing', true);

$dancer_id = $currently_viewing[0];
$dancer = get_user_by( 'id', $dancer_id );
(!get_user_meta($dancer_id, 'active', true)) ? $active_status = "Inactive" : $active_status = "Active";
?>

<div class="nkms-tabs ds-single-dancer">
  <h3 style="font-weight:300;">Dancer <span style="font-weight:600;"><?php echo $dancer->first_name . ' ' . $dancer->last_name; ?></span> for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Status</th>
    </tr>
    <tr>
      <td><?php echo $dancer_id ?></td>
      <td><?php echo $dancer->first_name . " " . $dancer->last_name ?> </td>
      <td><?php echo $active_status ?> </td>
    </tr>
  </table>
  <button class="change-dancer-status" data-dancer-id="<?php echo $dancer_id ?>">Change Status</button>
  <button class="remove-dancer" data-dancer-id="<?php echo $dancer_id ?>">Remove Dancer</button>
</div><!-- .nkms-tabs -->
