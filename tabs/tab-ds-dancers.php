<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$ds_dancers_list_array = get_user_meta( get_current_user_id(), 'dance_school_dancers_list', true );
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
      $active_status = "Active";
      if (!get_user_meta($value, 'active', true)) { $active_status = "Inactive"; }
      echo '<tr><td>' . $value . '</td><td><a data-toggle="tab" href="#ds-dancer-single" class="single-dancer" data-dancer-id="' . $value . '">' . $user_info->first_name . ' ' . $user_info->last_name . '</a></td><td>' . $active_status . '</td></tr>';
  	 }
    echo '</table>';
  } else {
  	echo "<p>" . $ds_name . " does not have any registered dancers.</p>";
    echo "<p>Add one by clicking the button below.</p>";
  }
  ?>
  <a data-toggle="tab" href="#ds-add-dancers" class="nkms-btn">Add Dancer</a>
  <!-- <button onclick="dsOpenTab(event, 'ds-add-dancers')">Add Dancer</button> -->
</div><!-- .nkms-tabs -->
