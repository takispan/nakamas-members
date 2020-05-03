<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$ds_dancers_list_array = get_user_meta( $current_user->ID, 'dance_school_dancers_list', true );
if (!is_array($ds_dancers_list_array)) { $ds_dancers_list_array = []; }
?>


<div class="nkms-tabs">
    <h3 style="font-weight:300;">Dancers for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <?php
    if ( ! empty( $ds_dancers_list_array ) ) { ?>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Status</th>
        </tr>
      <?php
    	foreach ($ds_dancers_list_array as $key => $value) {
    		$user_info = get_userdata($value);
        echo '<tr><td>' . $value . '</td><td><button class="single-dancer" data-dancer-id="' . $value . '">' . $user_info->first_name . ' ' . $user_info->last_name . '</button></td><td></td></tr>';
    	 }
      echo '</table>';
    } else {
    	echo $ds_name . " does not have any registered dancers.";
    }
    ?>
    <button onclick="dsOpenTab(event, 'ds-add-remove-dancers')">Add Dancers</button>
</div><!-- .nkms-tabs -->
