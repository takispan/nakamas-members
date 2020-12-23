<?php
/**
 * Template Name: Events to Register
 *
 * Allow users to register for events.
 */
?>
<div>
  <h3>Events to register for</h3>
  <table>
    <thead>
      <tr>
        <th>Event</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $products = wc_get_products( array( 'status' => 'publish' ) );
      foreach ( $products as $product ) {
        $product_categories = wc_get_product_category_list( $product->get_id() );
        if ( strpos( $product_categories, 'Dancer Registration' ) !== false ) {
          echo '<tr><td><a href="' . get_permalink( $product->get_id() ) . '">' . $product->get_name() . '</a></td></tr>';
        }
      }
    ?>
    </tbody>
  </table>
