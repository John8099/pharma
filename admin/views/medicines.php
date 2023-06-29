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
                            <th>Code</th>
                            <th>Therapeutic <br> Classification</th>
                            <th>Generic name</th>
                            <th>Brand name</th>
                            <th>Dose</th>
                            <th>Type</th>
                            <th>Price</th>
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
                              <td><?= $medicine->code ?></td>
                              <td><?= $medicine->classification ?></td>
                              <td><?= $medicine->generic_name ?></td>
                              <td><?= $medicine->brand_name ?></td>
                              <td><?= $medicine->dose ?></td>
                              <td><?= $type ?></td>
                              <td><?= "₱" . number_format($medicine->price, 2, ".") ?></td>
                              <td><?= $medicine->quantity ?></td>
                              <td>
                                <a href="#" onclick="handleAddQuantity('<?= $medicine->medicine_id ?>')" class="h5 text-success m-2">
                                  <i class="fa fa-plus-circle" title="Add Quantity" data-toggle="tooltip"></i>
                                </a>
                                <a href="#" onclick="handleOpenEditModal('<?= $medicine->medicine_id ?>')" class="h5 text-warning m-2">
                                  <i class="fa fa-edit" title="Edit Medicine" data-toggle="tooltip"></i>
                                </a>
                                <?php if ($medicine->quantity == 0 && getCartCountByMedId($medicine->medicine_id) == 0) : ?>
                                  <a href="#" onclick="return deleteData('medicines', 'medicine_id', '<?= $medicine->medicine_id ?>')" class="h5 text-danger m-2" title="Delete" data-toggle="tooltip">
                                    <i class="fa fa-times-circle"></i>
                                  </a>
                                <?php endif; ?>
                              </td>
                            </tr>
                            <?php include("../components/modal-edit-medicine.php"); ?>
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

  <div class="modal fade" id="addMed" tabindex="-1" role="dialog" aria-labelledby="New Medicine" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
                  <img src="<?= getMedicineImage() ?>" class="rounded mx-auto d-block" style="width: 200px; height: 200px;" id="modalAdd-display">
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

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Medicine Type <span class="text-danger">*</span></label>
                  <select name="med_type_id" class="choices form-select" required>
                    <option value="" selected disabled>Select Medicine Type</option>
                    <?php
                    $medicineTypes = getTableData("medicine_types", "status", "active");
                    foreach ($medicineTypes as $medicineType) :
                    ?>
                      <option value="<?= $medicineType->type_id ?>"><?= $medicineType->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Manufacturers <span class="text-danger">*</span></label>
                  <select name="med_manufacturer_id" class="choices form-select" required>
                    <option value="" selected disabled>Select Manufacturer</option>
                    <?php
                    $manufacturerTypes = getTableData("manufacturers", "status", "active");
                    foreach ($manufacturerTypes as $manufacturer) :
                    ?>
                      <option value="<?= $manufacturer->manufacturer_id ?>"><?= $manufacturer->name ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
              </div>

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label>Therapeutic Classification <span class="text-danger">*</span></label>
                  <input type="text" name="classification" class="form-control" required>
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
                  <input type="text" name="brand_name" class="form-control" required>
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
                  <label>Price <span class="text-danger">*</span></label>
                  <input type="number" name="price" class="form-control" required>
                </div>
              </div>

              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Quantity <span class="text-danger">*</span></label>
                  <input type="number" name="quantity" class="form-control" required>
                </div>
              </div>
              <div class="col-md-6 mb-1">
                <div class="form-group">
                  <label>Expiration <span class="text-danger">*</span></label>
                  <input type="date" name="expiration" class="form-control" min="<?= date("Y-m-d") ?>" required>
                </div>
              </div>

              <div class="col-md-12 mb-1">
                <div class="form-group">
                  <label>Medicine Description</label>
                  <textarea class="form-control" name="med_desc" rows="5"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="btnAdd" class="btn btn-primary">Submit</button>
            <button type="button" class="btn btn-danger" onclick="closeModal('#addMed')">Cancel</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <?php include("../components/scripts.php") ?>
  <script>
    $("#modalAdd-clear").hide()

    function handleAddQuantity(medId) {
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
              medicine_id: medId
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

    function handleOpenEditModal(medId) {
      const modalId = `#editMed${medId}`
      const modalEditBrowseBtn = `#modalEdit-browse${medId}`
      const modalClearBrowseBtn = `#modalEdit-clear${medId}`
      const modalDisplayImg = `#modalEdit-display${medId}`

      $(modalId).modal("show")
      const imgFile = $(modalDisplayImg)[0].src.split("/").pop()

      if (imgFile === "medicine.png") {
        $(modalClearBrowseBtn).hide()
      } else {
        $(modalEditBrowseBtn).hide()
      }
    }

    // $("#addMed").modal("show")
    const closeModal = (modalId) => $(modalId).modal("hide")

    function handleSubmitEditMedicine(formId, medId) {
      swal.showLoading();
      const type = $(`#type${medId}`).val()
      const man = $(`#man${medId}`).val()

      let formData = new FormData($(formId)[0]);
      formData.append("med_type_id", type)
      formData.append("med_manufacturer_id", man)

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
            columns: [1, 2, 3, 4]
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