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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMeds">
                          New Medicine
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="medsTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Therapeutic <br> Classification</th>
                            <th>Generic name</th>
                            <th>Brand name</th>
                            <th>Dose</th>
                            <th>Type</th>
                            <th>Manufacturer</th>
                            <th>Quantity</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $medicineData = getTableData("medicines");
                          foreach ($medicineData as $medicine) :
                            $typeData = getTableData("medicine_types", "type_id", $medicine->type_id);
                            $type = count($typeData) > 0 ? $typeData[0]->name : "";

                            $manufacturerData = getTableData("manufacturers", "manufacturer_id ", $medicine->manufacturer_id);
                            $manufacturer = count($manufacturerData) > 0 ? $manufacturerData[0]->name : "";
                          ?>
                            <tr>
                              <td><?= $medicine->classification ?></td>
                              <td><?= $medicine->generic_name ?></td>
                              <td><?= $medicine->brand_name ?></td>
                              <td><?= $medicine->dose ?></td>
                              <td><?= $type ?></td>
                              <td><?= $manufacturer ?></td>
                              <td><?= $medicine->quantity ?></td>
                              <td>
                                <a href="#" onclick="" class="h5 text-info m-2" title="Preview Details" data-toggle="tooltip">
                                  <i class="fa fa-eye"></i>
                                </a>
                                <a href="#" onclick="" class="h5 text-warning m-2" title="Edit Medicine" data-toggle="tooltip">
                                  <i class="fa fa-edit"></i>
                                </a>
                                <?php if ($medicine->quantity == 0) : ?>
                                  <a href="#" onclick="" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                    <i class="fa fa-times-circle"></i>
                                  </a>
                                <?php endif; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>

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

  <div class="modal fade" id="addMeds" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            <i class="fa fa-user-plus mr-1"></i>
            New Medicine
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addMedsForm" method="POST">

          <div class="modal-body">

            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
              </div>
              <input type="text" name="fname" class="form-control" placeholder="First name" required>
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
              </div>
              <input type="text" name="mname" class="form-control" placeholder="Middle name">
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-user"></i></span>
              </div>
              <input type="text" name="lname" class="form-control" placeholder="Last name" required>
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="feather icon-mail"></i></span>
              </div>
              <input type="email" name="email" class="form-control" placeholder="Email" required>
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
    $("#addMedsForm").on("submit", function(e) {
      swal.showLoading();

      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=addUser",
        $(this).serialize(),
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

      e.preventDefault()
    })
    $(document).ready(function() {
      const tableId = "#medsTable";
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
        buttons: [
          {
            extend: 'searchBuilder',
            config: {
              columns: [1, 2, 3, 4]
            }
          }
        ],
        dom: 'Bfrtip',
      });

      table.buttons().container()
        .appendTo(`${tableId}_wrapper .col-md-6:eq(0)`);
    });
  </script>
</body>

</html>