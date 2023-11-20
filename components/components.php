<?php
function orderTables($status)
{
  global $_SESSION, $conn;

  $orderData = getTableWithWhere("order_tbl", "user_id='$_SESSION[userId]' and status='$status' ORDER BY id DESC");

  $element = "";
  $totalDiscount = 0.00;

  if (count($orderData) > 0) {
    foreach ($orderData as $order) {
      $badgeColor = "";

      switch ($order->status) {
        case "pending":
          $badgeColor = "warning";
          break;
        case "preparing":
          $badgeColor = "primary";
          break;
        case "to claim":
          $badgeColor = "info";
          break;
        case "claimed":
          $badgeColor = "success";
          break;
        case "canceled":
        case "declined":
          $badgeColor = "danger";
          break;
        default:
          $badgeColor = "secondary";
          null;
      }

      $products = "";
      $cartData = getTableWithWhere("cart", "user_id='$_SESSION[userId]' and order_id='$order->id'");

      foreach ($cartData as $cart) {
        $inventoryQStr = mysqli_query(
          $conn,
          "SELECT 
          ig.id AS 'inventory_id',
          ig.medicine_id,
          mp.dosage,
          mp.medicine_name,
          ig.price_id AS 'price_id',
          (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
          FROM inventory_general ig
          LEFT JOIN medicine_profile mp
          ON mp.id = ig.medicine_id
          WHERE ig.id = '$cart->inventory_id'
      "
        );
        $inventory = mysqli_fetch_object($inventoryQStr);
        $discountPrice = getDiscounted($inventory->inventory_id, $inventory->price);

        $totalDiscount += doubleval($discountPrice) * intval($cart->quantity);

        $products .= "
        <tr>
          <td class='product-thumbnail'>
            <img src='" . (getMedicineImage($inventory->medicine_id)) . "' alt='Image' class='img-fluid'>
          </td>
          <td class='product-name'>
            <h2 class='h5 text-black'>" . ($inventory->medicine_name) . "</h2>
          </td>
          <td>" . ($inventory->dosage) . "</td>
          <td> " . ("₱ " . number_format($inventory->price, 2, '.', ',')) . "</td>
          <td> " . ("₱ " . number_format($discountPrice, 2, '.', ',')) . "</td>

          <td>$cart->quantity</td>
        </tr>
      ";
      }

      $element .= "
      <div class='card mb-4'>
        <div class='card-header'>
          <div class='row'>
            <div class='col-md-12'>
              <p class='text-danger'>
                Regular price is only applicable to NON PWD and SENIOR CITIZEN without ID and booklet.
              </p>
            </div>
          </div>
          <div class='row'>
            <div class='col-md-6'>
              <h5 class='card-title'>
                Order #: $order->order_number
              </h5>
            </div>
            <div class='col-md-6'>
              <h6 class='card-title text-md-right'>
                Date Ordered: " . (date("m-d-Y", strtotime($order->date_ordered))) . "
              </h6>
            </div>
          </div>
          <div class='row'>
            <div class='col-md-6'>
              <h5 class='card-title'>
                Status:
                <span class='badge badge-$badgeColor'>
                " . (ucfirst($order->status)) . "
              </span>
              </h5>
            </div>
          </div>
          " . ($order->note ? "
          <div class='row'>
              <div class='col-md-6'>
                <h5 class='card-title'>
                  Note: $order->note
                </h5>
              </div>
            </div>
          " : "") . "
        </div>
        <div class='card-body'>
          <div class='site-blocks-table'>
            <table class='table table-bordered'>
              <thead>
                <tr>
                  <th class='product-thumbnail'>Image</th>
                  <th class='product-name'>Product</th>
                  <th class='product-name'>Dosage</th>
                  <th class='product-price'>Regular</th>
                  <th class='product-discount'>Discounted</th>
                  <th class='product-quantity'>Item(s)</th>
                </tr>
              </thead>
              <tbody>
                  " . ($products) . "
              </tbody>
            </table>
          </div>
        </div>
        <div class='card-footer'>
          <div class='row'>
            <div class='col-md-6'>
              <span class='text-black'>
                Regular Price Total: " . ("₱ " . number_format($order->overall_total, 2, '.', ',')) . "
              </span>
              <br>
              <span class='text-black'>
                Discounted Price Total: " . ("₱ " . number_format($totalDiscount, 2, '.', ',')) . "
              </span>
            </div>
            <div class='col-md-6 text-lg-right text-md-center'>
              " . ($order->status == "pending" ? "
              <button type='submit' class='btn btn-danger btn-md m-1' onclick='handleCancelOrder($order->id)'>
              Cancel Order
            </button>" : "") . "
            </div>
          </div>
        </div>
      </div>
    ";
    }
  } else {
    $element .= "<h6 class='m-4 text-center'>No Result Found</h6>";
  }

  return $element;
}
