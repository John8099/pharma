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
                            <th>Customer/ Cashier</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>Total</th>
                          </tr>
                        </thead>
                        <tbody>

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
          columnDefs: [{
            "targets": [4],
            "orderable": false
          }],
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