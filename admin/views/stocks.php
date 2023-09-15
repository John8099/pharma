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

                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addStock">
                          Add Stock
                        </button>
                      </div>
                    </div>
                    <div class="card-body table-border-style">
                      <table id="stockTable" class="table table-hover">
                        <thead>
                          <tr>
                            <th>Product #</th>
                            <th>Medicine <small>(Name/ Brand/ Generic)</small></th>
                            <td>Dosage</td>
                            <th>Price</th>
                            <th>Supplier Name</th>
                            <th>Quantity</th>
                            <th>Received</th>
                            <th>Expiration</th>
                            <th>Serial #</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                          $query = mysqli_query(
                            $conn,
                            "SELECT 
                              ig.id AS 'inventory_id',
                              ig.medicine_id,
                              ig.product_number,
                              mp.medicine_name,
                              mp.generic_name,
                              mp.dosage,
                              (SELECT brand_name FROM brands b WHERE b.id = mp.brand_id) AS 'brand_name',
                              ig.price_id,
                              (SELECT supplier_name FROM supplier s WHERE s.id = ig.supplier_id) AS 'supplier_name',
                              ig.quantity,
                              ig.date_received,
                              ig.expiration_date,
                              ig.serial_number
                            FROM inventory_general ig
                            LEFT JOIN medicine_profile mp
                            ON mp.id = ig.medicine_id
                              "
                          );
                          while ($inventory = mysqli_fetch_object($query)) :
                            $priceData = getTableData("price", "id", $inventory->price_id);
                            $price = count($priceData) > 0 ? $priceData[0]->price : "NULL";

                            $imgSrc = getMedicineImage($inventory->medicine_id);
                            $exploded =  explode("/", $imgSrc);
                            $alt = $exploded[count($exploded) - 1];
                          ?>
                            <tr>
                              <td class="align-middle"><?= $inventory->product_number ?></td>
                              <td class="align-middle">
                                <button type="button" class="btn btn-link btn-lg p-0 m-0" onclick="handleOpenModalImg('divModalImage<?= $inventory->inventory_id ?>')">
                                  <?= "$inventory->medicine_name/ $inventory->brand_name/ $inventory->generic_name" ?>
                                </button>
                              </td>
                              <td class="align-middle dosage"><?= $inventory->dosage . "mg" ?></td>
                              <td class="align-middle"><?= "₱ " . number_format($price, 2, '.', ',') ?></td>
                              <td class="align-middle"><?= $inventory->supplier_name ?></td>
                              <td class="align-middle"><?= $inventory->quantity ?></td>
                              <td class="align-middle"><?= $inventory->date_received ?></td>
                              <td class="align-middle"><?= $inventory->expiration_date ?></td>
                              <td class="align-middle"><?= $inventory->serial_number ?></td>
                              <td class="align-middle">
                                <a href="#" onclick="handleAddQuantity('<?= $inventory->inventory_id ?>')" class="h5 text-success m-2">
                                  <i class="fa fa-plus-circle" title="Add Quantity" data-toggle="tooltip"></i>
                                </a>
                              </td>
                            </tr>
                            <div id='divModalImage<?= $inventory->inventory_id ?>' class='div-modal pt-5'>
                              <span class='close' onclick='handleClose(`divModalImage<?= $inventory->inventory_id ?>`)'>&times;</span>
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

  <!-- Modal Add Stock -->
  <div class="modal fade" id="addStock" tabindex="-1" role="dialog" aria-labelledby="Add Stock" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-secondary">
            Add Stock
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="addStockForm" method="POST">
          <div class="modal-body">
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
              <label class="form-label">Supplier Name <span class="text-danger">*</span></label>
              <div class="row">
                <div class="col-10">
                  <select name="supplier_id" id="select_supp" data-live-search="true" class="selectpicker form-control" title="-- select supplier --" required>
                    <?php
                    $supplierData = getTableWithWhere("supplier", "status = 1");
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

              <div class="row">
                <div class="col-md-6">
                  <label class="form-label">Price <span class="text-danger">*</span></label>
                  <input type="number" name="price" step=".01" class="form-control" required>
                  <!-- <select name="price" id="select_price" class="form-control" required>
                    <?php
                    $supplierData = getTableData("supplier");
                    foreach ($supplierData as $supplier) {
                      echo "<option value='$supplier->id'>$supplier->supplier_name</option>";
                    }
                    ?>
                  </select> -->
                </div>
                <div class="col-md-6">
                  <label class="form-label">Quantity <span class="text-danger">*</span></label>
                  <input type="number" name="quantity" class="form-control" required>
                </div>

              </div>
            </div>

            <div class="form-group ">

              <div class="row">
                <div class="col-md-6">
                  <label class="form-label">Received Date <span class="text-danger">*</span></label>
                  <input type="date" name="received_date" value="<?= date("Y-m-d") ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Expiration Date <span class="text-danger">*</span></label>
                  <input type="date" name="expiration_date" min="<?= date("Y-m-d") ?>" class="form-control" required>
                </div>

              </div>
            </div>

            <div class="form-group ">
              <label class="form-label">Serial Number <span class="text-danger">*</span></label>
              <input type="text" name="serial_number" class="form-control" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <!-- Add Medicine -->
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

  <!-- Modal Category -->
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

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">is Active</label>
              <div class="col-sm-9">
                <label class="switch">
                  <input type="checkbox" name="isActive" checked>
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

  <!-- Modal Brand  -->
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

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">is Active</label>
              <div class="col-sm-9">
                <label class="switch">
                  <input type="checkbox" name="isActive" checked>
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

  <!-- Modal Supplier -->
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

            <div class="form-group row">
              <label class="col-sm-3 col-form-label">is Active</label>
              <div class="col-sm-9">
                <label class="switch">
                  <input type="checkbox" name="isActive" checked>
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
    $("#select_price").editableSelect();
    $('#select_price').attr("placeholder", "Select or Type Price");

    const closeModal = (modalId) => $(modalId).modal("hide")

    // $("#addStock").modal("show")

    $("#modalAdd-clear").hide()

    $("#btnAddSup").on("click", function() {
      $("#addSupplier").modal("show")
      $("#addStock").modal("hide")
    })

    $("#btnAddMed").on("click", function() {
      $("#addMed").modal("show")
      $("#addStock").modal("hide")
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

    function handleAddQuantity(inventoryId) {
      swal.fire({
        title: 'Number of Quantity to be added',
        input: 'number',
        showLoaderOnConfirm: true,
        confirmButtonText: 'Add',
        confirmButtonColor: "#2ca961",
        showCancelButton: true,
      }).then((res) => {
        if (res.isConfirmed) {
          $.post(
            "<?= $SERVER_NAME ?>/backend/nodes?action=add_medicine_quantity", {
              quantity_to_add: res.value,
              inventory_id: inventoryId
            },
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
      })
    }

    function closeMedModal(action) {
      if (action === "med") {
        $("#addMedForm").get(0).reset()
        $("#addMed").modal("hide")
      } else {
        $("#addSupplierForm").get(0).reset()
        $("#addSupplier").modal("hide")
      }
      $("#addStock").modal("show")
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

    function handleEditStock(e) {
      swal.showLoading();
      handleSaveStock($(e[0].form).serialize())
    }

    $("#addStockForm").on("submit", function(e) {
      swal.showLoading();
      handleSaveStock($(this).serialize())
      e.preventDefault()
    })

    function handleSaveStock(serializeData) {
      $.post(
        "<?= $SERVER_NAME ?>/backend/nodes?action=save_stock",
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
      const tableId = "#stockTable";
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
          "targets": [8],
          "orderable": false
        }],
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