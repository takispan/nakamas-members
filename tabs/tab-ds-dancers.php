<?php
/**
 * Display all dancers in dance school.
 */
?>

<div class="dancers-list">
  <h3 style="font-weight:300;">Dancers of <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name'] ?></span></h3>
  <?php
  if ( ! empty( $dance_school_dancers_list ) ) { ?>
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
    	foreach ($dance_school_dancers_list as $dancer_id) {
    		$dancer = get_userdata( $dancer_id );
        echo '<tr><td>' . $dancer_id . '</td><td><a class="single-dancer" data-ds-id="' . $dance_school->ID . '" data-dancer-id="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</a></td><td>' . $dancer->nkms_dancer_fields['dancer_age_category'] . '</td><td>' . $dancer->nkms_dancer_fields['dancer_level'] . '</td><td>' . $dancer->nkms_dancer_fields['dancer_status'] . '</td></tr>';
    	 }
      echo '</tbody></table>';
  } else {
  	echo "<p>" . $dance_school->nkms_dance_school_fields['dance_school_name'] . " does not have any registered dancers.";
    echo "<br>Add one by clicking the button below.</p>";
  }
  ?>
  <a class="button ds-add-dancers-link">Add Dancer</a>
</div><!-- .nkms-tabs -->
