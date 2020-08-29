<?php
/**
 * Template Name: Dance School Register Groups
 *
 * Allow users to register groups for events.
 */
?>
<div class="nkms-tabs">
  <h3 style="font-weight:300;">Register groups part of <span style="font-weight:600;"><?php echo $dance_school->nkms_dance_school_fields['dance_school_name']; ?></span></h3></br>
  <h4>Events to register for</h4>
  <table>
    <tr>
      <th>Title</th>
      <th>Category</th>
    </tr>
    <?php
      $products = wc_get_products( array( 'status' => 'publish' ) );
      foreach ( $products as $product ) {
        $cats = $product->get_category_ids();
        $category = get_term_by( 'id', $cats[0], 'product_cat' );
        if ( $category->name === 'Dancer Registration' ) { $category = get_term_by( 'id', $cats[1], 'product_cat' ); }
        echo '<tr><td><a href="' . get_permalink($product->get_id()) . '">' . $product->get_name() . '</a></td><td>' . $category->name . '</td></tr>';
      }
    ?>
  </table>
</div><!-- .nkms-tabs -->
