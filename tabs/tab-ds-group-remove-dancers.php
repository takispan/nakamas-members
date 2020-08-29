<?php
/**
 * Remove Dancers from a group
 */
?>
<div class="nkms-tabs">
  <h3 style="font-weight:300;">Remove a dancer from <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h3>
  <form id="remove-group-dancer" method="post" action="" class="ajax">
    <p>
      <?php $group_dancers = $group->getDancers();
      if ( ! empty( $group_dancers ) ) : ?>
        <label for="dance_school_group_remove_dancers"><?php esc_html_e( 'Select a dancer to remove', 'nkms' ); ?></label>
  			<select id="remove_dancer_from_group">
          <option value="" selected disabled hidden>Select a dancer</option>
          <?php $group_dancers = $group->getDancers();
            foreach ( $group_dancers as $dancer_id ) {
              $dancer = get_user_by( 'id', $dancer_id );
              echo '<option value="' . $dancer_id . '">' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
            }
          ?>
        </select>
      <?php else :
        echo $group->getGroupName() . ' does not have any dancers.<br>';
      endif; ?>
    </p>
    <!-- info messages -->
    <p class="ajax-response"></p>
    <p>
      <input type="text" name="dance_school_group_remove_dancers_dance_school_id" value="<?php echo $dance_school->ID; ?>" hidden />
      <input type="submit" name="dance_school_group_remove_dancers_submit" value="Remove" class="nkms-btn" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
