<?php
/**
 * Add Dancers to a group
 *
 * Allow users to add a dancer to one of the dance groups.
 */
 $group_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['group'];
 if ( $group_id ) {
   $group = $dance_school_groups_list[$group_id];
   $group_dancers = $group->getDancers();
?>

<div>
  <div class="loader"><div class="lds-dual-ring"></div></div>
  <h3 style="font-weight:300;">Add a dancer to <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h3>
  <form id="add-group-dancer" method="post" action="" class="ajax">
    <p>
      <?php if ( ! empty( $dance_school_dancers_list ) ) : ?>
        <label for="dance_school_group_add_dancers"><?php esc_html_e( 'Select a dancer to add', 'nkms' ); ?></label>
  			<select id="add_dancer_to_group">
          <option value="" selected disabled hidden>Select a dancer</option>
          <?php
            foreach ($dance_school_dancers_list as $id) {
              $dancer = get_user_by( 'id', $id );
              echo '<option value="' . $id . '">' . $id . ': ' . $dancer->first_name . ' ' . $dancer->last_name . '</option>';
            }
          ?>
        </select>
      <?php else :
        echo $dance_school->nkms_dance_school_fields['dance_school_name'] . ' does not have any dancers.<br>Add dancers by clicking the button below.';
      endif; ?>
    </p>
    <!-- info messages -->
    <p class="ajax-response"></p>
    <p>
      <input type="hidden" name="dance_school_group_add_dancers_dance_school_id" value="<?php echo $dance_school->ID; ?>" />
      <input type="submit" name="dance_school_group_add_dancers_submit" value="Add" class="button" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
<?php  } ?>
