<?php
/**
 * Template Name: Dance School Register Groups
 *
 * Allow users to register groups for events from Frontend.
 */

?>

<div class="nkms-tabs">
    <h3 style="font-weight:300;">Register groups for <span style="font-weight:600;"><?php echo $ds_name; ?></span></h3></br>
    <h4>Events to register for</h4>
    <table>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Category</th>
      </tr>
      <?php
        $products = wc_get_products( array( 'status' => 'publish' ) );
        foreach ($products as $key => $product) {
          $cats = $product->get_category_ids();
          $category = get_term_by( 'id', $cats[0], 'product_cat' );
          echo '<tr><td>' . $product->get_id() . '</td><td><a href="' . get_permalink($product->get_id()) . '">' . $product->get_name() . '</a></td><td>' . $category->name . '</td></tr>';
        }
      ?>
    </table>
</div><!-- .nkms-tabs -->