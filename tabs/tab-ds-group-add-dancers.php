<?php
/**
 * Add Dancers to a group
 *
 * Allow users to add a dancer to one of the dance groups.
 */
 $currently_viewing = get_user_meta(get_current_user_id(), 'currently_viewing', true);
 $group_id = $currently_viewing[1];
 $ds_groups_list_array = get_user_meta( get_current_user_id(), 'dance_school_groups_list', true );
 if ( ! empty($ds_groups_list_array) ) {
   $group = $ds_groups_list_array[$group_id];
?>

<div class="nkms-tabs">
  <h3 style="font-weight:300;">Add a dancer for <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h3></br>
  <form id="add-group-dancer" method="post" action="" class="ajax">
    <p>
      <?php if ( is_array($ds_groups_list_array) ) : ?>
      <label for="dance_school_group_add_dancers"><?php esc_html_e( 'Select a dancer to add', 'nkms' ); ?></label>
			<select id="add_dancer_to_group">
        <?php
          foreach ($ds_dancers_list_array as $key => $id) {
            $dncr = get_user_by( 'id', $id );
            echo '<option value="' . $id . '">' . $id . ': ' . $dncr->first_name . ' ' . $dncr->last_name . '</option>';
          }
        ?>
      </select>
      <?php else :
        echo $ds_name . ' does not have any dancers. Add dancers to the dance school first.';
      endif; ?>
    </p>
    <!-- info messages -->
    <p id="ajax-groups-add-dancers"></p>
    <p>
      <input type="submit" name="dance_school_group_add_dancers_submit" value="Add" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
<?php }
else {
  echo '<p>No dance groups.</p>';
}
