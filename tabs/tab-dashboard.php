<?php
/**
 * Template Name: Dashboard
 *
 * Allow users to update their profiles from Frontend.
 *
 */
nkms_invitations();
?>
<div class="nkms-tabs">
  <h3 style="font-weight:300;">Dashboard of <span style="font-weight:600;"><?php echo $current_user->user_login; ?></span></h3>
  <div class="soar-mid">
		<h4><span>Soar ID</span> <?php echo $current_user->ID; ?></h4>

	</div>
  <?php
    if ( nkms_has_role( $current_user, 'guardian' ) ) :
      if ( ! $dancer_id ) : ?>
          <h4>Manage a dancer</h4>
          <p>You are not managing a dancer at the moment. Add their ID below in order to control their dashboard.</p>
          <form method="post" id="guardian-add-dancer-to-manage">
            <p><input type="hidden" name="guardian_id" value="<?php echo get_current_user_id(); ?>" />
            <p><input type="text" name="guardian_dancer_id_to_manage" value="" /></p>
            <p class="ajax-response"></p>
            <p><input type="submit" name="guardian_dancer_to_manage_submit" value="Submit" /></p>
          </form>
    <?php else : ?>
      <h3 style="font-weight:300;">Managing dashboard of <span style="font-weight:600;"><?php echo $dancer->user_login; ?></span></h3>
      <div class="soar-mid">
    		<h4><span>Soar ID</span> <?php echo $dancer->ID; ?></h4>
      </div>
    <?php endif; ?>
  <?php endif; ?>
  <?php if ( nkms_can_manage_dancer( $dancer_id, $current_user->ID ) ) : ?>
    <div class="dancer-invitatons">
      <?php
      // nkms_fix_user_meta();
      // IF $dancer has dancer_invites['guardian']
      $dancer_invites_guardian = $dancer->nkms_dancer_fields['dancer_invites']['guardian'];
      if ( ! empty( $dancer_invites_guardian ) ) {
        echo '<h4>Guardian invites</h4>';
        foreach ( $dancer_invites_guardian as $guardian_id ) {
          $guardian = get_userdata( $guardian_id ); ?>
          <div><p><?php echo $guardian->first_name . " " . $guardian->last_name; ?> wants to manage your account.</p>
            <form method="post" class="invite-btn">
              <input type="hidden" name="guardian_invite_dancer_id" value="<?php echo $dancer->ID; ?>" />
              <input type="hidden" name="guardian_invite_guardian_id" value="<?php echo $guardian_id; ?>" />
              <input type="submit" name="guardian_dancer_invite_accept" value="Accept" />
              <input type="submit" name="guardian_dancer_invite_decline" value="Decline" />
            </form>
          </div>
          <?php
        }
      }
      // else {
      //   echo '<h4>Guardian invites</h4>';
      //   echo '<p>You do not have any invites.</p>';
      // }

      // IF $dancer has dancer_invites['dance-school']
      $dancer_invites_dance_school = $dancer->nkms_dancer_fields['dancer_invites']['dance_school'];
      if ( ! empty( $dancer_invites_dance_school ) ) {
        echo '<h4>Dance School invites</h4>';
        foreach ( $dancer_invites_dance_school as $dance_school_id ) {
          $ds = get_userdata( $dance_school_id ); ?>
          <div class="dancer-invites"><p>You have been invited to join <?php echo $ds->nkms_dance_school_fields['dance_school_name']; ?>.</p>
            <form method="post" class="invite-btn">
              <input type="hidden" name="dancer_invite_dancer_id" value="<?php echo $dancer->ID; ?>" />
              <input type="hidden" name="dancer_invite_dance_school_id" value="<?php echo $ds->ID; ?>" />
              <input type="submit" name="dancer_invite_accept" value="Accept" />
              <input type="submit" name="dancer_invite_dismiss" value="Decline" />
            </form>
          </div>
          <?php
        }
      }
      // else {
      //   echo '<h4>Dance School invites</h4>';
      //   echo '<p>You do not have any invites.</p>';
      // }

      // Dancer requests to join dance school
      if ( nkms_can_manage_dancer( $dancer->ID, $current_user->ID ) ) :
        $part_of_ds = $dancer->nkms_dancer_fields['dancer_part_of'];
        if ( empty( $part_of_ds ) ) : ?>
          <h4>Request to join a dance school</h4>
          <?php
          $dance_schools_list = get_users( array( 'role__in' => 'dance-school' ) );
          // sort($dance_schools_list);
          // print_r($dance_schools_list);
          ?>
          <form method="post" id="dancer_requests_to_join_dance_school" class="invite-btn">
            <p><select id="dancer_requests_to_join_dance_school_id" name="dancer_request_to_join_dance_school_id"><option selected disabled hidden>Select dance school</option>
            <?php
            foreach ( $dance_schools_list as $ds ) {
              if ( ! empty( $ds->nkms_dance_school_fields['dance_school_name'] ) ) {
                echo '<option value="' . $ds->ID . '">' . $ds->nkms_dance_school_fields['dance_school_name'] . '</option>';
              }
            }
            ?>
            </select></p>
            <div class="ajax-response"></div>
            <input type="hidden" name="dancer_request_to_join_dancer_id" value="<?php echo $dancer->ID; ?>" />
            <input type="submit" name="dancer_request_to_join_submit" value="Request" />
          </form>
        <?php else :
          $dance_school_id = $part_of_ds[0];
          $dance_school = get_userdata( $dance_school_id ); ?>
          <p>Dancer representing <span><?php echo $dance_school->nkms_dance_school_fields['dance_school_name'] ?></span></p>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  <?php endif; ?>

	<h4>Recent orders</h4>
  <?php
  if ( class_exists( 'WooCommerce' ) ) :
    $my_orders_columns = apply_filters( 'woocommerce_my_account_my_orders_columns', array(
      'order-number'  => __( 'ID', 'woocommerce' ),
      'order-date'    => __( 'Date', 'woocommerce' ),
      'order-total'   => __( 'Packages', 'woocommerce' ),
      'order-total'   => __( 'Price', 'woocommerce' ),
      'order-status'  => __( 'Status', 'woocommerce' ),
    ) );

    // Get orders by customer ID.
    $customer_orders = wc_get_orders( array( 'customer_id' => $current_user->ID ) );
  	if ( $customer_orders ) : ?>
			<table class="shop_table shop_table_responsive my_account_orders">
        <thead>
          <tr>
            <?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
              <th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php
          foreach ( $customer_orders as $customer_order ) :
            $order      = wc_get_order( $customer_order ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
            $item_count = $order->get_item_count();
          ?>
            <tr class="order">
              <?php foreach ( $my_orders_columns as $column_id => $column_name ) : ?>
                <td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
                  <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                    <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                  <?php elseif ( 'order-number' === $column_id ) : ?>
                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
                      <?php echo _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </a>

                  <?php elseif ( 'order-date' === $column_id ) : ?>
                    <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>

                  <?php elseif ( 'order-status' === $column_id ) : ?>
                    <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>

                  <?php elseif ( 'order-total' === $column_id ) : ?>
                    <?php
                      /* translators: 1: formatted order total 2: total order items */
                      printf( _n( '%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>

                  <?php elseif ( 'order-actions' === $column_id ) : ?>
                  <?php
                    $actions = wc_get_account_orders_actions( $order );
                    if ( ! empty( $actions ) ) {
                      foreach ( $actions as $key => $action ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited
                        echo '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a>';
                      }
                    }
                  ?>
                  <?php endif; ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
  		</table>
    <?php
    else :
      echo 'No orders have been made yet.';
    endif;
  else :
      echo 'You need WooCommerce installed & activated';
  endif; ?>
  <p style="margin-top: 55px;">
    <a href="<?php echo wp_logout_url( home_url() ); ?>"><button>Logout</button></a>
  </p>
</div><!-- .nkms-tabs -->
