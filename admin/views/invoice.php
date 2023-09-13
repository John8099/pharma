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
                      <table id="invoiceTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Cashier</th>
                            <th>Type</th>
                            <th>Total Items</th>
                            <th>Subtotal</th>
                            <th>Discount</th>
                            <th>Total</th>
                            <th>Cash</th>
                            <th>Change</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $invoice = getTableData("invoice");
                          foreach ($invoice as $in) :
                            $order = getSingleDataWithWhere("order_tbl", "id='$in->order_id'");
                            $payment = getSingleDataWithWhere("payment", "order_id='$in->order_id'");
                            $sales = getSingleDataWithWhere("sales", "invoice_id='$in->id'");
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= $order->user_id ? getFullName($order->user_id) : "-----" ?></td>
                              <td><?= getFullName($in->user_id) ?></td>
                              <td><?= $order->type == "walk_in" ? "Over the counter" : "Online" ?></td>
                              <td>
                                <button type="button" onclick="return window.location.href='order-details?id=<?= $order->id ?>'" class="btn btn-link btn-sm">
                                  <?= $sales->total_quantity_sold ?>
                                </button>
                              </td>
                              <td><?= "₱ " . number_format($order->subtotal, 2, '.', ',') ?></td>
                              <td><?= "₱ " . number_format($order->discount, 2, '.', ',') ?></td>
                              <td><?= "₱ " . number_format($order->overall_total, 2, '.', ',') ?></td>
                              <td><?= "₱ " . number_format($payment->paid_amount, 2, '.', ',') ?></td>
                              <td><?= "₱ " . number_format($payment->customer_change, 2, '.', ',') ?></td>
                              <td><?= date("Y-m-d", strtotime($in->date_created)) ?></td>
                            </tr>
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
        const tableId = "#invoiceTable";
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