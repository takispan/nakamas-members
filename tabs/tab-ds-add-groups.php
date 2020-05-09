<?php
/**
 * Add / Remove Dancers from Dance School
 *
 * Allow users manage dance school's dancer list.
 */
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Add a dance group for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
  <form id="add-groups" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_add_groups"><?php esc_html_e( 'Group name', 'nkms' ); ?></label>
			<input id="add_group_name" type="text" name="dance_school_add_group_name" value="" class="regular-text" />
    </p>
    <p>
      <label for="dance_school_add_groups"><?php esc_html_e( 'Group name', 'nkms' ); ?></label>
			<select id="add_group_type" type="text" name="dance_school_add_group_type" value="">
        <option value="Duo" selected>Duo</option>
        <option value="Parent/Child">Parent/Child</option>
        <option value="Trio/Quad">Trio/Quad</option>
        <option value="Team">Team</option>
        <option value="Parent Team">Parent Team</option>
        <option value="Super Crew">Super Crew</option>
      </select>
    </p>
    <!-- info messages -->
    <p class="success_msg" style="display: none">Group added successfully!</p>
    <p class="error_msg" style="display: none">An error occured, group not added.</p>
    <p>
      <input type="submit" name="dance_school_add_groups_submit" value="Add" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
