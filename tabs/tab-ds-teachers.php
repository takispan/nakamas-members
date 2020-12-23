<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */
?>

<div class="teachers-list">
  <h3 style="font-weight:300;">Teachers of <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <?php
  if ( ! empty( $dance_school_teachers_list ) ) { ?>
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      <?php
    	foreach ( $dance_school_teachers_list as $teacher_id ) {
        $teacher = get_userdata( $teacher_id );
        echo '<tr><td>' . $teacher_id . '</td><td>' . $teacher->first_name . ' ' . $teacher->last_name . '</td><td>' . $teacher->nkms_dancer_fields['dancer_status'] . '</td></tr>';
      }
      echo '</tbody></table>';
    } else {
      echo "<p>" . $dance_school->nkms_dance_school_fields['dance_school_name'] . " does not have any registered teachers.<br>Add one by clicking the button below.</p>";
    }
    ?>
  <a class="button ds-add-teachers-link">Add Teacher</a>
</div><!-- .nkms-tabs -->
