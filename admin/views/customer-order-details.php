<?php
include("../../backend/nodes.php");
if (!$isLogin) {
  header("location: ../");
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include("../components/header.php"); ?>

<body class="">

  <?php include("../components/side-nav.php"); ?>
  <?php include("../components/header-nav.php"); ?>

  <div class="pcoded-main-container">
    <div class="pcoded-wrapper">
      <div class="pcoded-content">
        <div class="pcoded-inner-content">
          <div class="main-body">
            <div class="page-wrapper">
              <!-- [ breadcrumb ] start -->
              <?php include("../components/page-header.php") ?>
              <!-- [ breadcrumb ] end -->

              <!-- [ Main Content ] start -->

              <div class="row">
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header p-2 ml-2 mt-2">
                      <button type="button" onclick="return window.history.back()" class="btn btn-secondary btn-sm float-right">
                        Go Back
                      </button>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <table id="customerOrderDetails" class="table table-hover">
                            <thead>
                              <tr>
                                <th>Order #</th>
                                <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th>Date Ordered</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $orderDetails = getTableWithWhere("order_details", "order_id='$_GET[id]'");
                              $orderTotal = 0;
                              foreach ($orderDetails as $detail) :
                                $orderTotal += $detail->order_subtotal;
                                $order = getSingleDataWithWhere("order_tbl", "id='$detail->order_id'");

                                $medicineQ = mysqli_query(
                                  $conn,
                                  "SELECT 
                                    ig.id AS 'inventory_id',
                                    ig.medicine_id,
                                    mp.medicine_name,
                                    mp.generic_name,
                                    ig.product_number,
                                    (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                                    (SELECT price FROM price p WHERE p.id = ig.price_id) AS 'price'
                                  FROM inventory_general ig
                                  LEFT JOIN medicine_profile mp
                                  ON mp.id = ig.medicine_id
                                  WHERE ig.id = '$detail->inventory_general_id'
                              "
                                );
                                $inventory = mysqli_fetch_object($medicineQ);

                                $imgSrc = getMedicineImage($inventory->medicine_id);
                                $exploded =  explode("/", $imgSrc);
                                $alt = $exploded[count($exploded) - 1];
                              ?>
                                <tr>
                                  <td><?= $order->order_number ?></td>
                                  <td>
                                    <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage<?= $inventory->inventory_id ?>')">
                                      <?= "$inventory->medicine_name/ $inventory->brand_name/ $inventory->generic_name" ?>
                                    </button>
                                  </td>
                                  <td><?= "₱ " . number_format($inventory->price, 2, '.', ',') ?></td>
                                  <td><?= $detail->quantity ?></td>
                                  <td><?= "₱ " . number_format($detail->order_subtotal, 2, '.', ',') ?></td>
                                  <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                                </tr>
                                <div id='divModalImage<?= $inventory->inventory_id ?>' class='div-modal pt-5'>
                                  <span class='close' onclick='handleClose(`divModalImage<?= $inventory->inventory_id ?>`)'>&times;</span>
                                  <img class='div-modal-content' src="<?= $imgSrc  ?>">
                                  <div id="imgCaption"><?= $alt ?></div>
                                </div>
                              <?php endforeach; ?>

                            </tbody>
                          </table>

                        </div>
                      </div>

                      <div class="row justify-content-end">
                        <div class="col-md-4">
                          <div class="row">
                            <div class="col-6">
                              <ul class="list-group list-group-flush text-dark">
                                <li class="list-group-item">
                                  <h4>
                                    Total
                                  </h4>
                                </li>
                              </ul>
                            </div>
                            <div class="col-6">
                              <ul class="list-group list-group-flush text-dark">
                                <li class="list-group-item">
                                  <h4>
                                    <?= "₱ " . number_format($orderTotal, 2, '.', ',') ?>
                                  </h4>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                    $order = getSingleDataWithWhere("order_tbl", "id='$_GET[id]'");
                    ?>
                    <?php if ($order->status != "claimed") : ?>
                      <div class="card-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-primary btn-sm">Set Preparing</button>
                        <button type="button" class="btn btn-info btn-sm">Set To Claim</button>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php include("../components/scripts.php") ?>
    <script>
      $(document).ready(function() {
        const tableId = "#customerOrderDetails";
        var table = $(tableId).DataTable({
          paging: false,
          lengthChange: false,
          ordering: false,
          info: false,
          autoWidth: false,
          responsive: true,
        });

      });
    </script>
</body>

</html>