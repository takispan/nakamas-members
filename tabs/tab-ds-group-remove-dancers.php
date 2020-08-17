<?php
/**
 * Remove Dancers from a group
 */
 $currently_viewing = get_user_meta(get_current_user_id(), 'currently_viewing', true);
 $group_id = $currently_viewing[1];
 $ds_groups_list_array = get_user_meta( get_current_user_id(), 'dance_school_groups_list', true );
 if ( ! empty($ds_groups_list_array) ) {
   $group = $ds_groups_list_array[$group_id];
   $group_dancers = $group->getDancers();
 ?>
<div class="nkms-tabs">
  <h3 style="font-weight:300;">Remove a dancer from <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h3></br>
  <form id="remove-group-dancer" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_group_remove_dancers"><?php esc_html_e( 'Select a dancer to remove', 'nkms' ); ?></label>
			<select id="remove_dancer_from_group">
        <?php
          foreach ($group_dancers as $key => $value) {
            $dncr = get_user_by( 'id', $value );
            echo '<option value="' . $value . '">' . $dncr->first_name . ' ' . $dncr->last_name . '</option>';
          }
        ?>
      </select>
    </p>
    <!-- info messages -->
    <p id="ajax-groups-remove-dancers"></p>
    <p>
      <input type="submit" name="dance_school_group_remove_dancers_submit" value="Remove" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
<?php }
else {
  echo '<p>No dance groups.</p>';
}
?>
