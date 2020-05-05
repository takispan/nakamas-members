<?php
/**
 * Add / Remove Dancers from Dance School
 *
 * Allow users manage dance school's dancer list.
 */

 //Button to add dancers from input
 // if (isset($_POST['dance_school_add_dancers_submit'])) {
 //   if ( ! empty( $_POST['dance_school_add_dancers'] ) ) {
 //     $dancer2add = get_user_by( 'id', $_POST['dance_school_add_dancers'] );
 //     if ( nkms_has_role( $dancer2add, 'dancer' ) ) {
 //       $data_entry = get_user_meta($current_user->ID, 'dance_school_dancers_list', true);
 //       if (!is_array($data_entry)) {
 //         $data_entry = [];
 //       }
 //       $entry = sanitize_text_field($_POST['dance_school_add_dancers']);
 //       if (!in_array($entry, $data_entry)) {
 //         array_push($data_entry, $entry);
 //       }
 //       update_user_meta($current_user->ID, 'dance_school_dancers_list', $data_entry);
 //     }
 //   }
 // }

?>


<div class="nkms-tabs">
    <h3 style="font-weight:300;">Add / Remove dancers for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <form id="add-remove-dancers" method="post" action="" class="ajax">
      <p>
        <label for="dance_school_add_dancers"><?php esc_html_e( 'Add a dancer by ID', 'nkms' ); ?></label>
  			<input id="add_dancer_to_ds" type="text" name="dance_school_add_dancers" value="" class="regular-text" />
      </p>
      <!-- info messages -->
      <p class="success_msg" style="display: none">Dancer added successfully!</p>
      <p class="error_msg" style="display: none">An error occured, dancer not added.</p>
      <p>
        <input type="submit" name="dance_school_add_dancers_submit" value="Add" />
      </p>
      <?php
        //$ds = new DanceGroup("Teeneh team");
      ?>
    </form>
    <!-- <p><?php //echo $ ?></p> -->
</div><!-- .nkms-tabs -->
