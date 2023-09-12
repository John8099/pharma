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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addMed">
                          New Medicine
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="medsTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Brand name</th>
                            <th>Generic name</th>
                            <th>Description</th>
                            <th>Dose</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $medicineData = mysqli_query(
                            $conn,
                            "SELECT * FROM medicine_profile WHERE deleted <> 1"
                          );
                          while ($medicine = mysqli_fetch_object($medicineData)) :
                            $category = getTableData("category", "id", $medicine->category_id);
                            $brand = getTableData("brands", "id", $medicine->brand_id);
                          ?>
                            <tr>
                              <td class="align-middle">
                                <img src="<?= getMedicineImage($medicine->id) ?>" class="rounded" width="60px">
                              </td>
                              <td class="align-middle"><?= $medicine->medicine_name ?></td>
                              <td class="align-middle"><?= count($category) > 0 ? $category[0]->category_name : "<em>NULL</em>" ?></td>
                              <td class="align-middle"><?= count($brand) > 0 ? $brand[0]->brand_name : "<em>NULL</em>" ?></td>
                              <td class="align-middle"><?= $medicine->generic_name ?></td>
                              <td class="align-middle"><?= $medicine->description ?></td>
                              <td class="align-middle"><?= $medicine->dosage ?></td>
                              <td class="align-middle">
                                <a href="#editMed<?= $medicine->id ?>" class="h5 text-info m-2" data-toggle="modal">
                                  <i class="fa fa-cog" title="Edit" data-toggle="tooltip"></i>
                                </a>
                                <a href="#" onclick="deleteMed('<?= $medicine->id ?>')" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                  <i class="fa fa-times-circle"></i>
                                </a>

                              </td>
                            </tr>
                            <?php include("../components/modal-edit-medicine.php");
                            ?>
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

  <div class="modal fade" id="addMed" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Medicine
          </h5>
          <button type="button" class="close" onclick="closeModal('#addMed')">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form method="POST" id="addMedForm" enctype="multipart/form-data">
          <input type="text" value="add" name="action" hidden readonly>
          <div class="modal-body">
            <div class="row">

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <img src="<?= getMedicineImage() ?>" class="rounded mx-auto d-block" style="width: 150px; height: 150px;" id="modalAdd-display">
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;" id="modalAdd-browse">
                  <button type="button" class="btn btn-primary btn-sm" onclick="return changeImage('#formInput-add')">
                    Browse
                  </button>
                </div>
                <div class="mt-3" style="display: flex; justify-content: center;" id="modalAdd-clear">
                  <button type="button" class="btn btn-danger btn-sm" onclick="return clearImg('#modalAdd-display', '#modalAdd-clear', '#modalAdd-browse')">
                    Clear
                  </button>
                </div>
                <div class="mt-3" style="display: none;">
                  <input class="form-control form-control-sm" type="file" accept="image/*" onchange="return previewFile(this, '#modalAdd-display', '#modalAdd-clear', '#modalAdd-browse')" id="formInput-add" name="medicine_img">
                </div>
              </div>

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label>Name <span class="text-danger">*</span></label>
                  <input type="text" name="name" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label> Category <span class="text-danger">*</span> </label>
                  <button type="button" id="btnAddCategory" class="btn btn-sm btn-primary mr-0" style="float: right;">New</button>

                  <select class="selectpicker form-control" name="category_id" id="select_category" data-container="body" data-live-search="true" title="-- select category --" required>
                    <?php
                    $categoryData = getTableWithWhere("category", "status=1");
                    foreach ($categoryData as $category) {
                      echo "<option value='$category->id'>$category->category_name</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Dose <span class="text-danger">*</span></label>
                  <input type="text" name="dose" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Generic name <span class="text-danger">*</span></label>
                  <input type="text" name="generic_name" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Brand name <span class="text-danger">*</span></label>
                  <button id="btnAddBrand" type="button" class="btn btn-sm btn-primary mr-0" style="float: right;">New</button>
                  <select name="brand_id" id="select_brand" data-live-search="true" class="selectpicker form-control" title="-- select brand --" required>
                    <?php
                    $brandData = getTableWithWhere("brands", "status=1");
                    foreach ($brandData as $brand) {
                      echo "<option value='$brand->id'>$brand->brand_name</option>";
                    }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label>Description</label>
                  <textarea class="form-control" name="med_desc" rows="5"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnAdd" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-warning">Reset</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="modal fade" id="addCategory" tabindex="-1" role="dialog" aria-labelledby="New Category" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Category
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addCategoryForm" method="POST">
          <input type="text" name="action" value="add" hidden readonly>
          <input type="text" name="type" value="add_select" hidden readonly>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
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

  <div class="modal fade" id="addBrand" tabindex="-1" role="dialog" aria-labelledby="New Brand" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Brand
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addBrandForm" method="POST">
          <input type="text" name="action" value="add" hidden readonly>
          <input type="text" name="type" value="add_select" hidden readonly>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Description</label>
              <div class="col-sm-10">
                <textarea name="description" class="form-control" cols="30" rows="10"></textarea>
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
    // $("#addMed").modal("show")

    $("#modalAdd-clear").hide()

    $("#btnAddBrand").on("click", function() {
      $("#addMed").modal("hide")
      $("#addBrand").modal("show")
    })

    $("#btnAddCategory").on("click", function() {
      $("#addMed").modal("hide")
      $("#addCategory").modal("show")
    })

    $("#addCategory").on("hidden.bs.modal", function() {
      $("#addMed").modal("show")
      $("#addCategoryForm").get(0).reset()
    })

    function deleteMed(medId) {
      swal.fire({
        title: "Are you sure you want to remove this item?",
        text: "You can't undo this action after successful deletion.",
        icon: "warning",
        confirmButtonText: "Delete",
        confirmButtonColor: "#dc3545",
        showCancelButton: true,
      }).then((d) => {
        if (d.isConfirmed) {
          swal.showLoading();
          $.post(
            "<?= $SERVER_NAME ?>/backend/nodes?action=delete_med", {
              medicine_id: medId
            },
            (data, status) => {
              const resp = JSON.parse(data);
              if (!resp.success) {
                swal.fire({
                  title: "Error!",
                  html: resp.message,
                  icon: "error",
                });
              } else {
                window.location.reload();
              }
            }
          ).fail(function(e) {
            swal.fire({
              title: "Error!",
              html: e.statusText,
              icon: "error",
            });
          });
        }
      });
    }

    function getCategory() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_category",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].category_name}</option>`;
            options.push(option);
          }

          $('#select_category').empty();
          $('#select_category').html(options);
          $('#select_category').selectpicker('refresh');
        })
    }

    function getBrand() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_brand",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].brand_name}</option>`;
            options.push(option);
          }

          $('#select_brand').empty();
          $('#select_brand').html(options);
          $('#select_brand').selectpicker('refresh');
        })
    }

    $("#addBrand").on("hidden.bs.modal", function() {
      $("#addMed").modal("show")
      $("#addBrandForm").get(0).reset()
    })

    $("#addBrandForm").on("submit", function(e) {
      swal.showLoading();
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_brand",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => {
            if (resp.success) {
              closeModal("#addBrand")
              $("#addBrandForm").get(0).reset()
              getBrand()
              $("#addMed").modal("show")
            }
          })

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
      e.preventDefault()
    })

    $("#addCategoryForm").on("submit", function(e) {
      swal.showLoading();
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_category",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => {
            if (resp.success) {
              closeModal("#addCategory")
              $("#addCategoryForm").get(0).reset()
              getCategory()
              $("#addMed").modal("show")
            }
          })

        }).fail(function(e) {
        swal.fire({
          title: 'Error!',
          text: e.statusText,
          icon: 'error',
        })
      });
      e.preventDefault()
    })

    const closeModal = (modalId) => $(modalId).modal("hide")

    function handleOpenEditModal(medId) {
      const modalId = `#editMed${medId}`
      const modalEditBrowseBtn = `#modalEdit-browse${medId}`
      const modalClearBrowseBtn = `#modalEdit-clear${medId}`
      const modalDisplayImg = `#modalEdit-display${medId}`

      $(modalId).modal("show")
    }

    function handleSubmitEditMedicine(formId, medId) {
      swal.showLoading();
      const category = $(`#sel_cat${medId}`).val()
      const brand = $(`#sel_brand${medId}`).val()

      let formData = new FormData($(formId)[0]);
      formData.append("category_id", category)
      formData.append("brand_id", brand)

      handleSaveMedicine(formData)
    }

    $("#addMedForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveMedicine(new FormData(this))
      e.preventDefault()
    })

    function handleSaveMedicine(formData) {
      $.ajax({
        url: '<?= $SERVER_NAME ?>/backend/nodes?action=medicine_save',
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          const resp = JSON.parse(data);
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => resp.success ? window.location.reload() : undefined)
        },
        error: function(data) {
          swal.fire({
            title: 'Oops...',
            text: 'Something went wrong.',
            icon: 'error',
          })
        }
      });
    }

    $(document).ready(function() {

      const tableId = "#medsTable";
      var table = $(tableId).DataTable({
        paging: true,
        lengthChange: false,
        order: [
          [1, 'asc']
        ],
        info: true,
        autoWidth: false,
        responsive: true,
        language: {
          searchBuilder: {
            button: 'Filter',
          }
        },
        columnDefs: [{
          "targets": [0, 7],
          "orderable": false
        }],
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