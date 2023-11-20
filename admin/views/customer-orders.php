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
              <div class="card">
                <div class="card-body">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active text-uppercase" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-uppercase" id="preparing-tab" data-toggle="tab" href="#preparing" role="tab" aria-controls="preparing" aria-selected="false">Preparing</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-uppercase" id="to-claim-tab" data-toggle="tab" href="#to-claim" role="tab" aria-controls="to-claim" aria-selected="false">To Claim</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-uppercase" id="claimed-tab" data-toggle="tab" href="#claimed" role="tab" aria-controls="claimed" aria-selected="false">Claimed</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-uppercase" id="declined-tab" data-toggle="tab" href="#declined" role="tab" aria-controls="declined" aria-selected="false">Declined</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link text-uppercase" id="canceled-tab" data-toggle="tab" href="#canceled" role="tab" aria-controls="canceled" aria-selected="false">Canceled</a>
                    </li>
                  </ul>
                  <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                      <table id="pendingTable" class="table table-hover table-bordered table-striped ">
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
                          $orders = getTableWithWhere("order_tbl", "user_id IS NOT NULL and type='online' and status='pending'");
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getFullName($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
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
                                ?>
                                <span class="badge badge-<?= $badgeColor ?> px-3 py-1">
                                  <h6 class="mb-0 <?= $order->status != "pending" ? "text-white" : "" ?>">
                                    <?= ucfirst($order->status) ?>
                                  </h6>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-secondary btn-sm m-1">
                                  View Details
                                </button>
                              </td>
                            </tr>

                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" id="preparing" role="tabpanel" aria-labelledby="preparing-tab">
                      <table id="preparingTable" class="table table-hover table-bordered table-striped ">
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
                          $orders = getTableWithWhere("order_tbl", "user_id IS NOT NULL and type='online' and status='preparing'");
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getFullName($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
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
                                ?>
                                <span class="badge badge-<?= $badgeColor ?> px-3 py-1">
                                  <h6 class="mb-0 <?= $order->status != "pending" ? "text-white" : "" ?>">
                                    <?= ucfirst($order->status) ?>
                                  </h6>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-secondary btn-sm m-1">
                                  View Details
                                </button>
                              </td>
                            </tr>

                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" id="to-claim" role="tabpanel" aria-labelledby="to-claim-tab">
                      <table id="toClaimTable" class="table table-hover table-bordered table-striped ">
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
                          $orders = getTableWithWhere("order_tbl", "user_id IS NOT NULL and type='online' and status='to claim'");
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getFullName($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
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
                                ?>
                                <span class="badge badge-<?= $badgeColor ?> px-3 py-1">
                                  <h6 class="mb-0 <?= $order->status != "pending" ? "text-white" : "" ?>">
                                    <?= ucfirst($order->status) ?>
                                  </h6>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-secondary btn-sm m-1">
                                  View Details
                                </button>
                              </td>
                            </tr>

                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" id="claimed" role="tabpanel" aria-labelledby="claimed-tab">
                      <table id="claimedTable" class="table table-hover table-bordered table-striped ">
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
                          $orders = getTableWithWhere("order_tbl", "user_id IS NOT NULL and type='online' and status='claimed'");
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getFullName($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
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
                                ?>
                                <span class="badge badge-<?= $badgeColor ?> px-3 py-1">
                                  <h6 class="mb-0 <?= $order->status != "pending" ? "text-white" : "" ?>">
                                    <?= ucfirst($order->status) ?>
                                  </h6>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-secondary btn-sm m-1">
                                  View Details
                                </button>
                              </td>
                            </tr>

                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" id="declined" role="tabpanel" aria-labelledby="declined-tab">
                      <table id="declinedTable" class="table table-hover table-bordered table-striped ">
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
                          $orders = getTableWithWhere("order_tbl", "user_id IS NOT NULL and type='online' and status='declined'");
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getFullName($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
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
                                ?>
                                <span class="badge badge-<?= $badgeColor ?> px-3 py-1">
                                  <h6 class="mb-0 <?= $order->status != "pending" ? "text-white" : "" ?>">
                                    <?= ucfirst($order->status) ?>
                                  </h6>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-secondary btn-sm m-1">
                                  View Details
                                </button>
                              </td>
                            </tr>

                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                    <div class="tab-pane fade" id="canceled" role="tabpanel" aria-labelledby="canceled-tab">
                      <table id="canceledTable" class="table table-hover table-bordered table-striped ">
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
                          $orders = getTableWithWhere("order_tbl", "user_id IS NOT NULL and type='online' and status='canceled'");
                          foreach ($orders as $order) :
                            $orderDetails = getTableData("order_details", "order_id", $order->id);
                          ?>
                            <tr>
                              <td><?= $order->order_number ?></td>
                              <td><?= getFullName($order->user_id) ?></td>
                              <td><?= count($orderDetails) ?></td>
                              <td><?= date("Y-m-d", strtotime($order->date_ordered)) ?></td>
                              <td>
                                <?php
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
                                ?>
                                <span class="badge badge-<?= $badgeColor ?> px-3 py-1">
                                  <h6 class="mb-0 <?= $order->status != "pending" ? "text-white" : "" ?>">
                                    <?= ucfirst($order->status) ?>
                                  </h6>
                                </span>
                              </td>

                              <td>
                                <button type="button" onclick="return window.location.href = 'customer-order-details?id=<?= $order->id ?>'" class="btn btn-secondary btn-sm m-1">
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
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    $(document).ready(function() {
      const tableIds = [
        "#pendingTable",
        "#preparingTable",
        "#toClaimTable",
        "#claimedTable",
        "#declinedTable",
        "#canceledTable"
      ];

      const tableOption = {
        paging: true,
        lengthChange: false,
        ordering: false,
        info: true,
        autoWidth: false,
        responsive: true,
        language: {
          searchBuilder: {
            button: 'Filter',
          }
        },
        columns: [{
            "width": "10%"
          },
          {
            "width": "20%"
          },
          {
            "width": "10%"
          },
          {
            "width": "10%"
          },
          {
            "width": "10%"
          },
          {
            "width": "5%"
          },
        ],
        buttons: [{
          extend: 'searchBuilder',
          config: {
            columns: [0, 1, 2, 3, 4]
          }
        }],
        dom: 'Bfrtip',
      }

      for (const tableId of tableIds) {
        var table = $(tableId).DataTable(tableOption);

        table.buttons().container()
          .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
      }

    });
  </script>
</body>

</html>