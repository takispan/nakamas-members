<?php
/**
 * Add / Remove Dancers from Dance School
 *
 * Allow users manage dance school's dancer list.
 */

 //Button to add dancers from input
 if (isset($_POST['dance_school_add_dancers_submit'])) {
   if ( ! empty( $_POST['dance_school_add_dancers'] ) ) {
     $data_entry = get_user_meta($current_user->ID, 'dance_school_dancers_list', true);
     if (!is_array($data_entry)) {
       $data_entry = [];
     }
     $entry = sanitize_text_field($_POST['dance_school_add_dancers']);
     if (!in_array($entry, $data_entry)) {
       array_push($data_entry, $entry);
     }
     update_user_meta($current_user->ID, 'dance_school_dancers_list', $data_entry);
   }
 }

?>


<div class="nkms-tabs">
    <h3 style="font-weight:300;">Add / Remove dancers for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <div id="nkms-alert"></div>
    <form method="post" id="add-remove-dancers" action="<?php the_permalink(); ?>">
      <p>
        <label for="dance_school_add_dancers"><?php esc_html_e( 'Add a dancer by ID', 'nkms' ); ?></label>
  			<input type="text" name="dance_school_add_dancers" value="" class="regular-text" />
      </p>
      <p>
        <input type="submit" name="dance_school_add_dancers_submit" value="Add" />
      </p>
      <?php
        //$ds = new DanceGroup("Teeneh team");
      ?>
    </form>
</div><!-- .nkms-tabs -->
