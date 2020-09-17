<?php
/**
 * Display single group tab.
 */

$group_id = $dance_school->nkms_dance_school_fields['dance_school_currently_viewing']['group'];
if ( $group_id ) {
  $group = $dance_school_groups_list[$group_id];
  $group_dancers = $group->getDancers();
?>

<div class="ds-single-group">
  <div class="loader"><div class="lds-dual-ring"></div></div>
  <h3 style="font-weight:300;">Dance Group <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span> in <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <div class="group-details">
    <p><span>Type</span><?php echo $group->getType(); ?></p>
    <p><span>Name</span><?php echo $group->getGroupName(); ?></p>
    <p><span>Status</span><?php echo $group->getStatus(); ?></p>
    <p class="ajax-response"></p>
    <button class="change-group-status button" data-ds-id="<?php echo $dance_school_id; ?>" data-group-id="<?php echo $group_id; ?>">Change Status</button>
    <button class="remove-group button" data-ds-id="<?php echo $dance_school_id; ?>" data-group-id="<?php echo $group_id; ?>">Remove Group</button>
    <h4 style="font-weight: 300;">Dancers of <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h4>
    <div class="group-dancers">
      <p>
        <?php $group_dancers = $group->getDancers();
        if ( ! empty( $group_dancers ) ) {
          $print_dancers = "";
          foreach ( $group_dancers as $group_dancer_id ) {
            $group_dancer = get_user_by( 'id', $group_dancer_id );
            $print_dancers .= '<p>' . $group_dancer_id . ': ' . $group_dancer->first_name . ' ' . $group_dancer->last_name . '<br><span>Status</span>' . $group_dancer->nkms_dancer_fields['dancer_status'] . '</p>';
          }
          echo $print_dancers;
        }
        else {
          echo '<p>' . $group->getGroupName() . ' does not have any dancers.<br>You may add by clicking the button below.</p>';
        }
        ?>
      </p>
    </div>
    <a class="button ds-group-add-dancers-link">Add Dancers</a>
    <?php if ( ! empty( $group_dancers ) ) { ?>
      <a class="button ds-group-remove-dancers-link">Remove Dancers</a>
    <?php } ?>
  </div>
</div><!-- .nkms-tabs -->
<?php }
else {
  echo '<p>No dance groups.</p>';
}
?>
