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
                      <button type="button" onclick="return window.location.replace('<?= $SERVER_NAME ?>/admin/views/add-invoice')" class="btn btn-primary btn-sm float-right">
                        New Invoice
                      </button>
                    </div>
                    <div class="card-body">
                      <table id="customerOrders" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Cashier</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $invoice = getTableData("invoice");
                          foreach ($invoice as $in) :
                            $order = getSingleDataWithWhere("order_tbl", "id='$in->order_id'");

                            $orderDetails = getTableWithWhere("order_details", "order_id='$order->id'");
                            foreach ($orderDetails as $orderDetail) :
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
                                WHERE ig.id = '$orderDetail->inventory_general_id'
                                "
                              );
                              $inventory = mysqli_fetch_object($medicineQ);

                              $imgSrc = getMedicineImage($inventory->medicine_id);
                              $exploded =  explode("/", $imgSrc);
                              $alt = $exploded[count($exploded) - 1];
                          ?>
                              <tr>
                                <td><?= $order->order_number ?></td>
                                <td><?= $order->user_id ? getFullName($order->user_id) : "-----" ?></td>
                                <td><?= getFullName($in->user_id) ?></td>
                                <td>
                                  <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage')">
                                    <?= "$inventory->medicine_name/ $inventory->brand_name/ $inventory->generic_name" ?>
                                  </button>
                                </td>
                                <td><?= $order->type == "walk_in" ? "-----" : "Online" ?></td>
                                <td><?= $orderDetail->quantity ?></td>
                                <td><?= "₱ " . $inventory->price ?></td>
                                <td><?= $order->discount ?></td>
                                <td><?= $order->overall_total ?></td>
                              </tr>
                              <div id='divModalImage' class='div-modal pt-5'>
                                <span class='close' onclick='handleClose(`divModalImage`)'>&times;</span>
                                <img class='div-modal-content' src="<?= $imgSrc  ?>">
                                <div id="imgCaption"><?= $alt ?></div>
                              </div>
                            <?php endforeach; ?>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
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
        const tableId = "#customerOrders";
        var table = $(tableId).DataTable({
          paging: true,
          lengthChange: false,
          ordering: true,
          info: true,
          autoWidth: false,
          responsive: true,
          language: {
            searchBuilder: {
              button: 'Filter',
            }
          },
          buttons: [{
            extend: 'searchBuilder',
            config: {
              columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
            }
          }],
          dom: 'Bfrtip',
        });

        table.buttons().container()
          .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
      });
    </script>
</body>

</html>