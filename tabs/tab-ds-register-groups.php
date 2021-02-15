<?php
/**
 * Template Name: Dance School Register Groups
 *
 * Allow users to register groups for events.
 */
?>
<div>
  <h3 style="font-weight:300;">Register groups member of <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3>
  <h4>Events to register for</h4>
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
</div><!-- .nkms-tabs -->
