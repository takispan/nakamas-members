<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

$currently_viewing = get_user_meta(get_current_user_id(), 'currently_viewing', true);

$group_id = $currently_viewing[1];
$ds_groups_list_array = get_user_meta( get_current_user_id(), 'dance_school_groups_list', true );
$group = $ds_groups_list_array[$group_id];
$group_dancers = $group->getDancers();
?>

<div class="nkms-tabs">
    <h3 style="font-weight:300;">Dance Group <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span> for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
      <table>
        <tr>
          <th>Type</th>
          <th>Name</th>
          <th>Status</th>
        </tr>
        <tr>
          <td><?php echo $group->getType(); ?></td>
          <td><?php echo $group->getGroupName(); ?> </td>
          <td><?php echo $group->getStatus(); ?> </td>
        </tr>
      </table>
      <button class="change-group-status" data-group-id="<?php echo $group_id ?>">Change Status</button>
      <!-- <button class="disband-group" data-group-id="<?php //echo $group_id ?>">Disband Group</button> -->
      <h4 style="font-weight: 300;">Dancers of <span style="font-weight:600;"><?php echo $group->getGroupName(); ?></span></h4>
      <?php if ( !empty($group_dancers) ) { ?>
        <table>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Status</th>
          </tr>
          <tr>
            <?php
              foreach ($group_dancers as $key => $value) {
                echo '<td>' . $value . '</td><td></td><td></td>';
              }
            ?>
            <td><?php //var_dump($group_dancers); ?></td>
            <td><?php echo $group->getGroupName(); ?> </td>
            <td><?php echo $group->getStatus(); ?> </td>
          </tr>
        </table>
      <?php
      }
      else {
        echo '<p>' . $group->getGroupName() . ' does not have any dancers.</p>';
        echo '<p>You may add by clicking the button below.</p>';
      } ?>
      <button  onclick="dsOpenTab(event, 'ds-group-add-dancers')">Add Dancers</button>
      <?php if ( !empty($group_dancers) ) { ?>
        <button  onclick="dsOpenTab(event, 'ds-group-remove-dancers')">Remove Dancers</button>
      <?php } ?>
</div><!-- .nkms-tabs -->
