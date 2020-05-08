<?php
/**
 * Template Name: Dance School
 *
 * Allow users to update their profiles from Frontend.
 */

?>


<div class="nkms-tabs">
    <h3 style="font-weight:300;">Dance Groups for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <?php
      $array_dancegroups = get_user_meta($current_user->ID, 'ds_groups_list', true);
      if (!is_array($array_dancegroups)) {
				$array_dancegroups = [];
			}
      //$ds->addDancer('1');

      /*
       *
       * List dancers in Group duo
       */
      if ( ! empty( $array_dancegroups ) ) { ?>
        <table>
          <tr>
            <th>ID</th>
            <th>Group Name</th>
            <th>Status</th>
          </tr>
        <?php
        foreach ($array_dancegroups as $key => $value) {
          echo '<tr><td>' . $value . '</td><td><button class="single-group" data-group-id="' . $value . '">' . $user_info->first_name . ' ' . $user_info->last_name . '</button></td><td></td></tr>';

          echo "<tr><td>" . $key . "</td><td>" . $value->getGroupName() . "</td></tr>";
        }
        echo '</table>';
      } else {
        print_r($array_dancegroups);
        echo "This Dance School does not have any groups.<br/>";
      }

      /*
       *
       * Create a dance group
       */ ?>
       <form method="post" id="dance-group" action="<?php the_permalink(); ?>">
           <p class="form-add-group">
               <label for="add-group">Group Name</label>
               <input class="text-input" name="add-group" type="text" id="add-group" value="" />
           </p><!-- .form-username -->
           <p class="form-submit">
               <?php //echo $referer; ?>
               <input name="add_group" type="submit" id="add_group" class="submit button" value="Add Group" />
               <?php wp_nonce_field( 'update-user_'. $current_user->ID ) ?>
           </p><!-- .form-submit -->
       </form>

       <?php
      /*
       *
       * List dancers in Group duo
       */
      $select_ds_group;
      foreach ($array_dancegroups as $key => $value) {
        if ( $value->getGroupName() == $_POST['select-group'] && $_POST['select-group'] != 'select-group' ) {
          $select_ds_group = $value;
        }
      }
      if ( ! empty( $select_ds_group ) ) { ?>
        <table>
          <tr>
            <th>Group Name</th>
            <td colspan="2"><?php echo $select_ds_group->getGroupName(); ?></td>
          </tr>
          <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
          </tr>
        <?php
        foreach ($select_ds_group->getDancers() as $key => $value) {
          $user_info = get_userdata($value);
          echo "<tr><td>" . $value . "</td><td>" . $user_info->first_name . "</td><td>" . $user_info->last_name . "</td></tr>";
        }
        echo '</table>';
      } else {
        echo "This Dance Group does not have any dancers.<br/>";
      }

      //print_r($ds);
      /*
       * HTML
       * Add dancer in Group
       */ ?>
      <form method="post" id="adduser" action="<?php the_permalink(); ?>">
          <p class="form-select-group">
              <label for="select-group">Select a group</label>
              <select name="select-group">
    						<?php
    						echo "<option>Select a group</option>";
    						foreach ($array_dancegroups as $key => $value) {
    							echo "<option>" . $value->getGroupName() . "</option>"; //. $user_info->first_name . "</td><td>" . $user_info->last_name . "</td></tr>";
    						}
    						?>
    					</select>
          </p>
          <p class="form-add-dancer">
              <label for="add-dancer">Add a Dancer</label>
              <input class="text-input" name="add-dancer" type="text" id="add-group" value="" />
          </p><!-- .form-username -->
          <p class="form-submit">
              <?php //echo $referer; ?>
              <input name="add_dancer" type="submit" id="add_dancer" class="submit button" value="Add Dancer" />
              <?php wp_nonce_field( 'update-user_'. $current_user->ID ) ?>

          </p><!-- .form-submit -->
      </form>

    <?php
    /*
     * Save array of group objects
     * Save dancers in group object in db
     */
     if (isset($_POST['add_group'])) {
       if ( ! empty( $_POST['add-group'] ) ) {
         $team = new DanceGroup($_POST['add-group']);
         array_push($array_dancegroups, $team);
         update_user_meta($current_user->ID, 'ds_groups_list', $array_dancegroups );
         echo 'suki is awesome!';
       }
     }
     if (isset($_POST['add_dancer'])) {
       if ( ! empty( $_POST['add-dancer'] ) && ! empty ($_POST['select-group'] ) ) {
         // $ds->addDancer($_POST['add-dancer']);
         // update_user_meta($current_user->ID, 'ds_group', $ds );
         foreach ($array_dancegroups as $key => $value) {
           if ( $value->getGroupName() == $_POST['select-group'] ) {
             $value->addDancer($_POST['add-dancer']);
           }
         }
         update_user_meta($current_user->ID, 'ds_groups_list', $array_dancegroups );
         echo 'teeneh is awesome!';
       }
     }
    ?>
</div><!-- .nkms-tabs -->
