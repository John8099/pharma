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
                      <table id="orderHistory" class="table table-hover table-border-style">
                        <thead>
                          <tr>
                            <th>Order code</th>
                            <th>User</th>
                            <th>Order from</th>
                            <th>Date ordered</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $historyData = getTableData("orders");
                          foreach ($historyData as $history) :
                            $orderUserData = getUserById($history->user_id);
                          ?>
                            <tr>
                              <td><?= $history->order_code ?></td>
                              <td><?= getFullName($orderUserData->id) ?></td>
                              <td><?= $history->order_from ?></td>
                              <td><?= date("Y-m-d", strtotime($history->date_created)) ?></td>
                              <td>
                                <button type="button" class="btn btn-link" onclick="handleOpenPreview('<?= $history->order_id ?>')">
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

  <div class="modal fade" id="preview" tabindex="-1" role="dialog" aria-labelledby="Order Details" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            Order Details
          </h5>
          <button type="button" class="close" data-dismiss="modal">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div id="orderHistoryModalTable">
                <table class="table table-hover table-border-style">
                  <thead>
                    <tr>
                      <th>Classification</th>
                      <th>Generic name</th>
                      <th>Brand name</th>
                      <th>Price</th>
                      <th>Quantity</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody id="orderHistoryTableBody">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    function handleOpenPreview(orderId) {
      swal.showLoading()
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_order_details", {
          order_id: orderId,
        },
        (data, status) => {
          const resp = JSON.parse(data)
          if (resp.length === 0) {
            swal.fire({
              title: "Error!",
              html: "Error while retrieving data.<br>Please try again later.",
              icon: "error"
            })
          } else {
            const items = JSON.parse(resp[0])
            if (items.length > 0) {
              let bodyData = "";
              items.forEach((d) => {
                bodyData += `<tr>
                                <td>${d.classification}</td>
                                <td>${d.generic_name}</td>
                                <td>${d.brand_name}</td>
                                <td>${d.price}</td>
                                <td>${d.order_quantity}</td>
                                <td>${d.total}</td>
                              </tr>`
              })
              $("#orderHistoryTableBody").html(bodyData)
              $("#preview").modal("show")
            }
            swal.close()
          }

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    }

    $(document).ready(function() {
      const tableId = "#orderHistory";
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
            columns: [1, 2, 3, 4, 5, 6]
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