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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addType">
                          New Type
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="medicineTypeTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Name Type</th>
                            <th>Status</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $typeData = getTableData("medicine_types");

                          foreach ($typeData as $medType) :
                            $isStatusActive = $medType->status == "active" ? true : false;
                          ?>
                            <tr>
                              <td class=" align-middle"><?= $medType->name ?></td>
                              <td class=" align-middle">
                                <span class="status text-<?= $isStatusActive ? "success" : "danger" ?>">•</span>
                                <?= ucfirst($medType->status) ?>
                              </td>
                              <td class=" align-middle">
                                <a href="#editMed<?= $medType->type_id ?>" class="h5 text-info m-2" data-toggle="modal">
                                  <i class="fa fa-cog" title="Edit" data-toggle="tooltip"></i>
                                </a>
                                <?php if (!$isStatusActive) : ?>
                                  <a href="#" onclick="return deleteData('medicine_types', 'type_id', '<?= $medType->type_id ?>')" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                    <i class="fa fa-times-circle"></i>
                                  </a>
                                <?php endif; ?>
                              </td>
                            </tr>

                            <div class="modal fade" id="editMed<?= $medType->type_id ?>" tabindex="-1" role="dialog" aria-labelledby="New Manufacturer" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                              <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title text-secondary">
                                      Edit Medicine Type
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                    </button>
                                  </div>
                                  <form method="POST">
                                    <input type="text" name="action" value="edit" hidden readonly>
                                    <input type="text" name="typeId" value="<?= $medType->type_id ?>" hidden readonly>
                                    <div class="modal-body">

                                      <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                          <input type="text" name="name" class="form-control" value="<?= $medType->name ?>" required>
                                        </div>
                                      </div>

                                      <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">is Active</label>
                                        <div class="col-sm-10">
                                          <label class="switch">
                                            <input type="checkbox" id="activeSwitch" <?= $isStatusActive ? "checked" : "" ?> name="isActive">
                                            <span class="slider round"></span>
                                          </label>
                                        </div>
                                      </div>

                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" onclick="handleEditMedicineType($(this))" class="btn btn-primary">Save</button>
                                    </div>
                                  </form>

                                </div>
                              </div>
                            </div>
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

  <div class="modal fade" id="addType" tabindex="-1" role="dialog" aria-labelledby="New Medicine Type" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Medicine Type
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addTypeForm" method="POST">
          <input type="text" name="action" value="add" hidden readonly>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">is Active</label>
              <div class="col-sm-10">
                <label class="switch">
                  <input type="checkbox" id="activeSwitch" checked name="isActive">
                  <span class="slider round"></span>
                </label>
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
    function handleEditMedicineType(e) {
      swal.showLoading();
      handleSaveMedicineType($(e[0].form).serialize())
    }

    $("#addTypeForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveMedicineType($(this).serialize())
      e.preventDefault()
    })

    function handleSaveMedicineType(serializeData) {
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_medicine_type",
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
      const tableId = "#medicineTypeTable";
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
        "columns": [{
            "width": "50%"
          },
          null,
          null
        ],
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