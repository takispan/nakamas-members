<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$currently_viewing = get_user_meta(get_current_user_id(), 'currently_viewing', true);

//var_dump($currently_viewing);
$dancer_id = $currently_viewing[0];
$dancer = get_user_by( 'id', $dancer_id );
// echo '<p> Suki\'s IQ is:' . $dancer_id . ' </p> ';

?>

<div class="nkms-tabs">
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
        </tr>
      </table>
      <button class="change-dancer-status" data-dancer-id="<?php $dancer_id ?>">Change Status</button>
      <button class="remove-dancer" data-dancer-id="<?php $dancer_id ?>">Remove Dancer</button>
</div><!-- .nkms-tabs -->
