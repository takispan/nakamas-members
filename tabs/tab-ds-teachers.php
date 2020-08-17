<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */
$ds_teachers_list_array = get_user_meta( get_current_user_id(), 'dance_school_teachers_list', true );
?>

<div class="nkms-tabs teachers-list">
  <h3 style="font-weight:300;">Teachers for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <?php
  if ( ! empty( $ds_teachers_list_array ) ) { ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Status</th>
      </tr>
    <?php
  	foreach ($ds_teachers_list_array as $key => $value) {
  		$user_info = get_userdata($value);
      $active_status = "Active";
      ( ! get_user_meta($value, 'active', true ) ) ? $active_status = "Inactive" : $active_status = "Active";
      echo '<tr><td>' . $value . '</td><td><a data-toggle="tab" href="#ds-teacher-single" class="single-teacher" data-teacher-id="' . $value . '">' . $user_info->first_name . ' ' . $user_info->last_name . '</a></td><td>' . $active_status . '</td></tr>';
  	 }
    echo '</table>';
  } else {
  	echo "<p>" . $ds_name . " does not have any registered teachers.</p>";
    echo "<p>Add one by clicking the button below.</p>";
  }
  ?>
  <a data-toggle="tab" href="#ds-add-teachers" class="nkms-btn">Add Teacher</a>
</div><!-- .nkms-tabs -->
