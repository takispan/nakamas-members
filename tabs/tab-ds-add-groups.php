<?php
/**
 * Add Groups to Dance School
 */
?>

<div>
  <div class="loader"><div class="lds-dual-ring"></div></div>
  <h3 style="font-weight:300;">Add a dance group for <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <form id="add-groups" method="post" action="" class="ajax">
    <p>
      <label for="dance_school_add_groups">Group name</label>
			<input id="add_group_name" type="text" name="dance_school_add_group_name" value="" class="regular-text" />
    </p>
    <p>
      <label for="dance_school_add_groups">Group type</label>
			<select id="add_group_type" type="text" name="dance_school_add_group_type" value="">
        <option value="" selected disabled hidden>Select group type</option>
        <option value="Duo">Duo</option>
        <option value="Parent/Child">Parent/Child</option>
        <option value="Trio/Quad">Trio/Quad</option>
        <option value="Team">Team</option>
        <option value="Parent Team">Parent Team</option>
        <option value="Mega Crew">Mega Crew</option>
      </select>
    </p>
    <p>
      <label for="add_group_change_level_category_of_group">Level category</label>
			<select id="add_group_change_level_category_of_group">
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
      <input type="hidden" name="dance_school_add_groups_submit_ds_id" value="<?php echo $dance_school->ID; ?>" />
      <input type="submit" name="dance_school_add_groups_submit" value="Add" class="button" />
    </p>
  </form>
</div><!-- .nkms-tabs -->
