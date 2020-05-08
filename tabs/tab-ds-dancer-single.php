<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$currently_viewing = get_user_meta($current_user->ID, 'currently_viewing', true);

var_dump($currently_viewing);
$dancer_id = intval($currently_viewing[0]);
$dancer = get_user_by( 'id', $dancer_id );
echo '<p> Variable is:' . $dancer_id . ' & ' . $dancer . '</p>';

?>

<div class="nkms-tabs">
    <h3 style="font-weight:300;">Dancer <span style="font-weight:600;"><?php //echo $dancer->first_name . ' ' . $dancer->last_name; ?> for <span style="font-weight:600;"><?php //echo $ds_name; ?></span></h3></br>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Status</th>
        </tr>
        <tr>
          <td><?php //echo $dancer->status; ?></td>
          <td></td>
        </tr>
      </table>

</div><!-- .nkms-tabs -->
