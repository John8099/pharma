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
                    <div class="card-body">
                      <table id="customerOrders" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>No. of Items</th>
                            <th>Date Ordered</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $orders = getTableWithWhere("order_tbl"
                            /** , "type='online'" */
                          );
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getUserById($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
                                $badge = "";
                                switch ($order->status) {
                                  case "claimed":
                                    $badge = "badge-soft-success";
                                    break;
                                  case "preparing":
                                    $badge = "badge-soft-primary";
                                    break;
                                  case "to claim":
                                    $badge = "badge-soft-info";
                                    break;
                                  case "pending":
                                    $badge = "badge-soft-danger";
                                    break;
                                }
                                ?>
                                <span class="badge <?= $badge ?> mb-0">
                                  <?= ucfirst($order->status) ?>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-link btn-sm">
                                  View Details
                                </button>
                              </td>
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
              columns: [0, 1, 2, 3, 4]
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