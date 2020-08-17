<?php
/**
 * Template Name: Dashboard
 *
 * Allow users to update their profiles from Frontend.
 *
 */
?>
<div class="nkms-tabs">
  <h3 style="font-weight:300;">Dashboard of <span style="font-weight:600;"><?php echo $current_user->user_login ?></span></h3></br>
  <div class="soar-mid">
		<h4><span>Soar</span> membership ID</h4>
		<h3><?php echo $current_user->ID; ?></h3>
	</div>

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
    <a href="<?php echo wp_logout_url(home_url()); ?>"><button>Logout</button></a>
  </p>
</div><!-- .nkms-tabs -->
