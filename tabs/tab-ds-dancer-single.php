<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

if(isset($_GET['d'])){
  //$dancer_id =  $_GET['dancer'];
  $dancer = get_user_by( 'id', $_GET['d'] );
}
?>


<div class="nkms-tabs">
    <h3 style="font-weight:300;">Dancer <span style="font-weight:600;"><?php echo $dancer->first_name . ' ' . $dancer->last_name; ?> for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
        </tr>
        <tr>
          <!-- <th>Status</th> -->
          <td><?php echo $_GET['d']; ?></td>
          <td></td>
        </tr>
      </table>

    <button onclick="dsOpenTab(event, 'ds-add-remove-dancers')">Add Dancers</button>
</div><!-- .nkms-tabs -->
