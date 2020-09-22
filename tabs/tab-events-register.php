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
    <tr>
      <th>Title</th>
      <th>Category</th>
    </tr>
    <?php
      $products = wc_get_products( array( 'status' => 'publish' ) );
      foreach ( $products as $product ) {
        $cats = $product->get_category_ids();
        $category = get_term_by( 'id', $cats[0], 'product_cat' );
        if ( $category->name === 'Dancer Registration' ) {
          $category = get_term_by( 'id', $cats[1], 'product_cat' );
          echo '<tr><td><a href="' . get_permalink( $product->get_id() ) . '">' . $product->get_name() . '</a></td><td>' . $category->name . '</td></tr>';
        }
      }
    ?>
  </table>
