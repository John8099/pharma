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

                <!-- product profit start -->
                <div class="col-12">
                  <div class="card">
                    <div class="card-header p-2">
                      <div class="w-100 d-flex justify-content-end">

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPurchased">
                          New Purchase
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="purchasedTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Supplier Name</th>
                            <th>Created By</th>
                            <th>Medicine</th>
                            <th>Creation Date</th>
                            <th>Payment Amount</th>
                            <th>Payment Date</th>
                            <th>Payment Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = mysqli_query(
                            $conn,
                            "SELECT 
                                (SELECT supplier_name FROM supplier s WHERE s.id = po.supplier_id) AS 'supplier_name',
                                created_by,
                                (SELECT medicine_name FROM medicine_profile mp WHERE mp.id = po.medicine_id) AS 'medicine_name',
                                creation_date,
                                payment_amount,
                                payment_date,
                                quantity
                              FROM purchase_order po
                              WHERE po.supplier_id <> NULL and po.medicine_id <> NULL
                              "
                          );
                          while ($purchased = mysqli_fetch_object($query)) :
                          ?>
                            <td class="align-middle"><?= $purchased->supplier_name ?></td>
                            <td class="align-middle"><?= getFullName($purchased->created_by) ?></td>
                            <td class="align-middle"><?= $purchased->medicine_name ?></td>
                            <td class="align-middle"><?= $purchased->creation_date ?></td>
                            <td class="align-middle"><?= $purchased->payment_amount ?></td>
                            <td class="align-middle"><?= $purchased->payment_date ?></td>
                            <td class="align-middle"><?= $purchased->quantity ?></td>
                          <?php endwhile; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <!-- product profit end -->
              </div>

              <!-- [ Main Content ] end -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addPurchased" tabindex="-1" role="dialog" aria-labelledby="New Purchase" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Purchase
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addPurchaseForm" method="POST">
          <input type="text" name="action" value="add" hidden readonly>
          <div class="modal-body">

            <div class="form-group ">
              <label class="form-label">Supplier Name</label>
              <div class="row">
                <div class="col-10">
                  <select name="supplier_id" class="form-control" required>
                    <option value="">-- select supplier --</option>
                    <?php
                    $supplierData = getTableData("supplier");
                    foreach ($supplierData as $supplier) {
                      echo "<option value='$supplier->id'>$supplier->supplier_name</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                  <button class="btn btn-sm btn-primary" type="button">New</button>
                </div>
              </div>
            </div>

            <div class="form-group ">
              <label class="form-label">Medicine <small class="text-muted">(Name/ Brand/ Generic)</small> </label>
              <div class="row">
                <div class="col-10">
                  <select name="medicine_id" class="form-control" required>
                    <option value="">-- select medicine --</option>
                    <?php
                    $medicineData = getTableData("medicine_profile");
                    foreach ($medicineData as $medicine) {
                      echo "<option value='$medicine->id'>$medicine->medicine_name</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                  <button class="btn btn-sm btn-primary" type="button">New</button>
                </div>
              </div>
            </div>

            <div class="form-group ">

              <div class="row">
                <div class="col-md-4">
                  <label class="form-label">Payment Date</label>
                  <input type="date" name="payment_date" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Payment Amount</label>
                  <input type="number" name="payment_amount" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Quantity</label>
                  <input type="number" name="quantity" class="form-control" required>
                </div>

              </div>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    $("#addPurchased").modal("show")

    function handleEditBrand(e) {
      swal.showLoading();
      handleSaveBrand($(e[0].form).serialize())
    }

    $("#addPurchaseForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveBrand($(this).serialize())
      e.preventDefault()
    })

    function handleSaveBrand(serializeData) {
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_brand",
        serializeData,
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => resp.success ? window.location.reload() : undefined)

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
    }

    $(document).ready(function() {
      const tableId = "#purchasedTable";
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
            columns: [0, 1]
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