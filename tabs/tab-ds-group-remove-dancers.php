<?php
/**
 * Add / Remove Dancers from Dance School
 *
 * Allow users manage dance school's dancer list.
 */
 $currently_viewing = get_user_meta(get_current_user_id(), 'currently_viewing', true);

 $group_id = $currently_viewing[1];
 $ds_groups_list_array = get_user_meta( get_current_user_id(), 'dance_school_groups_list', true );
 $group = $ds_groups_list_array[$group_id];
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Add a dancer for <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h3></br>
  <form id="remove-group-dancer" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_group_remove_dancers"><?php esc_html_e( 'Select a dancer to remove', 'nkms' ); ?></label>
			<input id="add_dancer_to_group" type="text" name="dance_school_group_remove_dancers" value="" class="regular-text" />
    </p>
    <!-- info messages -->
    <p class="success_msg" style="display: none">Dancer removed successfully!</p>
    <p class="error_msg" style="display: none">An error occured, dancer was not removed.</p>
    <p>
      <input type="submit" name="dance_school_group_remove_dancers_submit" value="Add" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
