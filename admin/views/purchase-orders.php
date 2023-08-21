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
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <th>Creation Date</th>
                            <th>Payment Amount</th>
                            <th>Payment Date</th>
                            <th>Quantity</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = mysqli_query(
                            $conn,
                            "SELECT 
                            po.medicine_id,
                            (SELECT supplier_name FROM supplier s WHERE s.id = po.supplier_id) AS 'supplier_name',
                            created_by,
                            mp.medicine_name,
                            mp.generic_name,
                            (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                            creation_date,
                            payment_amount,
                            payment_date,
                            quantity
                            FROM purchase_order po
                            LEFT JOIN medicine_profile mp
                            ON mp.id = po.medicine_id
                            WHERE po.supplier_id IS NOT NULL and po.medicine_id IS NOT NULL
                              "
                          );
                          while ($purchased = mysqli_fetch_object($query)) :
                            $imgSrc = getMedicineImage($purchased->medicine_id);
                            $exploded =  explode("/", $imgSrc);
                            $alt = $exploded[count($exploded) - 1];
                          ?>
                            <tr>
                              <td class="align-middle"><?= $purchased->supplier_name ?></td>
                              <td class="align-middle"><?= getFullName($purchased->created_by) ?></td>
                              <td class="align-middle">
                                <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage')">
                                  <?= "$purchased->medicine_name/ $purchased->brand_name/ $purchased->generic_name" ?>
                                </button>
                              </td>
                              <td class="align-middle"><?= $purchased->creation_date ?></td>
                              <td class="align-middle"><?= "₱ " . number_format($purchased->payment_amount, 2, '.', ',') ?></td>
                              <td class="align-middle"><?= $purchased->payment_date ?></td>
                              <td class="align-middle"><?= $purchased->quantity ?></td>
                            </tr>
                            <div id='divModalImage' class='div-modal pt-5'>
                              <span class='close' onclick='handleClose(`divModalImage`)'>&times;</span>
                              <img class='div-modal-content' src="<?= $imgSrc  ?>">
                              <div id="imgCaption"><?= $alt ?></div>
                            </div>
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
          <div class="modal-body">

            <div class="form-group ">
              <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
              <div class="row">
                <div class="col-10">
                  <select name="supplier_id" id="select_supp" data-live-search="true" class="selectpicker form-control" title="-- select supplier --" required>
                    <?php
                    $supplierData = getTableData("supplier");
                    foreach ($supplierData as $supplier) {
                      echo "<option value='$supplier->id'>$supplier->supplier_name</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                  <button id="btnAddSup" class="btn btn-sm btn-primary" type="button">New</button>
                </div>
              </div>
            </div>

            <div class="form-group ">
              <label class="form-label">Medicine <small class="text-muted">(Name/ Brand/ Generic)</small> <span class="text-danger">*</span></label>
              <div class="row">
                <div class="col-10">
                  <select name="medicine_id" id="select_med" data-live-search="true" class="selectpicker form-control" title="-- select medicine --" required>
                    <?php
                    $medicineData = getTableData("medicine_profile");
                    foreach ($medicineData as $medicine) {
                      $brandData = getTableData("brands", "id", $medicine->brand_id);
                      $brand = "";
                      if (count($brandData) > 0) {
                        $brand = $brandData[0]->brand_name;
                      }
                      echo "<option value='$medicine->id'>
                      $medicine->medicine_name/ " . ($brand ? "$brand/ " : "") . "$medicine->generic_name
                      </option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="col-2 d-flex justify-content-center align-items-center">
                  <button id="btnAddMed" class="btn btn-sm btn-primary" type="button">New</button>
                </div>
              </div>
            </div>

            <div class="form-group ">

              <div class="row">
                <div class="col-md-4">
                  <label class="form-label">Payment Date <span class="text-danger">*</span></label>
                  <input type="date" name="payment_date" value="<?= date("Y-m-d") ?>" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                  <input type="number" name="payment_amount" class="form-control" required>
                </div>
                <div class="col-md-4">
                  <label class="form-label">Quantity <span class="text-danger">*</span></label>
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

  <div class="modal fade" id="addMed" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="overflow-y: scroll;">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Medicine
          </h5>
          <button type="button" class="close" onclick="closeMedModal('med')">
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
                    $categoryData = getTableData("category");
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
                    $brandData = getTableData("brands");
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
              <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
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
              <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
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

  <div class="modal fade" id="addSupplier" tabindex="-1" role="dialog" aria-labelledby="New Supplier" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            New Supplier
          </h5>
          <button type="button" class="close" onclick="closeMedModal('sup')" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addSupplierForm" method="POST">
          <input type="text" name="action" value="add" hidden readonly>
          <div class="modal-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="name" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Address <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="address" class="form-control" required>
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Contact <span class="text-danger">*</span></label>
              <div class="col-sm-10">
                <input type="text" name="contact" class="form-control" required>
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
    const closeModal = (modalId) => $(modalId).modal("hide")

    // $("#addPurchased").modal("show")

    $("#modalAdd-clear").hide()

    $("#btnAddSup").on("click", function() {
      $("#addSupplier").modal("show")
      $("#addPurchased").modal("hide")
    })

    $("#btnAddMed").on("click", function() {
      $("#addMed").modal("show")
      $("#addPurchased").modal("hide")
    })

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

    $("#addSupplierForm").on("submit", function(e) {
      swal.showLoading();
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_supplier",
        $(this).serialize(),
        (data, status) => {
          const resp = JSON.parse(data)
          swal.fire({
            title: resp.success ? "Success!" : 'Error!',
            html: resp.message,
            icon: resp.success ? "success" : 'error',
          }).then(() => {
            if (resp.success) {
              getSupplier()
              closeMedModal("supp")
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

    $("#addMedForm").on("submit", function(e) {
      swal.showLoading();
      $.ajax({
        url: '<?= $SERVER_NAME ?>/backend/nodes?action=medicine_save',
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function(data) {
          const resp = JSON.parse(data);
          swal.fire({
            title: resp.success ? 'Success!' : "Error!",
            html: resp.message,
            icon: resp.success ? 'success' : 'error',
          }).then(() => {
            if (resp.success) {
              getMed()
              closeMedModal("med")
            }
          })
        },
        error: function(data) {
          swal.fire({
            title: 'Oops...',
            text: 'Something went wrong.',
            icon: 'error',
          })
        }
      });
      e.preventDefault()
    })

    function closeMedModal(action) {
      if (action === "med") {
        $("#addMedForm").get(0).reset()
        $("#addMed").modal("hide")
      } else {
        $("#addSupplierForm").get(0).reset()
        $("#addSupplier").modal("hide")
      }
      $("#addPurchased").modal("show")
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

    function getSupplier() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_supplier",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].supplier_name}</option>`;
            options.push(option);
          }

          $('#select_supp').empty();
          $('#select_supp').html(options);
          $('#select_supp').selectpicker('refresh');
        })
    }

    function getMed() {
      $.get(
        "<?= $SERVER_NAME ?>/backend/nodes?action=get_medicine",
        (data, status) => {
          const resp = JSON.parse(data)

          var options = [];

          for (var i = 0; i < resp.length; i++) {
            var option = `<option value='${resp[i].id}' >${resp[i].medicine_name}/ ${resp[i].brand_name}/ ${resp[i].generic_name}</option>`;
            options.push(option);
          }

          $('#select_med').empty();
          $('#select_med').html(options);
          $('#select_med').selectpicker('refresh');
        })
    }

    function handleEditPurchase(e) {
      swal.showLoading();
      handleSavePurchase($(e[0].form).serialize())
    }

    $("#addPurchaseForm").on("submit", function(e) {
      swal.showLoading();
      handleSavePurchase($(this).serialize())
      e.preventDefault()
    })

    function handleSavePurchase(serializeData) {
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_purchase",
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
            columns: [0, 1, 2, 3, 4, 5, 6]
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