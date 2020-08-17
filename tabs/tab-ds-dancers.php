<?php
/**
 * Display all dancers in dance school.
 */

$ds_dancers_list_array = get_user_meta( get_current_user_id(), 'dance_school_dancers_list', true );
?>

<div class="nkms-tabs dancers-list">
  <h3 style="font-weight:300;">Dancers for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <?php
  if ( ! empty( $ds_dancers_list_array ) ) { ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Category</th>
        <th>Experience</th>
        <th>Status</th>
      </tr>
    <?php
  	foreach ($ds_dancers_list_array as $key => $dancer_id) {
  		$user_info = get_userdata($dancer_id);
      $active_status = "Active";
      ( ! get_user_meta($dancer_id, 'active', true) ) ? $active_status = "Inactive" : $active_status = "Active";
      echo '<tr><td>' . $dancer_id . '</td><td><a data-toggle="tab" href="#ds-dancer-single" class="single-dancer" data-dancer-id="' . $dancer_id . '">' . $user_info->first_name . ' ' . $user_info->last_name . '</a></td><td>' . $user_info->age_category . '</td><td>' . $user_info->experience . '</td><td>' . $active_status . '</td></tr>';
  	 }
    echo '</table>';
  } else {
  	echo "<p>" . $ds_name . " does not have any registered dancers.</p>";
    echo "<p>Add one by clicking the button below.</p>";
  }
  ?>
  <a data-toggle="tab" href="#ds-add-dancers" class="nkms-btn">Add Dancer</a>
</div><!-- .nkms-tabs -->
