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
                <div class="col-sm-12">
                  <div class="card">
                    <div class="card-body">
                      <table id="salesTable" class="table table-hover table-bordered table-striped ">
                        <thead>
                          <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Cashier</th>
                            <th>Items Sold</th>
                            <th>Type</th>
                            <th>Profit</th>
                            <th>Discount Type</th>
                            <th>Discount</th>
                            <th>Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $sales = getTableData("sales");
                          foreach ($sales as $sale) :
                            $invoice = getSingleDataWithWhere("invoice", "id='$sale->invoice_id'");

                            $order = getSingleDataWithWhere("order_tbl", "id='$invoice->order_id'");
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= $order->type == "online"  ? getFullName($order->user_id) : "<em class='text-muted'>N/A</em>" ?></td>
                              <td><?= getFullName($invoice->user_id) ?></td>
                              <td>
                                <button type="button" onclick="return window.location.href='order-details?id=<?= $order->id ?>'" class="btn btn-link btn-sm">
                                  <?= $sale->total_quantity_sold ?>
                                </button>
                              </td>
                              <td><?= $order->type == "walk_in" ? "Over the counter" : "Online" ?></td>
                              <td><?= number_format($order->overall_total, 2, '.', ',') ?></td>
                              <td><?= strtoupper($order->discount_type) ?></td>
                              <td><?= number_format($order->discount, 2, '.', ',') ?></td>
                              <td><?= date("Y-m-d", strtotime($sale->sales_date)) ?></td>
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
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    $(document).ready(function() {
      const tableId = "#salesTable";
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
              columns: [0, 1, 2, 3, 4, 6, 7, 8]
            }
          },
          "print"
        ],
        dom: 'Bfrtip',
      });

      table.buttons().container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
    });
  </script>
</body>

</html>