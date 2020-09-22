<?php
/**
 * Change group level category
 *
 */
 $group_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['group'];
 if ( $group_id ) {
   $group = $dance_school_groups_list[$group_id];
?>

<div>
  <div class="loader"><div class="lds-dual-ring"></div></div>
  <h3 style="font-weight:300;">Change level category of <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h3>
  <form id="change-group-level-category" method="post" action="" class="ajax">
    <p>
      <label for="change_level_category_of_group"><?php esc_html_e( 'Select a level category', 'nkms' ); ?></label>
			<select id="change_level_category_of_group">
        <option value="" selected disabled hidden>Select a level category</option>
        <option value="Newcomer">Newcomer</option>
        <option value="Novice">Novice</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Advanced">Advanced</option>
      </select>
    </p>
    <!-- info messages -->
    <p class="ajax-response"></p>
    <p>
      <input type="hidden" name="dance_school_group_change_level_category_dance_school_id" value="<?php echo $dance_school->ID; ?>" />
      <input type="submit" name="dance_school_group_change_level_category_submit" value="Change" class="button" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
<?php  } ?>
